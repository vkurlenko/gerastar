<?

class Subscribe
{
	public $tblPrefix	= '';	// префикс БД
	public $modeTest	= false;// тестовая рассылка
	public $modeStop	= false;// тестовая рассылка
	public $period		= 30;	// период рассылки (сек)
	public $mailCount 	= 50;	// кол-во писем в пачке за одну рассылку
	public $lastSend	= false; // время последней рассылки
	public $arrTo 		= array(); // массив адресов для текущей пачки рассылки
	public $id			= '';	// id подписчика в базе адресов
	
	public $f = '';	// url страницы-листа рассылки
	

	public $from 	= ''; 		// от кого 	
	public $to		= ''; 		// кому
	public $subject = 'тема';	// тема
	public $cc;					// копия

	public $text	= '';		// текст письма (html)
	public $un		= ''; 		// ссылка на удаление email из базы подписчиков (отписаться)
	
	public $error	= array();	// массив сообщений об ошибках
	 
	 
	 
	 
	// получим время предыдущей рассылки
	public function getLastSend()
	{
		$sql = "SELECT * FROM `".$this -> tblPrefix."_presets` 
				WHERE var_name = 'subscribe_count'";
		
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_array($res);
			$this -> lastSend = $row['var_default'];
		}
		else
		{
			$this -> error[] = 'Ошибка выполнения запроса: '.$sql;
			$this -> lastSend = false;
		}				
	}
	
	
	
	// получим массив адресов для очередной пачки рассылки
	function getTo()
	{
		if(time() > ($this -> lastSend + $this -> period))
		{
			$sql = "SELECT * FROM `".$this -> tblPrefix."_subscribe` 
					WHERE subscribe_status = '0' 
					ORDER BY id DESC 
					LIMIT 0, ".$this -> mailCount;
			$res = mysql_query($sql);
			
			if($res && mysql_num_rows($res) > 0)
			{
				while($row = mysql_fetch_array($res))
				{			
					// запишем в массив email'ы для рассылки
					$this -> arrTo[] = array(
						'id' 	=> $row['id'],
						'email' => $row['subscribe_mail']
					);								
				}
			}
			else
			{
				$this -> error[] = 'Ошибка выполнения запроса: '.$sql;
			}
		}
		else
		{
			$this -> error[] = 'Не выдержан интервал: '.$this -> period;
		}	
	}
	
	
	// получим текст сообщения 
	function getText()
	{
		if(isset($this -> f) && trim($this -> f) != '')
		{
			// прочитаем файл рассылки в массив
			$file = file($this -> f);
						
			if(!$file) 
				$this -> error[] = "Ошибка открытия файла $f";
			else
			{
				$this -> text = '';

				foreach($file as $k)
				{
					if(strpos($k, "src=\"/userfiles/") !== false)
					{
						$k = str_replace("src=\"/userfiles/", "src=\"http://".$_SERVER['HTTP_HOST']."/userfiles/", $k);				
					}
					
					if(strpos($k, "src=\"/upload/") !== false)
					{
						$k = str_replace("src=\"/upload/", "src=\"http://".$_SERVER['HTTP_HOST']."/upload/", $k);				
					}
					
					$k = str_replace("%28", "(", $k);	
					$k = str_replace("%29", ")", $k);	
					
					$this -> text .= $k; 
				}				
			}
		}
		else
			$this -> error[] = 'Нет файла '.$this -> f;
	}
	
	
	// сделаем очередную рассылку
	function doSend()
	{
		$s = '';
		$i = 0;
		
		$this -> getText();
		
		foreach($this -> arrTo as $k => $v)
		{
			$this -> to = $v['email'];
			$this -> id = $v['id'];
			
			$this -> makeUnLink();
			
			if($this -> multipart_mail())
			{
				$s .= "'".$v['email']."'";
				$i++;				
				if($i < count($this -> arrTo))
					$s .= ', ';				
			}	
			else
				$this -> error[] = 'Не удалось отправить на адрес '.$v['email'];		
			
		}	
		
		if($s != '')
		{
			// пометим записи с этими адресами как status = 1
			$sql = "UPDATE `".$this -> tblPrefix."_subscribe` 
					SET subscribe_status = '1' 
					WHERE subscribe_mail in (".$s.")";
					
			$res = mysql_query($sql);
			
			// обновим время последней рассылки
			$sql = "UPDATE `".$this -> tblPrefix."_presets` 
					SET var_default = ".time()." 
					WHERE var_name = 'subscribe_count'";
					
			$res = mysql_query($sql);
		}
		else
			$this -> error[] = 'Не отправлено ни одного письма';
	}
	
	
	// генерация ссылки на отказ от рассылки
	function makeUnLink()
	{		
		$this -> un = '<div style="padding:20px; color:#ffffff; font-size:13px; width: 978px;margin: auto;background: #6B6B6F;font-family: tahoma;">Если вы не хотите получать рассылку, пройдите <a style="color:#F37F88" href="http://'.$_SERVER['HTTP_HOST'].'/subscribe/delete/'.$this -> id.'/">по ссылке</a></div>';		
	}
	
	
	
	function stop()
	{
		// пометим записи с этими адресами как status = 1
		$sql = "UPDATE `".$this -> tblPrefix."_subscribe` 
				SET subscribe_status = '1' 
				WHERE 1";
				
		$res = mysql_query($sql);
	}
 
	function multipart_mail() 
	{
		/**
		* отправка письма с вложениями
		*
		* @param string $from от кого
		* @param string $to кому
		
		* @param string $subject тема
		* @param string $text собственно текст (html)
		* @param string $cc копия
		* @return unknown
		*/
	
		$NL = "\n";
		//global $domain; //Не забудьте проинициализировать 
		$domain	 = "http://".$_SERVER['HTTP_HOST'];
		
		$headers ="From: ".$this -> from.$NL;
		//$headers.="To:".$this -> to.$NL;
		if(!is_null($this -> cc))      
		{	
			$headers.="Cc: ".$this -> cc.$NL;
		}
		//$headers.="Subject: ".$this -> subject.$NL;
		$headers.="Date: ".date("r").$NL;
		
		$headers.="Return-Path: ".$this -> from.$NL;
		$headers.="X-Mailer: zm php script\n";
		$headers.="MIME-Version: 1.0\n";
		
		$headers.="Content-Type: multipart/alternative;\n";
		$baseboundary="------------".strtoupper(md5(uniqid(rand(), true)));
		
		$headers.="  boundary=\"$baseboundary\"\n";
		$headers.="This is a multi-part message in MIME format.\n";
		$message="--$baseboundary\n";
		
		$message.="Content-Type: text/plain; charset=utf-8\n";
		$message.="Content-Transfer-Encoding: 7bit\n\n";
		$text_plain=str_replace('<p>',"\n", $this -> text);
		
		$text_plain=str_replace('<b>',"",$text_plain);
		$text_plain=str_replace('</b>',"",$text_plain);
		
		$text_plain=str_replace('<br>',"\n",$text_plain);
		$text_plain = str_replace('%28','(', $text_plain);
		$text_plain= preg_replace('/<a(\s+)href="([^"]+)"([^>]+)>([^<]+)/i',"\$4\n\$2",$text_plain);
		$message.=strip_tags($text_plain);
		
		//
		$message.="\n\nIts simple text. Switch to HTML view!\n\n";
		
		$message.="--$baseboundary\n";
		$newboundary="------------".strtoupper(md5(uniqid(rand(), true)));
		
		$message.="Content-Type: multipart/related;\n";
		$message.="  boundary=\"$newboundary\"\n\n\n";
		$message.="--$newboundary\n";
		$message.="Content-Type: text/html; charset=utf-8\n";
		
		$message.="Content-Transfer-Encoding: 7bit\n\n";
		$message.=($this -> text);
		$message.= $this -> un."\n\n"; // добавим к тексту ссылку на отписку
		
		//preg_match_all('/img(\s+)src="([^"]+)"/i',$text,$m);
		preg_match_all('/(\s+)src="([^"]+)"/i', $this -> text,$m);
		
		if(isset($m[2])) 
		{
		   $img_f=$m[2];
		   if (is_array($img_f)) 
		   {
			   foreach ($img_f as $k => $v) 
			   {
				   $img_f[$k]=str_ireplace($domain.'/','',$v);
			   }
		   }
		}		
		
		
		/* прикрепленные файлы */
		$attachment_files=$img_f;
		
		if (is_array($attachment_files)) 
		{
			foreach($attachment_files as $filename)  
			{
			
				$filename = urldecode($filename);
				
				$filename = str_replace(array('%28', '%29'), array('(', ')'), $filename);
			
				$file_content = file_get_contents($_SERVER['DOCUMENT_ROOT']."/".$filename,true);
		
				$mime_type='image/png';
				if(function_exists("mime_content_type"))  
				{
					$mime_type=mime_content_type($filename);
				}
				else 
				{
					$f = getimagesize($_SERVER['DOCUMENT_ROOT']."/".$filename);
					switch ($f[2])    
					{
					   case 'jpg': $mime_type='image/jpeg';break;
					   case 'gif': $mime_type='image/gif';break;
					   case 'png': $mime_type='image/png';break;
					   default:;
					}
				}
							
				$message=str_replace($domain.'/'.$filename, 'cid:'.basename($filename), $message);
				$filename=basename($filename);
				$message.="--$newboundary\n";
				$message.="Content-Type: $mime_type;\n";
				$message.=" name=\"$filename\"\n";
				
				$message.="Content-Transfer-Encoding: base64\n";
				$message.="Content-ID: <$filename>\n";
				$message.="Content-Disposition: inline;\n";
				$message.=" filename=\"$filename\"\n\n";
				
				$message.=chunk_split(base64_encode($file_content));
			}
		}
		$message.="--$newboundary--\n\n";
		$message.="--$baseboundary--\n";
		/* /прикрепленные файлы */
		
		
		
		
		return mail($this -> to, $this -> subject, $message , $headers);
	}
}



?> 