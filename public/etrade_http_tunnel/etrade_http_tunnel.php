<?php
/* ##########################
	E-Trade Http Tunnel v2.0.
	HTTP tunnel script.    
	
	Copyright (c) 2011-2015 ElbuzGroup
	http://www.elbuz.com
   ##########################
*/

DEFINE('TUNNEL_VER', '2.0');

$ConvertSpecialCharactersToHTMLEntities=0; // Convert special characters to HTML entities

header("Content-type: text/html; charset=windows-1251");

if (version_compare(phpversion(), "4")<=0) {
	echo 'Версия PHP '.phpversion().' не совместима для работы E-Trade Http Tunnel, обновите версию PHP до 5 и выше.';
	exit;
}

$dir=dirname(__FILE__);

//error_reporting(E_ERROR);

// Debug level
$debug=1;
if ($debug==1) {

	if ( !defined( 'E_STRICT' ) )			 define( 'E_STRICT', 2048 );
	if ( !defined( 'E_RECOVERABLE_ERROR' ) ) define( 'E_RECOVERABLE_ERROR', 4096 );
	if ( !defined( 'E_DEPRECATED' ) )		 define( 'E_DEPRECATED', 8192 );
	if ( !defined( 'E_USER_DEPRECATED' ) )	 define( 'E_USER_DEPRECATED', 16384 );
	
	ini_set('display_errors', 1);
	ini_set('html_errors', 0);
	
	if (version_compare(phpversion(), "5.2")>=0) {
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED); // E_ERROR | E_WARNING | E_PARSE | E_NOTICE
	
		set_error_handler('myErrorHandler', E_ALL ^ E_NOTICE ^ E_DEPRECATED);
		register_shutdown_function('fatalErrorShutdownHandler');
	} else {
		error_reporting(E_ALL ^ E_NOTICE); // E_ERROR | E_WARNING | E_PARSE | E_NOTICE
	}
}

require_once($dir.'/etrade_http_tunnel_login.php'); // Auth setup

$authenticated=0;
if(isset($_GET['authorization'])) {
    if(preg_match('/^Basic\s+(.*)$/i', $_GET['authorization'], $user_pass)) {
        list($user,$pass)=explode(':',base64_decode($user_pass[1]));

		if ($user == $login && $pass == $password) {
			$authenticated=1;
        }
    }
} else {
	if(isset($_SERVER['PHP_AUTH_USER'])) {
		if($_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $password) {
			$authenticated=1;
		}
	}
}

if($authenticated==0) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
	header('HTTP/1.1 401 Unauthorized');
    SendAnswer("Error: Authenticate login or password not valid! (Ошибка: Не правильный логин или пароль для доступа к модулю!)");
	exit;
}

if (stristr(PHP_OS, 'WIN')) { // Detect operation system
	$dir_separator='\\\\';
} else {
	$dir_separator='/';
}
$base_path=dirname(__FILE__).$dir_separator;
$base_path=str_ireplace('\\', '\\\\', $base_path); // for Windows OS only


require_once($dir.'/etrade_http_tunnel_ifunc.php');
require_once($dir.'/json.php');
require_once($dir.'/pclzip.lib.php');
require_once($dir.'/fgetcsv.php');
require_once($dir.'/cscart_product_options_inventory.php');

if( !function_exists('json_encode') ) {
	function json_encode($data) {
		$json = new Services_JSON();
		return( $json->encode($data) );
	}
}

// Future-friendly json_decode
if( !function_exists('json_decode') ) {
	function json_decode($data, $bool) {
		if ($bool) {
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		} else {
			$json = new Services_JSON();
		}
		return( $json->decode($data) );
	}
}

// Create temp dir
if (is_dir('./temp/')==false) {
	mkdir('./temp/', 0777, true);
}

date_default_timezone_set('Europe/Kiev');
ini_set("memory_limit", "1024M");
ini_set("post_max_size", "256M");
ini_set("upload_max_filesize", "256M");
ini_set("max_execution_time", "30000");
ini_set("max_input_time", "6000");
ini_set('auto_detect_line_endings', '1');

$ini_get_locale=setlocale(LC_ALL, array('ru_RU.CP1251', 'ru_RU.cp1251', 'ru_RU.UTF8', 'ru_RU.utf8', 'ru_ru.CP1251', 'ru_ru.cp1251', 'ru_ru.UTF8', 'ru_ru.utf8', 'ru_RU', 'ru_ru', 'Russian', 'ru_UA'));

// Get options
$load_data_infile='0';
$sql_server_type='';
$sql_query='';
$programm_name='';
$row_num_per_steep = 50; // Количество строк отправляемых в СУБД за 1 пакет.
$time_for_work = 15; // Количество секунд для работы. При превышении этого времени скрипт останавливает свою работу.

//$data = file_get_contents('php://input');
if (isset($_POST['dbhost'])==true) $dbhost=htmlspecialchars($_POST['dbhost']);
if (isset($_POST['dbuser'])==true) $dbuser=htmlspecialchars($_POST['dbuser']);
if (isset($_POST['dbpass'])==true) $dbpass=htmlspecialchars($_POST['dbpass']);
if (isset($_POST['dbname'])==true) $dbname=htmlspecialchars($_POST['dbname']);
if (isset($_POST['dbcharset'])==true) $dbcharset=htmlspecialchars($_POST['dbcharset']);
if (isset($_POST['sql_query'])==true) $sql_query=htmlspecialchars($_POST['sql_query']);
if (isset($_POST['sql_query2'])==true) $sql_query2=htmlspecialchars($_POST['sql_query2']);
if (isset($_POST['rules_scheme'])==true) $rules_scheme=htmlspecialchars($_POST['rules_scheme']);
if (isset($_POST['zlib'])==true) $zlib=htmlspecialchars($_POST['zlib']);
if (isset($_POST['zlib_upload'])==true) $zlib_upload=htmlspecialchars($_POST['zlib_upload']);
if (isset($_POST['file_name'])==true) $file_name=htmlspecialchars($_POST['file_name']);
if (isset($_POST['type_op'])==true) $type_op=htmlspecialchars($_POST['type_op']);
if (isset($_POST['load_data_infile'])==true) $load_data_infile=htmlspecialchars($_POST['load_data_infile']);
if (isset($_POST['sql_server_type'])==true) $sql_server_type=htmlspecialchars($_POST['sql_server_type']);
if (isset($_POST['row_num_per_steep'])==true) $row_num_per_steep=htmlspecialchars($_POST['row_num_per_steep']);
if (isset($_POST['time_for_work'])==true) $time_for_work=htmlspecialchars($_POST['time_for_work']);
if (isset($_POST['programm_name'])==true) $programm_name=htmlspecialchars($_POST['programm_name']);

// Check parametrs
if (strlen($type_op)==0) {
	header("Content-type: text/html; charset=windows-1251");
	
	echo '<p> Вы двигаетесь в верном направлении и Вам удалось успешно установить модуль интеграции E-Trade HTTP Tunnel.<br />
</p>
<p>На данный момент Вам необходимо вставить ссылку, по которой Вы только что перешли на эту страницу, в настройку <br />
  E-Trade HTTP Tunnel в программе серии E-Trade.</p><p>Это можно сделать нажав кнопку &quot;Файл&quot; в верхнем левом углу программы серии E-Trade и выбрать раздел &quot;Импорт данных&quot;.</p>
<img src="http://www.elbuz.com/images/my/file_export_setup_etrade_tunnel.png" alt="" width="154" height="134" border="0">
<br />
<p><img src="http://www.elbuz.com/ETradeDocs/PLI/setup_etrade_tunnel4.png" alt="" width="524" height="563" border="0" align="left">Адрес к туннелю (URL) - это ссылка по которой вы перешли сейчас, <br /> которая отображается в строке адреса сайта.<br />
Имя пользователя и пароль это те данные, которые Вы вводили <br />
переходя по ссылке, до того как увидеть это сообщение.<br />
  <br />
  <a href="http://www.elbuz.com/ETradeDocs/PLI/configure_and_import_data_with_e_trade_http_tunnel.htm">Подробнее в документации, где найти это окно</a> <br />
</p>
<p>После ввода этих данных, необходимо перейти к настройкам <br />
  подключения к базе данных Вашего сайта.<br />
  Как найти эти данные для Вашего движка (CMS интернет-магазина) <br />
  Вы сможете прочитать в документации в разделе <br />
  <a href="http://www.elbuz.com/ETradeDocs/PLI/setting_up_access_to_the_database_site_and_other_settings.htm">Настройка доступа к БД сайта и другие настройки для Вашего движка</a>
</p>
<p>Если вы не смогли настроить модуль интеграции E-Trade HTTP Tunnel - <br />
обратись в службу технической поддержки с указанием проблемы, <br />
постараемся Вам помочь.</p>';

	//SendAnswer("Query 'type_op' is empty!");
	exit;
}
if (strlen($dbhost)==0) {
	if ($debug==1) SendAnswer("Error: Query 'dbhost' is empty!");
	exit;
}
if (strlen($dbuser)==0) {
	if ($debug==1) SendAnswer("Error: Query 'dbuser' is empty!");
	exit;
}
if (strlen($dbname)==0) {
	if ($debug==1) SendAnswer("Error: Query 'dbname' is empty!");
	exit;
}
if (strlen($dbcharset)==0) {
	if ($debug==1) SendAnswer("Error: Query 'dbcharset' is empty!");
	exit;
}
if (strlen($sql_query)==0 && $type_op!="TEST" && $zlib_upload==1) {
	if ($debug==1) SendAnswer("Error: Query 'sql_query' is empty!");
	exit;
}
if (strlen($zlib)==0) {
	if ($debug==1) SendAnswer("Error: Query 'zlib' is empty!");
	exit;
}
if (strlen($zlib_upload)==0) {
	if ($debug==1) SendAnswer("Error: Query 'zlib_upload' is empty!");
	exit;
}

if ($type_op=="CATALOG_CSV") {
	if (strlen($rules_scheme)==0) {
		if ($debug==1) SendAnswer("Error: Query 'rules_scheme' is empty!");
		exit;
	}
	
	if (strlen($sql_query2)==0) {
		if ($debug==1) SendAnswer("Error: Query 'sql_query2' is empty!");
		exit;
	}
}


if (!function_exists('str_getcsv')) {
    function str_getcsv($input, $delimiter = ",", $enclosure = '"', $escape = "\\") {
		$fiveMBs = 5 * 1024 * 1024;
        $fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+');
        fputs($fp, $input);
        rewind($fp);
		
		$data = fgetcsv($fp, strlen($input), $delimiter, $enclosure); //  $escape only got added in 5.3.0
			
        fclose($fp);
        return $data;
    }
} 

//file_put_contents('./temp/log1.txt', print_r($rules_scheme, 1)); // , FILE_APPEND

file_put_contents('./temp/tunnel_work_status.txt', '');

// Connect to DB
$link=connect_db($dbhost, $dbuser, $dbpass, $dbname, $dbcharset, $sql_server_type);

//($link instanceof mysqli_result)
if (is_object($link) === false) {
    SendAnswer('Error: no connect to database!');
	exit;
}



if ($type_op=="TEST") {
	SendAnswer("Connected!\n"."Memory Limit: ".ini_get("memory_limit")."\nPost max size: ".ini_get("post_max_size")."\nLocale: ".$ini_get_locale."\nTunnel ver. ".TUNNEL_VER);
	exit;
}

// Run internal command
if ($type_op=="RUN_COMMAND") {
	// base64 decode
	$sql_query=str_replace(' ','+',$sql_query);
	$sql_query=base64_decode($sql_query);

	$sql_query=str_replace('call_user_func(','',$sql_query);
	$sql_query=str_replace(');','',$sql_query);
	$sql_query=str_replace('"','',$sql_query);

	$sql_query=explode(",", $sql_query);
	$sql_query=array_trim($sql_query);
	
	$function_name=$sql_query[0];
	unset($sql_query[0]); 
	
	if(function_exists($function_name)) {
		call_user_func_array($function_name, $sql_query);
	}
	
	SendAnswer("Complete!");
	exit;
}


if ($zlib_upload==1) { // Unpack sql query
	// base64 decode
	$sql_query=str_replace(' ','+',$sql_query);
	$sql_query=base64_decode($sql_query);

	// save file
	$temp_file_name=md5(microtime());
	file_put_contents('./temp/'.$temp_file_name.'.zip', $sql_query);
	
	// unpack file contents
    if (class_exists('ZipArchive')) {
		$archive = new ZipArchive;
		if ($archive->open('./temp/'.$temp_file_name.'.zip') === true){
			$archive->extractTo('./temp/');
			$archive->close();
		}else{
			echo 'Не могу найти файл архива!';
		}
    } else {
		$archive = new PclZip('./temp/'.$temp_file_name.'.zip');
		$list = $archive->extract(PCLZIP_OPT_PATH, './temp/' ,
								  PCLZIP_OPT_REMOVE_ALL_PATH);
		
		//file_put_contents('./temp/error.txt', $archive->errorInfo(true));
	}

	// delete temp file
	if (file_exists('./temp/'.$temp_file_name.'.zip')) {
		@unlink('./temp/'.$temp_file_name.'.zip');
	}
	
	// get contents from extracted file
	if ($type_op!="CATALOG_CSV") {
		if (file_exists('./temp/'.$file_name)) {
			$sql_query=file_get_contents('./temp/'.$file_name);
			@unlink('./temp/'.$file_name);
		}
	}
	
	if (file_exists('./temp/next_row.txt')) {
		@unlink('./temp/next_row.txt');
	}	
}



// Import CSV catalog
if ($type_op=="CATALOG_CSV") {
	$import_file_name=$base_path.'temp'.$dir_separator.$file_name;
	if (file_exists($import_file_name)==false) {
		SendAnswer("Error: File ".$import_file_name." not exists!");
		exit;
	}
	
	// Create temporary tables for import CSV catalog
	run_sql_commands($sql_query2, $type_op, $link, 1, "", 0);

	$rules_scheme=str_replace(' ','+',$rules_scheme);
	$rules_scheme=base64_decode($rules_scheme);
	$import_rules_obj=json_decode($rules_scheme, true);

	if ($load_data_infile=='1') { // fast mode: LOAD DATA INFILE

			
		$field_names='';
		for ($iField = 2; $iField <= 100; $iField++) {
			$field_names.='`field'.$iField.'` MEDIUMTEXT NOT NULL, ';
		}
		
		sql_query_run("DROP TABLE IF EXISTS `etrade_ldif_tmp`", 0) or die(SendAnswer("Error: ". sql_error()));
		sql_query_run("CREATE TABLE IF NOT EXISTS `etrade_ldif_tmp` (field1 varchar(240), ".$field_names." 
			  KEY `field1` (`field1`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8", 0) or die(SendAnswer("Error: ". sql_error()));
		
		sql_query_run("LOAD DATA LOCAL INFILE '".$import_file_name."' INTO TABLE `etrade_ldif_tmp` LINES TERMINATED BY '\r\n'", 0) or die(SendAnswer("Error: ". sql_error()));
		
		foreach($import_rules_obj as $rules_block) {
			// $total_fields=substr_count($rules_block['fields_list'], ',')+1; for CC
			$total_fields=substr_count($rules_block['fields_list'], ',')+2;
			$field_names='';
			//for ($iField = 1; $iField <= $total_fields; $iField++) { // for CC
			for ($iField = 2; $iField <= $total_fields; $iField++) {
				$field_names.='field'.$iField;
				if ($iField<>$total_fields) $field_names.=", ";
			}
			
			sql_query_run("INSERT INTO ".$rules_block['table_name']." (".$rules_block['fields_list'].") SELECT ".$field_names." FROM etrade_ldif_tmp WHERE field1='".$rules_block['row_type']."'", 0) or die(SendAnswer("Error: ". sql_error()));
		}

		sql_query_run("DROP TABLE IF EXISTS `etrade_ldif_tmp`", 0) or die(SendAnswer("Error: ". sql_error()));

	} else {
		$next_row_id=0;
		if (file_exists('./temp/next_row.txt')) {
			$next_row_id=(int)file_get_contents('./temp/next_row.txt')+1;
		}
		
		$total_columns=100;
	
		if ($next_row_id==0) {
			$field_names='';
			for ($iField = 2; $iField <= $total_columns; $iField++) {
				$field_names.='`field'.$iField.'` MEDIUMTEXT NOT NULL, ';
			}
			
			sql_query_run("DROP TABLE IF EXISTS etrade_ldif_tmp", 0) or die(SendAnswer("Error: ". sql_error()));
			sql_query_run("CREATE TABLE IF NOT EXISTS etrade_ldif_tmp (field1 varchar(240), ".$field_names." 
				  KEY `field1` (`field1`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8", 0) or die(SendAnswer("Error: ". sql_error()));
		}

		$field_names_insert='';
		for ($iField = 1; $iField <= $total_columns; $iField++) {
			$field_names_insert.='`field'.$iField.'`';
			$field_names_insert.= (($iField == $total_columns) ? "" : ", ");
		}
		
		$sql_insert="";
		$sql_insert_header="INSERT INTO etrade_ldif_tmp (".$field_names_insert.") VALUES ";

		$current_row = $next_row_id;
		$time_start = microtime(1);
		
		$csv_file_contents = file($import_file_name);

		if ($next_row_id>0) $csv_file_contents = array_splice($csv_file_contents, $next_row_id-1);
		
		for ($iRow = 0; $iRow <= count($csv_file_contents)+1; $iRow++) {
			$data = str_getcsv($csv_file_contents[$iRow], "\t");
			$current_row++;
			$my_str = "";
			
			if (is_array($data)==false) continue;
			
			foreach ($data as $column_row_data) {
				$my_str.= ((strlen($my_str)>0) ? "," : "")." '".wash_string($column_row_data)."'";
			}
			
			if (count($data)<$total_columns) {
				for ($iField = 1; $iField <= $total_columns-count($data); $iField++) {
					$my_str.= ((strlen($my_str)>0) ? "," : "")." ''";
				}
			}
			
			$sql_insert.= "(".$my_str."),".chr(10); 
			
			if ($current_row % $row_num_per_steep == false) {
				$sql_insert = substr($sql_insert,0,-2);
				sql_query_run($sql_insert_header.$sql_insert) or die(SendAnswer("Invalid query. Error: ". sql_error()));
				$sql_insert="";
				
				$time_worked = round(microtime(1) - $time_start,2);	
				if ($time_for_work>0 && $time_worked>$time_for_work) {
					file_put_contents('./temp/next_row.txt', $current_row);
					SendAnswer("stopped at row: ".$current_row);
					exit;
				}				
			}
				
		}
		
		if (!empty($sql_insert)) {
			$sql_insert = substr($sql_insert,0,-2);
			sql_query_run($sql_insert_header.$sql_insert) or die(SendAnswer("Invalid query: ".$rules_block['table_name'].". Error: ". sql_error()));
		}
		
/* 		if ($programm_name=="E-T-CC") { // "E-T-PLI"
		} 
*/

//file_put_contents('./temp/log1.txt', print_r($import_rules_obj, 1)); // , FILE_APPEND
//file_put_contents('./temp/log2.txt', $rules_scheme); 

		foreach($import_rules_obj as $rules_block) {
			$iFieldStart=2;
			$iFieldTotalAddon=1;			

			$sql_insert="";
			$sql_insert_header="INSERT INTO ".$rules_block['table_name']."(".$rules_block['fields_list'].") SELECT ";
			$fields_values = explode(",", $rules_block['fields_values']);

			if (stripos($rules_block['table_name'], 'etrade_cc_filters') !== false) {
				$iFieldStart=1; 
				$iFieldTotalAddon=0;				
			}
			
			for ($iField = $iFieldStart; $iField <= count($fields_values)+$iFieldTotalAddon; $iField++) {
				$sql_insert.= ((strlen($sql_insert)>0) ? ", " : "")."field".$iField;
			}
			
			//$data_test=$sql_insert_header.$sql_insert." FROM etrade_ldif_tmp WHERE field1='".$rules_block['row_type']."'".chr(10);
			//file_put_contents('./temp/log3.txt', $data_test, FILE_APPEND); // , FILE_APPEND
			
			sql_query_run($sql_insert_header.$sql_insert." FROM etrade_ldif_tmp WHERE field1='".$rules_block['row_type']."'") or die(SendAnswer("Invalid query: ".$rules_block['table_name'].". Error: ". sql_error()));
		}
	}

	sql_query_run("DROP TABLE IF EXISTS etrade_ldif_tmp", 0) or die(SendAnswer("Error: ". sql_error()));
	
	// Delete temp file
	@unlink($import_file_name);
	if (file_exists('./temp/next_row.txt')) {
		@unlink('./temp/next_row.txt');
	}
		
	SendAnswer("Complete!");
	exit;
}


$server_answer=run_sql_commands($sql_query, $type_op, $link, 1, $file_name, $zlib);
SendAnswer($server_answer);
unset($server_answer);




// Connecting to the database
function connect_db($dbhost, $dbuser, $dbpass, $dbname, $dbcharset, $sql_server_type, $port = 3306) {
	if ($sql_server_type=='' or $sql_server_type=='mysql') {
		
		//$link = new \mysqli($dbhost, $dbuser, $dbpass, $dbname, $port);
		
		$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);


		if (mysqli_connect_errno()) {
			SendAnswer('Error: Could not make a database link (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			return 0;
		}

		mysqli_set_charset($link, $dbcharset);
		mysqli_query($link, "SET SQL_MODE = ''");
		mysqli_query($link, "SET SQL_BIG_SELECTS=1");
		
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	}
	
	if ($sql_server_type=='postgresql') {
		if(!$link = pg_connect('host='.$dbhost.' port=5432 user='.$dbuser.' password='.$dbpass.' dbname='.$dbname)) {
		  SendAnswer("Error: could not make a database link using " . $dbuser . '@' . $dbhost);
		  return 0;
		}

		if (is_resource($link)==FALSE) {
		  SendAnswer("Error: resource link is false, could not make a database link using " . $dbuser . '@' . $dbhost);
		  return 0;
		}
		
		//pg_query($link, "SET lock_timeout = 0");
		//pg_query($link, "SET client_encoding = 'UTF-8'");
	}
	
	/* MSSQL
    //$connectionInfo = array("UID"=>$uid,"PWD"=>$pwd,"Database"=>$db);
    //$conn =sqlsrv_connect($myServer, $connectionInfo);
	 
     if( $conn === false )
        {
          echo "Unable to connect.</br>";
          die( print_r( sqlsrv_errors(), true));
        }
	*/
	
	return $link;
}

function sql_fetch_row($result) {
	global $link, $sql_server_type;
	
	if ($sql_server_type=='' or $sql_server_type=='mysql') {
		if ($result === false) { 
			die(SendAnswer("Error: ". sql_error()));
		}

		return mysqli_fetch_row($result);
	}
	
	if ($sql_server_type=='postgresql') {
		return pg_fetch_row($result); // pg_fetch_assoc
	}
}

function sql_query_run($sql, $unbuffered = 0) {
	global $link, $sql_server_type;
	
	if (empty($sql) or trim($sql)==';') return false;
	
	if ($sql_server_type=='' or $sql_server_type=='mysql') {
		if ($unbuffered==0) {
			return mysqli_query($link, $sql);
		} else {
			return $link->query($sql, MYSQLI_USE_RESULT);
			//return mysqli_query($link, $sql);
			
		}
	}
	
	if ($sql_server_type=='postgresql') {
		return pg_query($link, $sql);
	}
}

function sql_error() {
	global $link, $sql_server_type;
	
	if ($sql_server_type=='' or $sql_server_type=='mysql') {
		return mysqli_error($link);
	}
	
	if ($sql_server_type=='postgresql') {
		return pg_last_error($link);
	}
}

// Run SQL Commands
function run_sql_commands($sql_query, $type_op, $link, $base64_decode, $file_name, $pack_answer) {
		
	$csv_result_all="";

	if ($base64_decode==1) {
		$sql_query=str_replace(' ','+',$sql_query);
		$sql_query=base64_decode($sql_query);
	}
	
	if (file_exists('./temp/'.$file_name.'.csv')) @@unlink('./temp/'.$file_name.'.csv');
	
	$row_id=0;
	$row_num_per_steep = 500;
	
	$sql_query_all = explode(";;;", $sql_query);
	foreach ($sql_query_all as $my_sql_query) {
		$my_sql_query=trim(str_replace(array(chr(13).chr(10), chr(13), chr(10)), ' ', $my_sql_query));
		if (strlen($my_sql_query)>0) {
			//$result = sql_query_run($my_sql_query, 0) or die(SendAnswer("Error: ". sql_error(). "\r\n SQL: ".$my_sql_query));
			//file_put_contents('./temp/was.txt', $my_sql_query);
			$result = sql_query_run($my_sql_query, 1) or die(SendAnswer("Error: ". sql_error(). "\r\n SQL: ".$my_sql_query));
			// mysqli_query returns false if something went wrong with the query
			if ($result === false) { 
				die(SendAnswer("Error: ". sql_error(). "\r\n SQL: ".$my_sql_query));
			}
			
			//if ($type_op=="SELECT" && is_resource($result)==TRUE) {
			//if ($type_op=="SELECT" && mysqli_num_rows($result)>0) {
			if ($type_op=="SELECT" && is_object($result)) {
				$field_separator="\t";
				$row_separator="\r\n";

				while($row = sql_fetch_row($result)) {
					$row=str_replace(array(chr(13), chr(10), chr(9), chr(32).chr(32)), ' ', $row);
					$row=str_replace(array(chr(32).chr(32).chr(32).chr(32), chr(32).chr(32).chr(32), chr(32).chr(32)), ' ', $row);
					$csv_result_all.=implode($field_separator, $row).$row_separator;
					
					$row_id++;
					if ($row_id % $row_num_per_steep == false) {
						file_put_contents('./temp/'.$file_name.'.csv', $csv_result_all, FILE_APPEND);
						$csv_result_all='';
					}
				}
				
				mysqli_free_result($result);
			}
			
			//if ($type_op=="INSERT") $server_answer.=" ID: ".$link->insert_id;
		}
	}
	
	if ($type_op=="SELECT" ) {
		if ($pack_answer==1 && strlen($file_name)>0) { // Pack answer
			file_put_contents('./temp/'.$file_name.'.csv', $csv_result_all, FILE_APPEND);
	
		    if (class_exists('ZipArchive')) {
				$zip = new ZipArchive;
				$res = $zip->open('./temp/'.$file_name.'.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
				if ($res === TRUE) {		
					$zip->addFile('./temp/'.$file_name.'.csv');
					//$zip->addFromString('./temp/'.$file_name.'.csv', $csv_result_all);
				}
				$zip->close();
			} else {
				$archive = new PclZip('./temp/'.$file_name.'.zip');
				$list = $archive->add('./temp/'.$file_name.'.csv', PCLZIP_OPT_REMOVE_ALL_PATH);
			}

			if (file_exists('./temp/'.$file_name.'.zip')) {
				$csv_result_all=file_get_contents('./temp/'.$file_name.'.zip');
				@unlink('./temp/'.$file_name.'.zip');
			}
			if (file_exists('./temp/'.$file_name.'.csv')) {
				@unlink('./temp/'.$file_name.'.csv');
			}
			
			
		}
		
		
	}
	
	if (strlen($csv_result_all)==0) {
		$csv_result_all="Complete!";
	}
	
	return $csv_result_all;
}

function wash_string($string) {
	global $link, $ConvertSpecialCharactersToHTMLEntities;
	
	//Conversion from utf8 to win1251
	// if (DB_CHARSET=="utf8") {
		// $string=iconv("windows-1251", 'utf-8', $string);
	// }
	
	// Remove line breaks
	$string=str_replace(array(chr(13).chr(10), chr(13), chr(10), chr(9)) , ' ' , $string);
	
	// Convert special characters to HTML entities
	if ($ConvertSpecialCharactersToHTMLEntities==1) $string=htmlspecialchars($string, ENT_QUOTES);
	
	// Mnemoni special characters in a string for use in a SQL statement, taking into account the current charset / charset connection
	if (get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	
	$string=mysqli_real_escape_string($link, $string);
			
	return $string;
}


// Send answer
function SendAnswer ($text) {

	global $link, $sql_server_type;
	
	if ($sql_server_type=='' or $sql_server_type=='mysql') {
		if (is_object($link)) {
			mysqli_close($link);
		}
	}
	
	if ($sql_server_type=='postgresql') {
		if (is_resource($link)==TRUE) {
			pg_close($link);
		}
	}
	
	@file_put_contents('./temp/tunnel_work_status.txt', $text);
	
	echo base64_encode($text);
	unset($text);
}

// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        //return;
    }

    switch ($errno) {
		case E_USER_ERROR:
			$type_error="Fatal error ";
			break;
		case E_USER_WARNING:
			$type_error="WARNING ";
			break;
		case E_USER_NOTICE:
			$type_error="NOTICE ";
			break;
		default:
			$type_error="Unknown error type ";
			break;
    }
	
	SendAnswer(error_reporting()."Error: ".$type_error."\nFile: ".$errfile."\nMessage: ".$errstr."\nLine: ".$errline);
	
    /* Don't execute PHP internal error handler */
    return true;
}

function fatalErrorShutdownHandler() {
	if (version_compare(phpversion(), "5.2")>=0) {
		$last_error = error_get_last();
		if ($last_error['type'] === E_ERROR) {
			// fatal error
			myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}
}

// Удаление пробелов во всех элементах массива
function array_trim( $array ) {
	return array_map( 'trim', $array );
}

?>