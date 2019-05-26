<?php
/* ##########################
	E-Trade Http Tunnel v2
	HTTP tunnel script.    
	
	Copyright (c) 2011-2019 ElbuzGroup
	https://elbuz.com
   ##########################
*/

function doubleExplode ($del1, $del2, $array){
	$array1 = explode($del1, $array);

	foreach($array1 as $key=>$value){
		$array2 = explode($del2, $value);
		
		foreach($array2 as $key2=>$value2){
			$array3[] = $value2; 
		}
	}

    $afinal = array();
	for ( $i = 0; $i <= count($array3); $i += 2) {
		if($array3[$i]!=""){
			$afinal[trim($array3[$i])] = array(trim($array3[$i+1]));
		}
	}
	
	return $afinal;
}

function get_file_extension($file_name) {
	$file_name=explode('.',$file_name);
	$file_name=end($file_name);
	
	return $file_name;
}

/**
 * Recursively remove directory (or just a file)
 *
 * @param string $source
 * @param bool $delete_root
 * @param string $pattern
 * @return bool
 */
function remove_files($source, $delete_root = true, $pattern = '')
{
	if (stristr(PHP_OS, 'WIN')) {// Detect operation system
		$source=str_replace('/', '\\' , $source);
	}
	
    // Simple copy for a file
    if (is_file($source)) {
		$res = true;
		if (empty($pattern) || (!empty($pattern) && preg_match('/' . $pattern . '/', basename($source)))) {
			if (file_exists($source)) {
				$res = @unlink($source);
			}
		}
		clearstatcache();
		if (@is_file($source)) {
			$filesys=preg_replace("/","\\",$source);
			$delete = @system("del $filesys");
			clearstatcache();
			if (@is_file($source)) {
				$delete = @chmod($source,0775);
				$delete = @unlink($source);
				$delete = @system("del $filesys");
			}
		}
		clearstatcache();
		if (@is_file($source)) {
			return false;
		} else {
			return true;
		}
    }

    // Loop through the folder
	if (is_dir($source)) {
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..' || $entry == 'index.html' || $entry == 'index.htm' || $entry == '.htaccess') {
				continue;
			}
	 		if (remove_files($source . '/' . $entry, true, $pattern) == false) {
				return false;
			}
		}
		// Clean up
		$dir->close();
		//return ($delete_root == true && empty($pattern)) ? @rmdir($source) : true;
		return ($delete_root == true && empty($pattern)) ? true : true;
	} else {
		return false;
	}
}

// translit
function cyr_to_translit($content) {
	$transA = array('А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'h', 'Ґ' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'jo', 'Є' => 'e', 'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'І' => 'i', 'Й' => 'i', 'Ї' => 'i', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ў' => 'u', 'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sz', 'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya'); 
	$transB = array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'є' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'і' => 'i', 'й' => 'i', 'ї' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sz', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '&quot;' => '', '&amp;' => '', 'µ' => 'u', '№' => '');
	$content = trim(strip_tags($content)); 
	$content = strtr($content, $transA); 
	$content = strtr($content, $transB); 
	$content = preg_replace("/\s+/ms", "-", $content); 
	$content = preg_replace("/[ ]+/", "-", $content);
	$content = preg_replace("/[^a-z0-9_]+/mi", "", $content);
	$content = stripslashes($content); 
	return $content; 
}

function imageResize($sourceFile, $destFile, $destWidth = NULL, $destHeight = NULL, $fileType = 'jpg') {
	list($sourceWidth, $sourceHeight, $type, $attr) = getimagesize($sourceFile);
	
	if (!$sourceWidth) return false;
	if ($destWidth == NULL) $destWidth = $sourceWidth;
	if ($destHeight == NULL) $destHeight = $sourceHeight;

	$sourceImage = createSrcImage($type, $sourceFile);

	$widthDiff = $destWidth / $sourceWidth;
	$heightDiff = $destHeight / $sourceHeight;
	
	if ($widthDiff > 1 AND $heightDiff > 1)
	{
		$nextWidth = $sourceWidth;
		$nextHeight = $sourceHeight;
	}
	else
	{
		if ($widthDiff > $heightDiff)
		{
			$nextHeight = $destHeight;
			$nextWidth = round(($sourceWidth * $nextHeight) / $sourceHeight);
			$destWidth = (int)$destWidth; // $nextWidth
		}
		else
		{
			$nextWidth = $destWidth;
			$nextHeight = round($sourceHeight * $destWidth / $sourceWidth);
			$destHeight = (int)$destHeight; // $nextHeight
		}
	}
	
	$destImage = imagecreatetruecolor($destWidth, $destHeight);

	$white = imagecolorallocate($destImage, 255, 255, 255);
	imagefill($destImage, 0, 0, $white);
	
	//imageCopyResampleBicubic($destImage, $sourceImage, (int)(($destWidth - $nextWidth) / 2), (int)(($destHeight - $nextHeight) / 2), 0, 0, $nextWidth, $nextHeight, $sourceWidth, $sourceHeight); // best quality, but slow
	imagecopyresampled($destImage, $sourceImage, (int)(($destWidth - $nextWidth) / 2), (int)(($destHeight - $nextHeight) / 2), 0, 0, $nextWidth, $nextHeight, $sourceWidth, $sourceHeight);
	imagecolortransparent($destImage, $white);
	return (returnDestImage($fileType, $destImage, $destFile));
}

function returnDestImage($type, $ressource, $filename) {
	$flag = false;
	switch ($type) {
		case 'gif':
			$flag = imagegif($ressource, $filename);
			break;
		case 'png':
			$flag = imagepng($ressource, $filename, 7);
			break;
		case 'jpeg':
		default:
			$flag = imagejpeg($ressource, $filename, 95);
			break;
	}
	
	imagedestroy($ressource);
	return $flag;
}

function createSrcImage($type, $filename) {
	switch ($type)
	{
		case 1:
			return imagecreatefromgif($filename);
			break;
		case 3:
			return imagecreatefrompng($filename);
			break;
		case 2:
		default:
			return imagecreatefromjpeg($filename);
			break;
	}
}

function imageCopyResampleBicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
{
  $scaleX = ($src_w - 1) / $dst_w;
  $scaleY = ($src_h - 1) / $dst_h;

  $scaleX2 = $scaleX / 2.0;
  $scaleY2 = $scaleY / 2.0;

  $tc = imageistruecolor($src_img);

  for ($y = $src_y; $y < $src_y + $dst_h; $y++)
  {
    $sY   = $y * $scaleY;
    $siY  = (int) $sY;
    $siY2 = (int) $sY + $scaleY2;

    for ($x = $src_x; $x < $src_x + $dst_w; $x++)
    {
      $sX   = $x * $scaleX;
      $siX  = (int) $sX;
      $siX2 = (int) $sX + $scaleX2;

      if ($tc)
      {
        $c1 = imagecolorat($src_img, $siX, $siY2);
        $c2 = imagecolorat($src_img, $siX, $siY);
        $c3 = imagecolorat($src_img, $siX2, $siY2);
        $c4 = imagecolorat($src_img, $siX2, $siY);

        $r = (($c1 + $c2 + $c3 + $c4) >> 2) & 0xFF0000;
        $g = ((($c1 & 0xFF00) + ($c2 & 0xFF00) + ($c3 & 0xFF00) + ($c4 & 0xFF00)) >> 2) & 0xFF00;
        $b = ((($c1 & 0xFF)   + ($c2 & 0xFF)   + ($c3 & 0xFF)   + ($c4 & 0xFF))   >> 2);

        imagesetpixel($dst_img, $dst_x + $x - $src_x, $dst_y + $y - $src_y, $r+$g+$b);
      }
      else
      {
        $c1 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY2));
        $c2 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY));
        $c3 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY2));
        $c4 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY));

        $r = ($c1['red']   + $c2['red']   + $c3['red']   + $c4['red']  ) << 14;
        $g = ($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) << 6;
        $b = ($c1['blue']  + $c2['blue']  + $c3['blue']  + $c4['blue'] ) >> 2;

        imagesetpixel($dst_img, $dst_x + $x - $src_x, $dst_y + $y - $src_y, $r+$g+$b);
      }
    }
  }
}

function phpshop_import_pf_type() {
	global $link;
	$sql_result = $link->query("SELECT row_type, field_value1, field_value2 FROM etrade_cc_filters WHERE row_type='pf' OR row_type='cs'") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result)) {
		if ($sql_row['row_type']=='pf') {
			$vendor_new=doubleExplode(',', ':', $sql_row['field_value2']);
			$vendor="";
			
			if(is_array($vendor_new)) {
				foreach($vendor_new as $k=>$v){
					if(is_array($v)){
						foreach($v as $o=>$p)
						@$vendor.="i".$k."-".$p."i";
					} else {
						@$vendor.="i".$k."-".$v."i";
					}
				}
			}
			
			$link->query("UPDATE phpshop_products SET vendor='".$vendor."', vendor_array='".addslashes(serialize($vendor_new))."' WHERE id='".$sql_row['field_value1']."'") or die(SendAnswer("Error: ". mysqli_error()));
		}
		
		if ($sql_row['row_type']=='cs') {
			$serialized_sort=addslashes(serialize(explode(",", $sql_row['field_value2'])));
			$link->query("UPDATE phpshop_categories SET sort='".$serialized_sort."' WHERE id=".$sql_row['field_value1']) or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
}

function virtuemart_import_ptv_type() {
	global $link;
	$DB_TablePrefix="jos_";
	
	// ссылка на тип товара для товаров
	$link->query("INSERT INTO ".$DB_TablePrefix."vm_product_product_type_xref (product_id, product_type_id) SELECT field_value1, field_value4 FROM etrade_cc_filters WHERE row_type='ptv' AND etrade_cc_filters.field_value1 NOT IN (SELECT product_id FROM ".$DB_TablePrefix."vm_product_product_type_xref) GROUP BY field_value1, field_value4") or die(SendAnswer("Error: ". mysqli_error()));
	

	// создаём таблицы
	$sql_result1 = $link->query("SELECT row_type, field_value1, field_value2, field_value3, field_value4 FROM etrade_cc_filters WHERE row_type='pt'") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result1)) {
	
		if ($sql_row['row_type']=='pt') {
			$link->query("INSERT INTO ".$DB_TablePrefix."vm_product_type (product_type_id, product_type_name, product_type_publish) VALUES('".$sql_row['field_value1']."', '".mysqli_real_escape_string($link, $sql_row['field_value2'])."', 'Y')") or die(SendAnswer("Error: ". mysqli_error()));	
			
			// Удаление таблицы
			$link->query("DROP TABLE IF EXISTS ".$DB_TablePrefix."vm_product_type_".$sql_row['field_value1']) or die(SendAnswer("Error: ". mysqli_error()));
			
			$fields_list='';
			$sql_result2 = $link->query("SELECT field_value4, field_value2 FROM `etrade_cc_filters` WHERE `row_type`='ptv' AND field_value4='".$sql_row['field_value1']."' GROUP BY field_value4, field_value2") or die(SendAnswer("Error: ". mysqli_error()));

			while ($sql_row2 = mysqli_fetch_array($sql_result2)) {
				$fields_list.="`".$sql_row2['field_value2']."` TEXT NULL, ";
			}
	
			// создание таблиц которые будут хранить значения характеристик
			$result = $link->query("CREATE TABLE ".$DB_TablePrefix."vm_product_type_".$sql_row['field_value1']." (
									`product_id` INT NOT NULL , ".$fields_list." 
									PRIMARY KEY ( `product_id` ) 
									) ENGINE = MYISAM DEFAULT CHARSET = utf8;") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	
	
	// добавляем данные в таблицах
	$sql_result3 = $link->query("SELECT row_type, field_value1, field_value2, field_value3, field_value4 FROM etrade_cc_filters WHERE row_type='ptv'") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($sql_row = mysqli_fetch_array($sql_result3)) {
		if ($sql_row['row_type']=='ptv') {
			$link->query("INSERT INTO ".$DB_TablePrefix."vm_product_type_".$sql_row['field_value4']." (".$sql_row['field_value2'].", product_id) VALUES('".mysqli_real_escape_string($link, $sql_row['field_value3'])."', '".$sql_row['field_value1']."') ON DUPLICATE KEY UPDATE ".$sql_row['field_value2']."='".mysqli_real_escape_string($link, $sql_row['field_value3'])."'") or die(SendAnswer("Error: ". mysqli_error()));
			
			$count_features_values_add++;
		}
	}
}


function hostcms_import_pics($DB_TablePrefix) {
	$delete_temp_file=0; // удалять временные файлы 0-нет или 1-да
	
	$UploadDirTemp="../upload/my_products_img/";
	if (is_dir($UploadDirTemp)==false) die(SendAnswer('Error: Для копирования фотограий, создайте временную папку - '.$UploadDirTemp.', перепишите файлы выгруженные из прогаммы E-Trade Content Creator в эту папку.'));
		
	if (is_file('../main_classes.php')==false) die(SendAnswer('Error: Не найден файл ../main_classes.php'));
	if (is_file('../modules/shop/shop.class.php')==false) die(SendAnswer('Error: Не найден файл ../modules/shop/shop.class.php'));
	
	// nesting_level for HostCMS v5
	// $sql_result = $link->query("SELECT site_nesting_level FROM site_table WHERE site_id=1") or die(SendAnswer("Error: ". mysqli_error()));
		
	require_once('../main_classes.php');
	require_once('../modules/shop/shop.class.php');
	$shop = new shop();
	
	global $link;

	$sql_result = $link->query("SELECT tov_id, pic_small, pic_medium, pic_big, pic_order, picID, tov_name, tov_guid FROM etrade_cc_pics_flat") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		// создание каталога для хранения фотографий товаров
		$UploadDir = '../'.$shop->GetItemDir($sql_row['tov_id']);
		if (is_dir($UploadDir)==false) mkdir($UploadDir, 0777, true);
		if (is_dir($UploadDir)==false) die(SendAnswer('Error: ошибка создания директории для хранения фотографий товаров - '.$UploadDir));

		// Копирование файлов из временной папки в основную
		if (is_file($UploadDirTemp.strtolower($sql_row['pic_small']))) {
			copy($UploadDirTemp.strtolower($sql_row['pic_small']), $UploadDir.strtolower($sql_row['pic_small']));
			if ($delete_temp_file==1) unlink($UploadDirTemp.strtolower($sql_row['pic_small']));
		}
		
		if (is_file($UploadDirTemp.strtolower($sql_row['pic_medium']))) {
			copy($UploadDirTemp.strtolower($sql_row['pic_medium']), $UploadDir.strtolower($sql_row['pic_medium']));
			if ($delete_temp_file==1) unlink($UploadDirTemp.strtolower($sql_row['pic_medium']));
		}
		
		if (is_file($UploadDirTemp.strtolower($sql_row['pic_big']))) {
			copy($UploadDirTemp.strtolower($sql_row['pic_big']), $UploadDir.strtolower($sql_row['pic_big']));
			if ($delete_temp_file==1) unlink($UploadDirTemp.strtolower($sql_row['pic_big']));
		}
	}
}


function hostcms6_import_pics($config_data) {
	global $link;

	$config_data = unserialize(base64_decode($config_data));
	
	$delete_temp_file = $config_data['delete_temp_file']; // удалять временные файлы 0-нет или 1-да
	
	$UploadDirTemp = "../upload/jumper_image_temp/";
	//$UploadDirTemp = $config_data['destination_path']; // from ftp
	if (is_dir($UploadDirTemp)==false) die(SendAnswer('Error: Не найдена временная папка - '.$UploadDirTemp.'.'));

	// nesting_level for HostCMS v6
	// $sql_result = $link->query("SELECT nesting_level FROM sites WHERE id=1") or die(SendAnswer("Error: ". mysqli_error()));
	$sql_result = $link->query("SELECT ei.row_type, ei.item_id, ei.image, ei.sort_order, ei.uuid, ei.item_uuid, shop_items.shop_id 
								FROM etrade_image_temp ei 
								INNER JOIN shop_items ON shop_items.id=ei.item_id 
								WHERE item_id>0 AND row_type='product' AND image<>''") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		// создание каталога для хранения фотографий товаров
		$UploadDir = '../upload/shop_'.$sql_row['shop_id'].'/'.hostcms_getNestingDirPath($sql_row['item_id']).'/item_'.$sql_row['item_id'].'/';
		
		if (is_dir($UploadDir)==false) mkdir($UploadDir, 0777, true);
		if (is_dir($UploadDir)==false) die(SendAnswer('Error: ошибка создания директории для хранения фотографий товаров - '.$UploadDir));

		// Копирование файлов из временной папки в основную
		if (is_file($UploadDirTemp . $sql_row['image'])) {
			
			// resize
			if (count($config_data['image_resize_data'])>0) {
				foreach ($config_data['image_resize_data'] as $image_resize_row) {
					//image_width, image_height, watermark_image, watermark_position, watermark_opacity, description, file_prefix, file_infinix, ftp_path
					if ($image_resize_row['image_width']>0 && $image_resize_row['image_width']>0) {
						imageResize($UploadDirTemp . $sql_row['image'], $UploadDir . $image_resize_row['file_prefix'] . basename($sql_row['image']), $image_resize_row['image_width'], $image_resize_row['image_width']);
					} else { // простое копирование
						copy($UploadDirTemp . $sql_row['image'], $UploadDir . $image_resize_row['file_prefix'] . basename($sql_row['image']));
					}
				}
			} else {
				copy($UploadDirTemp . $sql_row['image'], $UploadDir . basename($sql_row['image']));
			}
			
			if ($delete_temp_file==1) unlink($UploadDirTemp . $sql_row['image']);
			//shop_items_catalog_image172.jpeg
		}
	}
	
	// прописываем размеры фоток в БД сайта
	//$sql_result = $link->query("SELECT id, image_small as image_path, image_small_height as image_x, image_small_width as image_y FROM shop_items WHERE image_small_height=0 OR image_small_width=0") or die(SendAnswer("Error: ". mysqli_error()));
	//update_pics_size($sql_result, $UploadDirTemp, 'shop_items', 'image_path', 'image_small_height', 'image_small_width', 'id', '');
	
	//$sql_result = $link->query("SELECT id, image_large as image_path, image_large_height as image_x, image_large_width as image_y FROM shop_items WHERE image_large_height=0 OR image_large_width=0") or die(SendAnswer("Error: ". mysqli_error()));
	//update_pics_size($sql_result, $UploadDirTemp, 'shop_items', 'image_path', 'image_large_height', 'image_large_width', 'id', '');
}

/**
 * Получение пути к директории определенного уровня вложенности по идентификатору сущности.
 * Например, для сущности с кодом 17 и уровнем вложенности 3 вернется строка 0/1/7 или массив из 3-х элементов - 0,1,7
 * Для сущности с кодом 23987 и уровнем вложенности 3 возвращается строка 2/3/9 или массив из 3-х элементов - 2,3,9.
 *
 * @param $id идентификатор сущности
 * @param $level уровень вложенности, по умолчанию 3
 * @param $type тип возвращаемого результата, 0 (по умолчанию) - строка, 1 - массив
 * @return mixed строка или массив названий групп
 */
function hostcms_getNestingDirPath($id, $level = 3, $type = 0) {
	$id = intval($id);
	$level = intval($level);
	$sId = sprintf("%0{$level}d", $id);
	$aPath = array();
	
	for ($i = 0; $i < $level; $i ++) {
		$aPath[$i] = $sId{$i};
	}

	if ($type == 0) return implode('/', $aPath);

	return $aPath;
}




function prestashop_import_pics($config_data) {
	global $link;
	$config_data = unserialize(base64_decode($config_data));
	
	$sql_result = $link->query("SELECT * FROM etrade_image_temp WHERE image_id>0") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		$item_type = '';
		$where_addon = '';
		if ($sql_row['row_type'] == 'product') {
			$item_type = 'p';
			$where_addon = 'AND products = 1';
		}
		if ($sql_row['row_type'] == 'category') {
			$item_type = 'c';
			$where_addon = 'AND categories = 1';
		}
		if ($sql_row['row_type'] == 'manufacturer') {
			$item_type = 'm';
			$where_addon = 'AND manufacturers = 1';
		}
		if (empty($item_type)) continue;
		
		if (is_file('../img/'. $item_type .'/'. $sql_row['image'])) {
			// Список типов картинок и настройки ресайза
			$image_types = $link->query("SELECT name, width, height FROM ". $config_data['db_prefix'] ."image_type WHERE 1=1 ".$where_addon) or die(SendAnswer("Error: ". mysqli_error()));
			while ($row1 = mysqli_fetch_assoc($image_types)) {
				$file_name = str_ireplace('/'. $sql_row['image_id'].'.',  '/'. $sql_row['image_id'].'-'.stripslashes($row1['name']).'.', $sql_row['image']);
				
				file_put_contents('./test_img.txt', '../img/'. $item_type .'/'. $file_name);
				if (!is_file('../img/'. $item_type .'/'. $file_name)) {
					imageResize('../img/'. $item_type .'/'. $sql_row['image'], '../img/'. $item_type .'/'. $file_name, $row1['width'], $row1['height']);
				}
			}
		}
	}
}


/** PrestaShop
 * Returns the path to the folder containing the image in the new filesystem
 *
 * @param mixed $id_image
 * @return string path to folder
 */
function PrestaShop_getImgFolderStatic($id_image) {
	if (!is_numeric($id_image)) return false;
	
	$folders = str_split((string)$id_image);
	
	return implode('/', $folders).'/';
}


function check_fields($config_data) { // проверка наличия полей в БД
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	
	foreach ($config_data['check_fields_list'] as &$check_data) {
		$result = $link->query("SHOW COLUMNS FROM ". $check_data['table_name'] ." WHERE field = '". $check_data['table_field'] ."'") or die(SendAnswer("Error: ". mysqli_error()));
		$check_data['table_field_exist'] = (mysqli_num_rows($result)) ? 1 : 0;
		
		if ($check_data['table_field_exist'] == 0 && isset($check_data['sql_query_create']) && !empty($check_data['sql_query_create'])) {
			$link->query($check_data['sql_query_create']) or die(SendAnswer("Error: ". mysqli_error()));
			$check_data['table_field_exist'] = 1;
		}
			
		if ($check_data['table_field_exist'] == 1 && isset($check_data['sql_query_custom']) && !empty($check_data['sql_query_custom'])) {
			$sql_query_list = explode(";;;", $check_data['sql_query_custom']);
			foreach ($sql_query_list as $sql_query_data) {
				if (!empty($sql_query_data)) {
					$link->query($sql_query_data) or die(SendAnswer("Error: ". mysqli_error()));
				}
			}
		}
	}
	
	echo base64_encode(serialize($config_data['check_fields_list']));
}

function check_fields_index($config_data) { // создание индексов 
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	
	foreach ($config_data['check_fields_list'] as &$check_data) {
		$result = $link->query("SHOW INDEX FROM ". $check_data['table_name'] ." WHERE key_name = '". $check_data['table_field'] ."'") or die(SendAnswer("Error: ". mysqli_error()));
		if (mysqli_num_rows($result)==0) {
			$link->query("ALTER TABLE ". $check_data['table_name'] ." ADD INDEX ( `". $check_data['table_field'] ."` )") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	
	if (isset($check_data['sql_query_custom']) && !empty($check_data['sql_query_custom'])) {
		$sql_query_list = explode(";;;", $check_data['sql_query_custom']);
		foreach ($sql_query_list as $sql_query_data) {
			if (!empty($sql_query_data)) {
				$link->query($sql_query_data) or die(SendAnswer("Error: ". mysqli_error()));
			}
		}
	}	
	
	//echo base64_encode(serialize($config_data['check_fields_list']));
}



function Bitrix_create_table_index($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
}

function bitrix_create_table_flat_for_element_prop() {
	global $link;
	
	$blocks_list=$link->query("SELECT ID as iblock_id FROM b_iblock WHERE VERSION=2") or die(SendAnswer("Error: ". mysqli_error()));
	while ($arr_blocks_list = mysqli_fetch_assoc($blocks_list)) {
		$link->query("CREATE TABLE IF NOT EXISTS b_iblock_element_prop_s".$arr_blocks_list['iblock_id']." (
					  `IBLOCK_ELEMENT_ID` int(11) NOT NULL, 
					  PRIMARY KEY (`IBLOCK_ELEMENT_ID`) 
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));	

		$link->query("CREATE TABLE IF NOT EXISTS b_iblock_element_prop_m".$arr_blocks_list['iblock_id']." (
					  `ID` int(11) NOT NULL AUTO_INCREMENT,
					  `IBLOCK_ELEMENT_ID` int(11) NOT NULL,
					  `IBLOCK_PROPERTY_ID` int(11) NOT NULL,
					  `VALUE` text COLLATE utf8_unicode_ci NOT NULL,
					  `VALUE_ENUM` int(11) DEFAULT NULL,
					  `VALUE_NUM` decimal(18,4) DEFAULT NULL,
					  `DESCRIPTION` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
					  PRIMARY KEY (`ID`),
					  KEY `ix_iblock_elem_prop_m".$arr_blocks_list['iblock_id']."_1` (`IBLOCK_ELEMENT_ID`,`IBLOCK_PROPERTY_ID`),
					  KEY `ix_iblock_elem_prop_m".$arr_blocks_list['iblock_id']."_2` (`IBLOCK_PROPERTY_ID`),
					  KEY `ix_iblock_elem_prop_m".$arr_blocks_list['iblock_id']."_3` (`VALUE_ENUM`,`IBLOCK_PROPERTY_ID`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1") or die(SendAnswer("Error: ". mysqli_error()));	
					
		$link->query("INSERT INTO b_iblock_element_prop_s".$arr_blocks_list['iblock_id']." (IBLOCK_ELEMENT_ID) 
					SELECT ID FROM b_iblock_element 
					WHERE iblock_id=".$arr_blocks_list['iblock_id']." AND 
						  ID NOT IN (SELECT IBLOCK_ELEMENT_ID FROM b_iblock_element_prop_s".$arr_blocks_list['iblock_id'].")") or die(SendAnswer("Error: ". mysqli_error()));
	}
}

// Bitrix - Create fields for features
function Bitrix_FEATURES_SAVE_MODE2_CC($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	
	// Исправляем ошибку MySQL «Row size too large» в 1с-Битрикс
	// http://alexvaleev.ru/mysql-row-size-too-large/
	
	// создаём таблицы для хранения данных для 2й версии инфоблоков
	bitrix_create_table_flat_for_element_prop();

	// create fields
	$properties_list=$link->query("SELECT a.block_id as iblock_id, a.attribute_id as property_id, a.bitrix_property_type, bip.LIST_TYPE 
									FROM etrade_attribute_temp a 
									INNER JOIN b_iblock_property bip ON bip.ID = a.attribute_id
									WHERE a.block_id>0 AND a.attribute_id>0 AND a.block_id IN (SELECT ID as iblock_id FROM b_iblock WHERE VERSION=2) AND a.bitrix_property_multiple!='Y' 
									GROUP BY a.block_id, a.attribute_id") or die(SendAnswer("Error: ". mysqli_error()));
	//AND bip.LIST_TYPE!='L'
	while ($arr_properties_list = mysqli_fetch_assoc($properties_list)) {
		$field_exist_query=$link->query("SHOW COLUMNS FROM b_iblock_element_prop_s".$arr_properties_list['iblock_id']." WHERE `Field`='PROPERTY_".$arr_properties_list['property_id']."'") or die(SendAnswer("Error: ". mysqli_error()));
		
		if (mysqli_num_rows($field_exist_query)==0) {
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `PROPERTY_".$arr_properties_list['property_id']."` text") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `DESCRIPTION_".$arr_properties_list['property_id']."` text DEFAULT NULL") or die(SendAnswer("Error: ". mysqli_error()));
		}

		//$attribute_value = ' etrade_product_attribute_temp.attribute_value ';
		//if ($arr_properties_list['LIST_TYPE']=='L') continue;
		
		// update values
		$link->query("UPDATE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].", etrade_product_attribute_temp 
						SET b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".PROPERTY_".$arr_properties_list['property_id']." = ". ($arr_properties_list['bitrix_property_type']=='L' || $arr_properties_list['bitrix_property_type']=='E' ? ' etrade_product_attribute_temp.bitrix_enum_id ' : ' etrade_product_attribute_temp.attribute_value ' ) ." 
						WHERE etrade_product_attribute_temp.attribute_id>0 AND 
							  etrade_product_attribute_temp.attribute_id=".$arr_properties_list['property_id']." AND 
							  b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".IBLOCK_ELEMENT_ID=etrade_product_attribute_temp.product_id") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	
	// тип "Список" + многострочное
	//GROUP_CONCAT(DISTINCT a.attribute_id SEPARATOR ',') as attribute_id_list

	// список атрибутов тип "Список"
	$properties_list = $link->query("SELECT GROUP_CONCAT(DISTINCT a.attribute_id SEPARATOR ',') as attribute_id_list 
									FROM etrade_attribute_temp a 
									INNER JOIN b_iblock_property bip ON bip.ID = a.attribute_id 
									WHERE a.block_id>0 AND a.attribute_id>0 AND a.block_id IN (SELECT ID as iblock_id FROM b_iblock WHERE VERSION=2) AND 
										  bip.LIST_TYPE='L' AND 
										  a.bitrix_property_type!='L' AND 
										  a.bitrix_property_type!='E' AND 
										  a.bitrix_property_multiple='Y'
									") or die(SendAnswer("Error: ". mysqli_error()));
	
	$query_data = mysqli_fetch_assoc($properties_list);
	$attribute_id_list = explode(",", $query_data['attribute_id_list']);

	// список товаров
	$product_list = $link->query("SELECT pa.product_id, a.block_id 
									FROM etrade_product_attribute_temp pa 
									INNER JOIN etrade_attribute_temp a ON pa.attribute_id = a.attribute_id 
									INNER JOIN b_iblock_property bip ON bip.ID = a.attribute_id 
									WHERE a.block_id>0 AND 
										  pa.product_id>0 AND 
										  a.attribute_id>0 AND 
										  a.block_id IN (SELECT ID as iblock_id FROM b_iblock WHERE VERSION=2) AND 
										  bip.LIST_TYPE='L' AND 
										  a.bitrix_property_type!='L' AND 
										  a.bitrix_property_type!='E' AND 
										  a.bitrix_property_multiple='Y' 
									GROUP BY pa.product_id") or die(SendAnswer("Error: ". mysqli_error()));
									
	

	//while ($row_data = mysqli_fetch_assoc($properties_list)) {
	foreach ($attribute_id_list as $attribute_id) {
		// создаём список значений по каждому товару
		mysqli_data_seek($product_list, 0);
		while ($product_data = mysqli_fetch_assoc($product_list)) {
			$link->query("DELETE FROM b_iblock_element_prop_m". $product_data['block_id'] ." WHERE IBLOCK_PROPERTY_ID = ". $attribute_id ." AND IBLOCK_ELEMENT_ID = ". $product_data['product_id'] ." ");
			$link->query("INSERT INTO b_iblock_element_prop_m". $product_data['block_id'] ." (IBLOCK_ELEMENT_ID, IBLOCK_PROPERTY_ID, VALUE) 
							SELECT product_id, attribute_id, attribute_value 
							FROM etrade_product_attribute_temp 
							WHERE attribute_id = ". $attribute_id ." AND 
								  product_id = ". $product_data['product_id'] ." AND 
								  attribute_value!='' 
							GROUP BY product_id, attribute_id, value_crc32") or die(SendAnswer("Error: ". mysqli_error()));	
			
			$properties_list_product = $link->query("SELECT IBLOCK_ELEMENT_ID as product_id, IBLOCK_PROPERTY_ID as attribute_id, VALUE as attribute_value, ID as bitrix_enum_id  
														FROM b_iblock_element_prop_m". $product_data['block_id'] ." t2  
														WHERE IBLOCK_PROPERTY_ID = ". $attribute_id ." AND 
															  IBLOCK_ELEMENT_ID = ". $product_data['product_id'] ."  
														") or die(SendAnswer("Error: ". mysqli_error()));	

			$attribute_data_1 = array();
			$attribute_data_2 = array();
			$attribute_data_3 = array();
			while ($arr_properties_list_product = mysqli_fetch_assoc($properties_list_product)) {
				$attribute_data_1['VALUE'][] = $arr_properties_list_product['attribute_value'];
				$attribute_data_2['DESCRIPTION'][] = '';
				$attribute_data_3['ID'][] = $arr_properties_list_product['bitrix_enum_id'];
			}
			$attribute_value = serialize(array($attribute_data_1, $attribute_data_2, $attribute_data_3));
			
			$link->query("UPDATE b_iblock_element_prop_s".$product_data['block_id']." 
							SET b_iblock_element_prop_s".$product_data['block_id'].".PROPERTY_".$attribute_id." = '". mysqli_real_escape_string($link, $attribute_value) ."' 
							WHERE b_iblock_element_prop_s".$product_data['block_id'].".IBLOCK_ELEMENT_ID = ". $product_data['product_id'] ."") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	
	$link->query("UPDATE b_iblock SET PROPERTY_INDEX='I' WHERE `ID` >= (SELECT GROUP_CONCAT(DISTINCT IBLOCK_ID) FROM b_catalog_iblock)") or die(SendAnswer("Error: ". mysqli_error()));
}


function Bitrix_FEATURES_SAVE_MODE2_PICS_CC($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	$photo_property_code = $config_data['photo_property_code'];

	if (empty($photo_property_code)) $photo_property_code='MORE_PHOTO';
	
	bitrix_create_table_flat_for_element_prop(); // создание таблиц
	
	$link->query("DROP TABLE IF EXISTS etrade_cc_pics_ids") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("CREATE TABLE IF NOT EXISTS etrade_cc_pics_ids (
			  `IBLOCK_ELEMENT_ID_EXT` int(11) NOT NULL, 
			  `IBLOCK_ELEMENT_XML_ID` varchar(80) NOT NULL, 
			  PRIMARY KEY (`IBLOCK_ELEMENT_XML_ID`), 
			  KEY `IBLOCK_ELEMENT_ID_EXT` (`IBLOCK_ELEMENT_ID_EXT`)			  
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));	
			
	$link->query("INSERT INTO etrade_cc_pics_ids(IBLOCK_ELEMENT_XML_ID) 
					SELECT item_uuid 
					FROM etrade_image_temp 
					WHERE etrade_image_temp.row_type='product' 
					GROUP BY item_uuid") or die(SendAnswer("Error: ". mysqli_error()));
					
	$link->query("UPDATE etrade_cc_pics_ids, b_iblock_element SET etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=b_iblock_element.ID WHERE b_iblock_element.xml_id=etrade_cc_pics_ids.IBLOCK_ELEMENT_XML_ID ") or die(SendAnswer("Error: ". mysqli_error()));
	
	// set pics for version 2
	// 1 part
	$properties_list=$link->query("SELECT iblock_id, ID as property_id FROM b_iblock_property WHERE PROPERTY_TYPE='F' AND VERSION=2 AND CODE='".$photo_property_code."' GROUP BY b_iblock_property.iblock_id, b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));

	while ($arr_properties_list = mysqli_fetch_assoc($properties_list)) {	
		$link->query("DELETE b_iblock_element_prop_m".$arr_properties_list['iblock_id']." 
			FROM etrade_cc_pics_ids 
			JOIN b_iblock_element_prop_m".$arr_properties_list['iblock_id']." ON etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=IBLOCK_ELEMENT_ID 
			WHERE IBLOCK_PROPERTY_ID=".$arr_properties_list['property_id']." ") or die(SendAnswer("Error: ". mysqli_error()));

		$link->query("INSERT INTO b_iblock_element_prop_m".$arr_properties_list['iblock_id']." (IBLOCK_ELEMENT_ID, IBLOCK_PROPERTY_ID, VALUE, VALUE_NUM) SELECT IBLOCK_ELEMENT_ID, IBLOCK_PROPERTY_ID, VALUE, VALUE_NUM FROM b_iblock_element_property 
			INNER JOIN etrade_cc_pics_ids ON etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=IBLOCK_ELEMENT_ID 
			WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID=".$arr_properties_list['property_id']." ") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	// 2 part
	$properties_list=$link->query("SELECT iblock_id, ID as property_id FROM b_iblock_property WHERE PROPERTY_TYPE='F' AND VERSION=2 AND CODE='".$photo_property_code."' GROUP BY b_iblock_property.iblock_id, b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));

	while ($arr_properties_list = mysqli_fetch_assoc($properties_list)) {
		$properties_values_list=$link->query("SELECT IBLOCK_ELEMENT_ID, GROUP_CONCAT( VALUE ) as property_value_group 
			FROM b_iblock_element_prop_m".$arr_properties_list['iblock_id']." 
			INNER JOIN etrade_cc_pics_ids ON etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=IBLOCK_ELEMENT_ID 
			WHERE IBLOCK_PROPERTY_ID=".$arr_properties_list['property_id']." 
			GROUP BY IBLOCK_ELEMENT_ID") or die(SendAnswer("Error: ". mysqli_error()));
		
		while ($arr_properties_values_list = mysqli_fetch_assoc($properties_values_list)) {
			$property_value_group_content=array('VALUE' => explode(',', $arr_properties_values_list['property_value_group']), 'DESCRIPTION' => array(NULL, NULL, NULL));
			$property_value_group_content=serialize($property_value_group_content);

			$link->query("INSERT INTO b_iblock_element_prop_s".$arr_properties_list['iblock_id']." (IBLOCK_ELEMENT_ID, PROPERTY_".$arr_properties_list['property_id'].") 
							VALUES (".$arr_properties_values_list['IBLOCK_ELEMENT_ID'].", '".$property_value_group_content."') ON DUPLICATE KEY UPDATE PROPERTY_".$arr_properties_list['property_id']."='".$property_value_group_content."'") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	
	$link->query("DROP TABLE IF EXISTS etrade_cc_pics_ids") or die(SendAnswer("Error: ". mysqli_error()));
}

function Bitrix_update_offers_link($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));

	$sql_result = $link->query("SELECT b_catalog_iblock.IBLOCK_ID, b_catalog_iblock.SKU_PROPERTY_ID, b_iblock.VERSION 
								FROM b_catalog_iblock 
								LEFT JOIN b_iblock ON b_iblock.id=b_catalog_iblock.IBLOCK_ID 
								WHERE b_catalog_iblock.PRODUCT_IBLOCK_ID IN (SELECT b_iblock.id FROM b_iblock WHERE xml_id IN (". $config_data['b_iblock_list'] ."))");
	
	if (mysqli_num_rows($sql_result)==0) return;
	
	$sql_row = mysqli_fetch_array($sql_result);
	$offer_iblock_id = $sql_row['IBLOCK_ID'];
	$offer_property_id = $sql_row['SKU_PROPERTY_ID'];
	
	if ($sql_row['VERSION']=='2') {
		$link->query("UPDATE b_iblock_element_prop_s". $offer_iblock_id .", etrade_product_temp 
				SET PROPERTY_". $offer_property_id ." = etrade_product_temp.id_parent 
				WHERE etrade_product_temp.id_parent>0 AND 
					  etrade_product_temp.product_id=b_iblock_element_prop_s". $offer_iblock_id .".IBLOCK_ELEMENT_ID");
				
		$link->query("INSERT INTO b_iblock_element_prop_s". $offer_iblock_id ." (IBLOCK_ELEMENT_ID, PROPERTY_". $offer_property_id .")
				SELECT product_id, id_parent 
				FROM etrade_product_temp 
				WHERE id_parent>0 AND 
					  product_id NOT IN (SELECT IBLOCK_ELEMENT_ID FROM b_iblock_element_prop_s". $offer_iblock_id .")");
	} else {
		$link->query("UPDATE b_iblock_element_property, etrade_product_temp 
			SET b_iblock_element_property.VALUE=etrade_product_temp.id_parent, 
				b_iblock_element_property.VALUE_NUM=etrade_product_temp.id_parent 
			WHERE etrade_product_temp.id_parent>0 AND 
				  b_iblock_element_property.IBLOCK_PROPERTY_ID=". $offer_property_id ." AND 
				  b_iblock_element_property.IBLOCK_ELEMENT_ID=etrade_product_temp.product_id");
				  
		$link->query("INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_NUM)
			SELECT ". $offer_property_id ." as IBLOCK_PROPERTY_ID, product_id, id_parent, id_parent 
			FROM etrade_product_temp 
			WHERE id_parent>0 AND product_id NOT IN (SELECT IBLOCK_ELEMENT_ID FROM b_iblock_element_property WHERE IBLOCK_PROPERTY_ID=". $offer_property_id .")");
	}
}

function Bitrix_get_properties_v2($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	if (empty($config_data['b_iblock_list'])) return;
	
	bitrix_create_table_flat_for_element_prop(); // создание таблиц
	
	$link->query("DROP TABLE IF EXISTS etrade_product_temp_property_from_site_tmp_". $config_data['user_id'] ."") or die(SendAnswer("Error: ". mysqli_error()));
	//ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	$link->query("CREATE TABLE IF NOT EXISTS etrade_product_temp_property_from_site_tmp_". $config_data['user_id'] ." (
		  `iblock_id` int(11) NOT NULL,
		  `element_id` int(11) NOT NULL,
		  `property_id` int(11) NOT NULL,
		  `property_value` text NOT NULL, 
		  `property_value_int` int(11) NOT NULL, 
		  `product_xml_id` varchar(120) NOT NULL,
		  `property_xml_id` varchar(120) NOT NULL,
		  `property_code` varchar(120) NOT NULL,
		  `property_name` varchar(255) NOT NULL,
		  `property_sort_order` int(11) NOT NULL,
		  `IBLOCK_SECTION_ID` int(11) NOT NULL,
		  PROPERTY_TYPE char(1),
		  USER_TYPE varchar(255),
		  LIST_TYPE char(1),
		  FILE_EXTERNAL_ID varchar(80),
	  KEY `PROPERTY_TYPE` (`PROPERTY_TYPE`),
	  KEY `USER_TYPE` (`USER_TYPE`),
	  KEY `LIST_TYPE` (`LIST_TYPE`),
	  KEY `element_id` (`element_id`),
	  KEY `product_xml_id` (`product_xml_id`),
	  KEY `property_id` (`property_id`),
	  KEY `property_xml_id` (`property_xml_id`),
	  KEY `property_value_int` (`property_value_int`),
	  KEY `property_code` (`property_code`),
	  KEY `property_name` (`property_name`),
	  KEY `IBLOCK_SECTION_ID` (`IBLOCK_SECTION_ID`),
	  KEY `FILE_EXTERNAL_ID` (`FILE_EXTERNAL_ID`)
	) ENGINE=". (isset($config_data['db_engine']) && !empty($config_data['db_engine']) ? $config_data['db_engine'] : "InnoDB")." DEFAULT CHARSET=utf8 ". (isset($config_data['db_collate']) && !empty($config_data['db_collate']) ? " COLLATE=".$config_data['db_collate'] : "")) or die(SendAnswer("Error: ". mysqli_error()));
	
	$sql_pivot_convert = '';
	$sql_result = $link->query("SELECT b_iblock_property.IBLOCK_ID, b_iblock_property.ID as property_id, b_iblock_property.NAME, b_iblock_property.ACTIVE, b_iblock_property.SORT, b_iblock_property.CODE, b_iblock_property.XML_ID, b_iblock_property.PROPERTY_TYPE, b_iblock_property.USER_TYPE, b_iblock_property.LIST_TYPE 
								FROM b_iblock_property 
								INNER JOIN b_iblock ON b_iblock.id = b_iblock_property.IBLOCK_ID
								WHERE b_iblock.XML_ID IN (". $config_data['b_iblock_list'] .") AND 
									  b_iblock_property.VERSION = 2") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		$sql_pivot_convert = (!empty($sql_pivot_convert) ? chr(10) . $sql_pivot_convert . ' UNION ' . chr(10) : '');
		$sql_pivot_convert .= " SELECT IBLOCK_ID, 
										IBLOCK_ELEMENT_ID, 
										". $sql_row["property_id"] ." as PROPERTY_ID, 
										PROPERTY_". $sql_row["property_id"] ." as property_value, 
										PROPERTY_". $sql_row["property_id"] ." as property_value_int,
										b_iblock_element.xml_id as product_xml_id, 
										'". $sql_row["XML_ID"] ."' as property_xml_id, 
										'". $sql_row["CODE"] ."' as property_code, 
										'". $sql_row["NAME"] ."' as property_name, 
										'". $sql_row["SORT"] ."' as property_sort_order, 
										'". $sql_row["PROPERTY_TYPE"] ."' as PROPERTY_TYPE, 
										'". $sql_row["USER_TYPE"] ."' as USER_TYPE,
										'". $sql_row["LIST_TYPE"] ."' as LIST_TYPE,
										b_iblock_element.IBLOCK_SECTION_ID 
								FROM b_iblock_element_prop_s". $sql_row["IBLOCK_ID"] . "
								LEFT JOIN b_iblock_element ON b_iblock_element.ID=b_iblock_element_prop_s". $sql_row["IBLOCK_ID"] . ".IBLOCK_ELEMENT_ID 
								";
	}

//file_put_contents('./p_v2.sql', $sql_pivot_convert);
								
	if (!empty($sql_pivot_convert)) {
		$link->query("INSERT INTO etrade_product_temp_property_from_site_tmp_". $config_data['user_id'] ." (iblock_id, element_id, property_id, property_value, property_value_int, product_xml_id, property_xml_id, property_code, property_name, property_sort_order, PROPERTY_TYPE, USER_TYPE, LIST_TYPE, IBLOCK_SECTION_ID) ". chr(10). $sql_pivot_convert) or die(SendAnswer("Error: ". mysqli_error()));
		
		// фото
		$sql_result = $link->query("SELECT b_iblock_property.IBLOCK_ID 
									FROM b_iblock_property 
									INNER JOIN b_iblock ON b_iblock.id = b_iblock_property.IBLOCK_ID
									WHERE b_iblock.XML_ID IN (". $config_data['b_iblock_list'] .") AND 
										  b_iblock_property.VERSION = 2 
									GROUP BY b_iblock_property.IBLOCK_ID") or die(SendAnswer("Error: ". mysqli_error()));
		
		while ($sql_row = mysqli_fetch_array($sql_result)) {
			$link->query("UPDATE etrade_product_temp_property_from_site_tmp_". $config_data['user_id'] ." epp_tmp, 
								 b_iblock_element_prop_m". $sql_row["IBLOCK_ID"] ." epp_site, 
								 b_file 
							SET epp_tmp.property_value = CONCAT('". trim($config_data['site_url']) ."', '/upload/', TRIM(b_file.SUBDIR), '/', TRIM(b_file.FILE_NAME)),  
								epp_tmp.FILE_EXTERNAL_ID = b_file.EXTERNAL_ID 
							WHERE epp_tmp.PROPERTY_TYPE = 'F' AND 
								  epp_tmp.element_id = epp_site.IBLOCK_ELEMENT_ID AND 
								  epp_tmp.property_id = epp_site.IBLOCK_PROPERTY_ID AND 
								  b_file.ID = epp_site.VALUE ");
		}
	}
}




function Bitrix_GetHighloadValues($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];

	$link->query("DROP TABLE IF EXISTS etrade_product_temp_highloadvalues_from_site_tmp_". $config_data['user_id'] ."") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("CREATE TABLE IF NOT EXISTS etrade_product_temp_highloadvalues_from_site_tmp_". $config_data['user_id'] ." (
		  `iblock_id` int(11) NOT NULL,
		  `iblock_element_id` int(11) NOT NULL,
		  `product_xml_id` varchar(120) NOT NULL,
		  `property_xml_id` varchar(120) NOT NULL,
		  `property_code` varchar(120) NOT NULL,
		  `property_name` varchar(255) NOT NULL,
		  `property_value` text NOT NULL, 
		  `property_value_description` text NOT NULL, 
		  `user_type_settings` text NOT NULL, 
		  `iblock_property_id` int(11) NOT NULL,
		  `image_url` text NOT NULL,
		  `property_url` text NOT NULL,
		  `property_description` text NOT NULL,
		  `property_sort_order` int(11) NOT NULL,
		  property_external_code text NOT NULL,
		  property_highload_xml_id text NOT NULL,
	  KEY `iblock_element_id` (`iblock_element_id`),
	  KEY `product_xml_id` (`product_xml_id`),
	  KEY `iblock_property_id` (`iblock_property_id`),
	  KEY `property_xml_id` (`property_xml_id`),
	  KEY `property_code` (`property_code`),
	  KEY `property_name` (`property_name`)
	) ENGINE=". (isset($config_data['db_engine']) && !empty($config_data['db_engine']) ? $config_data['db_engine'] : "InnoDB")." DEFAULT CHARSET=utf8 ". (isset($config_data['db_collate']) && !empty($config_data['db_collate']) ? " COLLATE=".$config_data['db_collate'] : "")) or die(SendAnswer("Error: ". mysqli_error()));
// DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

	$link->query("INSERT INTO etrade_product_temp_highloadvalues_from_site_tmp_". $config_data['user_id'] ." (iblock_id, iblock_element_id, product_xml_id, property_xml_id, property_code, 
		property_name, property_value, property_value_description, user_type_settings, iblock_property_id) 
	SELECT b_iblock_property.iblock_id, b_iblock_element_property.IBLOCK_ELEMENT_ID, b_iblock_element.xml_id, b_iblock_property.xml_id, 
		b_iblock_property.CODE, b_iblock_property.NAME, b_iblock_element_property.VALUE, b_iblock_element_property.DESCRIPTION, b_iblock_property.user_type_settings, b_iblock_property.ID 
	FROM b_iblock_property 
	INNER JOIN b_iblock_element_property ON b_iblock_property.ID=b_iblock_element_property.IBLOCK_PROPERTY_ID 
	INNER JOIN b_iblock_element ON b_iblock_element.ID=b_iblock_element_property.IBLOCK_ELEMENT_ID 
	WHERE b_iblock_property.USER_TYPE='directory' 
	ORDER BY b_iblock_element_property.IBLOCK_ELEMENT_ID, b_iblock_property.NAME") or die(SendAnswer("Error: ". mysqli_error()));
	
	$res_properties_list = $link->query("SELECT user_type_settings, iblock_property_id, property_value FROM etrade_product_temp_highloadvalues_from_site_tmp_". $config_data['user_id'] ." GROUP BY iblock_property_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_properties_list = mysqli_fetch_assoc($res_properties_list)) {
		$user_type_settings = unserialize($arr_properties_list['user_type_settings']);
		
		$link->query("UPDATE etrade_product_temp_highloadvalues_from_site_tmp_". $config_data['user_id'] ." etrade_temp, ".$user_type_settings['TABLE_NAME']." bitrix_table 
			LEFT JOIN b_file ON b_file.id = bitrix_table.UF_FILE 
			SET etrade_temp.property_value = bitrix_table.UF_NAME, 
				etrade_temp.image_url = CONCAT('". $config_data['site_url'] ."', '/upload/', TRIM(b_file.SUBDIR), '/', TRIM(b_file.FILE_NAME)), 
				etrade_temp.property_url = bitrix_table.UF_LINK, 
				etrade_temp.property_sort_order = bitrix_table.UF_SORT, 
				etrade_temp.property_highload_xml_id = bitrix_table.UF_XML_ID 
			WHERE etrade_temp.iblock_property_id=".$arr_properties_list['iblock_property_id']." AND etrade_temp.property_value=bitrix_table.UF_XML_ID") or die(SendAnswer("Error: ". mysqli_error()));
			
			//bitrix_table.UF_XML_ID COLLATE utf8_general_ci
	}
}

// Bitrix - Create search content for products
function Bitrix_SearchContent($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	$link->query("CREATE TABLE IF NOT EXISTS `b_search_content_text` (
	  `SEARCH_CONTENT_ID` int(11) NOT NULL,
	  `SEARCH_CONTENT_MD5` char(32) collate utf8_unicode_ci NOT NULL,
	  `SEARCHABLE_CONTENT` longtext collate utf8_unicode_ci,
	  PRIMARY KEY  (`SEARCH_CONTENT_ID`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("DROP TEMPORARY TABLE IF EXISTS b_search_content_tmp") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("CREATE TEMPORARY TABLE b_search_content_tmp (`item_id` int(11) NOT NULL, KEY `item_id` (`item_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("INSERT INTO b_search_content_tmp (item_id) SELECT ITEM_ID FROM b_search_content WHERE MODULE_ID='iblock' AND ITEM_ID>0 GROUP BY ITEM_ID") or die(SendAnswer("Error: ". mysqli_error()));
	
	$res_block_list = $link->query("SELECT b_iblock_element.ID, b_iblock_element.XML_ID as EXTERNAL_ID, b_iblock_element.IBLOCK_SECTION_ID, b_iblock_element.IBLOCK_ID, b_iblock.CODE as IBLOCK_CODE, 
		b_iblock.XML_ID as IBLOCK_EXTERNAL_ID, b_iblock_element.CODE, b_iblock_element.NAME as TITLE, iblock_type_id 
		FROM b_iblock_element 
		LEFT JOIN b_iblock ON b_iblock_element.IBLOCK_ID=b_iblock.ID 
		LEFT JOIN b_search_content_tmp ON b_iblock_element.ID=b_search_content_tmp.item_id 
		WHERE b_iblock.iblock_type_id IN (SELECT ID FROM b_iblock_type WHERE SECTIONS='Y') AND b_iblock.active='Y' AND b_search_content_tmp.item_id IS NULL 
		GROUP BY b_iblock_element.ID") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_block_list = mysqli_fetch_assoc($res_block_list)) {
		$link->query("INSERT INTO b_search_content (DATE_CHANGE, MODULE_ID, ITEM_ID, URL, TITLE, BODY, TAGS, PARAM1, PARAM2) 
						VALUES (now(), 'iblock', ".$arr_block_list["ID"].", '=ID=". mysqli_real_escape_string($link, $arr_block_list["ID"])."&EXTERNAL_ID=". mysqli_real_escape_string($link, $arr_block_list["EXTERNAL_ID"]) ."&IBLOCK_SECTION_ID=". mysqli_real_escape_string($link, $arr_block_list["IBLOCK_SECTION_ID"]) ."&IBLOCK_TYPE_ID=". mysqli_real_escape_string($link, $arr_block_list["iblock_type_id"]) ."&IBLOCK_ID=".$arr_block_list["IBLOCK_ID"]."&IBLOCK_CODE=".mysqli_real_escape_string($link, $arr_block_list["IBLOCK_CODE"])."&IBLOCK_EXTERNAL_ID=".mysqli_real_escape_string($link, $arr_block_list["IBLOCK_EXTERNAL_ID"])."&CODE=".mysqli_real_escape_string($link, $arr_block_list["CODE"])."', '".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."', '', '', '".mysqli_real_escape_string($link, $arr_block_list["iblock_type_id"])."', '".mysqli_real_escape_string($link, $arr_block_list["IBLOCK_ID"])."') ON DUPLICATE KEY UPDATE DATE_CHANGE=now()") or die(SendAnswer("Error: ". mysqli_error()));

		$search_content_id = mysqli_insert_id($link);
		if ($search_content_id>0) {
			$link->query("INSERT INTO b_search_content_right (SEARCH_CONTENT_ID, GROUP_CODE) VALUES(".$search_content_id.", 'G1') ON DUPLICATE KEY UPDATE GROUP_CODE='G1'") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("INSERT INTO b_search_content_right (SEARCH_CONTENT_ID, GROUP_CODE) VALUES(".$search_content_id.", 'G2') ON DUPLICATE KEY UPDATE GROUP_CODE='G2'") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("INSERT INTO b_search_content_site (SEARCH_CONTENT_ID, SITE_ID, URL) VALUES (".$search_content_id.", 's1', '') ON DUPLICATE KEY UPDATE SITE_ID='s1'") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("INSERT INTO b_search_content_title (SEARCH_CONTENT_ID, SITE_ID, POS, WORD) VALUES (".$search_content_id.", 's1', 0, '".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."') ON DUPLICATE KEY UPDATE SITE_ID='s1'") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("INSERT INTO b_search_content_stem (SEARCH_CONTENT_ID, LANGUAGE_ID, STEM, TF) VALUES (".$search_content_id.", 'ru', 235, 0.2314) ON DUPLICATE KEY UPDATE LANGUAGE_ID='ru'") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("INSERT INTO b_search_content_text (SEARCH_CONTENT_ID, SEARCH_CONTENT_MD5, SEARCHABLE_CONTENT) VALUES (".$search_content_id.", md5('".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."'), '".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."\r\n\r\n') ON DUPLICATE KEY UPDATE SEARCHABLE_CONTENT='".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."\r\n\r\n'") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	
	$link->query("DROP TABLE IF EXISTS b_search_content_tmp") or die(SendAnswer("Error: ". mysqli_error()));
}

function Bitrix_update_highload_block($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	// обновление значений в типе свойств «Справочник» (Highload инфоблоки)
	$res_properties_list = $link->query("SELECT b_iblock_property.`ID`, b_iblock_property.iblock_id, b_iblock_property.`NAME`, b_iblock_property.`CODE`, b_iblock_property.`USER_TYPE_SETTINGS`, b_iblock_property.VERSION, b_iblock_property.MULTIPLE 
		FROM b_iblock_property 
		INNER JOIN etrade_attribute_temp ON b_iblock_property.xml_id=etrade_attribute_temp.uuid 
		WHERE b_iblock_property.USER_TYPE='directory' 
		GROUP BY b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_properties_list = mysqli_fetch_assoc($res_properties_list)) {
		$USER_TYPE_SETTINGS=unserialize($arr_properties_list['USER_TYPE_SETTINGS']);
/*		
		$link->query("INSERT INTO ".$USER_TYPE_SETTINGS['TABLE_NAME']." (UF_NAME, UF_SORT, UF_FILE, UF_LINK, UF_XML_ID) 
			SELECT etrade_product_attribute_temp.attribute_value, 0 as UF_SORT, 0 as UF_FILE, '' as UF_LINK, CONCAT('PLI-', md5(etrade_product_attribute_temp.attribute_value)) as UF_XML_ID
			FROM etrade_product_attribute_temp 
			WHERE etrade_product_attribute_temp.attribute_id=".$arr_properties_list['ID']." AND 
				  etrade_product_attribute_temp.attribute_value<>'' AND 
				  etrade_product_attribute_temp.attribute_value NOT IN (SELECT UF_NAME FROM ".$USER_TYPE_SETTINGS['TABLE_NAME'].") 
			GROUP BY etrade_product_attribute_temp.attribute_value") or die(SendAnswer("Error: ". mysqli_error()));
						
		SELECT product_id, attribute_id, substring_index(substring_index(attribute_value, ',', n), ',', -1) as attribute_value_list 
		FROM etrade_product_attribute_temp 
		JOIN (SELECT @row := @row + 1 as n FROM (select 0 union all select 1 union all select 3 union all select 4 union all select 5 union all select 6 union all select 6 union all select 7 union all select 8 union all select 9) t, (SELECT @row:=0) r) as numbers on char_length(attribute_value) - char_length(replace(attribute_value, ',', '')) >= n - 1 
		WHERE attribute_id = 5 
		GROUP BY attribute_value_list

*/		

		$result_total_values = $link->query('SELECT ROUND ( ( LENGTH(attribute_value) - LENGTH( REPLACE ( attribute_value, ",", "") ) ) / LENGTH(",") ) AS total_values FROM etrade_product_attribute_temp where attribute_id="'. $arr_properties_list["ID"] .'" GROUP BY total_values');
		
		$total_values = mysqli_fetch_assoc($result_total_values);
		$total_values = (int)$total_values['total_values'];
		
		$union_list = '';
		for ($iStep = 0; $iStep <= $total_values; $iStep++) {
			$union_list .= (!empty($union_list) ? ' union all '. chr(10) : '');
			$union_list .= ' select '. $iStep;
		}
		$union_list = (empty($union_list) ? ' select 0 union all select 1 union all select 3 union all select 4 union all select 5 union all select 6 union all select 6 union all select 7 union all select 8 union all select 9 ' : $union_list);
		
		//file_put_contents('./total_values.sql', $total_values);		
		//file_put_contents('./union_list.sql', $union_list);		

		$link->query('DROP TABLE IF EXISTS etrade_product_attribute_list_temp') or die(SendAnswer("Error: ". mysqli_error()));
		$link->query('CREATE TABLE IF NOT EXISTS etrade_product_attribute_list_temp 
			(attribute_value text NOT NULL, 
			  row_exist tinyint(1) NOT NULL, 
			  KEY attribute_value (attribute_value(100)), 
			  KEY row_exist (row_exist)  
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci') or die(SendAnswer("Error: ". mysqli_error()));
			
		if ($arr_properties_list["MULTIPLE"]=='Y') {
			$link->query("INSERT INTO etrade_product_attribute_list_temp (attribute_value) 
							SELECT TRIM( substring_index(substring_index(attribute_value, ',', n), ',', -1) ) as attribute_value_list
							FROM etrade_product_attribute_temp 
							JOIN (SELECT @row := @row + 1 as n FROM ( ". $union_list ." ) t, (SELECT @row:=0) r) as numbers on char_length(attribute_value) - char_length(replace(attribute_value, ',', '')) >= n - 1 
							WHERE attribute_id = ".$arr_properties_list['ID']." AND attribute_value<>'' 
							GROUP BY attribute_value_list") or die(SendAnswer("Error: ". mysqli_error()));

		} else {
			$link->query("INSERT INTO etrade_product_attribute_list_temp (attribute_value) 
				SELECT attribute_value 
				FROM etrade_product_attribute_temp 
				WHERE attribute_id = ".$arr_properties_list['ID']." AND attribute_value<>'' 
				GROUP BY attribute_value") or die(SendAnswer("Error: ". mysqli_error()));
		}

		$link->query("UPDATE etrade_product_attribute_list_temp, ".$USER_TYPE_SETTINGS['TABLE_NAME']." site_table 
				SET row_exist=1 
				WHERE BINARY etrade_product_attribute_list_temp.attribute_value = BINARY site_table.UF_NAME");	  
				
		$link->query("INSERT INTO ".$USER_TYPE_SETTINGS['TABLE_NAME']." (UF_NAME, UF_SORT, UF_FILE, UF_LINK, UF_XML_ID) 
						SELECT attribute_value, 0 as UF_SORT, 0 as UF_FILE, '' as UF_LINK, CONCAT('PLI-', md5(attribute_value)) as UF_XML_ID
						FROM etrade_product_attribute_list_temp  
						WHERE row_exist=0 
						") or die(SendAnswer("Error: ". mysqli_error()));
						
						//attribute_value NOT IN (SELECT UF_NAME FROM ".$USER_TYPE_SETTINGS['TABLE_NAME']." GROUP BY UF_NAME) 
		
		
		//die('ss');
		//$link->query("UPDATE ".$USER_TYPE_SETTINGS['TABLE_NAME']." SET UF_XML_ID=CONCAT('PLI-', md5(UF_NAME)) WHERE UF_XML_ID='' OR UF_XML_ID IS NULL") or die(SendAnswer("Error: ". mysqli_error()));
			
		$link->query('DROP TABLE IF EXISTS etrade_product_attribute_list_temp') or die(SendAnswer("Error: ". mysqli_error()));
	
		/*						
		$link->query("INSERT INTO b_iblock_element_property(IBLOCK_ELEMENT_ID, IBLOCK_PROPERTY_ID, value) 
				SELECT pa.product_id, ".$arr_properties_list['ID'].", pa.attribute_value 
				FROM etrade_product_attribute_temp pa 
				INNER JOIN etrade_attribute_temp a ON a.attribute_id=pa.attribute_id AND a.bitrix_property_user_type="" 
				WHERE pa.row_exist=0 AND pa.product_id>0 AND pa.attribute_id>0 AND a.bitrix_property_type<>'L';;;");
		*/

		$link->query("UPDATE b_iblock_element_property, ".$USER_TYPE_SETTINGS['TABLE_NAME']." 
						SET b_iblock_element_property.VALUE = ".$USER_TYPE_SETTINGS['TABLE_NAME'].".UF_XML_ID 
						WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID=".$arr_properties_list['ID']." AND 
							  BINARY b_iblock_element_property.VALUE = BINARY ".$USER_TYPE_SETTINGS['TABLE_NAME'].".UF_NAME") or die(SendAnswer("Error: ". mysqli_error()));
					  
		if ($arr_properties_list['VERSION']=='2') { // update values for FLAT table
			// создаём таблицы для хранения данных для 2й версии инфоблоков
			bitrix_create_table_flat_for_element_prop();
			
/* 			$link->query("CREATE TABLE IF NOT EXISTS b_iblock_element_prop_s".$arr_properties_list['iblock_id']." (
						  `IBLOCK_ELEMENT_ID` int(11) NOT NULL, 
						  PRIMARY KEY (`IBLOCK_ELEMENT_ID`) 
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error())); */	

			$field_exist_query=$link->query("SHOW COLUMNS FROM b_iblock_element_prop_s".$arr_properties_list['iblock_id']." WHERE `Field`='PROPERTY_".$arr_properties_list['ID']."'") or die(SendAnswer("Error: ". mysqli_error()));
			
			if (mysqli_num_rows($field_exist_query)==0) {
				$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `PROPERTY_".$arr_properties_list['ID']."` text") or die(SendAnswer("Error: ". mysqli_error()));
				$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `DESCRIPTION_".$arr_properties_list['ID']."` text DEFAULT NULL") or die(SendAnswer("Error: ". mysqli_error()));
			}
			
			$link->query("UPDATE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].", b_iblock_element_property 
				SET b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".PROPERTY_".$arr_properties_list['ID']." = b_iblock_element_property.VALUE 
				WHERE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".IBLOCK_ELEMENT_ID = b_iblock_element_property.IBLOCK_ELEMENT_ID AND 
					  b_iblock_element_property.IBLOCK_PROPERTY_ID = ".$arr_properties_list['ID']) or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	

}


function Bitrix_clean_cache($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	//$link->query("UPDATE b_iblock SET PROPERTY_INDEX='I' WHERE `ID` >= (SELECT GROUP_CONCAT(DISTINCT IBLOCK_ID) FROM b_catalog_iblock);;;");
	$link->query("UPDATE b_iblock SET PROPERTY_INDEX='I' WHERE XML_ID IN (". $config_data['b_iblock_list'] .")");
	
	// delete cache
	remove_files('../bitrix/managed_cache/MYSQL/b_iblock', $delete_root = false, $pattern = '');
	remove_files('../bitrix/managed_cache/MYSQL/b_iblock_type', $delete_root = false, $pattern = '');
	remove_files('../bitrix/managed_cache/MYSQL/b_file', $delete_root = false, $pattern = '');
	remove_files('../bitrix/managed_cache/MYSQL/catalog_group', $delete_root = false, $pattern = '');
	remove_files('../bitrix/managed_cache/MYSQL/b_catalog_currency', $delete_root = false, $pattern = '');
}

// Bitrix - Create new block in DB
function Bitrix_create_block_on_db($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	if ($config_data['main_category_is_block']==0) return;
	

	$link->query("INSERT INTO b_iblock_site (iblock_id, site_id) 
					SELECT id, 's1' as site_id 
					FROM b_iblock 
					WHERE id NOT IN (SELECT iblock_id FROM b_iblock_site)");

	$link->query("INSERT INTO b_iblock_group (iblock_id, group_id, permission) 
					SELECT id, 1 as group_id, 'X' as permission 
					FROM b_iblock  
					WHERE id NOT IN (SELECT iblock_id FROM b_iblock_group WHERE group_id=1)");

	$link->query("INSERT INTO b_iblock_group (iblock_id, group_id, permission) 
					SELECT id, 2 as group_id, 'R' as permission 
					FROM b_iblock  
					WHERE id NOT IN (SELECT iblock_id FROM b_iblock_group WHERE group_id=2)");
	
/*
	DROP TEMPORARY TABLE IF EXISTS b_iblock_fields_temp;;;
	CREATE TEMPORARY TABLE IF NOT EXISTS `b_iblock_fields_temp` (
	  `IBLOCK_ID` int(18) NOT NULL,
	  `FIELD_ID` varchar(50) NOT NULL,
	  `IS_REQUIRED` char(1) DEFAULT NULL,
	  `DEFAULT_VALUE` longtext
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;;; 
	
					INSERT INTO `b_iblock_fields` (`IBLOCK_ID`, `FIELD_ID`, `IS_REQUIRED`, `DEFAULT_VALUE`) 
					SELECT id, 'LOG_SECTION_EDIT' as field_id, 'N' as is_required, NULL as default_value 
						FROM b_iblock 
						b_iblock_fields 
						WHERE id NOT IN 
							(SELECT iblock_id FROM b_iblock_fields WHERE field_id='LOG_SECTION_EDIT');;;	
*/
							   
			$res_block_list = $link->query("SELECT b_iblock.id 
									FROM b_iblock 
									INNER JOIN etrade_category_temp ON b_iblock.xml_id=etrade_category_temp.uuid AND etrade_category_temp.parent_id=0 
									WHERE b_iblock.id NOT IN (SELECT iblock_id FROM b_iblock_fields) 
									GROUP BY b_iblock.id COLLATE utf8_general_ci");
									
			while ($arr_block_list = mysqli_fetch_assoc($res_block_list)) {
				$link->query("INSERT INTO `b_iblock_fields` (`IBLOCK_ID`, `FIELD_ID`, `IS_REQUIRED`, `DEFAULT_VALUE`) VALUES
						(". $arr_block_list['id'] .", 'ACTIVE', 'Y', 'Y'),
						(". $arr_block_list['id'] .", 'ACTIVE_FROM', 'N', ''),
						(". $arr_block_list['id'] .", 'ACTIVE_TO', 'N', ''),
						(". $arr_block_list['id'] .", 'CODE', 'N', 'a:8:{s:6:\"UNIQUE\";s:1:\"N\";s:15:\"TRANSLITERATION\";s:1:\"N\";s:9:\"TRANS_LEN\";i:100;s:10:\"TRANS_CASE\";s:1:\"L\";s:11:\"TRANS_SPACE\";s:1:\"-\";s:11:\"TRANS_OTHER\";s:1:\"-\";s:9:\"TRANS_EAT\";s:1:\"Y\";s:10:\"USE_GOOGLE\";s:1:\"N\";}'),
						(". $arr_block_list['id'] .", 'DETAIL_PICTURE', 'N', 'a:17:{s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}'),
						(". $arr_block_list['id'] .", 'DETAIL_TEXT', 'N', ''),
						(". $arr_block_list['id'] .", 'DETAIL_TEXT_TYPE', 'Y', 'text'),
						(". $arr_block_list['id'] .", 'DETAIL_TEXT_TYPE_ALLOW_CHANGE', 'N', 'Y'),
						(". $arr_block_list['id'] .", 'IBLOCK_SECTION', 'N', 'a:1:{s:22:\"KEEP_IBLOCK_SECTION_ID\";s:1:\"N\";}'),
						(". $arr_block_list['id'] .", 'LOG_ELEMENT_ADD', 'N', NULL),
						(". $arr_block_list['id'] .", 'LOG_ELEMENT_DELETE', 'N', NULL),
						(". $arr_block_list['id'] .", 'LOG_ELEMENT_EDIT', 'N', NULL),
						(". $arr_block_list['id'] .", 'LOG_SECTION_ADD', 'N', NULL),
						(". $arr_block_list['id'] .", 'LOG_SECTION_DELETE', 'N', NULL),
						(". $arr_block_list['id'] .", 'LOG_SECTION_EDIT', 'N', NULL),
						(". $arr_block_list['id'] .", 'NAME', 'Y', ''),
						(". $arr_block_list['id'] .", 'PREVIEW_PICTURE', 'N', 'a:20:{s:11:\"FROM_DETAIL\";s:1:\"N\";s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"DELETE_WITH_DETAIL\";s:1:\"N\";s:18:\"UPDATE_WITH_DETAIL\";s:1:\"N\";s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}'),
						(". $arr_block_list['id'] .", 'PREVIEW_TEXT', 'N', ''),
						(". $arr_block_list['id'] .", 'PREVIEW_TEXT_TYPE', 'Y', 'text'),
						(". $arr_block_list['id'] .", 'PREVIEW_TEXT_TYPE_ALLOW_CHANGE', 'N', 'Y'),
						(". $arr_block_list['id'] .", 'SECTION_CODE', 'N', 'a:8:{s:6:\"UNIQUE\";s:1:\"N\";s:15:\"TRANSLITERATION\";s:1:\"N\";s:9:\"TRANS_LEN\";i:100;s:10:\"TRANS_CASE\";s:1:\"L\";s:11:\"TRANS_SPACE\";s:1:\"-\";s:11:\"TRANS_OTHER\";s:1:\"-\";s:9:\"TRANS_EAT\";s:1:\"Y\";s:10:\"USE_GOOGLE\";s:1:\"N\";}'),
						(". $arr_block_list['id'] .", 'SECTION_DESCRIPTION', 'N', ''),
						(". $arr_block_list['id'] .", 'SECTION_DESCRIPTION_TYPE', 'Y', 'text'),
						(". $arr_block_list['id'] .", 'SECTION_DESCRIPTION_TYPE_ALLOW_CHANGE', 'N', 'Y'),
						(". $arr_block_list['id'] .", 'SECTION_DETAIL_PICTURE', 'N', 'a:17:{s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}'),
						(". $arr_block_list['id'] .", 'SECTION_NAME', 'Y', ''),
						(". $arr_block_list['id'] .", 'SECTION_PICTURE', 'N', 'a:20:{s:11:\"FROM_DETAIL\";s:1:\"N\";s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"DELETE_WITH_DETAIL\";s:1:\"N\";s:18:\"UPDATE_WITH_DETAIL\";s:1:\"N\";s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}'),
						(". $arr_block_list['id'] .", 'SECTION_XML_ID', 'N', ''),
						(". $arr_block_list['id'] .", 'SORT', 'N', '0'),
						(". $arr_block_list['id'] .", 'TAGS', 'N', ''),
						(". $arr_block_list['id'] .", 'XML_ID', 'Y', ''),
						(". $arr_block_list['id'] .", 'XML_IMPORT_START_TIME', 'N', NULL)");
			}

			// кнопки
			$res_block_list = $link->query("SELECT b_iblock.id 
									FROM b_iblock 
									INNER JOIN etrade_category_temp ON b_iblock.xml_id=etrade_category_temp.uuid AND etrade_category_temp.parent_id=0 
									WHERE b_iblock.id NOT IN (SELECT iblock_id FROM b_iblock_messages) 
									GROUP BY b_iblock.id COLLATE utf8_general_ci");
									
			while ($arr_block_list = mysqli_fetch_assoc($res_block_list)) {
				$link->query("INSERT INTO `b_iblock_messages` (`IBLOCK_ID`, `MESSAGE_ID`, `MESSAGE_TEXT`) VALUES
							(". $arr_block_list['id'] .", 'ELEMENTS_NAME', 'Элементы'),
							(". $arr_block_list['id'] .", 'ELEMENT_ADD', 'Добавить элемент'),
							(". $arr_block_list['id'] .", 'ELEMENT_DELETE', 'Удалить элемент'),
							(". $arr_block_list['id'] .", 'ELEMENT_EDIT', 'Изменить элемент'),
							(". $arr_block_list['id'] .", 'ELEMENT_NAME', 'Элемент'),
							(". $arr_block_list['id'] .", 'SECTIONS_NAME', 'Разделы'),
							(". $arr_block_list['id'] .", 'SECTION_ADD', 'Добавить раздел'),
							(". $arr_block_list['id'] .", 'SECTION_DELETE', 'Удалить раздел'),
							(". $arr_block_list['id'] .", 'SECTION_EDIT', 'Изменить раздел'),
							(". $arr_block_list['id'] .", 'SECTION_NAME', 'Раздел')");
			}

			// свойства
			$result_block_list = $link->query("SELECT b_iblock.id 
												FROM b_iblock 
												INNER JOIN etrade_category_temp ON b_iblock.xml_id=etrade_category_temp.uuid AND etrade_category_temp.parent_id=0 
												GROUP BY b_iblock.id COLLATE utf8_general_ci") or die(SendAnswer("Error: ". mysqli_error()));

			while ($arr_block_list = mysqli_fetch_assoc($result_block_list)) {	
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, LIST_TYPE, FILTRABLE, SEARCHABLE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'SPECIALOFFER' as CODE, 'Спецпредложение' as NAME, 100 as SORT, 'L' as PROPERTY_TYPE, 'C' as LIST_TYPE, 'Y' as FILTRABLE, 'Y' as SEARCHABLE, 'Y' as MULTIPLE, 
						UUID() as XML_ID, '5' as MULTIPLE_CNT, '0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE CODE='SPECIALOFFER' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, LIST_TYPE, FILTRABLE, SEARCHABLE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'NEWPRODUCT' as CODE, 'Новинка' as NAME, 110 as SORT, 'L' as PROPERTY_TYPE, 'C' as LIST_TYPE, 'Y' as FILTRABLE, 'Y' as SEARCHABLE, 'Y' as MULTIPLE, 
						UUID() as XML_ID, '5' as MULTIPLE_CNT, '0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE CODE='NEWPRODUCT' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, LIST_TYPE, FILTRABLE, SEARCHABLE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'SALELEADER' as CODE, 'Лидер продаж' as NAME, 120 as SORT, 'L' as PROPERTY_TYPE, 'C' as LIST_TYPE, 'Y' as FILTRABLE, 'Y' as SEARCHABLE, 'Y' as MULTIPLE, 
						UUID() as XML_ID, '5' as MULTIPLE_CNT, '0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE CODE='SALELEADER' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'RECOMMEND' as CODE, 'С этим товаром рекомендуем' as NAME, 1000 as SORT, 'E' as PROPERTY_TYPE, 'Y' as MULTIPLE, UUID() as XML_ID, '5' as MULTIPLE_CNT, 
						'0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE CODE='RECOMMEND' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'MORE_PHOTO' as CODE, 'Картинки' as NAME, 1100 as SORT, 'F' as PROPERTY_TYPE, 'Y' as MULTIPLE, UUID() as XML_ID, '5' as MULTIPLE_CNT, '0' as LINK_IBLOCK_ID, 
						'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE CODE='MORE_PHOTO' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'MINIMUM_PRICE' as CODE, 'Минимальная цена' as NAME, 1200 as SORT, 'N' as PROPERTY_TYPE, 'N' as MULTIPLE, UUID() as XML_ID, '5' as MULTIPLE_CNT, 
						'0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE CODE='MINIMUM_PRICE' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'MAXIMUM_PRICE' as CODE, 'Максимальная цена' as NAME, 1300 as SORT, 'N' as PROPERTY_TYPE, 'N' as MULTIPLE, UUID() as XML_ID, '5' as MULTIPLE_CNT, 
						'0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE CODE='MAXIMUM_PRICE' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, LIST_TYPE, FILTRABLE, SEARCHABLE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'ARTNUMBER' as CODE, 'Артикул' as NAME, 100 as SORT, 'S' as PROPERTY_TYPE, 'L' as LIST_TYPE, 'N' as FILTRABLE, 'Y' as SEARCHABLE, 'N' as MULTIPLE, 
						UUID() as XML_ID, '' as MULTIPLE_CNT, '0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE NAME='Артикул' GROUP BY iblock_id)");
							
				$link->query("INSERT INTO b_iblock_property(iblock_id, CODE, NAME, SORT, PROPERTY_TYPE, LIST_TYPE, FILTRABLE, SEARCHABLE, MULTIPLE, XML_ID, MULTIPLE_CNT, LINK_IBLOCK_ID, IS_REQUIRED, WITH_DESCRIPTION)  
					SELECT id, 'MANUFACTURER' as CODE, 'Производитель' as NAME, 100 as SORT, 'S' as PROPERTY_TYPE, 'L' as LIST_TYPE, 'N' as FILTRABLE, 'Y' as SEARCHABLE, 'N' as MULTIPLE, 
						UUID() as XML_ID, '' as MULTIPLE_CNT, '0' as LINK_IBLOCK_ID, 'N' as IS_REQUIRED, 'N' as WITH_DESCRIPTION 
						FROM b_iblock 
						WHERE b_iblock.ID = ". $arr_block_list['id'] ." AND id NOT IN 
							(SELECT iblock_id FROM b_iblock_property WHERE NAME='Производитель' GROUP BY iblock_id)");
			}

							
}

// Bitrix - Create new block on HDD 
function Bitrix_create_block_on_disk($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	if (!is_file('./iBlockTemplate.dat')) exit;
	
	// get data from db
	$link->query("CREATE TABLE IF NOT EXISTS `b_search_content_text` (
	  `SEARCH_CONTENT_ID` int(11) NOT NULL,
	  `SEARCH_CONTENT_MD5` char(32) collate utf8_unicode_ci NOT NULL,
	  `SEARCHABLE_CONTENT` longtext collate utf8_unicode_ci,
	  PRIMARY KEY  (`SEARCH_CONTENT_ID`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));
	
	$res_block_list = $link->query("SELECT id, code, name FROM b_iblock WHERE iblock_type_id IN (SELECT ID FROM b_iblock_type WHERE SECTIONS='Y') AND active='Y'") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_block_list = mysqli_fetch_assoc($res_block_list)) {
		$iBlockID=$arr_block_list["id"];
		$iBlockCode=$arr_block_list["code"];
		$iBlockName=$arr_block_list["name"];
		
		$dest_dir='../catalog/'.$iBlockCode.'/';

		if (is_dir($dest_dir)==false) { // Create temp dir
			mkdir($dest_dir, 0755, true);
			
			// block template
			if (is_dir($dest_dir)==true) {
				$iBlockTemplate=file_get_contents('./iBlockTemplate.dat');
				$iBlockTemplate=str_replace('{BlockTitle}', $iBlockName, $iBlockTemplate);
				$iBlockTemplate=str_replace('{BlockID}', $iBlockID, $iBlockTemplate);
				$iBlockTemplate=str_replace('{BlockFolderName}', $iBlockCode, $iBlockTemplate);
				
				file_put_contents($dest_dir.'index.php', $iBlockTemplate);
				
				$link->query("INSERT INTO b_search_content (DATE_CHANGE, MODULE_ID, ITEM_ID, URL, TITLE, BODY, TAGS, PARAM1, PARAM2) VALUES (now(), 'main', 's1|/catalog/".$iBlockCode."/index.php', '/catalog/".$iBlockCode."/index.php', '".mysqli_real_escape_string($link, $iBlockName)."', '', '', '', '') ON DUPLICATE KEY UPDATE DATE_CHANGE=now()") or die(SendAnswer("Error: ". mysqli_error()));
				$search_content_id = mysqli_insert_id($link);
				
				$link->query("INSERT INTO b_search_content_right (SEARCH_CONTENT_ID, GROUP_CODE) VALUES(".$search_content_id.", 'G1') ON DUPLICATE KEY UPDATE GROUP_CODE='G1'") or die(SendAnswer("Error: ". mysqli_error()));
				$link->query("INSERT INTO b_search_content_right (SEARCH_CONTENT_ID, GROUP_CODE) VALUES(".$search_content_id.", 'G2') ON DUPLICATE KEY UPDATE GROUP_CODE='G2'") or die(SendAnswer("Error: ". mysqli_error()));
				$link->query("INSERT INTO b_search_content_site (SEARCH_CONTENT_ID, SITE_ID, URL) VALUES (".$search_content_id.", 's1', '') ON DUPLICATE KEY UPDATE SITE_ID='s1'") or die(SendAnswer("Error: ". mysqli_error()));
				$link->query("INSERT INTO b_search_content_title (SEARCH_CONTENT_ID, SITE_ID, POS, WORD) VALUES (".$search_content_id.", 's1', 0, '".mysqli_real_escape_string($link, $iBlockName)."') ON DUPLICATE KEY UPDATE SITE_ID='s1'") or die(SendAnswer("Error: ". mysqli_error()));
				$link->query("INSERT INTO b_search_content_stem (SEARCH_CONTENT_ID, LANGUAGE_ID, STEM, TF) VALUES (".$search_content_id.", 'ru', 235, 0.2314) ON DUPLICATE KEY UPDATE LANGUAGE_ID='ru'") or die(SendAnswer("Error: ". mysqli_error()));
				$link->query("INSERT INTO b_search_content_text (SEARCH_CONTENT_ID, SEARCH_CONTENT_MD5, SEARCHABLE_CONTENT) VALUES (".$search_content_id.", md5('".mysqli_real_escape_string($link, $iBlockName)."'), '".mysqli_real_escape_string($link, $iBlockName)."\r\n\r\n') ON DUPLICATE KEY UPDATE SEARCHABLE_CONTENT='".mysqli_real_escape_string($link, $iBlockName)."\r\n\r\n'") or die(SendAnswer("Error: ". mysqli_error()));
				
			}
		}
	}

}


function Bitrix_Block_ReSort($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	$result_block_list = $link->query("SELECT id 
										FROM b_iblock 
										WHERE XML_ID IN (". $config_data['b_iblock_list'] .") 
										GROUP BY id") or die(SendAnswer("Error: ". mysqli_error()));

	while ($arr_blocks_info = mysqli_fetch_assoc($result_block_list)) {
		Bitrix_ReSort($arr_blocks_info["id"], 0, 0, 0, "Y");
	}
	
	// product to store
	$result_store_list = $link->query("SELECT id FROM b_catalog_store WHERE ACTIVE='Y'") or die(SendAnswer("Error: ". mysqli_error()));
	while ($store_data = mysqli_fetch_assoc($result_store_list)) {
		$link->query("INSERT INTO b_catalog_store_product (product_id, store_id) 
				SELECT t1.product_id, ". $store_data["id"] ."  
				FROM etrade_product_temp t1 
				LEFT JOIN b_catalog_store_product t2 ON t1.product_id = t2.product_id AND t2.store_id = ". $store_data["id"] ." 
				WHERE t2.store_id IS NULL") or die(SendAnswer("Error: ". mysqli_error()));
	}	

}

// Bitrix - ReSort sections
function Bitrix_ReSort($iblockID, $id = 0, $cnt = 0, $depth = 0, $active = "Y") {

	global $link;
	$iblockID = IntVal($iblockID);

	if ($id > 0)
		$link->query(
			"UPDATE b_iblock_section SET ".
			"	TIMESTAMP_X = TIMESTAMP_X, ".
			"	RIGHT_MARGIN = ".IntVal($cnt).", ".
			"	LEFT_MARGIN = ".IntVal($cnt)." ".
			"WHERE ID=".IntVal($id)) or die(SendAnswer("Error: ". mysqli_error()));

	$strSql =
		"SELECT BS.ID, BS.ACTIVE ".
		"FROM b_iblock_section BS ".
		"WHERE BS.IBLOCK_ID = ".$iblockID." ".
		"	AND ".(($id > 0) ? "BS.IBLOCK_SECTION_ID = ".IntVal($id) : "BS.IBLOCK_SECTION_ID IS NULL")." ".
		"ORDER BY BS.SORT, BS.NAME ";

	$cnt++;
	$res = $link->query($strSql) or die(SendAnswer("Error: ". mysqli_error()));
	while ($arr = mysqli_fetch_assoc($res))
		$cnt = Bitrix_ReSort($iblockID, $arr["ID"], $cnt, $depth + 1, (($active=="Y" && $arr["ACTIVE"]=="Y") ? "Y" : "N"));

	if ($id == 0)
		return true;

	$link->query(
		"UPDATE b_iblock_section SET ".
		"	TIMESTAMP_X = TIMESTAMP_X, ".
		"	RIGHT_MARGIN = ".IntVal($cnt).", ".
		"	DEPTH_LEVEL = ".IntVal($depth).", ".
		"	GLOBAL_ACTIVE = '".$active."' ".
		"WHERE ID=".IntVal($id)) or die(SendAnswer("Error: ". mysqli_error()));
	return $cnt + 1;
}


// // прописываем размеры фоток в БД сайта
function Bitrix_SetImageSize($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
	//$config_data['b_iblock_list'];
	//$config_data['user_id'];
	//$config_data['site_url'];
	
	$sql_result = $link->query("SELECT ID, CONCAT(TRIM(SUBDIR),'/',TRIM(FILE_NAME)) as image_path, WIDTH, HEIGHT, FILE_SIZE 
									FROM b_file 
									WHERE WIDTH=0 OR HEIGHT=0 OR (WIDTH=500 AND HEIGHT=500)") or die(SendAnswer("Error: ". mysqli_error()));
	
	update_pics_size($sql_result, "../upload/", 'b_file', 'image_path', 'WIDTH', 'HEIGHT', 'ID', 'FILE_SIZE');
}



	
// AmiroCMS - Meta tags (обновление мета тегов)
function AmiroCMS_meta_tags($DB_TablePrefix) {
	$activate_update_meta_tags=0; // 0 - выключено, 1 - включено
	
	if ($activate_update_meta_tags==0) exit;
	
	global $link;
	$sql_result = $link->query("SELECT tov_id, head_title, head_desc, head_keywords FROM etrade_product_temp WHERE head_title<>'' or head_desc<>'' or head_keywords<>''") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result)) {
		unset($meta);
		unset($meta_data);
		
		$meta['title'] = $sql_row['head_title'];
		$meta['keywords'] = $sql_row['head_keywords'];
		$meta['description'] = $sql_row['head_desc'];
		$meta['is_kw_manual'] = '0';
		$meta['filled'] = '1';

		$meta_data=serialize($meta); 
	
		$link->query("UPDATE ".$DB_TablePrefix."es_items SET sm_data='".mysqli_real_escape_string($link, $meta_data)."' WHERE id=".$sql_row['tov_id']) or die(SendAnswer("Error: ". mysqli_error()));
		
	}
}



// WA ShopScript 5/6
function WA_ShopScript5_update_tree_start() {
	global $link;
	
	$query_check_field = $link->query("show columns FROM shop_category where `Field` = 'sort_order'") or die(SendAnswer("Error: ". mysqli_error()));
	
	if (mysqli_num_rows($query_check_field)==0) {
		$link->query("ALTER TABLE `shop_category` ADD `sort_order` INT NOT NULL, ADD INDEX (`sort_order`)") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	WA_ShopScript5_update_tree(0);
}

function WA_ShopScript5_update_tree($id = 0, $cnt = 0, $depth = 0, $status = "1") {
	global $link;
	
	
	if ($id > 0) {
		$link->query(
			"UPDATE `shop_category` SET ".
			"	right_key = ".IntVal($cnt).", ".
			"	left_key = ".IntVal($cnt)." ".
			"WHERE id=".IntVal($id)) or die(SendAnswer("Error: ". mysqli_error()));
	}

	$cnt++;
	
	$query = $link->query(
		"SELECT c.id, c.status ".
			"FROM `shop_category` c ".
			"WHERE ".(($id > 0) ? "c.parent_id = ".IntVal($id) : "(c.parent_id IS NULL OR c.parent_id=0)")." ".
			"ORDER BY c.sort_order") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($result = mysqli_fetch_array($query)) {
		$cnt = WA_ShopScript5_update_tree($result["id"], $cnt, $depth + 1, (($status=="1" && $result["status"]=="1") ? "1" : "0"));
	}
	

	if ($id == 0) return true;

	$link->query(
		"UPDATE `shop_category` SET ".
		"	right_key = ".IntVal($cnt).", ".
		"	depth = ".IntVal($depth).", ".
		"	status = '".$status."' ".
		"WHERE id=".IntVal($id)) or die(SendAnswer("Error: ". mysqli_error()));
		
	return $cnt + 1;
}


function ShopScriptWA_import_pics($DB_TablePrefix, $TableSource) {

	global $link;

	//  проверка индексов в таблицах
	$index_query=$link->query("SHOW INDEX FROM ".$DB_TablePrefix."SC_product_pictures WHERE key_name = 'priority'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$DB_TablePrefix."SC_product_pictures ADD INDEX (priority)") or die(SendAnswer("Error: ". mysqli_error()));
	}

	//$sql_result = $link->query("SELECT tov_id, pic_small, pic_medium, pic_big, pic_order, picID, tov_name, tov_guid FROM etrade_cc_pics_flat") or die(SendAnswer("Error: ". mysqli_error()));

	// удаляем старые фотографии
	$link->query("DELETE ".$DB_TablePrefix."SC_product_pictures FROM etrade_cc_pics_flat JOIN ".$DB_TablePrefix."SC_product_pictures ON ".$DB_TablePrefix."SC_product_pictures.productID = etrade_cc_pics_flat.tov_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	// добавляем новые 
	$link->query("INSERT INTO ".$DB_TablePrefix."SC_product_pictures (productID, filename, thumbnail, enlarged, priority) SELECT tov_id, pic_medium, pic_small, pic_big, (pic_order-1) as priority FROM etrade_cc_pics_flat") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE etrade_cc_pics_flat, SC_product_pictures SET etrade_cc_pics_flat.picID=SC_product_pictures.photoID WHERE etrade_cc_pics_flat.tov_id=SC_product_pictures.productID AND SC_product_pictures.priority=0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE SC_products, etrade_cc_pics_flat SET SC_products.default_picture=etrade_cc_pics_flat.picID WHERE SC_products.productID=etrade_cc_pics_flat.tov_id AND etrade_cc_pics_flat.pic_order=1") or die(SendAnswer("Error: ". mysqli_error()));
}





// E-Trade Shop - delete cache
function etrade_shop_delete_cache($op_type) {
	
	remove_files('../system/cache/', $delete_root = false, $pattern = '');
	
	if ($op_type==3) { // filters from CC
		remove_files('../system/cache_mfp/', $delete_root = false, $pattern = '');
	}
}

// E-Trade Shop - Function to repair any erroneous categories that are not in the category path table.
function etrade_shop_CreateCategoriesPath ($DB_TablePrefix, $parent_id = 0) {
	global $link;	
	
	$link->query("CREATE TABLE IF NOT EXISTS `" . $DB_TablePrefix . "category_path` (
	  `category_id` int(11) NOT NULL,
	  `path_id` int(11) NOT NULL,
	  `level` int(11) NOT NULL,
	  PRIMARY KEY (`category_id`,`path_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));

	if ((int)$parent_id==0) {
		$field_exist_query=$link->query("SHOW COLUMNS FROM " . $DB_TablePrefix . "category WHERE `Field`='top'") or die(SendAnswer("Error: ". mysqli_error()));
				
		if (mysqli_num_rows($field_exist_query)==0) {
			$link->query("ALTER TABLE " . $DB_TablePrefix . "category ADD `top` tinyint(1)") or die(SendAnswer("Error: ". mysqli_error()));	
		}
		
		$sql_result = $link->query("UPDATE " . $DB_TablePrefix . "category SET top=1 WHERE parent_id=0 AND category_id IN (SELECT category_id FROM etrade_category_temp WHERE row_exist=0 GROUP BY category_id)") or die(SendAnswer("Error: ". mysqli_error()));
	}

	$sql_result = $link->query("SELECT * FROM " . $DB_TablePrefix . "category WHERE `parent_id` = '" . (int)$parent_id . "'") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($category = mysqli_fetch_array($sql_result)) {
		// Delete the path below the current one
		$link->query("DELETE FROM " . $DB_TablePrefix . "category_path WHERE `category_id` = '" . (int)$category['category_id'] . "'") or die(SendAnswer("Error: ". mysqli_error()));
		
		// Fix for records with no paths
		$level = 0;
		
		$sql_result2 = $link->query("SELECT * FROM " . $DB_TablePrefix . "category_path WHERE `category_id` = '" . (int)$parent_id . "' ORDER BY `level` ASC") or die(SendAnswer("Error: ". mysqli_error()));
		
		while ($result = mysqli_fetch_array($sql_result2)) {
			$link->query("INSERT INTO " . $DB_TablePrefix . "category_path SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'") or die(SendAnswer("Error: ". mysqli_error()));
			
			$level++;
		}
		
		$link->query("REPLACE INTO " . $DB_TablePrefix . "category_path SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', `level` = '" . (int)$level . "'") or die(SendAnswer("Error: ". mysqli_error()));
					
		etrade_shop_CreateCategoriesPath($DB_TablePrefix, $category['category_id']);
	}
}

// E-Trade Shop - update products filters
function etrade_shop_update_products_filters($DB_TablePrefix) {
	global $link;
	
	$mega_filter_attribs = Array();
	
	// список категорий
	$sql_result = $link->query("SELECT field_value2 as cat_parent_id 
		FROM etrade_cc_filters 
		WHERE row_type='f' AND field_value7='1' 
		GROUP BY cat_parent_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($cat = mysqli_fetch_array($sql_result)) {
		
		// список характеристик по категории
		$features_filters = $link->query("SELECT row_id, 
			field_value1 as feature_id, 
			field_value2 as cat_parent_id, 
			field_value3 as feature_name, 
			field_value4 as feature_order, 
			field_value5 as feature_filter_type, 
			field_value6 as feature_folded, 
			field_value7 as feature_filter, 
			field_value8 as feature_desc, 
			field_value9 as feature_block_id, 
			field_value10 as feature_pic, 
			field_value11 as feature_id_ext 
		FROM etrade_cc_filters 
		WHERE row_type='f' AND field_value7='1' AND field_value2='".$cat['cat_parent_id']."'") or die(SendAnswer("Error: ". mysqli_error()));		
		
		$mega_filter_attribs_tmp = Array();	
		
		while ($feature_filter = mysqli_fetch_array($features_filters)) {
			$mega_filter_attribs_tmp[$feature_filter['feature_id']] = Array
						(
							'enabled' => '1',
							'type' => $feature_filter['feature_filter_type'], 
							'display_live_filter' => '', 
							'collapsed' => $feature_filter['feature_folded'],
							'display_list_of_items' => '', 
							'sort_order_values' => 'string_asc', 
							'sort_order' => $feature_filter['feature_order']
						);
		}
		
		$mega_filter_attribs[$cat['cat_parent_id']] = 
			array(
				'sort_order' => '', 
				'items' => $mega_filter_attribs_tmp
				);
		
		unset($mega_filter_attribs_tmp);
		
		//print_r($mega_filter_attribs);
		
	}
	
	$mega_filter_module = Array();
	
	$mega_filter_module[1]['attribs'] = $mega_filter_attribs;
	$mega_filter_module[1]['options'] = Array();
	$mega_filter_module[1]['base_attribs'] = Array(
												'price' => Array(
															'enabled' => '1',
															'sort_order' => '-1',
															'collapsed' => '0'
															), 
												'search' => Array(
															'enabled' => '0',
															'sort_order' => '-1',
															'collapsed' => '',
															'refresh_delay' => '1000',
															'button' => '0'
															), 
												'manufacturers' => Array(
															'enabled' => '1',
															'sort_order' => '-1',
															'display_list_of_items' => '',
															'display_as_type' => 'checkbox',
															'collapsed' => '0',
															'display_live_filter' => '0'
															), 
												'stock_status' => Array(
															'enabled' => '1',
															'sort_order' => '-1',
															'display_list_of_items' => '',
															'display_as_type' => 'checkbox',
															'collapsed' => '0'
															), 
												'rating' => Array(
															'enabled' => '0',
															'sort_order' => '-1',
															'collapsed' => '0'
															)
												);
												
		$mega_filter_module[1]['filters'] = Array('based_on_category' => '0');
		$mega_filter_module[1]['name'] = 'Filters from E-Trade Content Creator';
		$mega_filter_module[1]['title'] =  Array('1' => 'Filters from E-Trade Content Creator');
		$mega_filter_module[1]['layout_id'] =  Array('0' => '3');
		$mega_filter_module[1]['store_id'] =  Array('0' => '0');
		$mega_filter_module[1]['position'] = 'column_left';
		$mega_filter_module[1]['display_options_as'] = 'inline_horizontal';
		$mega_filter_module[1]['status'] = '1';
		$mega_filter_module[1]['sort_order'] = '';

	

	$link->query('UPDATE '.$DB_TablePrefix.'setting 
		SET serialized="1", value="'.mysqli_real_escape_string($link, serialize($mega_filter_module)).'" 
		WHERE store_id=0 AND `group`="mega_filter_module" AND `key`="mega_filter_module"') or die(SendAnswer("Error: ". mysqli_error()));
	
}



// delete cache opencart
function opencart_delete_cache() {
	remove_files('../system/cache/', $delete_root = false, $pattern = '');
}
function opencart_delete_cache_v2() {
	remove_files('../system/storage/cache/', $delete_root = false, $pattern = '');
}

// Function to repair any erroneous categories that are not in the category path table.
function opencart_CreateCategoriesPath ($DB_TablePrefix, $parent_id = 0) {
	global $link;
	
	$link->query("CREATE TABLE IF NOT EXISTS `" . $DB_TablePrefix . "category_path` (
	  `category_id` int(11) NOT NULL,
	  `path_id` int(11) NOT NULL,
	  `level` int(11) NOT NULL,
	  PRIMARY KEY (`category_id`,`path_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));

	if ((int)$parent_id==0) {
		$field_exist_query=$link->query("SHOW COLUMNS FROM " . $DB_TablePrefix . "category WHERE `Field`='top'") or die(SendAnswer("Error: ". mysqli_error()));
				
		if (mysqli_num_rows($field_exist_query)==0) {
			$link->query("ALTER TABLE " . $DB_TablePrefix . "category ADD `top` tinyint(1)") or die(SendAnswer("Error: ". mysqli_error()));	
		}
		
		$sql_result = $link->query("UPDATE " . $DB_TablePrefix . "category SET top=1 WHERE parent_id=0 AND category_id IN (SELECT category_id FROM etrade_category_temp WHERE row_exist=0 GROUP BY category_id)") or die(SendAnswer("Error: ". mysqli_error()));
	}

	$sql_result = $link->query("SELECT * FROM " . $DB_TablePrefix . "category WHERE `parent_id` = '" . (int)$parent_id . "'") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($category = mysqli_fetch_array($sql_result)) {
		// Delete the path below the current one
		$link->query("DELETE FROM " . $DB_TablePrefix . "category_path WHERE `category_id` = '" . (int)$category['category_id'] . "'") or die(SendAnswer("Error: ". mysqli_error()));
		
		// Fix for records with no paths
		$level = 0;
		
		$sql_result2 = $link->query("SELECT * FROM " . $DB_TablePrefix . "category_path WHERE `category_id` = '" . (int)$parent_id . "' ORDER BY `level` ASC") or die(SendAnswer("Error: ". mysqli_error()));
		
		while ($result = mysqli_fetch_array($sql_result2)) {
			$link->query("INSERT INTO " . $DB_TablePrefix . "category_path SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'") or die(SendAnswer("Error: ". mysqli_error()));
			
			$level++;
		}
		
		$link->query("REPLACE INTO " . $DB_TablePrefix . "category_path SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', `level` = '" . (int)$level . "'") or die(SendAnswer("Error: ". mysqli_error()));
					
		opencart_CreateCategoriesPath($DB_TablePrefix, $category['category_id']);
	}
}

function ShopScript5WA_import_pics($DB_TablePrefix, $llUSE_UUID_FOR_UNIQUE) {

	global $link;

	$UploadDirTemp="../wa-data/my_products_img/";
	$delete_temp_file=0;
	
	if (is_dir($UploadDirTemp)==false) die(SendAnswer('Error: Для копирования фотограий, создайте временную папку - '.$UploadDirTemp.', перепишите файлы выгруженные из прогаммы E-Trade Content Creator в эту папку.'));
	
	if ($llUSE_UUID_FOR_UNIQUE==1) {
		$link->query("UPDATE etrade_cc_pics, ".$DB_TablePrefix."product 
				SET etrade_cc_pics.tov_id=".$DB_TablePrefix."product.id 
				WHERE etrade_cc_pics.tov_guid=".$DB_TablePrefix."product.id_1c") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	$index_query=$link->query("SHOW INDEX FROM ".$DB_TablePrefix."product_images WHERE key_name = 'original_filename'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$DB_TablePrefix."product_images ADD INDEX (original_filename)") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	$link->query("ALTER TABLE `etrade_cc_pics` ADD `img_id` INT NOT NULL, ADD INDEX ( `img_id` )") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("UPDATE etrade_cc_pics, ".$DB_TablePrefix."product_images 
				SET etrade_cc_pics.img_id=".$DB_TablePrefix."product_images.id 
				WHERE etrade_cc_pics.tov_id=".$DB_TablePrefix."product_images.product_id AND 
					  etrade_cc_pics.pic_name=".$DB_TablePrefix."product_images.original_filename") or die(SendAnswer("Error: ". mysqli_error()));

	$link->query("INSERT INTO ".$DB_TablePrefix."product_images (product_id, original_filename, sort, upload_datetime, description) 
		SELECT tov_id, pic_name, (pic_order-1) as porder, now(), tov_name as upload_datetime 
		FROM etrade_cc_pics WHERE img_id=0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE ".$DB_TablePrefix."product_images SET ext='jpg' WHERE (ext='' OR ext IS NULL) AND LOCATE('.jpg', LOWER(original_filename))>0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE ".$DB_TablePrefix."product_images SET ext='png' WHERE (ext='' OR ext IS NULL) AND LOCATE('.png', LOWER(original_filename))>0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE ".$DB_TablePrefix."product_images SET ext='gif' WHERE (ext='' OR ext IS NULL) AND LOCATE('.gif', LOWER(original_filename))>0") or die(SendAnswer("Error: ". mysqli_error()));
	
	// update size info for pics
	$sql_result = $link->query("SELECT id, original_filename, height, width FROM ".$DB_TablePrefix."product_images WHERE height=0 OR width=0") or die(SendAnswer("Error: ". mysqli_error()));
	update_pics_size($sql_result, $UploadDirTemp, $DB_TablePrefix.'product_images', 'original_filename', 'height', 'width', 'id', 'size');
	
	$link->query("UPDATE ".$DB_TablePrefix."product, ".$DB_TablePrefix."product_images SET ".$DB_TablePrefix."product.image_id=".$DB_TablePrefix."product_images.id, ".$DB_TablePrefix."product.ext=".$DB_TablePrefix."product_images.ext WHERE ".$DB_TablePrefix."product.id=".$DB_TablePrefix."product_images.product_id AND ".$DB_TablePrefix."product_images.sort=0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE etrade_cc_pics, ".$DB_TablePrefix."product_images 
					SET etrade_cc_pics.img_id=".$DB_TablePrefix."product_images.id 
					WHERE etrade_cc_pics.tov_id=".$DB_TablePrefix."product_images.product_id AND 
						  etrade_cc_pics.pic_name=".$DB_TablePrefix."product_images.original_filename") or die(SendAnswer("Error: ". mysqli_error()));
		  
	$sql_result = $link->query("SELECT img_id, tov_id, pic_name FROM etrade_cc_pics WHERE img_id>0") or die(SendAnswer("Error: ". mysqli_error()));
	
	// create dirs & files
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		
		$product_id=$sql_row['tov_id'];
		$img_id=$sql_row['img_id'];
		$pic_name=$sql_row['pic_name'];
			
		$path = str_pad($product_id, 4, '0', STR_PAD_LEFT);
		$path = 'products/'.substr($path, -2).'/'.substr($path, -4, 2).'/'.$product_id.'/images/';
		
		$path = preg_replace('!\.\.[/\\\]!','', $path);
		$DestinationDir = '../wa-data/protected/shop'.($path ? '/'.$path : '');
		
		wa_ss5_create_dir($DestinationDir);
		
		$get_file_extension_pic_name=get_file_extension($pic_name);
		
		if (!file_exists($DestinationDir.$img_id.'.'.$get_file_extension_pic_name)) {
			if (file_exists($UploadDirTemp.$pic_name)) {
				copy($UploadDirTemp.$pic_name, $DestinationDir.$img_id.'.'.$get_file_extension_pic_name);
				if ($delete_temp_file==1) unlink($UploadDirTemp.$pic_name);
			}
		}
	}
}

function wa_ss5_create_dir($path) {
	if (file_exists($path)) {
		return $path;
	}
	
	$result = $path;
	$basename_path=basename($path);
	$dirname_path=dirname($path);
	
	if (substr($path, -1) !== '/' && strpos($basename_path, ".") !== false) {
		$path = $dirname_path;
	}
	if ($path && !file_exists($path)) {
		$status = @mkdir($path, 0775, true);
		$file1=wa_ss5_create_dir($dirname_path);
		
		if (!file_exists($path) && file_exists($file1)) {
			$status = @mkdir($path, 0775, true);
		}
		if (!$status) {
			$result = false;
		}
	}
	return $result;
}


function update_pics_size($sql_result, $images_path, $images_table, $images_field_name_file, $images_field_name_x, $images_field_name_y, $images_field_id, $image_filesize_field_name) {
	global $link;
	
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		if (is_file($images_path.$sql_row[$images_field_name_file])) {
			$image_size = getimagesize($images_path.$sql_row[$images_field_name_file]);
			$image_filesize=filesize($images_path.$sql_row[$images_field_name_file]);
			if ($image_size[0]>0 && $image_size[1]>0) {
				$link->query("UPDATE `".$images_table."` SET `".$images_field_name_x."`=".$image_size[0].", `".$images_field_name_y."`=".$image_size[1]." WHERE `".$images_field_id."`=".$sql_row[$images_field_id]) or die(SendAnswer("Error: ". mysqli_error()));
			}
			if ($image_filesize_field_name<>'' && $image_filesize>0) {
				$link->query("UPDATE `".$images_table."` SET `".$image_filesize_field_name."`=".$image_filesize." WHERE `".$images_field_id."`=".$sql_row[$images_field_id]) or die(SendAnswer("Error: ". mysqli_error()));
			}
		}
	}
}

function download_image_from_cloud($data) {
	
	if ($stop_work==1) {
		file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
		return;
	}
	
	require_once('./rollingcurlx.php');
	
	global $link;
	$data = unserialize(base64_decode($data));
	//$base_path = str_replace('\\', '/', dirname(dirname(__FILE__)));
	
	// multi get init
	$RCX = new RollingCurlX($data['max_threads']);
	$RCX->setTimeout(10000); //in milliseconds

	$RCX_post_data = NULL; // ['user' => 'bob', 'token' => 'dQw4w9WgXcQ']; //set to NULL if not using POST
	if (!empty($data['login']) && !empty($data['psw'])) {
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			$RCX_options = array(CURLOPT_FOLLOWLOCATION => true, CURLOPT_USERPWD => $data['login'].":".$data['psw'], CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		} else {
			$RCX_options = array(CURLOPT_USERPWD => $data['login'].":".$data['psw'], CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		}
	} else {
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			$RCX_options = array(CURLOPT_FOLLOWLOCATION => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		} else {
			$RCX_options = array(CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		}
	}
	
	$RCX_headers = array();

	// формируем задания для скачивания файлов
	$time_start = microtime(1);
	$time_for_work = 20; // Количество секунд для работы. При превышении этого времени скрипт останавливает свою работу.
	
	$sql_result = $link->query("SELECT row_type, item_id, image, image_cloud_url, image_original, sort_order, uuid, item_uuid, downloaded, image_site_path, image_path, image_file_name 
								FROM etrade_image_temp ei 
								WHERE downloaded = 0 AND 
									  flag_from_site = 0 AND 
									  item_id>0 AND 
									  row_type = '". $data['row_type'] ."' AND 
									  (image_cloud_url<>'' OR image_original<>'')") or die(SendAnswer("Error: ". mysqli_error()));
	
	$total_rows = mysqli_num_rows($sql_result);							
	
	$next_row_id=0;
	if (file_exists($base_path.'temp/next_row.txt')) {
		$next_row_id=(int)file_get_contents($base_path.'temp/next_row.txt');
		$next_row_id++;
	}
	$current_row = $next_row_id;
	$file_row = 0;
	
	while ($row_data = mysqli_fetch_array($sql_result)) {
		$current_row++;

		// папка для хранения фото
		$dir_image_path = '../'.$data['image_dir'];
		
		if (!empty($row_data['image_path_original']) && $row_data['image_path_original'][0] != '/') '/'.$row_data['image_path_original'][0];
		if (!empty($row_data['image_site_path']) && $row_data['image_site_path'][0] != '/') '/'.$row_data['image_site_path'][0];
		
		// наличие файла загруженного через E-Trade Jumper
		if ($data['if_not_exist']==1 && is_file($dir_image_path . '/' . $row_data['image'])) { 
			continue;
		}
		// наличие файла загруженного ранее через админ часть CMS
		if ($data['if_not_exist']==1 && !empty($row_data['image_site_path']) && is_file('..'.$row_data['image_site_path'])) { 
			continue;
		}
/* 		if ($data['if_not_exist']==1 && !empty($row_data['image_path_original']) && is_file('..'.$row_data['image_path_original'].$row_data['image_file_name_original'])) {
			continue;
		} */		
		
		$image_url = trim($row_data['image_cloud_url']);
		if (empty($image_url) && !empty($row_data['image_original']) && stripos($row_data['image_original'], 'http') !== false) {
			$image_url = trim($row_data['image_original']);
		}
		if (empty($image_url)) continue;

		// multi get images
		$RCX_user_data = array('row_data' => $row_data, 'total_rows' => $total_rows, 'config_data' => $data); 
		$RCX->addRequest(trim($row_data['image_cloud_url']), $RCX_post_data, 'callback_RollingCurlX_save_product_image', $RCX_user_data, $RCX_options, $RCX_headers);

		if ($current_row % $data['max_threads'] == false) { // каждые 30 файлов запускаем многопоточное задание
			$RCX->execute();
			
			$time_worked = round(microtime(1) - $time_start, 2);	
			if ($time_for_work>0 && $time_worked>$time_for_work) {
				file_put_contents('./temp/next_row.txt', $current_row);
				SendAnswer("stopped at row: ".$current_row);
				exit;
			}
		}
	}
	
	$RCX->execute();
	
	if (file_exists('./temp/next_row.txt')) @unlink('./temp/next_row.txt');
	
	file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
}

function callback_RollingCurlX_save_product_image($response, $url, $request_info, $user_data, $time) {
	global $link;
	
	if (empty($response)) return;
	
	// папка для хранения фото
	//$base_path = str_replace('\\', '/', dirname(dirname(__FILE__)));
	
	$dir_image_path = '../'.$user_data['config_data']['image_dir'];
	$dir_image_path_sub = '';
	if ($user_data['config_data']['create_sub_dir']==1) {
		$dir_image_path_sub = pathinfo($user_data['row_data']['image'], PATHINFO_DIRNAME).'/';	
	}
	// создаём папку для хранения фото
	if (is_dir($dir_image_path.$dir_image_path_sub)==false) {
		$old_umask = umask(0);
		mkdir($dir_image_path.$dir_image_path_sub, 0777, true);
		umask($old_umask);
	}

	// сохраняем файл
	if ($user_data['config_data']['create_sub_dir']==1) {
		$file_name_image = $user_data['row_data']['image'];
	} else { // без поддиректорий, 1 папка со всеми файлами
		$file_name_image = $user_data['row_data']['image_file_name'];
	}
	file_put_contents($dir_image_path . '/' . $file_name_image, $response);

	$link->query("UPDATE etrade_image_temp 
					SET downloaded = 1, 
						saved_path = '". mysqli_real_escape_string($link, $dir_image_path) ."', 
						saved_file_name = '". mysqli_real_escape_string($link, $file_name_image) ."' 
					WHERE uuid = '". $user_data['row_data']['uuid'] ."'");
	
	// resize
	if (isset($user_data['config_data']['resize_config']['resize_data'])) {
		foreach ($user_data['config_data']['resize_config']['resize_data'] as $resize_data_row) {
			if ($resize_data_row['image_width']>0) {
				$file_array = pathinfo($user_data['row_data']['image_file_name']);
				$image_thumb_dir = $user_data['config_data']['image_thumb_dir'];
				if (empty($image_thumb_dir)) {
					$image_thumb_dir = str_ireplace($user_data['row_data']['image_file_name'], '', $user_data['row_data']['image_site_path']);
				}
				if (!empty($resize_data_row['ftp_path'])) {
					$image_thumb_dir = $image_thumb_dir . $resize_data_row['ftp_path'] . '/';
				}
				
				$file_destination = '..'. $image_thumb_dir .$resize_data_row['file_prefix'] . $file_array['filename'] . $resize_data_row['file_infinix'] . '.' . $file_array['extension'];
				
				// создаём папку для хранения фото
				$dir_image_path_thumb = pathinfo($file_destination, PATHINFO_DIRNAME).'/';	
				if (is_dir($dir_image_path_thumb)==false) {
					$old_umask = umask(0);
					mkdir($dir_image_path_thumb, 0777, true);
					umask($old_umask);
				}
	
				imageResize($dir_image_path . '/' . $file_name_image, 
							$file_destination, 
							$resize_data_row['image_width'], 
							$resize_data_row['image_height'], 
							$fileType = 'jpg');
			}
		}
	}
	
	// проверяем, это формат картинки или нет
/* 	if (etrade_check_is_image($dir_image_path . '/' . $file_new_name)==false) {
		unlink($dir_image_path . '/' . $file_new_name);
	} */
}

function etrade_check_is_image($image_file) {
	if (!is_file($image_file)) return false;
		
	$whitelist_type = array('image/jpeg', 'image/png','image/gif','image/webp');
	$is_good_type = true;

	if (function_exists('finfo_open')) { //(PHP >= 5.3.0, PECL fileinfo >= 0.1.0)
		$fileinfo = finfo_open(FILEINFO_MIME_TYPE);

		if (!in_array(finfo_file($fileinfo, $image_file), $whitelist_type)) {
			$is_good_type = false;
		}
	} else if (function_exists('exif_imagetype')) {  
		if (exif_imagetype($image_file) != (IMAGETYPE_JPEG || IMAGETYPE_GIF || IMAGETYPE_PNG)) {
			$is_good_type = false;
		}
	} else if (function_exists('mime_content_type')) {  //supported (PHP 4 >= 4.3.0, PHP 5)
		if (!in_array(mime_content_type($image_file), $whitelist_type)) {
			$is_good_type = false;
		}
	} else {
		if (!@getimagesize($image_file)) {  //@ - for hide warning when image not valid
			$is_good_type = false;
		}
	}
	
	return $is_good_type;
}


// фото из описания
function download_image_from_cloud_from_desc($data) {
	
	if ($stop_work==1) {
		file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
		return;
	}
	
	require_once('./rollingcurlx.php');
	
	global $link;
	$data = unserialize(base64_decode($data));
	
	if (empty($data['sql_query_select']) or empty($data['sql_query_update']) or empty($data['jumper_file_store_url'])) {
		return;
	}
	
	// multi get init
	$RCX = new RollingCurlX($data['max_threads']);
	$RCX->setTimeout(10000); //in milliseconds
	
	$RCX_post_data = NULL; // ['user' => 'bob', 'token' => 'dQw4w9WgXcQ']; //set to NULL if not using POST
	if (!empty($data['login']) && !empty($data['psw'])) {
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			$RCX_options = array(CURLOPT_FOLLOWLOCATION => true, CURLOPT_USERPWD => $data['login'].":".$data['psw'], CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		} else {
			$RCX_options = array(CURLOPT_USERPWD => $data['login'].":".$data['psw'], CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		}
	} else {
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			$RCX_options = array(CURLOPT_FOLLOWLOCATION => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		} else {
			$RCX_options = array(CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0);
		}
	}
	
	$RCX_headers = array();

	// формируем задания для скачивания файлов
	$time_start = microtime(1);
	$time_for_work = 20; // Количество секунд для работы. При превышении этого времени скрипт останавливает свою работу.
	
	$sql_result = $link->query($data['sql_query_select']) or die(SendAnswer("Error: ". mysqli_error()));
	$total_rows = mysqli_num_rows($sql_result);							
	
	$next_row_id=0;
	if (file_exists($base_path.'temp/next_row.txt')) {
		$next_row_id=(int)file_get_contents($base_path.'temp/next_row.txt');
		$next_row_id++;
	}
	$current_row = $next_row_id;
	$file_row = 0;
	
	while ($row_data = mysqli_fetch_array($sql_result)) {
		$current_row++;
		
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $row_data['field_text'], $match);

		if (!isset($match[0]) or empty($match[0])) continue;
		
		usort($match[0], function($a, $b) {
			return strlen($b) - strlen($a);
		});
		
		foreach ($match[0] as $jumper_storage_url) {
			if (stripos($jumper_storage_url, $data['jumper_file_store_url']) === false) continue;
			
			$jumper_storage_url = str_ireplace(array('&quot', '"'), '', $jumper_storage_url);
			$row_data['image'] = str_ireplace(array($data['jumper_file_store_url']), '', $jumper_storage_url);
			$row_data['jumper_storage_url'] = $jumper_storage_url;
			
/* 			// папка для хранения фото
			$dir_image_path = '../'.$data['image_dir'];
			if ($data['if_not_exist']==1 && is_file($dir_image_path . '/' . $row_data['image'])) { // наличие файла
				continue;
			} */
			
			
			// multi get images
			$RCX_user_data = array('row_data' => $row_data, 'total_rows' => $total_rows, 'config_data' => $data); 
			$RCX->addRequest(trim($jumper_storage_url), $RCX_post_data, 'callback_RollingCurlX_save_jumper_image_from_desc', $RCX_user_data, $RCX_options, $RCX_headers);

			if ($current_row % $data['max_threads'] == false) { // каждые 30 файлов запускаем многопоточное задание
				$RCX->execute();
				
				$time_worked = round(microtime(1) - $time_start, 2);	
				if ($time_for_work>0 && $time_worked>$time_for_work) {
					file_put_contents('./temp/next_row.txt', $current_row);
					SendAnswer("stopped at row: ".$current_row);
					exit;
				}
			}
		}
	}
	
	$RCX->execute();
	
	if (file_exists('./temp/next_row.txt')) @unlink('./temp/next_row.txt');
	
	file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
}

function callback_RollingCurlX_save_jumper_image_from_desc($response, $url, $request_info, $user_data, $time) {
	global $link;
	if (empty($response)) return;

	$dir_image_path = '../'.$user_data['config_data']['image_dir'];
	$dir_image_path_sub = '';
	if ($user_data['config_data']['create_sub_dir']==1) {
		$dir_image_path_sub = pathinfo($user_data['row_data']['image'], PATHINFO_DIRNAME).'/';	
	}
	$dir_image_path_sub = urldecode($dir_image_path_sub);
	
	// создаём папку для хранения
	if (is_dir($dir_image_path.$dir_image_path_sub)==false) {
		$old_umask = umask(0);
		mkdir($dir_image_path.$dir_image_path_sub, 0777, true);
		umask($old_umask);
	}

	// сохраняем файл
	$new_file_name = pathinfo($user_data['row_data']['image'], PATHINFO_BASENAME);
	if ($user_data['config_data']['create_sub_dir']==1) {
		$new_file_name = $user_data['row_data']['image'];
		$new_file_name = urldecode($new_file_name);
	}
	file_put_contents($dir_image_path . '/' . $new_file_name, $response);
		
	if (isset($user_data['config_data']['sql_query_update']) && !empty($user_data['config_data']['sql_query_update'])) {
		$sql_query_update = $user_data['config_data']['sql_query_update'];
		$sql_query_update = str_ireplace('{jumper_storage_url}', $user_data['row_data']['jumper_storage_url'], $sql_query_update);
		$sql_query_update = str_ireplace('{new_url}', $user_data['config_data']['site_url'] . $user_data['config_data']['image_dir']. '/' . $new_file_name, $sql_query_update);
		$sql_query_update = str_ireplace('{block_id}', $user_data['row_data']['block_id'], $sql_query_update);
		$sql_query_update = str_ireplace('{attribute_id}', $user_data['row_data']['attribute_id'], $sql_query_update);
		$sql_query_update = str_ireplace('{item_id}', $user_data['row_data']['item_id'], $sql_query_update);
		$sql_query_update = str_ireplace('{language_id}', $user_data['row_data']['language_id'], $sql_query_update);
                //file_put_contents("./data.sql", print_r($sql_query_update, true));
		$link->query($sql_query_update) or die(SendAnswer("Error: ". mysqli_error()));
		
	}
}

function woocommerce_get_attributes($data) { // получаем значения атрибутов товаров
	
	if ($stop_work==1) {
		file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
		return;
	}
	
	global $link;
	$data = unserialize(base64_decode($data));
	$current_row = 0;

	$link->query("DROP TABLE IF EXISTS etrade_product_attribute_get_temp") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("CREATE TABLE IF NOT EXISTS etrade_product_attribute_get_temp (
					product_id int(11) NOT NULL,
					attribute_id int(11) NOT NULL,
					flag_multiline tinyint(1) NOT NULL,
					attribute_value text NOT NULL,
					attribute_name varchar(80) NOT NULL,
					product_uuid varchar(80) NOT NULL,
					attribute_uuid varchar(80) NOT NULL,
						KEY product_id (product_id),
						KEY attribute_id (attribute_id),
						KEY product_uuid (product_uuid),
						KEY attribute_uuid (attribute_uuid)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));
	
	$sql_result = $link->query("SELECT p.ID, p.uuid, pm.meta_value 
					FROM ". $data['db_prefix'] ."posts p 
					INNER JOIN ". $data['db_prefix'] ."postmeta pm ON pm.post_id = p.ID AND pm.meta_key='_product_attributes' 
					WHERE p.post_type = 'product' 
					GROUP BY p.ID ") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($row_data = mysqli_fetch_array($sql_result)) {
		$current_row++;
		$meta_value = unserialize($row_data['meta_value']);
		
		if (is_array($meta_value)) {
			foreach($meta_value as $attribute_key=>$attribute_data) {
				$attribute_value = '';
				$attribute_uuid = $attribute_data['name'];
				
				if ($attribute_data['is_taxonomy']=='1') { // taxonomy
					$sql_result_taxonomy = $link->query("SELECT tt.*, GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') as attribute_value, (SELECT uuid FROM ". $data['db_prefix'] ."woocommerce_attribute_taxonomies WHERE attribute_name = REPLACE('". $attribute_data['name'] ."', 'pa_', '')) AS attribute_uuid 
												FROM ". $data['db_prefix'] ."term_taxonomy tt 
												INNER JOIN ". $data['db_prefix'] ."terms t ON t.term_id=tt.term_id 
												INNER JOIN ". $data['db_prefix'] ."term_relationships tr ON tr.term_taxonomy_id=tt.term_taxonomy_id 
												WHERE tt.taxonomy = '". $attribute_data['name'] ."' AND 
													  tr.object_id=". $row_data['ID'] ."") or die(SendAnswer("Error: ". mysqli_error()));
												
					if (mysqli_num_rows($sql_result_taxonomy)>0) {
						$row_data_taxonomy = mysqli_fetch_array($sql_result_taxonomy);							
						$attribute_value = $row_data_taxonomy['attribute_value'];
						$attribute_uuid = $row_data_taxonomy['attribute_uuid'];
					}
				}
				
				if (!empty($attribute_uuid) && !empty($attribute_value)) {
					$link->query("INSERT INTO etrade_product_attribute_get_temp (attribute_name, product_id, attribute_id, attribute_value, product_uuid, attribute_uuid, flag_multiline) VALUES
									('". $attribute_data['name'] ."', ". $row_data['ID'] .", '0', '". $attribute_value ."', '". $row_data['uuid'] ."', '". $attribute_uuid ."', ". $attribute_data['is_taxonomy'] .")
									") or die(SendAnswer("Error: ". mysqli_error()));
				}
			}
		}
	}
	
	file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
}

function woocommerce_update_new_product($data) {
	
	if ($stop_work==1) {
		file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
		return;
	}
	
	global $link;
	$data = unserialize(base64_decode($data));
	$current_row = 0;
	$sql_result = $link->query("SELECT * FROM etrade_product_temp WHERE row_exist = 0 AND product_id>0") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($row_data = mysqli_fetch_array($sql_result)) {
		$current_row++;
		
		$link->query("INSERT INTO `". $data['db_prefix'] ."postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
						( ". $row_data['product_id'] .", '_price', '". $row_data['price'] ."'),
						( ". $row_data['product_id'] .", '_regular_price', '". $row_data['price'] ."'),
						( ". $row_data['product_id'] .", '_sale_price', ''),
						( ". $row_data['product_id'] .", '_sale_price_dates_from', ''),
						( ". $row_data['product_id'] .", '_sale_price_dates_to', ''),
						( ". $row_data['product_id'] .", '_stock', ''),
						( ". $row_data['product_id'] .", '_stock_status', '". ($row_data['stock_status_id']==7 ? "instock" : "outofstock") ."'),
						( ". $row_data['product_id'] .", '_sku', '". $row_data['mpn'] ."'),
						( ". $row_data['product_id'] .", '_product_attributes', 'a:0:{}'),
						( ". $row_data['product_id'] .", '_product_image_gallery', ''),
						( ". $row_data['product_id'] .", '_product_version', '3.3.4'),
						( ". $row_data['product_id'] .", '_purchase_note', ''),
						( ". $row_data['product_id'] .", '_backorders', 'no'),
						( ". $row_data['product_id'] .", '_crosssell_ids', 'a:0:{}'),
						( ". $row_data['product_id'] .", '_default_attributes', 'a:0:{}'),
						( ". $row_data['product_id'] .", '_download_expiry', '-1'),
						( ". $row_data['product_id'] .", '_download_limit', '-1'),
						( ". $row_data['product_id'] .", '_downloadable', 'no'),
						( ". $row_data['product_id'] .", '_edit_lock', ''),
						( ". $row_data['product_id'] .", '_height', '". $row_data['height'] ."'),
						( ". $row_data['product_id'] .", '_length', '". $row_data['length'] ."'),
						( ". $row_data['product_id'] .", '_manage_stock', 'no'),
						( ". $row_data['product_id'] .", '_sold_individually', 'no'),
						( ". $row_data['product_id'] .", '_tax_class', ''),
						( ". $row_data['product_id'] .", '_tax_status', 'taxable'),
						( ". $row_data['product_id'] .", '_thumbnail_id', ''),
						( ". $row_data['product_id'] .", '_upsell_ids', 'a:0:{}'),
						( ". $row_data['product_id'] .", '_vc_post_settings', 'a:0:{}'),
						( ". $row_data['product_id'] .", '_virtual', 'no'),
						( ". $row_data['product_id'] .", '_wc_average_rating', ''),
						( ". $row_data['product_id'] .", '_wc_rating_count', 'a:0:{}'),
						( ". $row_data['product_id'] .", '_wc_review_count', '0'),
						( ". $row_data['product_id'] .", '_wcml_custom_prices_status', '0'),
						( ". $row_data['product_id'] .", '_weight', '". $row_data['weight'] ."'),
						( ". $row_data['product_id'] .", '_width', '". $row_data['width'] ."'),
						( ". $row_data['product_id'] .", '_wp_old_slug', ''),
						( ". $row_data['product_id'] .", '_wpas_done_all', '1'),
						( ". $row_data['product_id'] .", '_wpb_vc_js_status', 'false'),
						( ". $row_data['product_id'] .", '_wpml_location_migration_done', '1'),
						( ". $row_data['product_id'] .", '_wpml_media_duplicate', '0'),
						( ". $row_data['product_id'] .", '_wpml_media_featured', '0'),
						( ". $row_data['product_id'] .", 'slide_template', 'default'),
						( ". $row_data['product_id'] .", 'total_sales', '0')") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	file_put_contents('./temp/tunnel_work_status.txt', 'Complete!');
}

function etrade_category_path_update($config_data) {
	global $link;
	
	$config_data = unserialize(base64_decode($config_data));
//delim_char

	etrade_category_path_update_run($config_data, $category_id = 0, $parent_id = 0, $cnt = 0, $depth = 0);
	
	$link->query("UPDATE etrade_category_temp SET seo_url_full = ''") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("UPDATE etrade_category_temp etrade_temp 
					INNER JOIN (SELECT t1.name AS lev1, t2.name as lev2, t3.name as lev3, t4.name as lev4, t5.name as lev5, 
									CONCAT(t1.seo_url, 
										IF(t2.seo_url IS NOT NULL, CONCAT('/', t2.seo_url), ''), 
										IF(t3.seo_url IS NOT NULL, CONCAT('/', t3.seo_url), ''), 
										IF(t4.seo_url IS NOT NULL, CONCAT('/', t4.seo_url), ''), 
										IF(t5.seo_url IS NOT NULL, CONCAT('/', t5.seo_url), ''), 
										IF(t6.seo_url IS NOT NULL, CONCAT('/', t6.seo_url), ''), 
										IF(t7.seo_url IS NOT NULL, CONCAT('/', t7.seo_url), ''), 
										IF(t8.seo_url IS NOT NULL, CONCAT('/', t8.seo_url), ''), 
										IF(t9.seo_url IS NOT NULL, CONCAT('/', t9.seo_url), '')
											) as seo_url_full,
										IF(t9.category_id IS NOT NULL, t9.category_id,
										IF(t8.category_id IS NOT NULL, t8.category_id,
										IF(t7.category_id IS NOT NULL, t7.category_id,
										IF(t6.category_id IS NOT NULL, t6.category_id,
										IF(t5.category_id IS NOT NULL, t5.category_id,
										IF(t4.category_id IS NOT NULL, t4.category_id,
										IF(t3.category_id IS NOT NULL, t3.category_id,
										IF(t2.category_id IS NOT NULL, t2.category_id, 
										IF(t1.category_id IS NOT NULL, t1.category_id, 0))))))))) as category_id
								FROM etrade_category_temp AS t1
								LEFT JOIN etrade_category_temp AS t2 ON t2.parent_id = t1.category_id
								LEFT JOIN etrade_category_temp AS t3 ON t3.parent_id = t2.category_id
								LEFT JOIN etrade_category_temp AS t4 ON t4.parent_id = t3.category_id
								LEFT JOIN etrade_category_temp AS t5 ON t5.parent_id = t4.category_id
								LEFT JOIN etrade_category_temp AS t6 ON t6.parent_id = t5.category_id
								LEFT JOIN etrade_category_temp AS t7 ON t7.parent_id = t6.category_id
								LEFT JOIN etrade_category_temp AS t8 ON t8.parent_id = t7.category_id
								LEFT JOIN etrade_category_temp AS t9 ON t9.parent_id = t8.category_id
								WHERE t1.parent_id=0) t0 ON etrade_temp.category_id = t0.category_id 
					SET etrade_temp.seo_url_full = t0.seo_url_full") or die(SendAnswer("Error: ". mysqli_error()));
					
	$link->query("UPDATE etrade_category_temp c 
					LEFT JOIN (SELECT category_id, seo_url FROM etrade_category_temp) t2 ON t2.category_id = c.parent_id
					SET c.seo_url_full = IF(c.parent_id=0, c.seo_url, CONCAT(t2.seo_url, '/', c.seo_url)) 
					WHERE c.seo_url_full=''") or die(SendAnswer("Error: ". mysqli_error()));
				
	$link->query("UPDATE etrade_category_temp 
					SET seo_url_full = IF(parent_id=0, seo_url, CONCAT(parent_id, '/', category_id)) WHERE seo_url_full=''") or die(SendAnswer("Error: ". mysqli_error()));
					
	if (isset($config_data['sql_query']) && !empty($config_data['sql_query'])) {
		$link->query($config_data['sql_query']) or die(SendAnswer("Error: ". mysqli_error()));
	}
}

function etrade_category_path_update_run($config_data, $category_id = 0, $parent_id = 0, $cnt = 0, $depth = 0) {

	global $link;

	if ($category_id > 0)
		$link->query("UPDATE etrade_category_temp 
						SET right_key = ".IntVal($cnt).", 
							left_key = ".IntVal($cnt)." 
						WHERE category_id=".IntVal($category_id)) or die(SendAnswer("Error: ". mysqli_error()));

	$cnt++;
	$res = $link->query("SELECT category_id, parent_id 
							FROM etrade_category_temp 
							WHERE ".(($category_id > 0) ? " parent_id = ".IntVal($category_id) : " parent_id=0")." 
							ORDER BY sort_order, name") or die(SendAnswer("Error: ". mysqli_error()));
	while ($row_data = mysqli_fetch_assoc($res))
		$cnt = etrade_category_path_update_run($config_data, $row_data["category_id"], $row_data["parent_id"], $cnt, $depth + 1);

	if ($category_id == 0)
		return true;

	$link->query("UPDATE etrade_category_temp 
					SET right_key = ".IntVal($cnt).", 
						level = ".IntVal($depth)." 
					WHERE category_id = ".IntVal($category_id)) or die(SendAnswer("Error: ". mysqli_error()));
	return $cnt + 1;
}


// CS-Cart
function cs_cart_update_image($data) {
	global $link;
	$data = unserialize(base64_decode($data));

/* 	//  проверка индексов в таблицах
	$index_query=$link->query("SHOW INDEX FROM ".$data['db_prefix']."images WHERE key_name = 'image_path'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$data['db_prefix']."images ADD INDEX (image_path)") or die(SendAnswer("Error: ". mysqli_error()));
	}

	$index_query=$link->query("SHOW INDEX FROM ".$data['db_prefix']."images_links WHERE key_name = 'detailed_id'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$data['db_prefix']."images_links ADD INDEX (detailed_id)") or die(SendAnswer("Error: ". mysqli_error()));
	} */
	
	// прописываем размеры фоток в БД сайта
	$sql_result = $link->query("SELECT image_id, image_path, image_x, image_y FROM ".$data['db_prefix']."images WHERE image_x=0 OR image_y=0") or die(SendAnswer("Error: ". mysqli_error()));
	update_pics_size($sql_result, "../images/detailed/", $data['db_prefix'].'images', 'image_path', 'image_x', 'image_y', 'image_id', '');
}


function cs_cart4_update_filters($data) {
	global $link;
	$data = unserialize(base64_decode($data));
	
	// type - dynamic
	
	// filters ID
	$sql_result = $link->query("SELECT GROUP_CONCAT( filter_id ) as filters_id_list FROM `".$data['db_prefix']."product_filters`") or die(SendAnswer("Error: ". mysqli_error()));
	$sql_row = mysqli_fetch_array($sql_result);
	$filter_id_list=$sql_row['filters_id_list'];
	
	// update filters ID for makets
	$sql_result = $link->query("SELECT block_id, type, properties, company_id FROM `".$data['db_prefix']."bm_blocks` WHERE `type` = 'product_filters'") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result)) {
		$filters_content=array('items' => array('filling' => 'manually', 'item_ids' => $filter_id_list));
		$filters_content=serialize($filters_content);

		$link->query("UPDATE `".$data['db_prefix']."bm_blocks_content` SET `content`='".$filters_content."' WHERE block_id='".$sql_row['block_id']."'") or die(SendAnswer("Error: ". mysqli_error()));
	}
}

function wa_ss_get_feature_block_parent($data) {
	global $link;
	$data = unserialize(base64_decode($data));
	
	$link->query("DROP TABLE IF EXISTS etrade_type_features_temp") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("CREATE TABLE IF NOT EXISTS etrade_type_features_temp (
					type_id int(11) NOT NULL,
					feature_id int(11) NOT NULL,
					sort_order int(11) NOT NULL,
					feature_type varchar(255) NOT NULL, 
					name varchar(255) NOT NULL,
					uuid varchar(36) NOT NULL, 
					uuid_parent varchar(36) NOT NULL, 
						KEY type_id (type_id),
						KEY feature_id (feature_id),
						KEY sort_order (sort_order),
						KEY feature_type (feature_type),
						KEY uuid (uuid),
						KEY uuid_parent (uuid_parent)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci") or die(SendAnswer("Error: ". mysqli_error()));
					
	$link->query("INSERT INTO etrade_type_features_temp(type_id, feature_id, sort_order, feature_type, uuid, name)
					SELECT type_id, feature_id, sort, f.`type`, f.uuid, f.name
					FROM shop_type_features tf
					LEFT JOIN shop_feature f ON f.id = tf.feature_id 
					ORDER BY type_id, sort") or die(SendAnswer("Error: ". mysqli_error()));

	$row_list = $link->query("SELECT type_id, uuid, sort_order, name FROM etrade_type_features_temp WHERE feature_type = 'divider' GROUP BY type_id, uuid ORDER BY type_id, sort_order") or die(SendAnswer("Error: ". mysqli_error()));
	
	$current_row = 0;
	$total_rows = $row_list->num_rows;
	while ($row_data = mysqli_fetch_array($row_list)) {
		
		$uuid_parent = $row_data['uuid'];
		$sort_order_1 = $row_data['sort_order'];
		$current_type_id = $row_data['type_id'];
		$sort_order_2 = 0;
		
		if ($current_row + 1 <= $total_rows) {
			// row + 1
			$row_list->data_seek($current_row + 1);
			$row_data_temp = $row_list->fetch_assoc();
			
			if ($row_data_temp['type_id']==$current_type_id) {
				$sort_order_2 = $row_data_temp['sort_order'];
			}
			
			// row - 1
			$row_list->data_seek($current_row);
			$row_data = $row_list->fetch_assoc();
		}
		
		$link->query("UPDATE etrade_type_features_temp SET uuid_parent = '{$uuid_parent}' WHERE feature_type != 'divider' AND type_id={$current_type_id} AND sort_order>{$sort_order_1} ". ($sort_order_2>0 ? " AND sort_order<{$sort_order_2}" : "") ) or die(SendAnswer("Error: ". mysqli_error()));
		
		$current_row++;
	}
}

?>