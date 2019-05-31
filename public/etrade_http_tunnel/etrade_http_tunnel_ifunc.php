<?php
/* ##########################
	E-Trade Http Tunnel v2.0.
	HTTP tunnel script.    
	
	Copyright (c) 2011-2015 ElbuzGroup
	http://www.elbuz.com
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
			$res = @unlink($source);
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
		return ($delete_root == true && empty($pattern)) ? @rmdir($source) : true;
	} else {
		return false;
	}
}

// translit
function cyr_to_translit($content) {
	$transA = array('�' => 'a', '�' => 'b', '�' => 'v', '�' => 'h', '�' => 'g', '�' => 'd', '�' => 'e', '�' => 'jo', '�' => 'e', '�' => 'zh', '�' => 'z', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'k', '�' => 'l', '�' => 'm', '�' => 'n', '�' => 'o', '�' => 'p', '�' => 'r', '�' => 's', '�' => 't', '�' => 'u', '�' => 'u', '�' => 'f', '�' => 'h', '�' => 'c', '�' => 'ch', '�' => 'sh', '�' => 'sz', '�' => '', '�' => 'y', '�' => '', '�' => 'e', '�' => 'yu', '�' => 'ya'); 
	$transB = array('�' => 'a', '�' => 'b', '�' => 'v', '�' => 'g', '�' => 'g', '�' => 'd', '�' => 'e', '�' => 'jo', '�' => 'e', '�' => 'zh', '�' => 'z', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'k', '�' => 'l', '�' => 'm', '�' => 'n', '�' => 'o', '�' => 'p', '�' => 'r', '�' => 's', '�' => 't', '�' => 'u', '�' => 'u', '�' => 'f', '�' => 'h', '�' => 'c', '�' => 'ch', '�' => 'sh', '�' => 'sz', '�' => '', '�' => 'y', '�' => '', '�' => 'e', '�' => 'yu', '�' => 'ya', '&quot;' => '', '&amp;' => '', '�' => 'u', '�' => '');
	$content = trim(strip_tags($content)); 
	$content = strtr($content, $transA); 
	$content = strtr($content, $transB); 
	$content = preg_replace("/\s+/ms", "-", $content); 
	$content = preg_replace("/[ ]+/", "-", $content);
	$content = preg_replace("/[^a-z0-9_]+/mi", "", $content);
	$content = stripslashes($content); 
	return $content; 
}

function imageResize($sourceFile, $destFile, $destWidth = NULL, $destHeight = NULL, $fileType = 'jpg')
{
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

function returnDestImage($type, $ressource, $filename)
{
	$flag = false;
	switch ($type)
	{
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


function createSrcImage($type, $filename)
{
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
	
	// ������ �� ��� ������ ��� �������
	$link->query("INSERT INTO ".$DB_TablePrefix."vm_product_product_type_xref (product_id, product_type_id) SELECT field_value1, field_value4 FROM etrade_cc_filters WHERE row_type='ptv' AND etrade_cc_filters.field_value1 NOT IN (SELECT product_id FROM ".$DB_TablePrefix."vm_product_product_type_xref) GROUP BY field_value1, field_value4") or die(SendAnswer("Error: ". mysqli_error()));
	

	// ������ �������
	$sql_result1 = $link->query("SELECT row_type, field_value1, field_value2, field_value3, field_value4 FROM etrade_cc_filters WHERE row_type='pt'") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result1)) {
	
		if ($sql_row['row_type']=='pt') {
			$link->query("INSERT INTO ".$DB_TablePrefix."vm_product_type (product_type_id, product_type_name, product_type_publish) VALUES('".$sql_row['field_value1']."', '".mysqli_real_escape_string($link, $sql_row['field_value2'])."', 'Y')") or die(SendAnswer("Error: ". mysqli_error()));	
			
			// �������� �������
			$link->query("DROP TABLE IF EXISTS ".$DB_TablePrefix."vm_product_type_".$sql_row['field_value1']) or die(SendAnswer("Error: ". mysqli_error()));
			
			$fields_list='';
			$sql_result2 = $link->query("SELECT field_value4, field_value2 FROM `etrade_cc_filters` WHERE `row_type`='ptv' AND field_value4='".$sql_row['field_value1']."' GROUP BY field_value4, field_value2") or die(SendAnswer("Error: ". mysqli_error()));

			while ($sql_row2 = mysqli_fetch_array($sql_result2)) {
				$fields_list.="`".$sql_row2['field_value2']."` TEXT NULL, ";
			}
	
			// �������� ������ ������� ����� ������� �������� �������������
			$result = $link->query("CREATE TABLE ".$DB_TablePrefix."vm_product_type_".$sql_row['field_value1']." (
									`product_id` INT NOT NULL , ".$fields_list." 
									PRIMARY KEY ( `product_id` ) 
									) ENGINE = MYISAM DEFAULT CHARSET = utf8;") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	
	
	// ��������� ������ � ��������
	$sql_result3 = $link->query("SELECT row_type, field_value1, field_value2, field_value3, field_value4 FROM etrade_cc_filters WHERE row_type='ptv'") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($sql_row = mysqli_fetch_array($sql_result3)) {
		if ($sql_row['row_type']=='ptv') {
			$link->query("INSERT INTO ".$DB_TablePrefix."vm_product_type_".$sql_row['field_value4']." (".$sql_row['field_value2'].", product_id) VALUES('".mysqli_real_escape_string($link, $sql_row['field_value3'])."', '".$sql_row['field_value1']."') ON DUPLICATE KEY UPDATE ".$sql_row['field_value2']."='".mysqli_real_escape_string($link, $sql_row['field_value3'])."'") or die(SendAnswer("Error: ". mysqli_error()));
			
			$count_features_values_add++;
		}
	}
}


function hostcms_import_pics($DB_TablePrefix) {
	$delete_temp_file=0; // ������� ��������� ����� 0-��� ��� 1-��
	
	$UploadDirTemp="../upload/my_products_img/";
	if (is_dir($UploadDirTemp)==false) die(SendAnswer('Error: ��� ����������� ���������, �������� ��������� ����� - '.$UploadDirTemp.', ���������� ����� ����������� �� �������� E-Trade Content Creator � ��� �����.'));
		
	if (is_file('../main_classes.php')==false) die(SendAnswer('Error: �� ������ ���� ../main_classes.php'));
	if (is_file('../modules/shop/shop.class.php')==false) die(SendAnswer('Error: �� ������ ���� ../modules/shop/shop.class.php'));
	
	// nesting_level for HostCMS v5
	// $sql_result = $link->query("SELECT site_nesting_level FROM site_table WHERE site_id=1") or die(SendAnswer("Error: ". mysqli_error()));
		
	require_once('../main_classes.php');
	require_once('../modules/shop/shop.class.php');
	$shop = new shop();
	
	global $link;

	$sql_result = $link->query("SELECT tov_id, pic_small, pic_medium, pic_big, pic_order, picID, tov_name, tov_guid FROM etrade_cc_pics_flat") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		// �������� �������� ��� �������� ���������� �������
		$UploadDir = '../'.$shop->GetItemDir($sql_row['tov_id']);
		if (is_dir($UploadDir)==false) mkdir($UploadDir, 0777, true);
		if (is_dir($UploadDir)==false) die(SendAnswer('Error: ������ �������� ���������� ��� �������� ���������� ������� - '.$UploadDir));

		// ����������� ������ �� ��������� ����� � ��������
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


function hostcms6_import_pics($DB_TablePrefix, $shop_id) {
	global $link;
	
	$delete_temp_file=0; // ������� ��������� ����� 0-��� ��� 1-��
	
	$UploadDirTemp="../upload/my_products_img/";
	if (is_dir($UploadDirTemp)==false) die(SendAnswer('Error: ��� ����������� ���������, �������� ��������� ����� - '.$UploadDirTemp.', ���������� ����� ����������� �� �������� E-Trade Content Creator � ��� �����.'));
		
	if ($shop_id==0) die(SendAnswer('Error: �� ������ �� �����!'));
	
	// nesting_level for HostCMS v6
	// $sql_result = $link->query("SELECT nesting_level FROM sites WHERE id=1") or die(SendAnswer("Error: ". mysqli_error()));
	
	$sql_result = $link->query("SELECT tov_id, pic_small, pic_medium, pic_big, pic_order, picID, tov_name, tov_guid FROM etrade_cc_pics_flat") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result)) {
		// �������� �������� ��� �������� ���������� �������
		$UploadDir = '../upload/shop_'.$shop_id.'/'.hostcms_getNestingDirPath($sql_row['tov_id']).'/item_'.$sql_row['tov_id'].'/';
		
		if (is_dir($UploadDir)==false) mkdir($UploadDir, 0777, true);
		if (is_dir($UploadDir)==false) die(SendAnswer('Error: ������ �������� ���������� ��� �������� ���������� ������� - '.$UploadDir));

		// ����������� ������ �� ��������� ����� � ��������
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
	
	// ����������� ������� ����� � �� �����
	$sql_result = $link->query("SELECT id, image_small as image_path, image_small_height as image_x, image_small_width as image_y FROM shop_items WHERE image_small_height=0 OR image_small_width=0") or die(SendAnswer("Error: ". mysqli_error()));
	update_pics_size($sql_result, $UploadDirTemp, 'shop_items', 'image_path', 'image_small_height', 'image_small_width', 'id', '');
	
	$sql_result = $link->query("SELECT id, image_large as image_path, image_large_height as image_x, image_large_width as image_y FROM shop_items WHERE image_large_height=0 OR image_large_width=0") or die(SendAnswer("Error: ". mysqli_error()));
	update_pics_size($sql_result, $UploadDirTemp, 'shop_items', 'image_path', 'image_large_height', 'image_large_width', 'id', '');
}

/**
 * ��������� ���� � ���������� ������������� ������ ����������� �� �������������� ��������.
 * ��������, ��� �������� � ����� 17 � ������� ����������� 3 �������� ������ 0/1/7 ��� ������ �� 3-� ��������� - 0,1,7
 * ��� �������� � ����� 23987 � ������� ����������� 3 ������������ ������ 2/3/9 ��� ������ �� 3-� ��������� - 2,3,9.
 *
 * @param $id ������������� ��������
 * @param $level ������� �����������, �� ��������� 3
 * @param $type ��� ������������� ����������, 0 (�� ���������) - ������, 1 - ������
 * @return mixed ������ ��� ������ �������� �����
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




// CS-UserCart
function cs_cart_import_pics($DB_TablePrefix, $TableSource, $pic1_pic2_identical_pics) {
	global $link;
	$always_copy_photo=0;

	//  �������� �������� � ��������
	$index_query=$link->query("SHOW INDEX FROM ".$DB_TablePrefix."images WHERE key_name = 'image_path'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$DB_TablePrefix."images ADD INDEX (image_path)") or die(SendAnswer("Error: ". mysqli_error()));
	}

	$index_query=$link->query("SHOW INDEX FROM ".$DB_TablePrefix."images_links WHERE key_name = 'detailed_id'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$DB_TablePrefix."images_links ADD INDEX (detailed_id)") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	// ������ ���������� (�����) TEMPORARY
	$link->query("DROP TEMPORARY TABLE IF EXISTS pics_list_temp") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("CREATE TEMPORARY TABLE pics_list_temp (product_id int(11) NOT NULL, pic_file_name varchar(120) NOT NULL, pic_type varchar(24) NOT NULL, new_image_id int(11) NOT NULL, image_exist tinyint(1) NOT NULL, image_id_exist tinyint(1) NOT NULL, KEY product_id (product_id),  KEY pic_file_name (pic_file_name), KEY new_image_id (new_image_id), KEY image_exist (image_exist), KEY image_id_exist (image_id_exist)) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));
	
	// ������ ���������� �� CC
	if ($TableSource=='etrade_cc_desc' or $TableSource=='') {
		$sql_result = $link->query("SELECT product_id, pic_file1, pic_file2, product_addon_pics FROM etrade_cc_desc") or die(SendAnswer("Error: ". mysqli_error()));
	} else {
		$sql_result = $link->query("SELECT field_value1 as product_id, field_value2 as pic_file1, field_value3 as pic_file2, field_value4 as product_addon_pics FROM etrade_cc_filters WHERE row_type='pics'") or die(SendAnswer("Error: ". mysqli_error()));
	}

	$link->query("INSERT INTO pics_list_temp (product_id, pic_file_name, pic_type) SELECT tov_id, pic_name, pic_type FROM etrade_cc_pics") or die(SendAnswer("Error: ". mysqli_error()));
	
	// ������� ������ ����������
	//$link->query("DELETE FROM ".$DB_TablePrefix."images WHERE image_id IN (SELECT image_id FROM ".$DB_TablePrefix."images_links WHERE object_type='product' AND object_id IN (SELECT product_id FROM pics_list_temp GROUP BY product_id))") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("DELETE FROM ".$DB_TablePrefix."images_links WHERE object_type='product' AND object_id IN (SELECT product_id FROM pics_list_temp GROUP BY product_id)") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE pics_list_temp, ".$DB_TablePrefix."images SET image_exist=1 WHERE pics_list_temp.pic_file_name<>'' AND pics_list_temp.pic_file_name=".$DB_TablePrefix."images.image_path") or die(SendAnswer("Error: ". mysqli_error()));	
	
	$link->query("INSERT INTO ".$DB_TablePrefix."images (image_path) SELECT pic_file_name FROM pics_list_temp WHERE pics_list_temp.pic_file_name<>'' AND pics_list_temp.image_exist=0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE pics_list_temp, ".$DB_TablePrefix."images SET pics_list_temp.new_image_id=".$DB_TablePrefix."images.image_id WHERE pics_list_temp.pic_file_name<>'' AND pics_list_temp.pic_file_name=".$DB_TablePrefix."images.image_path") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE pics_list_temp, ".$DB_TablePrefix."images_links SET pics_list_temp.image_id_exist=1 WHERE pics_list_temp.pic_file_name<>'' AND pics_list_temp.new_image_id=".$DB_TablePrefix."images_links.detailed_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("INSERT INTO ".$DB_TablePrefix."images_links (object_id, object_type, type, detailed_id) SELECT product_id, 'product' as object_type, pic_type, new_image_id FROM pics_list_temp WHERE pics_list_temp.pic_file_name<>'' AND pics_list_temp.image_id_exist=0") or die(SendAnswer("Error: ". mysqli_error()));	

	// ����������� ������� ����� � �� �����
	$sql_result = $link->query("SELECT image_id, image_path, image_x, image_y FROM ".$DB_TablePrefix."images WHERE image_x=0 OR image_y=0") or die(SendAnswer("Error: ". mysqli_error()));
	update_pics_size($sql_result, "../images/detailed/", $DB_TablePrefix.'images', 'image_path', 'image_x', 'image_y', 'image_id', '');
	
	
	// �������� ���������� � ������ �����
	$UploadDirTemp="../images/detailed/";
	$config_local_file="../config.local.php";
	$delete_temp_file=0;
	
	if (is_dir($UploadDirTemp)==true && is_file($config_local_file)==true) {
		// MAX_FILES_IN_DIR
		$config_local_file_contents=file_get_contents('../config.local.php');
		preg_match("@define\('MAX_FILES_IN_DIR', (.*?)\);@smi", $config_local_file_contents, $nMAX_FILES_IN_DIR);
		$nMAX_FILES_IN_DIR=$nMAX_FILES_IN_DIR[1];
		if (empty($nMAX_FILES_IN_DIR)) $nMAX_FILES_IN_DIR=1000;
		
		$sql_result = $link->query("SELECT image_id, image_path FROM ".$DB_TablePrefix."images WHERE image_path IN (SELECT pic_file_name FROM pics_list_temp GROUP BY pic_file_name) GROUP BY image_id") or die(SendAnswer("Error: ". mysqli_error()));

		while ($sql_row = mysqli_fetch_array($sql_result)) {
		
			if (is_file($UploadDirTemp.strtolower($sql_row['image_path']))) {
				$UploadDir_prefix=floor((int)$sql_row['image_id'] / (int)$nMAX_FILES_IN_DIR).'/';
				$UploadDir=$UploadDirTemp.$UploadDir_prefix;
				if (is_dir($UploadDir)==false) mkdir($UploadDir, 0755, true);
		
				if ($always_copy_photo==1) {
					$result=copy($UploadDirTemp.strtolower($sql_row['image_path']), $UploadDir.strtolower($sql_row['image_path']));
					if (!$result) die(SendAnswer("Error: copy file ".$UploadDirTemp.strtolower($sql_row['image_path'])." to ".$UploadDir.strtolower($sql_row['image_path'])));
				} else {
					if (is_file($UploadDir.strtolower($sql_row['image_path']))==false) { // �������� ������ ���� ����� ���
						$result=copy($UploadDirTemp.strtolower($sql_row['image_path']), $UploadDir.strtolower($sql_row['image_path']));
						if (!$result) die(SendAnswer("Error: copy file ".$UploadDirTemp.strtolower($sql_row['image_path'])." to ".$UploadDir.strtolower($sql_row['image_path'])));
					} else { // �������� ������ ���� ��������� ������ ����� 
						$UploadTempFileSize=filesize($UploadDirTemp.strtolower($sql_row['image_path']));
						$UploadFileSize=filesize($UploadDir.strtolower($sql_row['image_path']));
						if ($UploadFileSize<>$UploadTempFileSize) {
							$result=copy($UploadDirTemp.strtolower($sql_row['image_path']), $UploadDir.strtolower($sql_row['image_path']));
							if (!$result) die(SendAnswer("Error: copy file ".$UploadDirTemp.strtolower($sql_row['image_path'])." to ".$UploadDir.strtolower($sql_row['image_path'])));
						}
					}
				}
				
				if ($delete_temp_file==1) unlink($UploadDirTemp.strtolower($sql_row['image_path']));
			}
		}
	}
	
	$link->query("DROP TEMPORARY TABLE IF EXISTS pics_list_temp") or die(SendAnswer("Error: ". mysqli_error()));
}


function cs_cart4_import_filters($DB_TablePrefix) {
	global $link;
	
	// type - dynamic
	
	// filters ID
	$sql_result = $link->query("SELECT GROUP_CONCAT( filter_id ) as filters_id_list FROM `".$DB_TablePrefix."product_filters`") or die(SendAnswer("Error: ". mysqli_error()));
	$sql_row = mysqli_fetch_array($sql_result);
	$filter_id_list=$sql_row['filters_id_list'];
	
	// update filters ID for makets
	$sql_result = $link->query("SELECT block_id, type, properties, company_id FROM `".$DB_TablePrefix."bm_blocks` WHERE `type` = 'product_filters'") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result)) {
		$filters_content=array('items' => array('filling' => 'manually', 'item_ids' => $filter_id_list));
		$filters_content=serialize($filters_content);

		$link->query("UPDATE `".$DB_TablePrefix."bm_blocks_content` SET `content`='".$filters_content."' WHERE block_id='".$sql_row['block_id']."'") or die(SendAnswer("Error: ". mysqli_error()));
	}
}


function prestashop_import_pics($DB_TablePrefix, $lang_id) {
	global $link;
	
	$cover_row=0;
	$delete_temp_pics=0;
	$UploadDir_my="../img/my_products_img/";

	if (is_dir($UploadDir_my)==false) die(SendAnswer('Error: ��� ����������� ����������, �������� ��������� ����� - '.$UploadDir_my.', ���������� ����� ����������� �� �������� E-Trade Content Creator � ��� �����.'));

	$field_exist_query=$link->query("SHOW COLUMNS FROM ".$DB_TablePrefix."image_shop WHERE `Field`='id_product'") or die(SendAnswer("Error: ". mysqli_error()));
	
	if (mysqli_num_rows($field_exist_query)==0) {
		$link->query("ALTER TABLE  ".$DB_TablePrefix."image_shop ADD  `id_product` INT NOT NULL , ADD INDEX (  `id_product` )") or die(SendAnswer("Error: ". mysqli_error()));
	}
		
	// ALTER TABLE  `ps_image_shop` ADD  `id_product` INT NOT NULL , ADD INDEX (  `id_product` ) ;


	// ����
	$sql_result = $link->query("SELECT tov_id, pic_type, pic_order, pic_name, tov_name FROM etrade_cc_pics") or die(SendAnswer("Error: ". mysqli_error()));

	while ($sql_row = mysqli_fetch_array($sql_result)) {
		
		// ����1
		if (!empty($sql_row['pic_name']) && $sql_row['pic_type']=='M' && is_file($UploadDir_my.strtolower($sql_row['pic_name']))) {
				
			$parameters_query = $link->query("SELECT id_image, id_product FROM ".$DB_TablePrefix."image WHERE id_product='".$sql_row['tov_id']."' AND position=".$sql_row['pic_order']." LIMIT 1");
			if ($my_row = mysqli_fetch_assoc($parameters_query)) {
				$insert_id=$my_row['id_image'];
			} else {
				$link->query("INSERT INTO ".$DB_TablePrefix."image (id_product, position, cover) VALUES(".$sql_row['tov_id'].", ".$sql_row['pic_order'].", 1)") or die(SendAnswer("Error: ". mysqli_error()));
				
				$insert_id=mysqli_insert_id($link) or die(SendAnswer("Error: ". mysqli_error()));
				
				if ($insert_id>0) {
					$sql_lang_result = $link->query("SELECT id_lang FROM ".$DB_TablePrefix."lang WHERE active=1") or die(SendAnswer("Error: ". mysqli_error()));
					while ($sql_lang_row = mysqli_fetch_array($sql_lang_result)) {
						$link->query("INSERT INTO ".$DB_TablePrefix."image_lang (id_image, id_lang, legend) VALUES(".$insert_id.", '".$sql_lang_row['id_lang']."', '".mysqli_real_escape_string($link, $sql_row['tov_name'])."')") or die(SendAnswer("Error: ". mysqli_error()));
					}
				
					$link->query("INSERT INTO ".$DB_TablePrefix."image_shop (id_image, id_shop, cover, id_product) VALUES(".$insert_id.", '1', 1, ".$sql_row['tov_id'].")") or die(SendAnswer("Error: ". mysqli_error()));
				}
			}
			
			// �������� �������� ��� �������� ���������� �������
			$UploadDir = '../img/p/'.PrestaShop_getImgFolderStatic($insert_id);
			if (is_dir($UploadDir)==false) mkdir($UploadDir, 0777, true);
			if (is_dir($UploadDir)==false) die(SendAnswer('Error: ������ �������� ���������� ��� �������� ���������� ������� - '.$UploadDir));
	
			// ������ ����� �������� � ��������� �������
			$image_types = $link->query("SELECT name, width, height FROM ".$DB_TablePrefix."image_type WHERE products=1") or die(SendAnswer("Error: ". mysqli_error()));
			
			while ($row1 = mysqli_fetch_assoc($image_types)) {
				imageResize($UploadDir_my.strtolower($sql_row['pic_name']), $UploadDir.(int)$insert_id.'-'.stripslashes($row1['name']).'.jpg', $row1['width'], $row1['height']);
			}
			
			// orig file
			copy($UploadDir_my.strtolower($sql_row['pic_name']), $UploadDir.(int)$insert_id.'.jpg');
		}
		
		// ����2 - ��������������
		if (!empty($sql_row['pic_name']) && $sql_row['pic_type']=='A' && is_file($UploadDir_my.strtolower($sql_row['pic_name']))) {
				
			$parameters_query = $link->query("SELECT id_image, id_product FROM ".$DB_TablePrefix."image WHERE id_product='".$sql_row['tov_id']."' AND position=".$sql_row['pic_order']." LIMIT 1");
			if ($my_row = mysqli_fetch_assoc($parameters_query)) {
				$insert_id=$my_row['id_image'];
			} else {
				$link->query("INSERT INTO ".$DB_TablePrefix."image (id_product, position, cover) VALUES(".$sql_row['tov_id'].", ".$sql_row['pic_order'].", ".$sql_row['pic_order'].")") or die(SendAnswer("Error: ". mysqli_error()));
				
				$insert_id=mysqli_insert_id($link) or die(SendAnswer("Error: ". mysqli_error()));
				
				if ($insert_id>0) {
					$sql_lang_result = $link->query("SELECT id_lang FROM ".$DB_TablePrefix."lang WHERE active=1") or die(SendAnswer("Error: ". mysqli_error()));
					while ($sql_lang_row = mysqli_fetch_array($sql_lang_result)) {
						$link->query("INSERT INTO ".$DB_TablePrefix."image_lang (id_image, id_lang, legend) VALUES(".$insert_id.", '".$sql_lang_row['id_lang']."', '".mysqli_real_escape_string($link, $sql_row['tov_name'])."')") or die(SendAnswer("Error: ". mysqli_error()));
					}
					
					$link->query("INSERT INTO ".$DB_TablePrefix."image_shop (id_image, id_shop, cover, id_product) VALUES(".$insert_id.", '1', ".$sql_row['pic_order'].", ".$sql_row['tov_id'].")") or die(SendAnswer("Error: ". mysqli_error()));
				}
			}
			
			// �������� �������� ��� �������� ���������� �������
			$UploadDir = '../img/p/'.PrestaShop_getImgFolderStatic($insert_id);
			if (is_dir($UploadDir)==false) mkdir($UploadDir, 0777, true);
			if (is_dir($UploadDir)==false) die(SendAnswer('Error: ������ �������� ���������� ��� �������� ���������� ������� - '.$UploadDir));
			
			// ������ ����� �������� � ��������� �������
			$image_types = $link->query("SELECT name, width, height FROM ".$DB_TablePrefix."image_type WHERE products=1") or die(SendAnswer("Error: ". mysqli_error()));
			
			while ($row1 = mysqli_fetch_assoc($image_types)) {
				imageResize($UploadDir_my.strtolower($sql_row['pic_name']), $UploadDir.(int)$insert_id.'-'.stripslashes($row1['name']).'.jpg', $row1['width'], $row1['height']);
			}
			
			// orig file
			copy($UploadDir_my.strtolower($sql_row['pic_name']), $UploadDir.(int)$insert_id.'.jpg');
		}
		
			
		// delete temp pics
		if ($delete_temp_pics==1) {
			if (is_file($UploadDir_my.strtolower($sql_row['pic_name']))) {
				unlink($UploadDir_my.strtolower($sql_row['pic_name']));
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


// Recommended indexs for Bitrix
// ALTER TABLE `b_iblock_element` ADD INDEX ( `IBLOCK_ID` ); 
// ALTER TABLE `b_iblock_element` ADD INDEX ( `XML_ID` ); 
// ALTER TABLE `b_iblock_element` ADD INDEX ( `ACTIVE` ); 
// ALTER TABLE `b_iblock` ADD INDEX ( `IBLOCK_TYPE_ID` ); 
// ALTER TABLE `b_iblock` ADD INDEX ( `ACTIVE` ); 
// ALTER TABLE `b_iblock` ADD INDEX ( `XML_ID` ); 
// ALTER TABLE `b_file` ADD INDEX ( `FILE_NAME` ); 
// ALTER TABLE `b_iblock_element_property` ADD INDEX ( `IBLOCK_ELEMENT_ID` );
// ALTER TABLE `b_iblock_property` ADD INDEX ( `ACTIVE` );
// ALTER TABLE `b_iblock_property` ADD INDEX ( `NAME` ) 
// ALTER TABLE `b_iblock_section_element` ADD INDEX ( `IBLOCK_SECTION_ID` );
// ALTER TABLE `b_catalog_price` ADD INDEX ( `PRODUCT_ID` );

// Bitrix - Create fields for features
function Bitrix_FEATURES_SAVE_MODE2_CC() {
	global $link;
	
	// ���������� ������ MySQL �Row size too large� � 1�-�������
	// http://alexvaleev.ru/mysql-row-size-too-large/
	
	// add products
	$blocks_list=$link->query("SELECT iblock_id FROM b_iblock_property GROUP BY iblock_id") or die(SendAnswer("Error: ". mysqli_error()));
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
					  KEY `ix_iblock_elem_prop_m8_1` (`IBLOCK_ELEMENT_ID`,`IBLOCK_PROPERTY_ID`),
					  KEY `ix_iblock_elem_prop_m8_2` (`IBLOCK_PROPERTY_ID`),
					  KEY `ix_iblock_elem_prop_m8_3` (`VALUE_ENUM`,`IBLOCK_PROPERTY_ID`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1") or die(SendAnswer("Error: ". mysqli_error()));	
					
		$link->query("INSERT INTO b_iblock_element_prop_s".$arr_blocks_list['iblock_id']." (IBLOCK_ELEMENT_ID) SELECT ID FROM b_iblock_element WHERE iblock_id=".$arr_blocks_list['iblock_id']." AND ID NOT IN (SELECT IBLOCK_ELEMENT_ID FROM b_iblock_element_prop_s".$arr_blocks_list['iblock_id'].")") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	// create fields
	$properties_list=$link->query("SELECT iblock_id, ID as property_id FROM etrade_cc_filters INNER JOIN b_iblock_property ON b_iblock_property.XML_ID=etrade_cc_filters.field_value2 WHERE row_type='f' GROUP BY b_iblock_property.iblock_id, b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_properties_list = mysqli_fetch_assoc($properties_list)) {		
		$field_exist_query=$link->query("SHOW COLUMNS FROM b_iblock_element_prop_s".$arr_properties_list['iblock_id']." WHERE `Field`='PROPERTY_".$arr_properties_list['property_id']."'") or die(SendAnswer("Error: ". mysqli_error()));
		
		if (mysqli_num_rows($field_exist_query)==0) {
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `PROPERTY_".$arr_properties_list['property_id']."` text") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `DESCRIPTION_".$arr_properties_list['property_id']."` text DEFAULT NULL") or die(SendAnswer("Error: ". mysqli_error()));
		}
		
		// update values
		$link->query("UPDATE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].", etrade_cc_filters SET b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".PROPERTY_".$arr_properties_list['property_id']."=etrade_cc_filters.field_value5 WHERE etrade_cc_filters.row_type='v' AND etrade_cc_filters.b_fid>0 AND etrade_cc_filters.b_fid=".$arr_properties_list['property_id']." AND b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".IBLOCK_ELEMENT_ID=etrade_cc_filters.b_pid") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	$link->query("UPDATE b_iblock SET PROPERTY_INDEX='I' WHERE `ID` >= (SELECT GROUP_CONCAT(DISTINCT IBLOCK_ID) FROM b_catalog_iblock)") or die(SendAnswer("Error: ". mysqli_error()));
	
}


// Bitrix - Create fields for products-attributes
function Bitrix_PRODUCTS_ATTRIBUTES_SAVE_MODE2_PLI() {
	global $link;

	
	// ���������� ������ MySQL �Row size too large� � 1�-�������
	// http://alexvaleev.ru/mysql-row-size-too-large/
	
	// add products
	$blocks_list=$link->query("SELECT iblock_id FROM b_iblock_property GROUP BY iblock_id") or die(SendAnswer("Error: ". mysqli_error()));
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
					  KEY `ix_iblock_elem_prop_m8_1` (`IBLOCK_ELEMENT_ID`,`IBLOCK_PROPERTY_ID`),
					  KEY `ix_iblock_elem_prop_m8_2` (`IBLOCK_PROPERTY_ID`),
					  KEY `ix_iblock_elem_prop_m8_3` (`VALUE_ENUM`,`IBLOCK_PROPERTY_ID`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1") or die(SendAnswer("Error: ". mysqli_error()));	
					
		$link->query("INSERT INTO b_iblock_element_prop_s".$arr_blocks_list['iblock_id']." (IBLOCK_ELEMENT_ID) SELECT ID FROM b_iblock_element WHERE iblock_id=".$arr_blocks_list['iblock_id']." AND ID NOT IN (SELECT IBLOCK_ELEMENT_ID FROM b_iblock_element_prop_s".$arr_blocks_list['iblock_id'].")") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	// create fields
	$properties_list=$link->query("SELECT iblock_id, ID as property_id FROM b_iblock_property GROUP BY b_iblock_property.iblock_id, b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_properties_list = mysqli_fetch_assoc($properties_list)) {		
		$field_exist_query=$link->query("SHOW COLUMNS FROM b_iblock_element_prop_s".$arr_properties_list['iblock_id']." WHERE `Field`='PROPERTY_".$arr_properties_list['property_id']."'") or die(SendAnswer("Error: ". mysqli_error()));
		
		if (mysqli_num_rows($field_exist_query)==0) {
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `PROPERTY_".$arr_properties_list['property_id']."` longtext") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `DESCRIPTION_".$arr_properties_list['property_id']."` text") or die(SendAnswer("Error: ". mysqli_error()));
		}
		
		// update values
		$link->query("UPDATE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].", b_iblock_element_property 
			SET b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".PROPERTY_".$arr_properties_list['property_id']."=b_iblock_element_property.VALUE 
			WHERE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".IBLOCK_ELEMENT_ID=b_iblock_element_property.IBLOCK_ELEMENT_ID AND 
				  b_iblock_element_property.IBLOCK_PROPERTY_ID=".$arr_properties_list['property_id']) or die(SendAnswer("Error: ". mysqli_error()));
		
	}
	
	$link->query("UPDATE b_iblock SET PROPERTY_INDEX='I' WHERE `ID` >= (SELECT GROUP_CONCAT(DISTINCT IBLOCK_ID) FROM b_catalog_iblock)") or die(SendAnswer("Error: ". mysqli_error()));
}

function Bitrix_FEATURES_SAVE_MODE2_PICS_CC($photo_file_property_code) {
	global $link;
	
	if (empty($photo_file_property_code)) $photo_file_property_code='MORE_PHOTO';
	
	$link->query("DROP TABLE IF EXISTS etrade_cc_pics_ids") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("CREATE TABLE IF NOT EXISTS etrade_cc_pics_ids (
			  `IBLOCK_ELEMENT_ID_EXT` int(11) NOT NULL, 
			  `IBLOCK_ELEMENT_XML_ID` varchar(80) NOT NULL, 
			  PRIMARY KEY (`IBLOCK_ELEMENT_XML_ID`), 
			  KEY `IBLOCK_ELEMENT_ID_EXT` (`IBLOCK_ELEMENT_ID_EXT`)			  
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));	
			
	$link->query("INSERT INTO etrade_cc_pics_ids (IBLOCK_ELEMENT_XML_ID) SELECT tov_guid FROM `etrade_cc_pics` GROUP BY tov_guid") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("UPDATE etrade_cc_pics_ids, b_iblock_element SET etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=b_iblock_element.ID WHERE b_iblock_element.xml_id=etrade_cc_pics_ids.IBLOCK_ELEMENT_XML_ID ") or die(SendAnswer("Error: ". mysqli_error()));
	
	// set pics for version 2
	// 1 part
	$properties_list=$link->query("SELECT iblock_id, ID as property_id FROM b_iblock_property WHERE PROPERTY_TYPE='F' AND VERSION=2 AND CODE='".$photo_file_property_code."' GROUP BY b_iblock_property.iblock_id, b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));

	while ($arr_properties_list = mysqli_fetch_assoc($properties_list)) {	
		$link->query("DELETE b_iblock_element_prop_m".$arr_properties_list['iblock_id']." FROM etrade_cc_pics_ids JOIN b_iblock_element_prop_m".$arr_properties_list['iblock_id']." ON etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=IBLOCK_ELEMENT_ID WHERE IBLOCK_PROPERTY_ID=".$arr_properties_list['property_id']." ") or die(SendAnswer("Error: ". mysqli_error()));
		// AND IBLOCK_ELEMENT_ID IN (SELECT b_iblock_element.id FROM b_iblock_element INNER JOIN etrade_cc_pics ON b_iblock_element.xml_id=etrade_cc_pics.tov_guid GROUP BY b_iblock_element.id)
		$link->query("INSERT INTO b_iblock_element_prop_m".$arr_properties_list['iblock_id']." (IBLOCK_ELEMENT_ID, IBLOCK_PROPERTY_ID, VALUE, VALUE_NUM) SELECT IBLOCK_ELEMENT_ID, IBLOCK_PROPERTY_ID, VALUE, VALUE_NUM FROM b_iblock_element_property INNER JOIN etrade_cc_pics_ids ON etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=IBLOCK_ELEMENT_ID WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID=".$arr_properties_list['property_id']." ") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	// 2 part
	$properties_list=$link->query("SELECT iblock_id, ID as property_id FROM b_iblock_property WHERE PROPERTY_TYPE='F' AND VERSION=2 AND CODE='".$photo_file_property_code."' GROUP BY b_iblock_property.iblock_id, b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));

	while ($arr_properties_list = mysqli_fetch_assoc($properties_list)) {
		$properties_values_list=$link->query("SELECT IBLOCK_ELEMENT_ID, GROUP_CONCAT( VALUE ) as property_value_group FROM b_iblock_element_prop_m".$arr_properties_list['iblock_id']." INNER JOIN etrade_cc_pics_ids ON etrade_cc_pics_ids.IBLOCK_ELEMENT_ID_EXT=IBLOCK_ELEMENT_ID WHERE IBLOCK_PROPERTY_ID=".$arr_properties_list['property_id']." GROUP BY IBLOCK_ELEMENT_ID") or die(SendAnswer("Error: ". mysqli_error()));
		
		while ($arr_properties_values_list = mysqli_fetch_assoc($properties_values_list)) {
			$property_value_group_content=array('VALUE' => explode(',', $arr_properties_values_list['property_value_group']), 'DESCRIPTION' => array(NULL, NULL, NULL));
			$property_value_group_content=serialize($property_value_group_content);

			$link->query("INSERT INTO b_iblock_element_prop_s".$arr_properties_list['iblock_id']." (IBLOCK_ELEMENT_ID, PROPERTY_".$arr_properties_list['property_id'].") VALUES (".$arr_properties_values_list['IBLOCK_ELEMENT_ID'].", '".$property_value_group_content."') ON DUPLICATE KEY UPDATE PROPERTY_".$arr_properties_list['property_id']."='".$property_value_group_content."'") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	
	$link->query("DROP TABLE IF EXISTS etrade_cc_pics_ids") or die(SendAnswer("Error: ". mysqli_error()));
}


function Bitrix_GetHighloadValues() {
	global $link;
	
	$link->query("DROP TABLE IF EXISTS `etrade_products_highloadvalues_from_site_tmp`") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("CREATE TABLE IF NOT EXISTS `etrade_products_highloadvalues_from_site_tmp` (
		  `op_type` varchar(10) NOT NULL,
		  `iblock_element_id` int(11) NOT NULL,
		  `xml_id` varchar(120) NOT NULL,
		  `bitrix_code` varchar(120) NOT NULL,
		  `property_name` varchar(255) NOT NULL,
		  `property_value` text NOT NULL, 
		  `property_value_description` text NOT NULL, 
		  `user_type_settings` text NOT NULL, 
		  `iblock_property_id` int(11) NOT NULL,
	  KEY `op_type` (`op_type`),
	  KEY `iblock_element_id` (`iblock_element_id`),
	  KEY `iblock_property_id` (`iblock_property_id`),
	  KEY `xml_id` (`xml_id`),
	  KEY `bitrix_code` (`bitrix_code`),
	  KEY `property_name` (`property_name`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));

	$link->query("INSERT INTO etrade_products_highloadvalues_from_site_tmp (op_type, iblock_element_id, xml_id, bitrix_code, 
		property_name, property_value, property_value_description, user_type_settings, iblock_property_id) 
	SELECT 'af' as op_type, b_iblock_element_property.IBLOCK_ELEMENT_ID, b_iblock_property.xml_id, 
		CONCAT('bitrix_', b_iblock_property.CODE) AS BITRIX_CODE, b_iblock_property.NAME, 
		b_iblock_element_property.VALUE, b_iblock_element_property.DESCRIPTION, b_iblock_property.user_type_settings, b_iblock_property.ID 
	FROM b_iblock_property 
	INNER JOIN b_iblock_element_property ON b_iblock_property.ID=b_iblock_element_property.IBLOCK_PROPERTY_ID 
	WHERE b_iblock_property.USER_TYPE='directory' 
	ORDER BY b_iblock_element_property.IBLOCK_ELEMENT_ID, b_iblock_property.NAME") or die(SendAnswer("Error: ". mysqli_error()));
	
	$res_properties_list = $link->query("SELECT user_type_settings, iblock_property_id, property_value FROM etrade_products_highloadvalues_from_site_tmp GROUP BY iblock_property_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_properties_list = mysqli_fetch_assoc($res_properties_list)) {
		$USER_TYPE_SETTINGS=unserialize($arr_properties_list['user_type_settings']);
		
		 $link->query("UPDATE etrade_products_highloadvalues_from_site_tmp, ".$USER_TYPE_SETTINGS['TABLE_NAME']." 
			SET etrade_products_highloadvalues_from_site_tmp.property_value=".$USER_TYPE_SETTINGS['TABLE_NAME'].".UF_NAME 
			WHERE etrade_products_highloadvalues_from_site_tmp.iblock_property_id=".$arr_properties_list['iblock_property_id']." AND etrade_products_highloadvalues_from_site_tmp.property_value=".$USER_TYPE_SETTINGS['TABLE_NAME'].".UF_XML_ID") or die(SendAnswer("Error: ". mysqli_error()));
	}
}

// Bitrix - Create search content for products
function Bitrix_SearchContent() {
	// get data from db
	global $link;

	//  �������� �������� � ��������
	$index_query=$link->query("SHOW INDEX FROM `b_file` WHERE key_name = 'FILE_NAME'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE `b_file` ADD INDEX ( `FILE_NAME` )") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	$link->query("CREATE TABLE IF NOT EXISTS `b_search_content_text` (
	  `SEARCH_CONTENT_ID` int(11) NOT NULL,
	  `SEARCH_CONTENT_MD5` char(32) collate utf8_unicode_ci NOT NULL,
	  `SEARCHABLE_CONTENT` longtext collate utf8_unicode_ci,
	  PRIMARY KEY  (`SEARCH_CONTENT_ID`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("DROP TABLE IF EXISTS b_search_content_tmp") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("CREATE TEMPORARY TABLE b_search_content_tmp (`item_id` int(11) NOT NULL, KEY `item_id` (`item_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("INSERT INTO b_search_content_tmp (item_id) SELECT ITEM_ID FROM b_search_content WHERE MODULE_ID='iblock' AND ITEM_ID>0 GROUP BY ITEM_ID") or die(SendAnswer("Error: ". mysqli_error()));
	
	$res_block_list = $link->query("SELECT b_iblock_element.ID, b_iblock_element.XML_ID as EXTERNAL_ID, b_iblock_element.IBLOCK_SECTION_ID, b_iblock_element.IBLOCK_ID, b_iblock.CODE as IBLOCK_CODE, 
		b_iblock.XML_ID as IBLOCK_EXTERNAL_ID, b_iblock_element.CODE, b_iblock_element.NAME as TITLE, iblock_type_id 
		FROM b_iblock_element 
		LEFT JOIN b_iblock ON b_iblock_element.IBLOCK_ID=b_iblock.ID 
		LEFT JOIN b_search_content_tmp ON b_iblock_element.ID=b_search_content_tmp.item_id 
		WHERE b_iblock.iblock_type_id IN (SELECT ID FROM b_iblock_type WHERE SECTIONS='Y') AND b_iblock.active='Y' AND b_search_content_tmp.item_id IS NULL") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_block_list = mysqli_fetch_assoc($res_block_list)) {
		$link->query("INSERT INTO b_search_content (DATE_CHANGE, MODULE_ID, ITEM_ID, URL, TITLE, BODY, TAGS, PARAM1, PARAM2) VALUES (now(), 'iblock', ".$arr_block_list["ID"].", '=ID=".$arr_block_list["ID"]."&EXTERNAL_ID=".$arr_block_list["EXTERNAL_ID"]."&IBLOCK_SECTION_ID=".$arr_block_list["IBLOCK_SECTION_ID"]."&IBLOCK_TYPE_ID=".$arr_block_list["iblock_type_id"]."&IBLOCK_ID=".$arr_block_list["IBLOCK_ID"]."&IBLOCK_CODE=".mysqli_real_escape_string($link, $arr_block_list["IBLOCK_CODE"])."&IBLOCK_EXTERNAL_ID=".mysqli_real_escape_string($link, $arr_block_list["IBLOCK_EXTERNAL_ID"])."&CODE=".mysqli_real_escape_string($link, $arr_block_list["CODE"])."', '".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."', '', '', '".mysqli_real_escape_string($link, $arr_block_list["iblock_type_id"])."', '".mysqli_real_escape_string($link, $arr_block_list["IBLOCK_ID"])."')") or die(SendAnswer("Error: ". mysqli_error()));

		$search_content_id = mysqli_insert_id($link);
		
		$link->query("INSERT INTO b_search_content_right (SEARCH_CONTENT_ID, GROUP_CODE) VALUES(".$search_content_id.", 'G1') ON DUPLICATE KEY UPDATE GROUP_CODE='G1'") or die(SendAnswer("Error: ". mysqli_error()));
		$link->query("INSERT INTO b_search_content_right (SEARCH_CONTENT_ID, GROUP_CODE) VALUES(".$search_content_id.", 'G2') ON DUPLICATE KEY UPDATE GROUP_CODE='G2'") or die(SendAnswer("Error: ". mysqli_error()));
		$link->query("INSERT INTO b_search_content_site (SEARCH_CONTENT_ID, SITE_ID, URL) VALUES (".$search_content_id.", 's1', '') ON DUPLICATE KEY UPDATE SITE_ID='s1'") or die(SendAnswer("Error: ". mysqli_error()));
		$link->query("INSERT INTO b_search_content_title (SEARCH_CONTENT_ID, SITE_ID, POS, WORD) VALUES (".$search_content_id.", 's1', 0, '".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."') ON DUPLICATE KEY UPDATE SITE_ID='s1'") or die(SendAnswer("Error: ". mysqli_error()));
		$link->query("INSERT INTO b_search_content_stem (SEARCH_CONTENT_ID, LANGUAGE_ID, STEM, TF) VALUES (".$search_content_id.", 'ru', 235, 0.2314) ON DUPLICATE KEY UPDATE LANGUAGE_ID='ru'") or die(SendAnswer("Error: ". mysqli_error()));
		$link->query("INSERT INTO b_search_content_text (SEARCH_CONTENT_ID, SEARCH_CONTENT_MD5, SEARCHABLE_CONTENT) VALUES (".$search_content_id.", md5('".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."'), '".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."\r\n\r\n') ON DUPLICATE KEY UPDATE SEARCHABLE_CONTENT='".mysqli_real_escape_string($link, $arr_block_list["TITLE"])."\r\n\r\n'") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	$link->query("DROP TABLE IF EXISTS b_search_content_tmp") or die(SendAnswer("Error: ". mysqli_error()));
	
	
	// ���������� �������� � ���� ������� ����������� (Highload ���������)
	$res_properties_list = $link->query("SELECT b_iblock_property.`ID`, b_iblock_property.iblock_id, b_iblock_property.`NAME`, b_iblock_property.`CODE`, b_iblock_property.`USER_TYPE_SETTINGS` 
		FROM b_iblock_property 
		INNER JOIN etrade_products_addon_fields ON b_iblock_property.CODE=etrade_products_addon_fields.field_name 
		WHERE b_iblock_property.USER_TYPE='directory' 
		GROUP BY b_iblock_property.ID") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_properties_list = mysqli_fetch_assoc($res_properties_list)) {
		$USER_TYPE_SETTINGS=unserialize($arr_properties_list['USER_TYPE_SETTINGS']);
		
		$link->query("INSERT INTO ".$USER_TYPE_SETTINGS['TABLE_NAME']." (UF_NAME, UF_SORT, UF_FILE, UF_LINK) 
			SELECT field_value, 0 as UF_SORT, 0 as UF_FILE, '' as UF_LINK 
			FROM etrade_products_addon_fields 
			WHERE etrade_products_addon_fields.property_id=".$arr_properties_list['ID']." AND field_value<>'' AND field_value NOT IN (SELECT UF_NAME FROM ".$USER_TYPE_SETTINGS['TABLE_NAME'].") GROUP BY field_value") or die(SendAnswer("Error: ". mysqli_error()));
		
		$link->query("UPDATE ".$USER_TYPE_SETTINGS['TABLE_NAME']." SET UF_XML_ID=CONCAT('PLI-', md5(UF_NAME)) WHERE UF_XML_ID='' OR UF_XML_ID IS NULL") or die(SendAnswer("Error: ". mysqli_error()));
		
		$link->query("UPDATE b_iblock_element_property, ".$USER_TYPE_SETTINGS['TABLE_NAME']." 
			SET b_iblock_element_property.VALUE=".$USER_TYPE_SETTINGS['TABLE_NAME'].".UF_XML_ID 
			WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID=".$arr_properties_list['ID']." AND b_iblock_element_property.VALUE=".$USER_TYPE_SETTINGS['TABLE_NAME'].".UF_NAME") or die(SendAnswer("Error: ". mysqli_error()));
			
		// update values for FLAT table
		$link->query("CREATE TABLE IF NOT EXISTS b_iblock_element_prop_s".$arr_properties_list['iblock_id']." (
					  `IBLOCK_ELEMENT_ID` int(11) NOT NULL, 
					  PRIMARY KEY (`IBLOCK_ELEMENT_ID`) 
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die(SendAnswer("Error: ". mysqli_error()));	

		$field_exist_query=$link->query("SHOW COLUMNS FROM b_iblock_element_prop_s".$arr_properties_list['iblock_id']." WHERE `Field`='PROPERTY_".$arr_properties_list['ID']."'") or die(SendAnswer("Error: ". mysqli_error()));
		
		if (mysqli_num_rows($field_exist_query)==0) {
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `PROPERTY_".$arr_properties_list['ID']."` text") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("ALTER TABLE b_iblock_element_prop_s".$arr_properties_list['iblock_id']." ADD `DESCRIPTION_".$arr_properties_list['ID']."` text DEFAULT NULL") or die(SendAnswer("Error: ". mysqli_error()));
		}
		
		$link->query("UPDATE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].", b_iblock_element_property 
			SET b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".PROPERTY_".$arr_properties_list['ID']."=b_iblock_element_property.VALUE 
			WHERE b_iblock_element_prop_s".$arr_properties_list['iblock_id'].".IBLOCK_ELEMENT_ID=b_iblock_element_property.IBLOCK_ELEMENT_ID AND 
				  b_iblock_element_property.IBLOCK_PROPERTY_ID=".$arr_properties_list['ID']) or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	
	// delete cache
	remove_files('../bitrix/managed_cache/MYSQL/b_iblock', $delete_root = false, $pattern = '');
	remove_files('../bitrix/managed_cache/MYSQL/b_iblock_type', $delete_root = false, $pattern = '');
}

// Bitrix - Create new block on HDD
function Bitrix_CreateNewBlock() {
	if (!is_file('./iBlockTemplate.dat')) exit;
	
	
	// get data from db
	global $link;
	
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

// Bitrix - Block ReSort sections
function Bitrix_Block_ReSort($iblockIDs) {
	if (!empty($iblockIDs)) {
		$pos1 = stripos($iblockIDs, ",");
		
		if ($pos1 === false) { // one block ID only
			Bitrix_ReSort($iblockIDs, 0, 0, 0, "Y");
		} else { // many blocks ID exist
			$my_iblockIDs = explode(",", $iblockIDs);
			foreach ($my_iblockIDs as $iblockID) {
				Bitrix_ReSort($iblockID, 0, 0, 0, "Y");
			}
		}
	}
	
	// processing NULL data for new blocks (for new cats)
	global $link;
	$res_empty_blocks = $link->query("SELECT iblock_id FROM b_iblock_section WHERE iblock_id>0 AND (left_margin IS NULL OR right_margin IS NULL OR depth_level IS NULL)  GROUP BY iblock_id") or die(SendAnswer("Error: ". mysqli_error()));
	while ($arr_blocks_info = mysqli_fetch_assoc($res_empty_blocks)) {
		Bitrix_ReSort($arr_blocks_info["iblock_id"], 0, 0, 0, "Y");
	}
	
	// Create new block on HDD
	Bitrix_CreateNewBlock();
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

// // ����������� ������� ����� � �� �����
function Bitrix_SetImageSize() {
	global $link;
	
	$sql_result = $link->query("SELECT ID, CONCAT(TRIM(SUBDIR),'/',TRIM(FILE_NAME)) as image_path, WIDTH, HEIGHT, FILE_SIZE FROM b_file WHERE WIDTH=0 OR HEIGHT=0 OR (WIDTH=500 AND HEIGHT=500)") or die(SendAnswer("Error: ". mysqli_error()));
	
	update_pics_size($sql_result, "../upload/", 'b_file', 'image_path', 'WIDTH', 'HEIGHT', 'ID', 'FILE_SIZE');
}



// �������� ������ - 06.02.2015
function Bitrix_FacetIndexUpdate() {
	global $link;
	
	$res_block_list = $link->query("SELECT id, code, name FROM b_iblock WHERE iblock_type_id IN (SELECT ID FROM b_iblock_type WHERE SECTIONS='Y') AND active='Y'") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($arr_block_list = mysqli_fetch_assoc($res_block_list)) {
		Bitrix_FacetIndexCreateList($arr_block_list["id"]);
	}
}

function Bitrix_FacetIndexCreateList($iblock_id) {
	global $link;
	
	$link->query('DROP TABLE IF EXISTS b_iblock_'.$iblock_id.'_index_val_tmp') or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query('CREATE TABLE IF NOT EXISTS b_iblock_'.$iblock_id.'_index 
		( `SECTION_ID` int(11) NOT NULL, 
		  `ELEMENT_ID` int(11) NOT NULL, 
		  `FACET_ID` int(11) NOT NULL, 
		  `VALUE` int(11) NOT NULL, 
		  `VALUE_NUM` double NOT NULL, 
		  `INCLUDE_SUBSECTIONS` varchar(1) COLLATE utf8_unicode_ci NOT NULL, 
		  PRIMARY KEY (`SECTION_ID`,`FACET_ID`,`VALUE`,`VALUE_NUM`,`ELEMENT_ID`), 
		  KEY `IX_b_iblock'.$iblock_id.'index_0` (`SECTION_ID`,`FACET_ID`,`VALUE_NUM`,`VALUE`,`ELEMENT_ID`), 
		  KEY `IX_b_iblock'.$iblock_id.'index_1` (`ELEMENT_ID`,`SECTION_ID`,`FACET_ID`)) 
		  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci') or die(SendAnswer("Error: ". mysqli_error()));
	  
	$link->query('CREATE TABLE IF NOT EXISTS b_iblock_'.$iblock_id.'_index_val 
		( `ID` int(11) NOT NULL AUTO_INCREMENT, 
		  `VALUE` varchar(2000) COLLATE utf8_unicode_ci NOT NULL, 
		  PRIMARY KEY (`ID`), 
		  KEY `IX_b_iblock'.$iblock_id.'index_val_0` (`VALUE`(200))) 
		  ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1') or die(SendAnswer("Error: ". mysqli_error()));

	$link->query('CREATE TABLE IF NOT EXISTS b_iblock_'.$iblock_id.'_index_val_tmp 
		( `ID` int(11) NOT NULL AUTO_INCREMENT, 
		  `VALUE` varchar(2000) COLLATE utf8_unicode_ci NOT NULL, 
		  `new_value` tinyint(1) NOT NULL DEFAULT 1,	
		  PRIMARY KEY (`ID`), 
		  KEY `IX_b_iblock'.$iblock_id.'index_val_0` (`VALUE`(200))) 
		  ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1') or die(SendAnswer("Error: ". mysqli_error()));
	
	// ��������
	$link->query('INSERT INTO b_iblock_'.$iblock_id.'_index_val_tmp (`VALUE`) 
		SELECT SUBSTRING( `VALUE` FROM 1 FOR 2000) 
		FROM b_iblock_element_property 
		GROUP BY `VALUE`') or die(SendAnswer("Error: ". mysqli_error()));

	$link->query('UPDATE b_iblock_'.$iblock_id.'_index_val_tmp, b_iblock_'.$iblock_id.'_index_val 
			SET b_iblock_'.$iblock_id.'_index_val_tmp.new_value=0 
			WHERE b_iblock_'.$iblock_id.'_index_val_tmp.VALUE=b_iblock_'.$iblock_id.'_index_val.VALUE') or die(SendAnswer("Error: ". mysqli_error()));

	$link->query('INSERT INTO b_iblock_'.$iblock_id.'_index_val (`VALUE`) 
		SELECT`VALUE` 
		FROM b_iblock_'.$iblock_id.'_index_val_tmp 
		WHERE b_iblock_'.$iblock_id.'_index_val_tmp.new_value=1') or die(SendAnswer("Error: ". mysqli_error()));
	
	// ������
	$link->query('DROP TABLE IF EXISTS `b_iblock_element_property_tmp`') or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query('CREATE TABLE IF NOT EXISTS `b_iblock_element_property_tmp` (
	  `ID` int(11) NOT NULL,
	  `IBLOCK_PROPERTY_ID` int(11) NOT NULL,
	  `IBLOCK_ELEMENT_ID` int(11) NOT NULL,
	  `VALUE` varchar(2000) COLLATE utf8_unicode_ci NOT NULL, 
	  `VALUE_TYPE` char(4) COLLATE utf8_unicode_ci NOT NULL ,
	  `VALUE_ENUM` int(11) DEFAULT NULL,
	  `VALUE_NUM` decimal(18,4) DEFAULT NULL,
	  `DESCRIPTION` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  KEY `VALUE` (`VALUE`(200))
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci') or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query('INSERT INTO b_iblock_element_property_tmp 
		SELECT * FROM b_iblock_element_property 
		WHERE IBLOCK_PROPERTY_ID IN (SELECT b_iblock_property.ID FROM b_iblock_property WHERE IBLOCK_ID='.$iblock_id.')') or die(SendAnswer("Error: ". mysqli_error()));

	$link->query("ALTER TABLE b_iblock_element_property_tmp ADD `FACET_ID` INT NOT NULL DEFAULT '0', ADD INDEX ( `FACET_ID` )") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("ALTER TABLE b_iblock_element_property_tmp ADD `VALUE_ID` INT NOT NULL DEFAULT '0', ADD INDEX ( `VALUE_ID` )") or die(SendAnswer("Error: ". mysqli_error()));	
	  
	$link->query('UPDATE b_iblock_element_property_tmp, b_iblock_'.$iblock_id.'_index_val 
		SET b_iblock_element_property_tmp.VALUE_ID=b_iblock_'.$iblock_id.'_index_val.ID, 
			FACET_ID=IBLOCK_PROPERTY_ID*2 
		WHERE b_iblock_'.$iblock_id.'_index_val.VALUE=b_iblock_element_property_tmp.VALUE') or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query('DELETE FROM b_iblock_'.$iblock_id.'_index 
		WHERE ELEMENT_ID IN (SELECT IBLOCK_ELEMENT_ID FROM b_iblock_element_property_tmp GROUP BY IBLOCK_ELEMENT_ID)') or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query('INSERT INTO b_iblock_'.$iblock_id.'_index  (SECTION_ID, ELEMENT_ID, FACET_ID, VALUE, VALUE_NUM, INCLUDE_SUBSECTIONS) 
		SELECT IBLOCK_SECTION_ID, b_iblock_element_property_tmp.IBLOCK_ELEMENT_ID, FACET_ID, VALUE_ID, 0 as VALUE_NUM, "" as INCLUDE_SUBSECTIONS
		FROM b_iblock_element_property_tmp 
		INNER JOIN b_iblock_section_element ON b_iblock_section_element.IBLOCK_ELEMENT_ID=b_iblock_element_property_tmp.IBLOCK_ELEMENT_ID 
		GROUP BY IBLOCK_ELEMENT_ID, FACET_ID, VALUE_ID') or die(SendAnswer("Error: ". mysqli_error()));

	$link->query("UPDATE b_iblock SET PROPERTY_INDEX='Y' WHERE b_iblock.ID=".$iblock_id) or die(SendAnswer("Error: ". mysqli_error()));
	
	//$link->query('DROP TABLE IF EXISTS b_iblock_'.$iblock_id.'_index_val_tmp') or die(SendAnswer("Error: ". mysqli_error()));
	//$link->query('DROP TABLE IF EXISTS b_iblock_element_property_tmp') or die(SendAnswer("Error: ". mysqli_error()));

}

	
// AmiroCMS - Meta tags (���������� ���� �����)
function AmiroCMS_meta_tags($DB_TablePrefix) {
	$activate_update_meta_tags=0; // 0 - ���������, 1 - ��������
	
	if ($activate_update_meta_tags==0) exit;
	
	global $link;
	$sql_result = $link->query("SELECT tov_id, head_title, head_desc, head_keywords FROM etrade_products WHERE head_title<>'' or head_desc<>'' or head_keywords<>''") or die(SendAnswer("Error: ". mysqli_error()));

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

	//  �������� �������� � ��������
	$index_query=$link->query("SHOW INDEX FROM ".$DB_TablePrefix."SC_product_pictures WHERE key_name = 'priority'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$DB_TablePrefix."SC_product_pictures ADD INDEX (priority)") or die(SendAnswer("Error: ". mysqli_error()));
	}

	//$sql_result = $link->query("SELECT tov_id, pic_small, pic_medium, pic_big, pic_order, picID, tov_name, tov_guid FROM etrade_cc_pics_flat") or die(SendAnswer("Error: ". mysqli_error()));

	// ������� ������ ����������
	$link->query("DELETE ".$DB_TablePrefix."SC_product_pictures FROM etrade_cc_pics_flat JOIN ".$DB_TablePrefix."SC_product_pictures ON ".$DB_TablePrefix."SC_product_pictures.productID = etrade_cc_pics_flat.tov_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	// ��������� ����� 
	$link->query("INSERT INTO ".$DB_TablePrefix."SC_product_pictures (productID, filename, thumbnail, enlarged, priority) SELECT tov_id, pic_medium, pic_small, pic_big, (pic_order-1) as priority FROM etrade_cc_pics_flat") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE etrade_cc_pics_flat, SC_product_pictures SET etrade_cc_pics_flat.picID=SC_product_pictures.photoID WHERE etrade_cc_pics_flat.tov_id=SC_product_pictures.productID AND SC_product_pictures.priority=0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE SC_products, etrade_cc_pics_flat SET SC_products.default_picture=etrade_cc_pics_flat.picID WHERE SC_products.productID=etrade_cc_pics_flat.tov_id AND etrade_cc_pics_flat.pic_order=1") or die(SendAnswer("Error: ". mysqli_error()));
}

/* function ShopScriptWA_import_pics($DB_TablePrefix, $TableSource) {

	global $link;

	//  �������� �������� � ��������
	$index_query=$link->query("SHOW INDEX FROM ".$DB_TablePrefix."SC_product_pictures WHERE key_name = 'priority'") or die(SendAnswer("Error: ". mysqli_error()));
	if (mysqli_num_rows($index_query)==0) {
		$link->query("ALTER TABLE ".$DB_TablePrefix."SC_product_pictures ADD INDEX (priority)") or die(SendAnswer("Error: ". mysqli_error()));
	}
	
	$link->query("CREATE TEMPORARY TABLE addon_pics_temp (product_id int(11) NOT NULL, priority int(11) NOT NULL, photoID_new int(11) NOT NULL, filename varchar(240) NOT NULL, thumbnail varchar(240) NOT NULL, enlarged varchar(240) NOT NULL, KEY `product_id` (`product_id`),  KEY `filename` (`filename`), KEY `photoID_new` (`photoID_new`)) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));
	
	if ($TableSource=='etrade_cc_desc' or $TableSource=='') {
		$sql_result = $link->query("SELECT product_id, pic_file1, pic_file2, product_addon_pics FROM etrade_cc_desc") or die(SendAnswer("Error: ". mysqli_error()));
	} else {
		$sql_result = $link->query("SELECT field_value1 as product_id, field_value2 as pic_file1, field_value3 as pic_file2, field_value4 as product_addon_pics FROM etrade_cc_filters WHERE row_type='pics'") or die(SendAnswer("Error: ". mysqli_error()));
	}

	// ��������� ������ ����������
	while ($sql_row = mysqli_fetch_array($sql_result)) {
 		// ���� �1
		$priority_num=0;
		$file_extension=get_file_extension($sql_row['pic_file1']);
		$pic_thumbnail=strtolower(trim(str_ireplace('.'.$file_extension, '_1.'.$file_extension, $sql_row['pic_file1'])));
		$pic_medium=strtolower(trim(str_ireplace('.'.$file_extension, '_2.'.$file_extension, $sql_row['pic_file1'])));
		$pic_enlarged=strtolower(trim(str_ireplace('.'.$file_extension, '_3.'.$file_extension, $sql_row['pic_file1'])));
		
		$link->query("INSERT INTO addon_pics_temp (product_id, priority, filename, thumbnail, enlarged) VALUES (".$sql_row['product_id'].", ".$priority_num.", '".$pic_medium."', '".$pic_thumbnail."', '".$pic_enlarged."')") or die(SendAnswer("Error: ". mysqli_error()));
		
		// ���� �2
		$priority_num=1;
		$file_extension=get_file_extension($sql_row['pic_file2']);
		$pic_thumbnail=strtolower(trim(str_ireplace('.'.$file_extension, '_1.'.$file_extension, $sql_row['pic_file2'])));
		$pic_medium=strtolower(trim(str_ireplace('.'.$file_extension, '_2.'.$file_extension, $sql_row['pic_file2'])));
		$pic_enlarged=strtolower(trim(str_ireplace('.'.$file_extension, '_3.'.$file_extension, $sql_row['pic_file2'])));
		
		if (!empty($pic_thumbnail)) {
			$link->query("INSERT INTO addon_pics_temp (product_id, priority, filename, thumbnail, enlarged) VALUES (".$sql_row['product_id'].", ".$priority_num.", '".$pic_medium."', '".$pic_thumbnail."', '".$pic_enlarged."')") or die(SendAnswer("Error: ". mysqli_error())); 
		}
		
		// ���� ��
		if (empty($sql_row['product_addon_pics'])) continue; // ��� ���. ����
		
		$priority_num=0;
		$product_addon_pics=explode(',', $sql_row['product_addon_pics']);
		
		foreach ($product_addon_pics as $product_addon_pic) {
			if (empty($product_addon_pic)) continue; // ��� ���. ����

			$file_extension=get_file_extension($product_addon_pic);
			$pic_thumbnail=strtolower(trim(str_ireplace('.'.$file_extension, '_1.'.$file_extension, $product_addon_pic)));
			$pic_medium=strtolower(trim(str_ireplace('.'.$file_extension, '_2.'.$file_extension, $product_addon_pic)));
			$pic_enlarged=strtolower(trim(str_ireplace('.'.$file_extension, '_3.'.$file_extension, $product_addon_pic)));
			
			$link->query("INSERT INTO addon_pics_temp (product_id, priority, filename, thumbnail, enlarged) VALUES (".$sql_row['product_id'].", ".$priority_num.", '".$pic_medium."', '".$pic_thumbnail."', '".$pic_enlarged."')") or die(SendAnswer("Error: ". mysqli_error()));
			
			$priority_num++;
		}
	}

	// ������� ������ ����������
	$link->query("DELETE ".$DB_TablePrefix."SC_product_pictures FROM addon_pics_temp JOIN ".$DB_TablePrefix."SC_product_pictures ON ".$DB_TablePrefix."SC_product_pictures.productID = addon_pics_temp.product_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	// ��������� ����� 
	$link->query("INSERT INTO ".$DB_TablePrefix."SC_product_pictures (productID, filename, thumbnail, enlarged, priority) SELECT product_id, addon_pics_temp.filename, addon_pics_temp.thumbnail, addon_pics_temp.enlarged, addon_pics_temp.priority FROM addon_pics_temp") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE addon_pics_temp, SC_product_pictures SET addon_pics_temp.photoID_new=SC_product_pictures.photoID WHERE addon_pics_temp.product_id=SC_product_pictures.productID AND SC_product_pictures.priority=0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("UPDATE SC_products, addon_pics_temp SET SC_products.default_picture=addon_pics_temp.photoID_new WHERE SC_products.productID=addon_pics_temp.product_id AND addon_pics_temp.priority=0") or die(SendAnswer("Error: ". mysqli_error()));
	
	$link->query("DROP TEMPORARY TABLE IF EXISTS addon_pics_temp") or die(SendAnswer("Error: ". mysqli_error()));
} */



// UMI.CMS - hierarchy_relations
function umicms_hierarchy_relations_upd() {
	global $link;
	
	// cats
	$link->query("DELETE cms3_hierarchy_relations FROM etrade_cats JOIN cms3_hierarchy_relations ON etrade_cats.uc_hier_parent_id=cms3_hierarchy_relations.rel_id") or die(SendAnswer("Error: ". mysqli_error()));
	$link->query("DELETE cms3_hierarchy_relations FROM etrade_cats JOIN cms3_hierarchy_relations ON etrade_cats.uc_hier_id=cms3_hierarchy_relations.child_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	$sql_result = $link->query("SELECT uc_obj_id, uc_hier_id, uc_obj_type_id FROM etrade_cats WHERE uc_hier_id>0") or die(SendAnswer("Error: ". mysqli_error()));
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		
		umicms_makeHierarchyRelationsTable($sql_row['uc_hier_id']);
		
		if ((int)$sql_row['uc_obj_id']>0) { // create new default fields for new cats
			$link->query("INSERT INTO cms3_object_content (obj_id, field_id) 
				SELECT ".$sql_row['uc_obj_id']." as uc_obj_id, cms3_fields_controller.field_id
				FROM cms3_fields_controller 
				INNER JOIN cms3_object_field_groups ON cms3_fields_controller.group_id=cms3_object_field_groups.id 
				WHERE cms3_fields_controller.group_id IN (SELECT id FROM cms3_object_field_groups WHERE type_id =(SELECT id FROM cms3_object_types WHERE guid='catalog-category' GROUP BY guid)) AND 
				".$sql_row['uc_obj_id']." NOT IN (SELECT cms3_object_content.obj_id FROM cms3_object_content WHERE cms3_object_content.field_id=cms3_fields_controller.field_id)
				GROUP BY cms3_fields_controller.field_id") or die(SendAnswer("Error: ". mysqli_error()));
		}
		
		if ((int)$sql_row['uc_obj_type_id']>0) { // create new field groups
			$link->query("INSERT INTO cms3_object_field_groups (type_id, name, title, is_active, is_visible, ord, is_locked) 
				SELECT ".$sql_row['uc_obj_type_id']." as type_id, name, title, is_active, is_visible, ord, is_locked 
				FROM (SELECT name, title, is_active, is_visible, ord, is_locked FROM cms3_object_field_groups WHERE type_id=(SELECT id FROM cms3_object_types WHERE guid='catalog-object' GROUP BY guid)) as t2 
				WHERE ".$sql_row['uc_obj_type_id']." NOT IN (SELECT type_id FROM cms3_object_field_groups GROUP BY type_id)") or die(SendAnswer("Error: ". mysqli_error()));
			
			$link->query("DROP TABLE IF EXISTS cms3_fields_controller_tmp") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("CREATE TEMPORARY TABLE cms3_fields_controller_tmp ( `field_id` int(11) NOT NULL, `ord` int(11) NOT NULL, `group_id` int(11) NOT NULL, `new_group_id` int(11) NOT NULL, `name` VARCHAR(120) NOT NULL,  KEY `field_id` (`field_id`),  KEY `group_id` (`group_id`),  KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("INSERT INTO cms3_fields_controller_tmp (field_id, ord, group_id, new_group_id, name)
			SELECT cms3_fields_controller.field_id, cms3_fields_controller.ord, cms3_fields_controller.group_id, 000000 as new_group_id, cms3_object_field_groups.name 
			FROM cms3_fields_controller 
			INNER JOIN cms3_object_field_groups ON cms3_fields_controller.group_id=cms3_object_field_groups.id 
			WHERE group_id IN (SELECT id FROM cms3_object_field_groups WHERE type_id =(SELECT id FROM cms3_object_types WHERE guid='catalog-object' GROUP BY guid)) 
			ORDER BY cms3_object_field_groups.name") or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("UPDATE cms3_fields_controller_tmp, cms3_object_field_groups SET new_group_id=cms3_object_field_groups.id WHERE cms3_fields_controller_tmp.name=cms3_object_field_groups.name AND cms3_object_field_groups.type_id=".$sql_row['uc_obj_type_id']) or die(SendAnswer("Error: ". mysqli_error()));
			$link->query("INSERT INTO cms3_fields_controller (ord, field_id, group_id) 
			SELECT ord, field_id, new_group_id 
			FROM cms3_fields_controller_tmp 
			WHERE new_group_id NOT IN (SELECT cms3_fields_controller.group_id FROM cms3_fields_controller WHERE cms3_fields_controller.field_id=cms3_fields_controller_tmp.field_id)") or die(SendAnswer("Error: ". mysqli_error()));


			// CREATE TEMPORARY TABLE cms3_fields_controller_tmp ( `field_id` int(11) NOT NULL, `ord` int(11) NOT NULL, `group_id` int(11) NOT NULL, `new_group_id` int(11) NOT NULL, `name` VARCHAR(120) NOT NULL,  KEY `field_id` (`field_id`),  KEY `group_id` (`group_id`),  KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			// INSERT INTO cms3_fields_controller_tmp (field_id, ord, group_id, new_group_id, name)
			// SELECT cms3_fields_controller.field_id, cms3_fields_controller.ord, cms3_fields_controller.group_id, 000000 as new_group_id, cms3_object_field_groups.name 
			// FROM cms3_fields_controller 
			// INNER JOIN cms3_object_field_groups ON cms3_fields_controller.group_id=cms3_object_field_groups.id 
			// WHERE group_id IN (SELECT id FROM cms3_object_field_groups WHERE type_id =(SELECT id FROM cms3_object_types WHERE guid='catalog-object' GROUP BY guid)) 
			// ORDER BY cms3_object_field_groups.name;
			// UPDATE cms3_fields_controller_tmp, cms3_object_field_groups SET new_group_id=cms3_object_field_groups.id WHERE cms3_fields_controller_tmp.name=cms3_object_field_groups.name AND cms3_object_field_groups.type_id=261;
			// INSERT INTO cms3_fields_controller (ord, field_id, group_id) 
			// SELECT ord, field_id, new_group_id 
			// FROM cms3_fields_controller_tmp 
			// WHERE new_group_id NOT IN (SELECT cms3_fields_controller.group_id FROM cms3_fields_controller WHERE cms3_fields_controller.field_id=cms3_fields_controller_tmp.field_id);
			
			//SELECT * FROM cms3_fields_controller_tmp;

		}
	}
	
	// products
	$link->query("DELETE cms3_hierarchy_relations FROM etrade_products JOIN cms3_hierarchy_relations ON etrade_products.uc_hier_id=cms3_hierarchy_relations.child_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	$sql_result = $link->query("SELECT uc_hier_id, uc_obj_id FROM etrade_products WHERE uc_hier_id>0") or die(SendAnswer("Error: ". mysqli_error()));
	while ($sql_row = mysqli_fetch_array($sql_result)) {
		umicms_makeHierarchyRelationsTable($sql_row['uc_hier_id']);
		
		if ((int)$sql_row['uc_obj_id']>0) { // create new default fields for new products
			$link->query("INSERT INTO cms3_object_content (obj_id, field_id) 
				SELECT ".$sql_row['uc_obj_id']." as uc_obj_id, cms3_fields_controller.field_id
				FROM cms3_fields_controller 
				INNER JOIN cms3_object_field_groups ON cms3_fields_controller.group_id=cms3_object_field_groups.id 
				WHERE cms3_fields_controller.group_id IN (SELECT id FROM cms3_object_field_groups WHERE type_id =(SELECT id FROM cms3_object_types WHERE guid='catalog-object' GROUP BY guid)) AND 
				".$sql_row['uc_obj_id']." NOT IN (SELECT cms3_object_content.obj_id FROM cms3_object_content WHERE cms3_object_content.field_id=cms3_fields_controller.field_id)
				GROUP BY cms3_fields_controller.field_id") or die(SendAnswer("Error: ". mysqli_error()));
		}
	}
	

}

function umicms_makeHierarchyRelationsTable($id) {
	global $link;
	
	$parents = umicms_getAllParents($id);
	
	$level = sizeof($parents);
	
	//First-level for every element required
	$link->query("INSERT INTO cms3_hierarchy_relations (rel_id, child_id, level) VALUES (NULL, ".$id.", ".$level.")") or die(SendAnswer("Error: ". mysqli_error()));
	
	foreach($parents as $parent_id) {
		$link->query("INSERT INTO cms3_hierarchy_relations (rel_id, child_id, level) VALUES (".$parent_id.", ".$id.", ".$level.")") or die(SendAnswer("Error: ". mysqli_error()));
	}
}

function umicms_getAllParents($id) {
	global $link;
	
	$parents = Array();
	
	while($id) {
		$result = $link->query("SELECT rel FROM cms3_hierarchy WHERE id = ".$id) or die(SendAnswer("Error: ". mysqli_error()));
		
		if(mysqli_num_rows($result)) {
			list($id) = mysqli_fetch_row($result);
			
			if(!$id) continue;
			if(in_array($id, $parents)) break;	//Infinity recursion
			
			$parents[] = $id;
		} else {
			return false;
		}
	}
	
	return array_reverse($parents);
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
		
		$sql_result = $link->query("UPDATE " . $DB_TablePrefix . "category SET top=1 WHERE parent_id=0 AND category_id IN (SELECT cat_id FROM etrade_cats WHERE row_exist=0)") or die(SendAnswer("Error: ". mysqli_error()));
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
	
	// ������ ���������
	$sql_result = $link->query("SELECT field_value2 as cat_parent_id 
		FROM etrade_cc_filters 
		WHERE row_type='f' AND field_value7='1' 
		GROUP BY cat_parent_id") or die(SendAnswer("Error: ". mysqli_error()));
	
	while ($cat = mysqli_fetch_array($sql_result)) {
		
		// ������ ������������� �� ���������
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
		
		$sql_result = $link->query("UPDATE " . $DB_TablePrefix . "category SET top=1 WHERE parent_id=0 AND category_id IN (SELECT cat_id FROM etrade_cats WHERE row_exist=0)") or die(SendAnswer("Error: ". mysqli_error()));
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
	
	if (is_dir($UploadDirTemp)==false) die(SendAnswer('Error: ��� ����������� ���������, �������� ��������� ����� - '.$UploadDirTemp.', ���������� ����� ����������� �� �������� E-Trade Content Creator � ��� �����.'));
	
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

?>
