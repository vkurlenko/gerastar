<?
/*~~~~~~~~~~~~~~~~~~~~~*/
/*  класс изображений  */
/*~~~~~~~~~~~~~~~~~~~~~*/
	
class Image
{
	public $imgCatalogId;			// id альбома с изображением в БД;
	public $imgId;	 				// id изображения в БД;
	
	public $imgPathAbs;				// абсолютный путь к изображению;
	public $imgPath;				// относительный путь к изображению;
	
	public $imgTransform 	= "crop";	// метод преобразования (crop - обрезание до заданных размеров, 
										// resize - ресайз до заданных размеров с полями)
										
	public $imgSize;				// задаваемые размеры изображения array(maxWidth, maxHeight)
	
	// атрибуты тега IMG
	public $imgAttrId		= "";	// атрибут id
	public $imgAlt 			= "";	// атрибут alt
	public $imgAlign 		= "";	// атрибут align
	public $imgTitle 		= "";	// атрибут title
	public $imgClass 		= "";	// атрибут class
	public $imgRel 			= "";	// атрибут rel
	public $imgW			= '';	// атрибут width
	public $imgH			= ''; 	// атрибут height
	public $imgLongdesc 	= "";	// атрибут longdesc
		
	
	public $imgMakeGrayScale= false;	// делать ли черно-белую копию (false|true)
	public $imgGrayScale 	= false;//показать черно-белую копию (false|true)
	public $imgWidthMax 	= 100;	// максимальная ширина нового изображения
	public $imgHeightMax 	= 100;	// максимальная высота нового изображения
	public $imgJpegQuality 	= 90;	// качество Jpeg
	public $imgWaterMark 	= "";	// картинка водяной знак
	
	
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* определим абсолютный путь к изображению */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	public function absPath()
	{
		global $_VARS;
		
		/*~~~ считывание из БД инфы об изображении ~~*/
		$sql_img = "SELECT * FROM `".$_VARS['tbl_photo_name'].$this -> imgCatalogId."` 
					WHERE id = ".$this -> imgId;
		//echo $sql_img;
		$res_img = mysql_query($sql_img);
		$row_img = mysql_fetch_array($res_img);
		$ext = $row_img['file_ext'];
		
		$absPath = $_SERVER['DOC_ROOT']."/".$_VARS['photo_alb_dir']."/".$_VARS['photo_alb_sub_dir'].$this -> imgCatalogId."/".$this -> imgId.".".$ext;
				
		return $absPath;
	}	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* определим относительный путь к изображению */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	public function relPath()
	{
		global $_VARS;
		
		/*~~~ считывание из БД инфы об изображении ~~*/
		$sql_img = "SELECT * FROM `".$_VARS['tbl_photo_name'].$this -> imgCatalogId."` 
					WHERE id = ".$this -> imgId;
		//echo $sql_img;
		$res_img = mysql_query($sql_img);
		$row_img = mysql_fetch_array($res_img);
		$ext = $row_img['file_ext'];
		
		$relPath = "/".$_VARS['photo_alb_dir']."/".$_VARS['photo_alb_sub_dir'].$this -> imgCatalogId."/".$this -> imgId.".".$ext;
				
		return $relPath;
	}	
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* определим размеры изображения к изображению */
	/* строка вида width="xxx" height="xxx"		   */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	public function picSize()
	{
		$file = $this->absPath();
		if(file_exists($file))
		{
			$fileInfo = getimagesize($file);
			return $fileInfo[3];
		}
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* вывод в браузер картинки с заданными размерами */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	public function showPic()
	{
		global $_VARS;
		
		// найдем в БД запись о заданной картинке
		$sql_img = "SELECT * FROM `".$_VARS['tbl_photo_name'].$this -> imgCatalogId."` 
					WHERE id = ".$this -> imgId;
		//echo $sql_img;
		$res_img = mysql_query($sql_img);
		if($res_img && mysql_num_rows($res_img ) > 0)
		{
			$row_img = mysql_fetch_array($res_img);
			$ext = $row_img['file_ext'];
			
			
			// путь к новому изображению
			$imgNewName = $this -> imgId."-".$this -> imgWidthMax."x".$this -> imgHeightMax.".".$ext;
			$imgNewNameMono = $this -> imgId."-".$this -> imgWidthMax."x".$this -> imgHeightMax."-mono.".$ext;
						
			
			// путь к файлу от корня сервера
			$path = $_VARS['photo_alb_dir']."/".$_VARS['photo_alb_sub_dir'].$this -> imgCatalogId."/".$imgNewName;
			$pathMono = $_VARS['photo_alb_dir']."/".$_VARS['photo_alb_sub_dir'].$this -> imgCatalogId."/".$imgNewNameMono;
			$this -> imgPath = $path;
			
			// если файла не существует создадим его
			if(!file_exists($_SERVER['DOC_ROOT']."/".$path))
			{
				$this -> imageCreateNew();
			}
			
			if($this->imgGrayScale) 
				$path = $pathMono;
			
			$fileInfo = getimagesize($_SERVER['DOC_ROOT']."/".$path);	
			$this -> imgW = $fileInfo[0];
			$this -> imgH = $fileInfo[1];
			
			
			// если атрибут alt указан как DEFAULT, то берем в качестве alt название картинки
			if($this->imgAlt == "DEFAULT") $this->imgAlt = $row_img['name'];
			if($this->imgTitle == "DEFAULT") $this->imgTitle = $row_img['name'];
			
			$html = "<img id='$this->imgAttrId' src='/$path' class='$this->imgClass' rel='$this->imgRel' longdesc='$this->imgLongdesc' $fileInfo[3] title='$this->imgTitle' alt='$this->imgAlt' align='$this->imgAlign' />";
			
		}
		else $html = "Изображение не найдено.";
		
		return $html;		
					
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* создание черно-белой копии картинки */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	public function grayscale($filename, $mono_filename)
	{
	
		//echo $filename;
		//Получаем размеры изображения
		$img_size 	= getimagesize($filename);
		//echo $img_size;
		$width 		= $img_size[0];
		$height 	= $img_size[1];
		
		
		//Создаем новое изображение с такмими же размерами
		$img_mono = imagecreate($width,$height);
		
		
		//Задаем новому изображению палитру "оттенки серого" (grayscale)
		for($c = 0; $c < 256; $c++) 
		{
			imagecolorallocate($img_mono, $c,$c,$c);
		}
		
		//Содаем изображение из файла Jpeg
		switch ($img_size[2])
		{
			case 1	: $img2 = imagecreatefromgif	($filename); break;
			case 2	: $img2 = imagecreatefromjpeg($filename); break;
			case 3 	: $img2 = imagecreatefrompng	($filename); break;
			default : $img2 = imagecreatefromjpeg($filename); break;
		}
		//$img2 = imagecreatefromjpeg($filename);
		
		//Объединяем два изображения
		imagecopymerge($img_mono,$img2,0,0,0,0, $width, $height, 100);
		
		//Сохраняем полученное изображение
		imagejpeg($img_mono, $mono_filename);
		
		//Освобождаем память, занятую изображением
		imagedestroy($img_mono);
	}	
	
	

	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* преобразование картинки до заданных размеров с сохранением на диск */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	function imageCreateNew()
	{
		// имя файла
		$name = basename($this->absPath());	
		$arr_name = explode(".", $name, 2);
		
		// имя мини-файла 
		$name_mini = $arr_name[0]."-".$this -> imgWidthMax."x".$this -> imgHeightMax.".".$arr_name[1];	
		
		// имя мини-файла в монохроме
		$name_mini_mono	= $arr_name[0]."-".$this -> imgWidthMax."x".$this -> imgHeightMax."-mono.".$arr_name[1];
		
		// папка файла	
		$folder	= dirname($this->absPath());
		
		// определяем графический формат файла и его размеры
		$file_type = getimagesize($this->absPath());
		switch ($file_type[2])
		{
			case 1	: $src = imagecreatefromgif	($this->absPath()); break;
			case 2	: $src = imagecreatefromjpeg($this->absPath()); break;
			case 3 	: $src = imagecreatefrompng	($this->absPath()); break;
			default : $src = imagecreatefromjpeg($this->absPath()); break;
		}
		
		// размеры исходного изображения
		$w_src = imagesx($src); 
		$h_src = imagesy($src);
		$ratio_src = $w_src / $h_src;
		
		// размеры нового изображения
		$w = $this -> imgWidthMax;  
		$h = $this -> imgHeightMax;	
		$ratio = $w / $h;
		
		// жестко задаем размеры выходного изображения
		$k = $w_src / $w;
		$w_min = ceil($w_src / $k);
		$h_min = ceil($h_src / $k);
		
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		/*~~~ трансформация картинки ~~~*/
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		switch($this -> imgTransform)
		{
			case "resize" 	: 
				$w_min = $w_src;
				$h_min = $h_src;
					
				if($w_min > $w || $h_min > $h)
				{
					if($w_min > $w)
					{
						$w_min = $w;
						$h_min = ceil($h_min / $k);
					}
					//echo $w_min."x".$h_min."<br>";
					if($h_min > $h)
					{
						$n = $h_min / $h;
						$h_min = $h;
						
						$w_min = ceil($w_min / $n);
					}
					//echo $w_min."x".$h_min."<br>";
				}
				else
				{}
				
				
				// создаём пустую картинку 
				$dest = imagecreatetruecolor($w_min, $h_min);	
				imageAlphaBlending($dest, false);
				
				imagesavealpha($dest, true);
				//echo "$dest, $src, 0, 0, $x_copy, $y_copy, $w, $h, $w_copy, $h_copy";
				//resource dst_im, resource src_im, int dstX, int dstY, int srcX, int srcY, int dstW, int dstH, int srcW, int srcH
				imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_min, $h_min, $w_src, $h_src); 
				if($this -> imgWaterMark != "")
				{
					$dest = $this -> addWaterMark($dest, $w_min, $h_min);	
					//echo $dest;
					//exit;		
				}
				
				break;
			
			
			
			
			
			
			
			case "crop"		: 
				if($w_min < $w or $h_min > $h)
				{
					$crop = "h";
				}
				elseif($h_min < $h)
				{
					$crop = "w";
				}
				else
				{
					$crop = "none";
				}
				
				switch($crop)
				{
					case "h" :	$w_copy = $w_src;
								$x_copy = 0;
								$h_copy = $w_src / $ratio;
								$y_copy = ($h_src / 2) - ($h_copy / 2);
								break;
								
					case "w" :	$h_copy = $h_src;
								$y_copy = 0;
								$w_copy = $h_src * $ratio;
								$x_copy = ($w_src / 2) - ($w_copy / 2);
								break;
				
					default : 	$w_copy = $w_src;
								$x_copy = 0;
								$h_copy = $w_src / $ratio;
								$y_copy = ($h_src / 2) - ($h_copy / 2);
								break;
				}
				// создаём пустую картинку 
				$dest = imagecreatetruecolor($w, $h);	
				imageAlphaBlending($dest, false);
				
				imagesavealpha($dest, true);
				//echo "$dest, $src, 0, 0, $x_copy, $y_copy, $w, $h, $w_copy, $h_copy";
				imagecopyresampled($dest, $src, 0, 0, $x_copy, $y_copy, $w, $h, $w_copy, $h_copy);
				
				if($this -> imgWaterMark != "")
				{
					$dest = $this -> addWaterMark($dest, $w, $h);	
					//echo $dest;
					//exit;		
				}			
				break;
				
				default: break;
		}
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		/*~~~ /трансформация картинки ~~*/
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		
		//echo 'wm';
		/*if($this -> imgWaterMark != "")
		{
			$dest = $this -> addWaterMark($dest, $w, $h);	
			//echo $dest;
			//exit;		
		}*/
		/*else 
			echo 'no';*/
		
		// сохраняем обработанную картинку на диск
		switch ($file_type[2])
		{
			case 1 :	imagegif($dest, $folder."/".$name_mini);
						break;
						
			case 2 :	imagejpeg($dest, $folder."/".$name_mini, $this -> imgJpegQuality);
						break;
						
			case 3 :	imagepng($dest, $folder."/".$name_mini);
						break;
						
			default :	imagejpeg($dest, $folder."/".$name_mini, $this -> imgJpegQuality);
						break;
		}
		
		// создаем ч/б копию
		if($this -> imgMakeGrayScale)
		{
			$this -> grayscale($folder."/".$name_mini, $folder."/".$name_mini_mono); 
		}	
	}	
	
	
	
	
	
	function addWaterMark($dest, $w_copy, $h_copy)
	{
	
		$wmFile = $_SERVER['DOCUMENT_ROOT'].$this -> imgWaterMark;
		//$dest = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'].'/img/pic/banner_small_1.jpg');
		/*imageAlphaBlending($dest, true);
			imagesavealpha($dest, true);	*/
				
		if(file_exists($wmFile))
		{
			$wm = imagecreatefrompng($wmFile);
			/*imageAlphaBlending($wm, true);
			imagesavealpha($wm, true);	*/	
		
		}
		else 
		{
			return $dest;
			//echo 'File not found';
		}
		
		// imagecreatetruecolor - создаёт новое изображение true color
		//echo $dest_file_type[0];
		$image = imagecreatetruecolor($w_copy, $h_copy);
		$white = imagecolorallocate($image, 255, 255, 255);
		imagefilledrectangle ($image, 0,0, $w_copy, $h_copy, $white);
		/*imageAlphaBlending($image, false);
		imagesavealpha($image, true);	*/	
				
		
		/*imagesettile($image, $wm);
		imagefilledrectangle($image, 0, 0, $dest_file_type[0], $dest_file_type[1], IMG_COLOR_TILED);*/
		//imagecopyresampled ($image, $wm, 0, 0, 0, 0, 200,200,200,200);
		imagecopyresampled($image, $dest, 0, 0, 0, 0, $w_copy, $h_copy,$w_copy, $h_copy);
		imagecopyresampled($image, $wm, 0, 0, 0, 0, $w_copy, $h_copy, $w_copy, $h_copy);
		//imagecopy($dest, $wm, 0, 0, 0, 0, 200, 200); 
		

		return $image;
		// imagepng($image, $_SERVER['DOCUMENT_ROOT'].'/1.png');
		
	}
}
?>