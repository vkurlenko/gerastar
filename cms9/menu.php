<?php
session_start();
/*~~~~~~~~~~~~~~~~*/
/*~~~ CMS МЕНЮ ~~~*/
/*~~~~~~~~~~~~~~~~*/

include '../config.php';
include $_SERVER['DOC_ROOT'].'/'.$_VARS['cms_dir'].'/'.$_VARS['cms_modules'].'/modules.php';
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Администрирование сайта <?=$HTTP_HOST;?></title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<link href="admin.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="/cms9/js/jquery-1.6.2.min.js"></script>
<style>
ul{margin:0; padding-left:20px;}
a:hover{text-decoration:underline}
a.active{color:#FF0000}
li.sub{padding-left:20px; list-style:circle}
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) 
{
  window.open(theURL,winName,features);
}

$(document).ready(function(){
	$("a").click(function()
	{
		$("a").removeClass("active");
		$(this).addClass("active");
	})
})
//-->
</script>
</head>

<body style="background-color:#eeeeee">


<?
foreach($_MODULES as $k => $v)
{
	if($v[2] == true && !isset($v[3]))
	{		
		?>
		<p>
			<strong><a href="/cms9/workplace.php?page=<?=$k?>" target="content"><?=$v[0]?></a></strong>
			
			<?
			foreach($_MODULES as $k1 => $v1)
			{
				if($v1[2] == true && isset($v1[3]) && $v1[3] == $k)
				{
					//echo $v1[3];
					?><li class="sub"><a href="/cms9/workplace.php?page=<?=$k1?>" target="content"><?=$v1[0]?></a></li><?
				}
			}
			?>
		</p>
		<hr>
		<?
	}
}
?> 

<!--<pre>
<?
print_r($_MODULES);
?>
</pre>-->


</body>
</html>