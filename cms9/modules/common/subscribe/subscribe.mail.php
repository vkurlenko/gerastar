<?
// интервал рассылки пачек писем по умолчанию (сек.)
// может быть переопределен в настройках системы
if(!isset($_VARS['env']['subscribe_period']))
{
	$_VARS['env']['subscribe_period'] = 30;
}

// кол-во писем в час по умолчанию
// может быть переопределен в настройках системы
if(!isset($_VARS['env']['subscribe_count']))
{
	$_VARS['env']['subscribe_count'] = 50;
}

$domain = $_SERVER['HTTP_HOST'];
$period = $_VARS['env']['subscribe_period']; // интервал рассылки (сек.)


/*  */
$html = "";
foreach($file as $k)
{
	if(strpos($k, "src=\"/userfiles/") !== false)
	{
		$k = str_replace("src=\"/userfiles/", "src=\"http://".$_SERVER['HTTP_HOST']."/userfiles/", $k);				
	}
	
	/*if(strpos($k, "?delItem=") !== false)
	{
		$k = str_replace("?delItem=", "?delItem=".$row['subscribe_mail'], $k);				
	}*/
	
	$html .= $k; 	
}
$text = $html;
$to = '';


// проверяем в какое время закончилась последняя пачка рассылки
$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_presets` 
		WHERE var_name = 'subscribe_count'";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

// если больше, чем $period назад, то запускаем следующую пачку
if(time() > ($row['var_default']) + $period)
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_subscribe` 
			WHERE subscribe_status = '0' 
			ORDER BY id DESC 
			LIMIT 0, ".$_VARS['env']['subscribe_count'];
	$res = mysql_query($sql);
	
	//$Bcc = "";
	while($row = mysql_fetch_array($res))
	{			
		//$Bcc .= "Cc: ".trim($row['subscribe_mail'])."\r\n";
		$sql = "UPDATE `".$_VARS['tbl_prefix']."_subscribe` 
				SET subscribe_status = '1' 
				WHERE subscribe_mail = '".$row['subscribe_mail']."'";
		$res2 = mysql_query($sql);	
		
		$to .= $row['subscribe_mail'].", ";
	}
	
	$sql = "UPDATE `".$_VARS['tbl_prefix']."_presets` 
			SET var_default = ".time()." 
			WHERE var_name = 'subscribe_count'";
			
	$res = mysql_query($sql);
}
?>



