<?php


/**
 * @version 0.1
 * @author recens
 * @license GPL
 * @copyright Гельтищева Нина (http://recens.ru)
 */

/**
* Масштабирование изображения
*
* Функция работает с PNG, GIF и JPEG изображениями.
* Масштабирование возможно как с указаниями одной стороны, так и двух, в процентах или пикселях.
*
* @param string Расположение исходного файла
* @param string Расположение конечного файла
* @param integer Ширина конечного файла
* @param integer Высота конечного файла
* @param bool Размеры даны в пискелях или в процентах
* @return bool
*/
function resize($file_input, $file_output, $w_o, $h_o, $percent = false) 
{
	list($w_i, $h_i, $type) = getimagesize($file_input);
	if (!$w_i || !$h_i) {
		echo 'Невозможно получить длину и ширину изображения';
		return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {
    	$func = 'imagecreatefrom'.$ext;
    	$img = $func($file_input);
    } else {
    	echo 'Некорректный формат файла';
		return;
    }
	if ($percent) {
		$w_o *= $w_i / 100;
		$h_o *= $h_i / 100;
	}
	if (!$h_o) $h_o = $w_o/($w_i/$h_i);
	if (!$w_o) $w_o = $h_o/($h_i/$w_i);
	$img_o = imagecreatetruecolor($w_o, $h_o);
	imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
	if ($type == 2) {
		imagejpeg($img_o,$file_output,100);
	} else {
		$func = 'image'.$ext;
		$func($img_o,$file_output);
	}
	imagedestroy($img_o);
}

/**
* Обрезка изображения
*
* Функция работает с PNG, GIF и JPEG изображениями.
* Обрезка идёт как с указанием абсоютной длины, так и относительной (отрицательной).
*
* @param string Расположение исходного файла
* @param string Расположение конечного файла
* @param array Координаты обрезки
* @param bool Размеры даны в пискелях или в процентах
* @return bool
*/
function crop($file_input, $file_output, $file_output2, $alb, $crop = 'square',$percent = false) 
{

	global $_VARS;
	
	list($w_i, $h_i, $type) = getimagesize($_SERVER['DOCUMENT_ROOT'].$file_input);	
	
	
	//echo $_SERVER['DOCUMENT_ROOT'].$file_input;
	if (!$w_i || !$h_i) 
	{
		echo 'Невозможно получить длину и ширину изображения';
		return;
    }
	
    $types = array('','gif','jpeg','png');
	
    $ext = $types[$type];
	
    if ($ext) 
	{
    	$func = 'imagecreatefrom'.$ext;
    	$img = $func($_SERVER['DOCUMENT_ROOT'].$file_input);
    } 
	else 
	{
    	echo 'Некорректный формат файла';
		return;
    }
	
	if ($crop == 'square') 
	{
		$min = $w_i;
		if ($w_i > $h_i) $min = $h_i;
		$w_o = $h_o = $min;
	} 
	else 
	{
		list($x_o, $y_o, $w_o, $h_o) = $crop;
		
		if ($percent) 
		{
			$w_o *= $w_i / 100;
			$h_o *= $h_i / 100;
			$x_o *= $w_i / 100;
			$y_o *= $h_i / 100;
		}
		
    	if ($w_o < 0) $w_o += $w_i;
	    $w_o -= $x_o;
		
	   	if ($h_o < 0) $h_o += $h_i;
		$h_o -= $y_o;
	}
	
	$img_o = imagecreatetruecolor($w_o, $h_o);
	
	imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
	
	//echo SITE_PREFIX;
	
	/*~~~~~~~~~~~~~~~~~~~~~*/
	
	$name = explode('/', $file_output2);
	$n = $name[count($name) - 1];
	$fn = explode('.', $n);
	
	$ext == 'jpeg' ? $ext = 'jpg' : $ext = $ext;
	
	// ORDER BY
	$sql = "SELECT MAX(order_by) AS max_order 
			FROM `".SITE_PREFIX."_pic` 
			WHERE alb_id = $alb";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	$max_order = $row['max_order'] + 1;
	
	
	/* сделаем запись в таблицу чтобы узнать id загружаемой картинки и сформировать ее имя */
	$sql = "INSERT INTO `".SITE_PREFIX."_pic`
			(alb_id, file_ext, name,  order_by, img_create)
			VALUES ($alb, '$ext', '$n', $max_order, '".date('Y-m-d')."')";
	$res = mysql_query($sql);	
	
	$name[count($name) - 1] = mysql_insert_id().'.'.$ext;
	
	$file_output2 = implode('/', $name);	
	
		
	/*~~~~~~~~~~~~~~~~~~~~~*/
	$s = false;
	
	if ($type == 2) 
	{
		
		$s = imagejpeg($img_o,$_SERVER['DOCUMENT_ROOT'].$file_output2,90);	
		imagejpeg($img_o,$_SERVER['DOCUMENT_ROOT'].$file_output,90);
	} 
	else 
	{
		$func = 'image'.$ext;
		
		$s = $func($img_o,$_SERVER['DOCUMENT_ROOT'].$file_output2);
		$func($img_o,$_SERVER['DOCUMENT_ROOT'].$file_output);
	}
	
	if(!$s)
	{
		// если сохранить картинку не получилось, то удалим запись о ней из БД
		$sql = "DELETE FROM `".SITE_PREFIX."_pic`
				WHERE id = ".mysql_insert_id();
		$res = mysql_query($sql);
	}
	
	
	
	imagedestroy($img_o);
}


?>