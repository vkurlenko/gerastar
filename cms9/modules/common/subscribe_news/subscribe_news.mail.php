<?
/*function sendMail($mailText, $mail)
{
	global $mail_admin, $from;
	//$s = mail($mail_admin, 'Рассылка', $mailText, "From: ".$from."\nContent-Type: text/html; charset=windows-1251\r\n"."Content-Transfer-Encoding: 8bit\r\n".$Bcc);
	//$s = mail($mail_admin, 'Рассылка', $mailText, "From: ".$from."\nContent-Type: text/html; charset=windows-1251\r\n"."Content-Transfer-Encoding: 8bit\r\n");
	//$s = mail($mail, 'Рассылка', $mailText, "From: ".$from."\nContent-Type: text/html; charset=utf-8\r\n"."Content-Transfer-Encoding: 8bit\r\n");
	$s = mail($mail, 'Рассылка', $mailText, "From: ".$from."\nContent-Type: text/html; charset=utf-8\r\n"."Content-Transfer-Encoding: 8bit\r\n");
	return $s;	
}*/


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
function multipart_mail_2($from, $to, $subject, $text, $cc=null) 
{
       //global $domain; //Не забудьте проинициализировать 
	   $domain = "http://".$_SERVER['HTTP_HOST'];
	   //echo $domain;
       $headers ="From: $from\n";
       //$headers.="To: $to\n";
       if (!is_null($cc))      {

         $headers.="Cc: $cc\n";
       }
       //$headers.="Subject: $subject\n";
       $headers.="Date: ".date("r")."\n";

       $headers.="Return-Path: $from\n";
       $headers.="X-Mailer: zm php script\n";
       $headers.="MIME-Version: 1.0\n";

       $headers.="Content-Type: multipart/alternative;\n";
       $baseboundary="------------".strtoupper(md5(uniqid(rand(), true)));

       $headers.="  boundary=\"$baseboundary\"\n";
       $headers.="This is a multi-part message in MIME format.\n";
       $message="--$baseboundary\n";

       $message.="Content-Type: text/plain; charset=utf-8\n";
       $message.="Content-Transfer-Encoding: 7bit\n\n";
       $text_plain=str_replace('<p>',"\n",$text);

       $text_plain=str_replace('<b>',"",$text_plain);
       $text_plain=str_replace('</b>',"",$text_plain);

       $text_plain=str_replace('<br>',"\n",$text_plain);
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
       $message.=($text)."\n\n";
	   
	   
       //preg_match_all('/img(\s+)src="([^"]+)"/i',$text,$m);
	   preg_match_all('/(\s+)src="([^"]+)"/i',$text,$m);

       if (isset($m[2])) 
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
	   
	   	$attachment_files=$img_f;
		if (is_array($attachment_files)) 
		{
			foreach($attachment_files as $filename)  
			{
				$file_content = file_get_contents($_SERVER['DOC_ROOT']."/".$filename,true);
	  
				$mime_type='image/png';
				if(function_exists("mime_content_type"))  
				{
	            	$mime_type=mime_content_type($filename);
    			}
				else 
				{
	   				$f = getimagesize($_SERVER['DOC_ROOT']."/".$filename);
					switch ($f[2])    
			   		{
                       case 'jpg': $mime_type='image/jpeg';break;
                       case 'gif': $mime_type='image/gif';break;
                       case 'png': $mime_type='image/png';break;
                       default:;
               		}
       			}
							
				$message=str_replace($domain.'/'.$filename,'cid:'.basename($filename),$message);
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
		
		return mail($to, $subject, $message , $headers);
}













$domain = $_SERVER['HTTP_HOST'];
$period = $_VARS['env']['subscribe_period']; // интервал рассылки (сек.)



		
$filter = "";

/*if(isset($_GET['param2'])) 
{	// если задан диапазон дат, то применяем фильтр по дате
	$filter = " AND news_date LIKE '%".$_GET['param2']."%'";
}*/

$sql = "SELECT * FROM `".$_VARS['tbl_news']."`
		WHERE news_show = '1' 
		AND news_mark = '0' ".$filter." 
		AND news_date LIKE '%".date('Y-m-d')."%'
		ORDER BY news_date DESC";
$res_article = mysql_query($sql);
echo $sql;

if(mysql_num_rows($res_article) > 0)
{
	
	// сформируем текст рассылки
	$html = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	</head>
	<body>
	<h3>Новостная рассылка сайта ".$_SERVER['HTTP_HOST']."</h3>
	";
	
	while($row_article = mysql_fetch_array($res_article))
	{
		switch($row_article['news_cat'])
		{
			case "actions" : $news_url = "action_one"; break;
			default : $news_url = "news_one"; break;
		}
		$html .= '<p><strong>'.$row_article['news_title'].'</strong>
					<br>'.$row_article['news_text_1'].'<br>
					<a href="http://'.$_SERVER['HTTP_HOST'].'/'.$news_url.'/'.$row_article['id'].'/">Далее</a></p>';
	}	
	$html .= "<a href='http://".$_SERVER['HTTP_HOST']."/'>Перейти на сайт ".$_SERVER['HTTP_HOST']."</a>
	</body></html>";
	
	
	// проверяем в какое время закончилась последняя пачка рассылки
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_presets` 
			WHERE var_name = 'subscribe_news_count'";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	// если больше, чем $period назад, то запускаем следующую пачку
	//echo time() .">". ($row['var_default'] + $period);
	if(time() > ($row['var_default'] + $period))
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_subscribe_news` 
				WHERE subscribe_status = '0' 
				ORDER BY id DESC 
				LIMIT 0, ".$_VARS['env']['subscribe_news_count'];
		$res = mysql_query($sql);
		//echo $sql;
		//$Bcc = "";
		
		while($row = mysql_fetch_array($res))
		{			
			//$Bcc .= "Cc: ".trim($row['subscribe_mail'])."\r\n";
			$sql = "UPDATE `".$_VARS['tbl_prefix']."_subscribe_news` 
					SET subscribe_status = '1' 
					WHERE subscribe_mail = '".$row['subscribe_mail']."'";
			$res2 = mysql_query($sql);	
			
		
			$text = $html;
			multipart_mail_2($_VARS['env']['mail_admin'], $row['subscribe_mail'], "Рассылка новостей", $text, $cc=null);
			
		}
		
		$sql = "UPDATE `".$_VARS['tbl_prefix']."_presets` 
				SET var_default = ".time()." 
				WHERE var_name = 'subscribe_news_count'";
		$res = mysql_query($sql);
	}
}
//else echo "Нет свежих новостей для рассылки";





?>



