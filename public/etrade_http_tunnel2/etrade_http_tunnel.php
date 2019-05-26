<?php
/* ##########################
	E-Trade Http Tunnel v2
	HTTP tunnel script.    
	
	Copyright (c) 2011-2019 ElbuzGroup
	https://elbuz.com
   ##########################
*/

header("Content-type: text/html; charset=utf-8");

if (version_compare(phpversion(), "4")<=0) {
	echo 'Версия PHP '.phpversion().' не совместима для работы E-Trade Http Tunnel, обновите версию PHP до 5 и выше.';
	exit;
}

$tunnel_ver = '';
if (is_file('./version_tunnel_last.txt')) {
	$tunnel_ver = file_get_contents('./version_tunnel_last.txt');
}

$convert_special_characters_to_html_entities=0;
$convert_special_characters_from_html_entities=1;

$work_dir=dirname(__FILE__);

ini_set('log_errors_max_len', 0);
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


if (stristr(PHP_OS, 'WIN')) { // Detect operation system
	$dir_separator='\\\\';
} else {
	$dir_separator='/';
}
$base_path=dirname(__FILE__).$dir_separator;
$base_path=str_ireplace('\\', '\\\\', $base_path); // for Windows OS only


require_once($work_dir.'/etrade_http_tunnel_login.php'); // Auth setup

if (isset($new_password)) {
	$password = $new_password;
}

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
	
require_once($work_dir.'/etrade_http_tunnel_ifunc.php');
require_once($work_dir.'/json.php');
//require_once($work_dir.'/pclzip.lib.php');
require_once($work_dir.'/fgetcsv.php');

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
if (is_dir('./temp/update/')==false) {
	mkdir('./temp/update/', 0777, true);
}
if (is_dir('./backup/')==false) {
	mkdir('./backup/', 0777, true);
}


date_default_timezone_set('Europe/Kiev');
ini_set("memory_limit", "1024M");
ini_set("post_max_size", "256M");
ini_set("upload_max_filesize", "256M");
ini_set("max_execution_time", "30000");
ini_set("max_input_time", "6000");
ini_set('auto_detect_line_endings', '1');

$ini_get_locale=setlocale(LC_ALL, array('ru_RU.UTF8', 'ru_RU.utf8', 'ru_ru.UTF8', 'ru_ru.utf8', 'ru_RU.CP1251', 'ru_RU.cp1251', 'ru_ru.CP1251', 'ru_ru.cp1251', 'ru_RU', 'ru_ru', 'Russian', 'ru_UA'));

// Get options
$load_data_infile='0';
$sql_server_type='';
$sql_query='';
$programm_name='';
$row_num_per_steep = 150; // Количество строк отправляемых в СУБД за 1 пакет.
$time_for_work = 20; // Количество секунд для работы. При превышении этого времени скрипт останавливает свою работу.
$check_db_setting=1;
$custom_data='';
$stop_work = 0;
$dbcollate = 'utf8_general_ci';
$dbengine = 'MyISAM';

//$data = file_get_contents('php://input');
if (isset($_POST['check_db_setting'])==true) $check_db_setting=htmlspecialchars($_POST['check_db_setting']);
if (isset($_POST['dbhost'])==true) $dbhost=htmlspecialchars($_POST['dbhost']);
if (isset($_POST['dbport'])==true) $dbport=htmlspecialchars($_POST['dbport']);
if (isset($_POST['dbuser'])==true) $dbuser=htmlspecialchars($_POST['dbuser']);
if (isset($_POST['dbpass'])==true) $dbpass=htmlspecialchars($_POST['dbpass']);
if (isset($_POST['dbname'])==true) $dbname=htmlspecialchars($_POST['dbname']);
if (isset($_POST['dbcharset'])==true) $dbcharset=htmlspecialchars($_POST['dbcharset']);
if (isset($_POST['dbcollate'])==true && !empty($_POST['dbcollate'])) $dbcollate=htmlspecialchars($_POST['dbcollate']);
if (isset($_POST['dbengine'])==true && !empty($_POST['dbengine'])) $dbengine=htmlspecialchars($_POST['dbengine']);
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
if (isset($_POST['custom_data'])==true) $custom_data=htmlspecialchars($_POST['custom_data']);
if (isset($_POST['stop_work'])==true) $stop_work=htmlspecialchars($_POST['stop_work']);

if (empty($dbport) ? 3306 : $dbport);

// Check parametrs
if (strlen($type_op)==0) {
	header("Content-type: text/html; charset=windows-1251");
	
	echo '<p> Вы двигаетесь в верном направлении и Вам удалось успешно установить модуль интеграции E-Trade HTTP Tunnel.</p>';

	//SendAnswer("Query 'type_op' is empty!");
	exit;
}
if ($check_db_setting==1) {
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
}
if (strlen($sql_query)==0 && $type_op != "TEST" && $type_op != "TUNNEL_UPDATE" && $zlib_upload==1) {
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


if (isset($new_password) && $type_op=="TUNNEL_UPDATE") { // обновление модуля
	
	// ключ jumper
	$jumper_key = (!isset($_POST['jumper_key']) ? '' : $_POST['jumper_key']);
	if (empty($jumper_key)) exit;

	// ссылка на сервер обновлений
	$update_server_url = (!isset($_POST['update_server_url']) ? '' : $_POST['update_server_url']);
	if (empty($update_server_url)) exit;
	
	// качаем файл для обновления
	$update_src_file = file_get_contents($update_server_url . "?jumper_key=". $jumper_key ."&action=get-update-tunnel&hash=".rand(1000,999999));
	if (empty($update_src_file)) {
		SendAnswer(base64_encode(serialize(array('error' => 1, 'text' => "Error: Tunnel src empty file!"))), 0);
		//SendAnswer(serialize($current_site_config), 0);
		exit;
	} else {
		file_put_contents($base_path . "temp/update/tunnel_src.zip", $update_src_file);
	}
	
	// обновляем версию туннеля
	ob_end_clean();
	if (is_file($base_path . "temp/update/tunnel_src.zip")) {
		$zip = new ZipArchive;
		$zip->open($base_path . "temp/update/tunnel_src.zip");
		$zip->extractTo($base_path); // extract to root folder with tunnel!

		$extract_status = $zip->getStatusString(); // статус распаковки
		if (!empty($extract_status) && $extract_status != 'No error') {
			$zip->close();
			if(file_exists($base_path . "temp/update/tunnel_src.zip")) unlink($base_path . "temp/update/tunnel_src.zip"); // Удаляем tmp-архив
			
			SendAnswer(base64_encode(serialize(array('error' => 1, 'text' => "Error: ".$extract_status))), 0);
		}
		
		$zip->close();
		if(file_exists($base_path . "temp/update/tunnel_src.zip")) unlink($base_path . "temp/update/tunnel_src.zip"); // Удаляем tmp-архив
		
		SendAnswer(base64_encode(serialize(array('error' => 0, 'text' => "Update success!"))), 0);
	}
	
	exit;
}

if ($type_op=="BACKUP_DB") { // архивация БД сайта
	require_once($work_dir.'/etrade_mysqldump.php');

	$include_tables = (!isset($_POST['include_tables']) ? '' : $_POST['include_tables']);
	$include_tables = array_map('trim', explode(',', $include_tables));
	
	$compress = (!isset($_POST['compress']) ? '1' : $_POST['compress']);
	$compress = ($compress==1 ? 'Gzip' : 'None');
	
	$file_max_days = (!isset($_POST['file_max_days']) ? '2' : $_POST['file_max_days']);
	$file_prefix = (!isset($_POST['file_prefix']) ? '' : $_POST['file_prefix']);
	
	if (count($include_tables)==0) {
		SendAnswer(base64_encode(serialize(array('error' => 1, 'text' => 'Empty param: include tables!'))), 0);
		exit;
	}
	
	// удаляем старые файлы
	$fileSystemIterator = new FilesystemIterator('./backup/');
	$now = time();
	foreach ($fileSystemIterator as $file) {
		if ($now - $file->getCTime() >= 60 * 60 * 24 * $file_max_days) 
			unlink('./backup/'.$file->getFilename());
	}

	// настройки Mysqldump
	$dumpSettingsDefault = array(
		'include-tables' => $include_tables,
		'exclude-tables' => array(),
		'compress' => $compress, // Ifsnop\Mysqldump\Mysqldump::GZIP
		'init_commands' => array(),
		'no-data' => array(),
		'reset-auto-increment' => false,
		'add-drop-database' => false,
		'add-drop-table' => true,
		'add-drop-trigger' => true,
		'add-locks' => true,
		'complete-insert' => true,
		'databases' => false,
		'default-character-set' => Ifsnop\Mysqldump\Mysqldump::UTF8,
		'disable-keys' => true,
		'extended-insert' => true,
		'events' => false,
		'hex-blob' => true, /* faster than escaped content */
		'net_buffer_length' => 1000000,
		'no-autocommit' => true,
		'no-create-info' => false,
		'lock-tables' => true,
		'routines' => false,
		'single-transaction' => true,
		'skip-triggers' => true,
		'skip-tz-utc' => false,
		'skip-comments' => true,
		'skip-dump-date' => false,
		'skip-definer' => true,
		'where' => '',
		/* deprecated */
		'disable-foreign-keys-check' => true
	);
	
	// запускаем архивацию
	$unix_socket = ini_get("mysqli.default_socket");
	$file_backup = $file_prefix . $dbname .'-'. date('d-m-Y-H-i') .'.sql'. ($compress=='Gzip' ? '.gz' : '');
	$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.$dbhost.';port='.$dbport.';dbname='.$dbname.';unix_socket='.$unix_socket, $dbuser, $dbpass, $dumpSettingsDefault);
	$dump->start('./backup/'. $file_backup);	
	SendAnswer(base64_encode(serialize(array('error' => 0, 'text' => $file_backup))), 0);
	exit;
}

if ($type_op=="BACKUP_DB_RESTORE") { // восстановление БД сайта
	require_once($work_dir.'/etrade_mysqlimport.php');
	
	$file_backup = (!isset($_POST['file_backup']) ? '' : $_POST['file_backup']);
	
	if (empty($file_backup) || !is_file('./backup/' . $file_backup)) {
		SendAnswer(base64_encode(serialize(array('error' => 1, 'text' => 'File "'. './backup/' . $file_backup .'" not found!'))), 0);
		exit;
	}
	
	$db_link = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
	$import = new MySQLImport($db_link);
	$import->load('./backup/' . $file_backup);
	mysqli_close($db_link);

	SendAnswer(base64_encode(serialize(array('error' => 0, 'text' => 'Ok'))), 0);
	exit;
}

if ($type_op=="BACKUP_DB_FILE_LIST") { // список архивных копий БД сайта
	// удаляем старые файлы
	$fileSystemIterator = new FilesystemIterator('./backup/');
	$now = time();
	$file_list = array();
	foreach ($fileSystemIterator as $file) {
		$file_list[] = $file->getFilename();
	}
	
	SendAnswer(base64_encode(serialize(array('error' => 0, 'text' => $file_list))), 0);
	exit;
}

/*
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
*/


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
if ($check_db_setting==1) {
	$link=connect_db($dbhost, $dbuser, $dbpass, $dbname, $dbcharset, $sql_server_type, $dbport);

	//($link instanceof mysqli_result)
	if (is_object($link) === false) {
		SendAnswer('Error: no connect to database!');
		exit;
	}
}

if ($type_op=="CONNECT_CHECK") {
	$current_site_config = array(
			'memory_limit' => ini_get("memory_limit"),
			'post_max_size' => ini_get("post_max_size"),
			'upload_max_filesize' => ini_get("upload_max_filesize"),
			'max_execution_time' => ini_get("max_execution_time"),
			'extension_loaded_zip' => extension_loaded('zip'),
			'extension_loaded_mysqli' => extension_loaded('mysqli'),
			'extension_loaded_curl' => extension_loaded('curl'),
			'locale' => $ini_get_locale,
			'tunnel_ver' => $tunnel_ver
		);
	
	SendAnswer(base64_encode(serialize($current_site_config)), 0);
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
	
	$result='';
	if(function_exists($function_name)) {
		$result = call_user_func_array($function_name, $sql_query);
	}
	
	if (empty($result)) {
		SendAnswer("Complete!", 0);
	} else {
		SendAnswer($result, 0);
	}
	
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
	ob_end_clean();
    if (class_exists('ZipArchive')) {
		$archive = new ZipArchive;
		if ($archive->open('./temp/'.$temp_file_name.'.zip') === true){
			$archive->extractTo('./temp/');
			$archive->close();
		}else{
			echo 'Не могу найти файл архива!';
		}
    } else {
		SendAnswer("Error: module php ZipArchive not exists!");
		return;
		
		/*
		$archive = new PclZip('./temp/'.$temp_file_name.'.zip');
		$list = $archive->extract(PCLZIP_OPT_PATH, './temp/' ,
								  PCLZIP_OPT_REMOVE_ALL_PATH);
		*/
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
	
	if ($dbcharset=='cp1251') { // конвертируем кодировку файла utf8 в cp1251
		copy($import_file_name, $import_file_name.'_new.txt');
		$fd = fopen($import_file_name.'_new.txt', 'r');
		stream_filter_append($fd, 'convert.iconv.UTF-8/CP1251');
		$fd2 = fopen($import_file_name, 'w');
		stream_copy_to_stream($fd, $fd2);
		fclose($fd);
		fclose($fd2);
		unlink($import_file_name.'_new.txt');
	}

	if (!empty($custom_data)) {
		$custom_data = base64_decode($custom_data);
		$custom_data = unserialize($custom_data);
		
		// SQL запросы 
		if (!empty($custom_data['sql_query_list'])) {
			run_sql_commands($custom_data['sql_query_list'], '', $link, 0, "", 0);
		}
	}
	
	// пробуем использовать режим LOAD DATA LOCAL INFILE
	if (is_array($custom_data)) { // если это режим наполенения указанной таблицы по указанным столбцам
		$local_infile_query = mysqli_query($link, "SHOW VARIABLES LIKE 'local_infile'");
		if (mysqli_num_rows($local_infile_query)>0) {
			$local_infile = mysqli_fetch_row($local_infile_query);
			$local_infile = $local_infile[1];
			
			if ($local_infile=='ON') {
				//CREATE TABLE `test_utf8mb4` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `v` varchar(100) DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
				//CHARACTER SET latin1
				//CHARACTER SET utf8mb4
				sql_query_run("LOAD DATA LOCAL INFILE '".$import_file_name."' 
					INTO TABLE ". $custom_data['table_name'] ." 
					CHARACTER SET ". $dbcharset ." 
						FIELDS 
							TERMINATED BY '\t' 
							LINES TERMINATED BY '\r\n' (".$custom_data['field_name_list'].")", 0) or die(SendAnswer("Error: ". sql_error()));
			} else {
				SendAnswer("Error! Need mode LOAD DATA LOCAL INFILE! Variable local_infile: ".$local_infile);
			}
			
			unlink($import_file_name);
			
			SendAnswer("Complete!");
			exit;
		}
	}
	
	// Create temporary tables for import CSV catalog
	//run_sql_commands($sql_query2, $type_op, $link, 1, "", 0);

	$next_row_id=0;
	if (file_exists($base_path.'temp/next_row.txt')) {
		$next_row_id=(int)file_get_contents($base_path.'temp/next_row.txt');
		$next_row_id++;
	}
	
	if (is_array($custom_data)) { // если это режим наполенения указанной таблицы по указанным столбцам
		$total_columns = substr_count($custom_data['field_name_list'], ',');
		$total_columns++;
		
		$sql_insert_header="INSERT INTO ". $custom_data['table_name'] ." (".$custom_data['field_name_list'].") VALUES ";
	} else { // универсальная таблица 
		$total_columns=100;

		if ($next_row_id==0) {
			$field_names='';
			for ($iField = 2; $iField <= $total_columns; $iField++) {
				$field_names.='`field'.$iField.'` MEDIUMTEXT NOT NULL, ';
			}
			//COLLATE=utf8_unicode_ci 
			sql_query_run("DROP TABLE IF EXISTS etrade_ldif_tmp", 0) or die(SendAnswer("Error: ". sql_error()));
			sql_query_run("CREATE TABLE IF NOT EXISTS etrade_ldif_tmp (field1 varchar(240), ".$field_names." 
				  KEY `field1` (`field1`)
				) ENGINE=". $dbengine ." DEFAULT CHARSET=". $dbcharset ." COLLATE ". $dbcollate, 0) or die(SendAnswer("Error: ". sql_error()));
		}

		$field_names_insert='';
		for ($iField = 1; $iField <= $total_columns; $iField++) {
			$field_names_insert.='`field'.$iField.'`';
			$field_names_insert.= (($iField == $total_columns) ? "" : ", ");
		}
		
		$sql_insert_header="INSERT INTO etrade_ldif_tmp (".$field_names_insert.") VALUES ";
	}
	
	$sql_insert="";
	$current_row = $next_row_id;
	$time_start = microtime(1);

	// new code at 08022017
	$file_row = 0;
	$catalog_csv_handle = fopen($import_file_name, "r");
	if ($catalog_csv_handle) {
		while (($line_data = fgets($catalog_csv_handle)) !== FALSE) {
			$file_row++;
			if ($next_row_id>0 && $file_row < $next_row_id) {
				continue;
			}
			
			// text empty line
			$data_test = str_replace(array(chr(13), chr(10), chr(9)), '', $line_data);
			if (empty($data_test)) continue;
			
			// get CVS line
			$data = str_getcsv($line_data, "\t");
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
				
				$time_worked = round(microtime(1) - $time_start, 2);	
				if ($time_for_work>0 && $time_worked>$time_for_work) {
					fclose($catalog_csv_handle);
					
					file_put_contents('./temp/next_row.txt', $current_row);
					SendAnswer("stopped at row: ".$current_row);
					exit;
				}
			}
		
		} 
		fclose($catalog_csv_handle);
	}
	
	
	if (!empty($sql_insert)) {
		$sql_insert = substr($sql_insert,0,-2);
		sql_query_run($sql_insert_header.$sql_insert) or die(SendAnswer("Error: ". sql_error()));
	}
	
	if (is_array($custom_data)) {
		sql_query_run("ALTER TABLE ". $custom_data['table_name'] ." ENABLE KEYS", 0) or die(SendAnswer("Error: ". sql_error()));
	}
	
	// Delete temp file
	@unlink($import_file_name);
	if (file_exists('./temp/next_row.txt')) {
		unlink('./temp/next_row.txt');
	}
		
	SendAnswer("Complete!");
	exit;
}


// RUN SQL
$server_answer=run_sql_commands($sql_query, $type_op, $link, 1, $file_name, $zlib);
SendAnswer($server_answer);



// Connecting to the database
function connect_db($dbhost, $dbuser, $dbpass, $dbname, $dbcharset, $sql_server_type, $dbport = 3306) {
	if ($sql_server_type=='' or $sql_server_type=='mysql') {
		
		$link = mysqli_init();
		mysqli_options($link, MYSQLI_OPT_LOCAL_INFILE, true);
		mysqli_real_connect($link, $dbhost, $dbuser, $dbpass, $dbname, $dbport);
		
		//$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname, $dbport);

		if (mysqli_connect_errno()) {
			SendAnswer('Error: Could not make a database link (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			return 0;
		}

		mysqli_set_charset($link, $dbcharset);
		mysqli_query($link, "SET NAMES ". $dbcharset ."");
		//SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
		//SET CHARACTER SET utf8mb4;
		mysqli_query($link, "SET SQL_MODE = 'NO_BACKSLASH_ESCAPES'");
		mysqli_query($link, "SET SQL_BIG_SELECTS=1");
		
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	}
	
	if ($sql_server_type=='postgresql') {
		$dbport = ($dbport==3306 ? '5432' : $dbport);
		
		if(!$link = pg_connect('host='.$dbhost.' port='. $dbport .' user='.$dbuser.' password='.$dbpass.' dbname='.$dbname)) {
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
			//file_put_contents('./temp/sql_query.sql', $sql.chr(10), FILE_APPEND);
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
	//file_put_contents('./temp/sql_query.sql', $sql_query);
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
			
			ob_end_clean();
		    if (class_exists('ZipArchive')) {
				$zip = new ZipArchive;
				$res = $zip->open('./temp/'.$file_name.'.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
				if ($res === TRUE) {		
					$zip->addFile('./temp/'.$file_name.'.csv', $file_name.'.csv');
					//$zip->addFromString('./temp/'.$file_name.'.csv', $csv_result_all);
				}
				$zip->close();
			} else {
				/*
				$archive = new PclZip('./temp/'.$file_name.'.zip');
				$list = $archive->add('./temp/'.$file_name.'.csv', PCLZIP_OPT_REMOVE_ALL_PATH);
				*/
				
				SendAnswer("Error: module php ZipArchive not exists!");
				return;
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
	global $link, $convert_special_characters_to_html_entities;
	
	//Conversion from utf8 to win1251
	// if (DB_CHARSET=="utf8") {
		// $string=iconv("windows-1251", 'utf-8', $string);
	// }
	
	// Remove line breaks
	$string=str_replace(array(chr(13).chr(10), chr(13), chr(10), chr(9)) , ' ' , $string);
	
	// Convert special characters to HTML entities
	if ($convert_special_characters_to_html_entities==1) $string=htmlspecialchars($string, ENT_QUOTES);
	if ($convert_special_characters_from_html_entities==1) $string=htmlspecialchars_decode($string);

	//html_entity_decode
	//htmlspecialchars_decode 
	
	// Mnemoni special characters in a string for use in a SQL statement, taking into account the current charset / charset connection
	if (get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	
	$string=mysqli_real_escape_string($link, $string);
			
	return $string;
}

function db_escape($value) {
	global $link;
	return mysqli_real_escape_string($link, $value);
}
	
// Send answer
function SendAnswer($text, $put_file = 1) {

	global $link, $sql_server_type, $base_path;
	
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
	
	if ($put_file==1) @file_put_contents($base_path.'/temp/tunnel_work_status.txt', $text);
	
	//echo base64_encode($text);
	echo $text;
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

// изменение поля в таблице
function alter_table($table_name, $column_name, $table_alter_sql) {
	global $link;
	
	$result = mysqli_num_rows(mysqli_query($link, "SHOW COLUMNS FROM `". db_escape($table_name) ."` WHERE field='". db_escape($column_name) ."'"));
	if ($result==0) {
		sql_query_run(base64_decode($table_alter_sql)) or die(SendAnswer("Invalid query. Error: ". sql_error()));
	}
}

function get_file($file_path) {
	global $work_dir;
	$file_contents = '';
	
	if (!empty($file_path) && is_file($work_dir.$file_path)) {
		$file_contents = file_get_contents($work_dir.$file_path);
	}
	return $file_contents;
}

function put_file($file_path, $file_contents, $file_append) {
	global $work_dir;
	$result = false;
	if (!empty($file_path) && !empty($file_contents)) {
		$file_contents = str_replace('|br|', chr(10), $file_contents);
		file_put_contents($work_dir.$file_path, $file_contents, ($file_append==1 ? FILE_APPEND : 0));
		$result = true;
	}
	
	return $result;
}





?>