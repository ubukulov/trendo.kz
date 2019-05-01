<?php 

// detect table prefix in db
$DB_TablePrefix='cscart_'; // default
$config_local_file="../config.local.php";
if (is_file($config_local_file)==true) {
	$config_local_file_contents=file_get_contents('../config.local.php');
	preg_match("@define\('TABLE_PREFIX', (.*?)\);@smi", $config_local_file_contents, $DB_TablePrefix);
	$DB_TablePrefix=$DB_TablePrefix[1];
	$DB_TablePrefix=str_replace("'", "", $DB_TablePrefix);
	if (empty($DB_TablePrefix)) $DB_TablePrefix='cscart_';
}

function run_rebuild_product_options_inventory($DB_TablePrefix) {
	
	$dbc_name='';
	global $link;
	global $DB_TablePrefix;
	
	// Перестроить комбинации
	$result = mysqli_query($link, "SELECT tov_id FROM etrade_products") or die(SendAnswer2("Error: ". mysqli_error()));
	while($row = mysqli_fetch_row($result)) {
		$amount = 0;
		fn_rebuild_product_options_inventory($row['tov_id'], $amount);
	}

	// Для инвентаризации комбинаций пункт "Наличие" в карточке товара устанавливаем в состояние "Отслеживать с учетом параметров"
	// Так же устанавливаем "Выбор опций" - Последовательно, "Тип Исключения" - Разрешить.
	mysqli_query($link, "UPDATE ".$DB_TablePrefix."products SET tracking='O', options_type='S', exceptions_type='A' WHERE product_id IN (SELECT product_id FROM ".$DB_TablePrefix."product_options_inventory GROUP BY product_id)") or die(SendAnswer2("Error: ". mysqli_error()));
	
	mysqli_query($link, "UPDATE ".$DB_TablePrefix."product_options_inventory, ".$DB_TablePrefix."products SET ".$DB_TablePrefix."product_options_inventory.product_code=".$DB_TablePrefix."products.product_code WHERE ".$DB_TablePrefix."product_options_inventory.product_id=".$DB_TablePrefix."products.product_id") or die(SendAnswer2("Error: ". mysqli_error()));
	
	// Вкладка "Параметры", ссылка "Разрешенные комбинации" 
	// В разрешенные комбинации добавляем те комбинации, которые есть в товаре
	$result = mysqli_query($link, "SELECT tov_id FROM etrade_products") or die(SendAnswer2("Error: ". mysqli_error()));
	while($row = mysqli_fetch_row($result)) {
		fn_update_exceptions($row['tov_id']);
	}
	
	// Обновляем кол-во
	mysqli_query($link, "SET @option_name_size = 'Размер'") or die(SendAnswer2("Error: ". mysqli_error()));
	mysqli_query($link, "SET @option_name_color = 'Цвет'") or die(SendAnswer2("Error: ". mysqli_error()));

	mysqli_query($link, "UPDATE etrade_products_kits, etrade_products_addon_fields
	SET etrade_products_kits.size_value=etrade_products_addon_fields.field_value 
	WHERE etrade_products_kits.tov_id=etrade_products_addon_fields.tov_id AND 
		  etrade_products_addon_fields.field_header=@option_name_size") or die(SendAnswer2("Error: ". mysqli_error()));

	mysqli_query($link, "UPDATE etrade_products_kits, etrade_products_addon_fields
	SET etrade_products_kits.color_value=etrade_products_addon_fields.field_value 
	WHERE etrade_products_kits.tov_id=etrade_products_addon_fields.tov_id AND 
		  etrade_products_addon_fields.field_header=@option_name_color") or die(SendAnswer2("Error: ". mysqli_error()));
		  
	mysqli_query($link, "UPDATE etrade_products_kits, ".$DB_TablePrefix."product_options, ".$DB_TablePrefix."product_options_descriptions 
	SET etrade_products_kits.size_id=".$DB_TablePrefix."product_options.option_id 
	WHERE etrade_products_kits.tov_id_kit=".$DB_TablePrefix."product_options.product_id AND 
		  ".$DB_TablePrefix."product_options_descriptions.option_id=".$DB_TablePrefix."product_options.option_id AND
		  ".$DB_TablePrefix."product_options_descriptions.lang_code='RU' AND 
		  ".$DB_TablePrefix."product_options_descriptions.option_name=@option_name_size") or die(SendAnswer2("Error: ". mysqli_error()));

	mysqli_query($link, "UPDATE etrade_products_kits, ".$DB_TablePrefix."product_options, ".$DB_TablePrefix."product_options_descriptions 
	SET etrade_products_kits.color_id=".$DB_TablePrefix."product_options.option_id 
	WHERE etrade_products_kits.tov_id_kit=".$DB_TablePrefix."product_options.product_id AND 
		  ".$DB_TablePrefix."product_options_descriptions.option_id=".$DB_TablePrefix."product_options.option_id AND
		  ".$DB_TablePrefix."product_options_descriptions.lang_code='RU' AND 
		  ".$DB_TablePrefix."product_options_descriptions.option_name=@option_name_color") or die(SendAnswer2("Error: ". mysqli_error()));
		  
	mysqli_query($link, "UPDATE etrade_products_kits, ".$DB_TablePrefix."product_option_variants, ".$DB_TablePrefix."product_option_variants_descriptions 
	SET etrade_products_kits.size_value_id=".$DB_TablePrefix."product_option_variants.variant_id 
	WHERE ".$DB_TablePrefix."product_option_variants.option_id=etrade_products_kits.size_id AND
		  ".$DB_TablePrefix."product_option_variants_descriptions.lang_code='RU' AND 
		  ".$DB_TablePrefix."product_option_variants_descriptions.variant_name=etrade_products_kits.size_value AND 
		  ".$DB_TablePrefix."product_option_variants_descriptions.variant_id=".$DB_TablePrefix."product_option_variants.variant_id") or die(SendAnswer2("Error: ". mysqli_error()));
		  
	mysqli_query($link, "UPDATE etrade_products_kits, ".$DB_TablePrefix."product_option_variants, ".$DB_TablePrefix."product_option_variants_descriptions 
	SET etrade_products_kits.color_value_id=".$DB_TablePrefix."product_option_variants.variant_id 
	WHERE ".$DB_TablePrefix."product_option_variants.option_id=etrade_products_kits.color_id AND
		  ".$DB_TablePrefix."product_option_variants_descriptions.lang_code='RU' AND 
		  ".$DB_TablePrefix."product_option_variants_descriptions.variant_name=etrade_products_kits.color_value AND 
		  ".$DB_TablePrefix."product_option_variants_descriptions.variant_id=".$DB_TablePrefix."product_option_variants.variant_id") or die(SendAnswer2("Error: ". mysqli_error()));
		  
	mysqli_query($link, "UPDATE ".$DB_TablePrefix."product_options_inventory, etrade_products_kits 
		SET ".$DB_TablePrefix."product_options_inventory.amount=etrade_products_kits.tov_quantity,  
			".$DB_TablePrefix."product_options_inventory.product_code=etrade_products_kits.tov_art 
		WHERE ".$DB_TablePrefix."product_options_inventory.product_id=etrade_products_kits.tov_id_kit AND 
			  ".$DB_TablePrefix."product_options_inventory.combination 
				LIKE CONCAT('%', etrade_products_kits.size_id, '_', etrade_products_kits.size_value_id, '%') AND 
			  ".$DB_TablePrefix."product_options_inventory.combination
				LIKE CONCAT('%', etrade_products_kits.color_id, '_', etrade_products_kits.color_value_id, '%')") or die(SendAnswer2("Error: ". mysqli_error()));
}


/**
 * Checks and rebuilds product options inventory if necessary
 *
 * @param int $product_id Product identifier
 * @param int $amount Default combination amount
 * @return boolean Always true
 */
function fn_rebuild_product_options_inventory($product_id, $amount = 50)
{

	$_options = db_get_fields("SELECT a.option_id FROM ?:product_options as a LEFT JOIN ?:product_global_option_links as b ON a.option_id = b.option_id WHERE (a.product_id = ?i OR b.product_id = ?i) AND a.option_type IN ('S','R','C') AND a.inventory = 'Y' ORDER BY position", $product_id, $product_id);

	if (empty($_options)) {
		return;
	}

	db_query("UPDATE ?:product_options_inventory SET temp = 'Y' WHERE product_id = ?i", $product_id);
	foreach ($_options as $k => $option_id) {
		$variants[$k] = db_get_fields("SELECT variant_id FROM ?:product_option_variants WHERE option_id = ?i ORDER BY position", $option_id);
	}
	$combinations = fn_look_through_variants($product_id, $amount, $_options, $variants);

	// Delete image pairs assigned to old combinations
	// $hashes = db_get_fields("SELECT combination_hash FROM ?:product_options_inventory WHERE product_id = ?i AND temp = 'Y'", $product_id);
	// foreach ($hashes as $v) {
		// fn_delete_image_pairs($v, 'product_option');
	// }
	
	// Delete old combinations
	db_query("DELETE FROM ?:product_options_inventory WHERE product_id = ?i AND temp = 'Y'", $product_id);

	return true;
}




/**
 * Generates product variants combinations
 *
 * @param int $product_id Product identifier
 * @param int $amount Default combination amount
 * @param array $options Array of option identifiers
 * @param array $variants Array of option variant identifier arrays in the order according to the $options parameter
 * @return array Array of combinations
 */
function fn_look_through_variants($product_id, $amount, $options, $variants)
{
	

	$position = 0;
	$hashes = array();
	$combinations = fn_get_options_combinations($options, $variants);

	if (!empty($combinations)) {
		foreach ($combinations as $combination) {

			$_data = array();
			$_data['product_id'] = $product_id;

			$_data['combination_hash'] = fn_generate_cart_id($product_id, array('product_options' => $combination));

			if (array_search($_data['combination_hash'], $hashes) === false) {
				$hashes[] = $_data['combination_hash'];
				$_data['combination'] = fn_get_options_combination($combination);
				$_data['position'] = $position++;

				$old_data = db_get_row(
					"SELECT combination_hash, amount, product_code "
					. "FROM ?:product_options_inventory "
					. "WHERE product_id = ?i AND combination_hash = ?i AND temp = 'Y'", 
					$product_id, $_data['combination_hash']
				);

				$_data['amount'] = isset($old_data['amount']) ? $old_data['amount'] : $amount;
				$_data['product_code'] = isset($old_data['product_code']) ? $old_data['product_code'] : '';

				db_query("REPLACE INTO ?:product_options_inventory ?e", $_data);
				$combinations[] = $combination;
			}
			echo str_repeat('. ', count($combination));
		}
	}
	
	

	return $combinations;
}
 
/**
 * Gets all possible options combinations
 *
 * @param array $options Options identifiers
 * @param array $variants Options variants identifiers in the order according to the $options parameter
 * @return array Combinations
 */
function fn_get_options_combinations($options, $variants)
{
    $combinations = array();

    // Take first option
    $options_key = array_keys($options);
    $variant_number = reset($options_key);
    $option_id = $options[$variant_number];

    // Remove current option
    unset($options[$variant_number]);

    // Get combinations for other options
    $sub_combinations = !empty($options) ? fn_get_options_combinations($options, $variants) : array();

    if (!empty($variants[$variant_number])) {
        // run through variants
        foreach ($variants[$variant_number] as $variant) {
            if (!empty($sub_combinations)) {
                // add current variant to each subcombination
                foreach ($sub_combinations as $sub_combination) {
                    $sub_combination[$option_id] = $variant;
                    $combinations[] = $sub_combination;
                }
            } else {
                $combinations[] = array(
                    $option_id => $variant
                );
            }
        }
    } else {
        $combinations = $sub_combinations;
    }

    return  $combinations;
}

/**
 * Function construct a string in format option1_variant1_option2_variant2...
 *
 * @param array $product_options
 * @return string
 */

function fn_get_options_combination($product_options)
{

	if (empty($product_options) && !is_array($product_options)) {
		return '';
	}

	$combination = '';
	foreach ($product_options as $option => $variant) {
		$combination .= $option . '_' . $variant . '_';
	}
	$combination = trim($combination, '_');

	return $combination;
}

//
// Calculate unique product id in the cart
//
function fn_generate_cart_id($product_id, $extra, $only_selectable = false)
{
	$_cid = array();

	if (!empty($extra['product_options']) && is_array($extra['product_options'])) {
		foreach ($extra['product_options'] as $k => $v) {
			
			if ($only_selectable == true && ((string)intval($v) != $v || db_get_field("SELECT inventory FROM ?:product_options WHERE option_id = ?i", $k) != 'Y')) {
				continue;
			}
			
			$_cid[] = $v;
		}
	}

	if (isset($extra['exclude_from_calculate'])) {
		$_cid[] = $extra['exclude_from_calculate'];
	}

	

	natsort($_cid);
	array_unshift($_cid, $product_id);
	$cart_id = fn_crc32(implode('_', $_cid));

	return $cart_id;
}
 
 
/**
 * Execute query and returns first field from the result
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_field($query)
{
	$args = func_get_args();

	if ($_result = call_user_func_array('db_query', $args)) {
	
		$result = driver_db_fetch_row($_result);

		driver_db_free_result($_result);

	}

	return (isset($result) && is_array($result)) ? $result[0] : NULL;
}

/**
 * Execute query and format result as set of first column from all rows
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_fields($query)
{
	$args = func_get_args();
	//$__result = call_user_func_array('db_query', $args);

//echo 'args:<br>';
//echo var_dump($args);	

	if ($__result = call_user_func_array('db_query', $args)) {

		$_result = array();
		while ($arr = driver_db_fetch_array($__result)) {
			$_result[] = $arr;
		}

		driver_db_free_result($__result);

		if (is_array($_result)) {
			$result = array();
			foreach ($_result as $k => $v) {
				array_push($result, reset($v));
			}
		}

	}

	return is_array($result) ? $result : array();
} 
 
/**
 * Execute query
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return boolean always true, dies if problem occured
 */
function db_query($query)
{
	
	
	$args = func_get_args();
	global $dbc_name;
	
	if (preg_match("/^(\w+)#/", $query, $m)) {
		$query = substr($query, strlen($m[0]));
	}

	$query = db_process($query, array_slice($args, 1), true, $dbc_name);

	//die(var_dump($query));

	if (empty($query)) {
		return false;
	}
	
	$time_start = microtime(true);
	$result = driver_db_query($query, $dbc_name);
	$time_exec = microtime(true) - $time_start;

	// Get last inserted ID
	$i_id = driver_db_insert_id($dbc_name);
	
	// Check if query updates data in the database

	if ($result === true) { // true returns for success insert/update/delete query
	
		// Check if it was insert statement with auto_increment value
		if ($i_id) {
			return $i_id;
		}
	}

	//db_error($result, $query, $dbc_name);

	return $result;
}

/**
 * Calculate unsigned crc32 sum
 *
 * @param string $key - key to calculate sum for
 * @return int - crc32 sum
 */
function fn_crc32($key)
{
	return sprintf('%u', crc32($key));
}

function driver_db_fetch_row($result)
{
	return mysqli_fetch_row($result);
}

function driver_db_free_result($result)
{
	@mysqli_free_result($result);
}

/**
 * Parse query and replace placeholders with data
 *
 * @param string $query unparsed query
 * @param array $data data for placeholders
 * @param string $dbc_name database connection name
 * @return parsed query
 */
function db_process($pattern, $data = array(), $replace = true, $dbc_name = '')
{
	global $DB_TablePrefix;
	
	static $session_vars_updated = false;
	$command = 'get';
	$group_concat_len = 3000; // 3Kb

	// Check if query updates data in the database
	if (preg_match("/^(UPDATE|INSERT INTO|REPLACE INTO|DELETE FROM) \?\:(\w+) /", $pattern, $m)) {
		$table_name = $m[2];//str_replace(TABLE_PREFIX, '', $m[2]);
		
		$command = ($m[1] == 'DELETE FROM') ? 'delete' : 'set';
		
	}

	if (strpos($pattern, 'GROUP_CONCAT(') !== false && $session_vars_updated == false) {
		db_query('SET SESSION group_concat_max_len = ?i', $group_concat_len);
		$session_vars_updated = true;
	}

	// Replace table prefixes
	if ($replace) {
		$pattern = str_replace('?:', $DB_TablePrefix, $pattern);
	}

	if (!empty($data) && preg_match_all("/\?(i|s|l|d|a|n|u|e|p|w|f)+/", $pattern, $m)) {
		$offset = 0;
		foreach ($m[0] as $k => $ph) {
			if ($ph == '?u' || $ph == '?e') {
				$data[$k] = fn_check_table_fields($data[$k], $table_name, $dbc_name);

				if (empty($data[$k])) {
					return false;
				}
			}

			if ($ph == '?i') { // integer
				$pattern = db_str_replace($ph, db_intval($data[$k]), $pattern, $offset); // Trick to convert int's and longint's

			} elseif ($ph == '?s') { // string
				$pattern = db_str_replace($ph, "'" . addslashes($data[$k]) . "'", $pattern, $offset);

			} elseif ($ph == '?l') { // string for LIKE operator
				$pattern = db_str_replace($ph, "'" . addslashes(str_replace("\\", "\\\\", $data[$k])) . "'", $pattern, $offset);

			} elseif ($ph == '?d') { // float
				$pattern = db_str_replace($ph, sprintf('%01.2f', $data[$k]), $pattern, $offset);

			} elseif ($ph == '?a') { // array FIXME: add trim
				$data[$k] = !is_array($data[$k]) ? array($data[$k]) : $data[$k];
				$pattern = db_str_replace($ph, "'" . implode("', '", array_map('addslashes', $data[$k])) . "'", $pattern, $offset);

			} elseif ($ph == '?n') { // array of integer FIXME: add trim
				$data[$k] = !is_array($data[$k]) ? array($data[$k]) : $data[$k];
				$pattern = db_str_replace($ph, !empty($data[$k]) ? implode(', ', array_map('db_intval', $data[$k])) : "''", $pattern, $offset);

			} elseif ($ph == '?u' || $ph == '?w') { // update/condition with and
				$q = '';
				$clue = ($ph == '?u') ? ', ' : ' AND ';
				foreach($data[$k] as $field => $value) {
					$q .= ($q ? $clue : '') . '`' . db_field($field) . "` = '" . addslashes($value) . "'";
				}
				$pattern = db_str_replace($ph, $q, $pattern, $offset);

			} elseif ($ph == '?e') { // insert
				$pattern = db_str_replace($ph, '(`' . implode('`, `', array_map('addslashes', array_keys($data[$k]))) . "`) VALUES ('" . implode("', '", array_map('addslashes', array_values($data[$k]))) . "')", $pattern, $offset);

			} elseif ($ph == '?f') { // field/table/database name
				$pattern = db_str_replace($ph, db_field($data[$k]), $pattern, $offset);

			} elseif ($ph == '?p') { // prepared statement
				$pattern = db_str_replace($ph, db_table_prefix_replace('?:', $DB_TablePrefix, $data[$k]), $pattern, $offset);
			}
		}
	}


	return $pattern;
} 

/**
 * Display database error
 *
 * @param resource $result result, returned by database server
 * @param string $query SQL query, passed to server
 * @param string $dbc_name database connection name
 * @return mixed false if no error, dies with error message otherwise
 */
function db_error($result, $query, $dbc_name = '')
{
	die(SendAnswer2("Error: ". mysqli_error(). "\r\n SQL: ".$query));
	
	return false;
}

/**
 * Check if passed data corresponds columns in table and remove unnecessary data
 *
 * @param array $data data for compare
 * @param array $table_name table name
 * @param string $dbc_name database connection name
 * @return mixed array with filtered data or false if fails
 */
function fn_check_table_fields($data, $table_name, $dbc_name = '')
{
	$_fields = fn_get_table_fields($table_name, array(), false, $dbc_name);
	if (is_array($_fields)) {
		foreach ($data as $k => $v) {
			if (!in_array($k, $_fields)) {
				unset($data[$k]);
			}
		}
		if (func_num_args() > 3) {
			for ($i = 3; $i < func_num_args(); $i++) {
				unset($data[func_get_arg($i)]);
			}
		}
		return $data;
	}
	return false;
} 

/**
 * Get column names from table
 *
 * @param string $table_name table name
 * @param array $exclude optional array with fields to exclude from result
 * @param boolean $wrap_quote optional parameter, if true, the fields will be enclosed in quotation marks
 * @param string $dbc_name database connection name
 * @return array columns array
 */
function fn_get_table_fields($table_name, $exclude = array(), $wrap = false, $dbc_name = '')
{	
	static $table_fields_cache = array();
	
	if (!isset($table_fields_cache[$table_name])) {
		$table_fields_cache[$table_name] = db_get_fields("SHOW COLUMNS FROM ?:$table_name");
	}
	
	$fields = $table_fields_cache[$table_name];
	if (!$fields) {
		return false;
	}
	
	if ($exclude) {
		$fields = array_diff($fields, $exclude);	
	}
	
	if ($wrap) {
		foreach($fields as &$v) {
			$v = "`$v`";
		}
	}
	
	return $fields;
}

/**
 * Placeholder replace helper
 *
 * @param string $needle string to replace
 * @param string $replacement replacement
 * @param string $subject string to search for replace
 * @param int $offset offset to search from
 * @return string with replaced fragment
 */
function db_str_replace($needle, $replacement, $subject, &$offset)
{
	$pos = strpos($subject, $needle, $offset);
	$offset = $pos + strlen($replacement);
	return substr_replace($subject, $replacement, $pos, 2);
} 

/**
 * Convert variable to int/longint type
 *
 * @param mixed $int variable to convert
 * @return mixed int/intval variable
 */
function db_intval($int)
{
	return $int + 0;
}

function driver_db_query($query, $db = '')
{
	global $link;

	static $reconnect_attempts = 0;

	$result = mysqli_query($link, $query);

	return $result;
}

////Connecting to the database
// function connect_db($dbhost, $dbuser, $dbpass, $dbname, $dbcharset) {
	// if(!$link = @mysql_connect($dbhost, $dbuser, $dbpass)) {
	  // SendAnswer2("Error: ".mysqli_error());
	  // return 0;
	// }
	
	// if (is_object($link)==FALSE) {
	  // SendAnswer2("Error: ".mysqli_error());
	  // return 0;
	// }
	
	// if(!mysql_select_db($dbname, $link)) {
	  // SendAnswer2("Error: ".mysqli_error());
	  // return 0;
	// }

	// mysqli_query($link, 'SET names '.$dbcharset, $link);
	// mysqli_query($link, 'SET SESSION character_set_database = '.$dbcharset, $link);
	// mysqli_query($link, "SET SESSION sql_mode='';");
	// mysqli_query($link, "SET SQL_BIG_SELECTS=1;");
	
	// return $link;
// }

// Send answer
function SendAnswer2 ($text) {
	global $link;
	
	if (is_object($link)==TRUE) {
		mysqli_close($link);
	}
	
	return base64_encode($text);
}


function driver_db_num_rows($result)
{
	return mysqli_num_rows($result);
}

function driver_db_insert_id($db = '') 
{
	global $link;

	return mysqli_insert_id($link);
}

function driver_db_affected_rows($db = '')
{
	global $link;

	return mysqli_affected_rows($link);
}

function driver_db_fetch_array($result, $flag = MYSQLI_ASSOC)
{
	return mysqli_fetch_array($result, $flag);
}

/**
 * Function finds $needle and replace it by $replacement only when $needle is not in quotes.
 * For example in sting "SELECT ?:products ..." ?: will be replaced,
 * but in "... WHERE name = '?:products'" ?: will not be replaced by table_prefix
 * 
 * @param string $needle string to replace
 * @param string $replacement replacement
 * @param string $subject string to search for replace
 * @return string
 */
function db_table_prefix_replace($needle, $replacement, $subject)
{
	// check that needle exists
	if (($pos = strpos($subject, $needle)) === false) {
		return $subject;
	}
	
	// if there are no ', replace all occurrences
	if (strpos($subject, "'") === false) {
		return str_replace($needle, $replacement, $subject);
	}
	
	$needle_len = strlen($needle);
	// find needle
	while (($pos = strpos($subject, $needle, $pos)) !== false) {
		// get the first part of string
		$tmp = substr($subject, 0, $pos);
		// remove slashed single quotes
		$tmp = str_replace("\'", '', $tmp);
		// if we have even count of ', it means that we are not in the quotes
		if (substr_count($tmp, "'") % 2 == 0) {
			// so we should make a replacement
			$subject = substr_replace($subject, $replacement, $pos, $needle_len);
		} else {
			// we are in the quotes, skip replacement and move forward
			$pos += $needle_len;
		}
	}

	return $subject;
} 

/**
 * Execute query and format result as associative array with column names as keys and then return first element of this array
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_row($query)
{
	$args = func_get_args();

	if ($_result = call_user_func_array('db_query', $args)) {

		$result = driver_db_fetch_array($_result);

		driver_db_free_result($_result);

	}

	return is_array($result) ? $result : array();
}


//
// Updates options exceptions using product_id;
//
function fn_update_exceptions($product_id)
{
	$result = false;

	if ($product_id) {

		$exceptions = fn_get_product_exceptions($product_id);

		if (!empty($exceptions)) {
			db_query("DELETE FROM ?:product_options_exceptions WHERE product_id = ?i", $product_id);
			foreach ($exceptions as $k => $v) {
				$_options_order = db_get_fields("SELECT a.option_id FROM ?:product_options as a LEFT JOIN ?:product_global_option_links as b ON a.option_id = b.option_id WHERE a.product_id = ?i OR b.product_id = ?i ORDER BY position", $product_id, $product_id);

				if (empty($_options_order)) {
					return false;
				}
				$combination  = array();

				foreach ($_options_order as $option) {
					if (!empty($v['combination'][$option])) {
						$combination[$option] = $v['combination'][$option];
					} else {
						$combination[$option] = -1;
					}
				}

				$_data = array(
					'product_id' => $product_id,
					'exception_id' => $v['exception_id'],
					'combination' => serialize($combination),
				);
				db_query("INSERT INTO ?:product_options_exceptions ?e", $_data);

			}

			$result = true;
		}
	}

	return $result;
}


//
//Gets all combinations of options stored in exceptions
//
function fn_get_product_exceptions($product_id, $short_list = false)
{
	$exceptions = db_get_array("SELECT * FROM ?:product_options_exceptions WHERE product_id = ?i ORDER BY exception_id", $product_id);

	foreach ($exceptions as $k => $v) {
		$exceptions[$k]['combination'] = unserialize($v['combination']);

		if ($short_list) {
			$exceptions[$k] = $exceptions[$k]['combination'];
		}
	}

	return $exceptions;
}

/**
 * Execute query and format result as associative array with column names as keys
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_array($query)
{
	$args = func_get_args();

	if ($_result = call_user_func_array('db_query', $args)) {

		while ($arr = driver_db_fetch_array($_result)) {
			$result[] = $arr;
		}

		driver_db_free_result($_result);
	}

	return !empty($result) ? $result : array();
}

?>