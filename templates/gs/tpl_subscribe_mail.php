<?

$arr = array(
	'title' 	=> $_PAGE['p_title'],
	'content'	=> ':::content:::'
);



$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_subscribe_content`
		WHERE text_active = '1'
		ORDER BY id DESC
		LIMIT 0,1";
$res_1 = mysql_query($sql);

if($res_1 && mysql_num_rows($res_1) > 0)
{
	$row_1 = mysql_fetch_assoc($res_1);
	
	
	if(trim($row_1['text_content']) != '')
		$arr['content'] = $row_1['text_content'];
		
	if(trim($row_1['text_title']) != '')
		$arr['title'] = $row_1['text_title'];
		
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$arr['title']?></title>
<meta http-equiv="keywords" content="<?=$arr['title']?>" />
<meta http-equiv="description" content="<?=$arr['title']?>" />

<style>
p{font-size: 13px;color: #666;}
</style>
</head>
<body>

<div class="divMain tplText" style="width: 1018px; margin: auto; position: relative; background: #6B6B6F; font-family: tahoma;">

  <!-- шапка -->
  <div class="divHeader" style="padding: 0 20px;">
    <div class="top" style="height: 120px; position: relative;">
      <a class="logo" href="http://<?=$_SERVER['HTTP_HOST']?>" style="text-align: center; margin-top: 22px; display: block;float: left;width: 100%;"></a>
      <div style="clear:both"></div>
      <div id="line" style="position: absolute;height: 1px;width: 100%;bottom: 21px;border-bottom: 1px solid #505151;"></div>
    </div>
  <!-- /шапка -->
  
  <!-- заголовок -->
	<div class="textTitle" style="background: #F37F88;position: relative;min-height: 70px; line-height:normal">
		<span style="border: 0;color: #FFFFFF;display: block;padding: 16px;font-family: 'Times New Roman', Times, serif;font-size: 35px;"><?=$arr['title']?></span>
	</div>
  <!-- /заголовок -->
  
  <!-- текст -->
  <div class="content" style="padding: 23px 40px 15px 35px;background: #F9F8F6;">
       <div class="textContent floatLeft" style="font-size: 13px;color: #666;">
	    <?
		  if(isset($_GET['param2']) && $_GET['param2'] == 'delete' && isset($_GET['param3']) && intval($_GET['param3']))
		  {
				$sql = "DELETE FROM `".$_VARS['tbl_prefix']."_subscribe`
						WHERE id = ".$_GET['param3'];
						
				$res = mysql_query($sql);
				
				if($res && mysql_affected_rows() > 0)
				{
					echo '<div style="padding:20px">Вы успешно отписались от рассылки.<br>
					<a href="/">Перейти на сайт.</a></div>';
				}
				else
					echo $arr['content'];
		  }
		  else
				echo $arr['content']
		  ?>
	   
	   </div>
    <div style="clear:both"></div>
  </div>
  <!-- /текст -->
  
  <!-- футер -->
  <div class="footer" style="padding:20px 0">
    <!--<div class="copyright" style="color:#FFFFFF; font-size:13px">Если вы не хотите получать рассылку, пройдите <a href="#" style="color:#F37F88">по ссылке</a></div>-->
   
    <div style="clear:both"></div>
  </div>
  <!-- /футер -->
 
</div>

</body>
</html>