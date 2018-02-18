<?

/*~~~ определяем расширение файла ~~~*/
function ext($f)
{
	$file_info = getimagesize($f);
	switch ($file_info[2])
	{
		case 1 : $ext = "gif"; break;
		case 2 : $ext = "jpg"; break;
		case 3 : $ext = "png"; break;
		default : break;			
	}
	return $ext;
}



/*~~~ создаем папку для фотоальбома ~~~*/
/*function CreateFolder($zhanr)
{
	mkdir($_SERVER['DOCUMENT_ROOT']."/photo".$zhanr);
	chmod($_SERVER['DOCUMENT_ROOT']."/photo".$zhanr, 0777);
}*/



/*~~~~~~~~ сохраняем с расширением ~~~~~~~~~~~*/
function saveImage($f, $folder, $name)
{
	$file_info = getimagesize($f);
	
	switch ($file_info[2])
	{
		case 1 : $src = imagecreatefromgif($f); break;
		case 2 : $src = imagecreatefromjpeg($f); break;
		case 3 : $src = imagecreatefrompng($f); break;
		default : $src = imagecreatefromjpeg($f); break;
	}
	
	$im = imagecreatetruecolor($file_info[0], $file_info[1]);
	
	// сохраняем прозрачность для png-24
	imageAlphaBlending($im, false);
	imagesavealpha($im, true);
	
	imagecopyresampled($im, $src, 0, 0, 0, 0, $file_info[0], $file_info[1], $file_info[0], $file_info[1]);
	
	switch ($file_info[2])
	{
		case 1 : imagegif($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".gif"); break;
		//case 2 : imagejpeg($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".jpg", 100); break;
		case 3 : imagepng($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".png"); break;
		default : imagejpeg($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".jpg", 100); break;
	}
	
	/*imagejpeg($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".jpg", 100);*/
}
/*~~~~~~~~ //конвертируем файл в jpeg и сохраняем с расширением ~~~~~~~~~~~*/

/*	изменение порядка следования записей	*/
function MoveItem($id, $direction, $alb_id)
{
	global $_VARS;
	
	$order_field = "order_by";
	
	if($direction == "asc") 
		$arrow = ">";
	else
		$arrow = "<";
	
	$sql = "SELECT * FROM `".SITE_PREFIX."_pic` 
			WHERE id=".$id."
			AND alb_id = ".$alb_id;
			
	//echo $sql."<br>";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);

		$sql = "SELECT * FROM `".SITE_PREFIX."_pic` 
				WHERE (alb_id = ".$alb_id." AND ".$order_field." ".$arrow." ".$row[$order_field].") 
				ORDER BY ".$order_field." ".$direction." 
				LIMIT 1";
		//echo $sql."<br>";
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row_2 = mysql_fetch_array($res);
		
			$sql = "UPDATE `".SITE_PREFIX."_pic` 
					SET ".$order_field."=".$row_2[$order_field]." 
					WHERE id=".$id;
			//echo $sql."<br>";
			$res = mysql_query($sql);
			
			$sql = "UPDATE `".SITE_PREFIX."_pic` 
					SET ".$order_field."=".$row[$order_field]." 
					WHERE id=".$row_2['id'];
			//echo $sql."<br>";
			$res = mysql_query($sql);
		}
		
	}
	
	
}

// создадим массив загруженных файлов
function makeArrFiles()
{
	/*printArray($_FILES);
	echo count($_FILES['small']);*/
	
	$arr = array();
	
	if(is_array($_FILES['small']['tmp_name']))
	{
		foreach($_FILES['small'] as $param => $v)
		{
			foreach($_FILES['small'][$param] as $k => $v)
			{
				$arr[$k][$param] = $v;
			}
		}			
	}
	else
	{
		foreach($_FILES['small'] as $param => $v)
		{
			$arr[$param] = $v;
		}	
	}
	
	return $arr;
	
}


/*	список всех альбомов	*/
function getAlbList()
{
	global $_VARS;
	
	$sql = "SELECT * FROM `".$_VARS['tbl_photo_alb_name']."`
			WHERE 1
			ORDER BY alb_order ASC";
			
	$res = mysql_query($sql);
	
	$arrAlb = array();
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$arrAlb[$row['id']] = $row['alb_title'];
		}
	}

	
	return $arrAlb;
}
/*	/список всех альбомов	*/

function del($id)
{
	global $_VARS, $parent_folder, $page, $alb_id;

	$sql = "DELETE FROM `".SITE_PREFIX."_pic` 
			WHERE id = '$id'";
	$res = mysql_query($sql);	
	
	$dir_name = $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$alb_id";
	$dir = opendir($dir_name);
	chdir($dir_name);
	while($file = readdir($dir))
	{
		//$template = "[^".$id."-?\w*(-mono)?.(jpg|gif|png)$]";
		$template = "[^".$id."(-\w*(-mono)?)?.(jpg|gif|png)$]";
		$result = preg_match($template, $file); 
		if($result)	
		{
			//echo "Удаление файла ".$file.'<br>';
			unlink($file);
		}		
	}
	
	
	header("Location: ?page=$page&alb_id=$alb_id");
	
	exit;
}
?>