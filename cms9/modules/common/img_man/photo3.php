<?
include $_SERVER['DOCUMENT_ROOT']."/cms9/modules/framework/class.image.php";

include 'f.php';

session_start();
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*~~~ CMS МЕНЕДЖЕР КАРТИНОК ~~~*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/

check_access(array("admin", "editor"));

//error_reporting(E_ALL);

$arrExt = array("jpg", "png", "gif");

if(isset($_POST['pub'])) 
	$last_type = $_POST['pub'];
	
if(!isset($last_type)) 
	$last_type = 0;

$in_page = $_VARS['cms_pic_in_page'];
//$arr = $_VARS['cms_tumb_types'];

if(isset($name)) 
	$name = str_replace("'","#039;",$name);
	
if(!isset($_GET['alb_id'])) 
	$alb_id = 0;
else
	$alb_id = $_GET['alb_id'];



$parent_folder = $_VARS['photo_alb_dir']."/";




/*	получим список всех альбомов	*/
$arrAlb = getAlbList();




/*~~~ удаление картинки ~~~*/
if(isset($_GET['del'])) 
{
	del($id);	
}
/*~~~ /удаление картинки ~~~*/




/*~~~ изменение записи ~~~*/
if(isset($_POST['upd'])) 
{
	$sql = "SELECT * FROM `".SITE_PREFIX."_pic`
			WHERE id = ".$id;
	//echo $sql;
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		$ext = $row['file_ext'];
			
		if($_POST['removeTo'] != '')
		{
			// имя нового альбома
			$newDir = $_POST['removeTo'];
			
			// скопируем файл-оригинал в новый альбом			
			$path_old = $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$alb_id/$id.".$ext;	
			$path_new = $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/$id.".$ext;
			
			if(is_dir($_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir"))	
				$copy = copy($path_old, $path_new);
			else
				echo 'Нет такой папки '.$path_new.'<br>';
			
			// если копирование прошло удачно
			if($copy)
			{
				
				$sql = "SELECT MAX(order_by) order_by FROM `".SITE_PREFIX."_pic`
						WHERE alb_id = ".$_POST['removeTo'];
								
				$res = mysql_query($sql);
				
				
				$row = mysql_fetch_assoc($res);
				
				$order_by = $row['order_by'] + 1;
				
				$sql = "UPDATE `".SITE_PREFIX."_pic` 
						SET order_by = '".$order_by."', 
							alb_id   = ".$_POST['removeTo']." 
						WHERE id = '".$id."'";
						
				
				$res = mysql_query($sql);
				
								
				$old_dir = $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$alb_id";
				
				$dir = opendir($old_dir);
				chdir($old_dir);
							
				while($file = readdir($dir))
				{

					$template = "[^".$id."-?\w*(-mono)?.(jpg|gif|png)$]";
					$result = preg_match($template, $file); 
					if($result)	
					{

						unlink($file);
					}		
				}			
			}
		}
		else
		{
			$sql = "UPDATE `".SITE_PREFIX."_pic` 
					SET name='$name', tags='$tags', url='$url' 
					WHERE id = '$id'";
			mysql_query($sql);	
		}
	}
	
		
	
	
	//header("Location: ?page=$page&alb_id=$alb_id&p=$p&last_type=$last_type");
//	exit;
}
/*~~~ /изменение записи ~~~*/





/*~~~ новая картинка ~~~*/
if(isset($_POST['loadNew'])) 
{
	$arrFiles = makeArrFiles();
	$arrError = array();
	$arrErrorCode = array(
		1 => 'Размер принятого файла превысил максимально допустимый размер',
		2 => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме',
		3 => 'Загружаемый файл был получен только частично',
		4 => 'Файл не был загружен',
		6 => 'Отсутствует временная папка',
		7 => 'Не удалось записать файл на диск',
		8 => 'PHP-расширение остановило загрузку файла'
	);
	
	//printArray($arrFiles);
	
	foreach($arrFiles as $k => $v)
	{
		if($v['error'] == 0)
		{
			$sql = "INSERT INTO `".SITE_PREFIX."_pic`
					SET name='".$v['name']."', alb_id = ".$alb_id;
					
			$res = mysql_query($sql);			
			
			$id = mysql_insert_id();
			
			// определим тип закачанного файла
			$file_type 	= getimagesize($v["tmp_name"]);
			
			// если jpeg, то просто копируем его
			if($file_type[2] == 2)
			{
				copy($v["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$alb_id/$id.jpg");
			}	
			// иначе конвертируем в jpeg
			else 
			{
				saveImage($v["tmp_name"], $parent_folder.$_VARS['photo_alb_sub_dir']."$alb_id", $id);
			}
			
			
			switch ($file_type[2])
			{
				case 1 : $ext = "gif"; break;
				case 3 : $ext = "png"; break;
				default : $ext = "jpg"; break;
			}
			
			$sql = "SELECT MAX(order_by) order_by FROM `".SITE_PREFIX."_pic`
					WHERE alb_id = ".$alb_id;
					//echo $sql;
					
			$res = mysql_query($sql);
			
			$row = mysql_fetch_assoc($res);
			$order_by = $row['order_by'] + 1;
			
			$sql = "UPDATE `".SITE_PREFIX."_pic` 
					SET file_ext = '$ext', 
						order_by = '".$order_by."', 
						img_create = '".date('Y-m-d')."' 
					WHERE id = '$id'";
			$res = mysql_query($sql);	
		}
		else
		{
			$arrError[] = $k;
		}			
	}
	
	if(!empty($arrError))
	{
		foreach($arrError as $k)
		{
			echo 'Ошибка при загрузке файла '.$arrFiles[$k]['name'].' ('.$k.'):<br>';
			echo $arrErrorCode[$arrFiles[$k]['error']].'<br>';
			?><a href="?page=<?=$page?>&alb_id=<?=$alb_id?>">Перейти в альбом</a><?
			
		}
	}
	else
	{
		header("Location: ?page=$page&alb_id=$alb_id");
		
	}
	exit;	
}
/*~~~ /новая картинка ~~~*/





if(isset($_GET['move']) && isset($_GET['dir']) && isset($_GET['id']) && isset($_GET['alb_id']))
{
	MoveItem($_GET['id'], $_GET['dir'], $_GET['alb_id']);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="admin.css" type="text/css">
<!--<script language="javascript" type="text/javascript" src="js/jquery-1.5.min.js"></script>-->

<!-- ui dialog -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<!-- /ui dialog -->

<!-- jcrop -->
<script  src="/js/jcrop/js/jquery.Jcrop.min.js"></script>
<link  rel="stylesheet" href="/js/jcrop/css/jquery.Jcrop.css"  type="text/css" />

<style  type="text/css">
#crop{
	display:none;
}
#cropresult{
	border:2px solid #ddd;
}
.mini{
	margin:5px;
}
#dialog, #reload{display:none}
</style>
<!-- /jcrop -->


<script language="javascript">

$(document).ready(function()
{
	$('a.add').click(function()
	{
		var obj = $(".formField").eq($(".formField").size() - 1);
		$(".formField").eq(0).clone().insertAfter(obj);
		return false;
	})
	
	$('.formField a.del').click(function()
	{
		if($('.formField').size() > 1)
		{
			var obj = $(this).parents('.formField');
			$(obj).remove();
		}
		return false;
	})
	
	
	$('.edit-link').click(function()
	{
		
		$('#dialog').load('/blocks/dialog.jcrop.php', 
			{
				src : $(this).attr('href'),
				alb_id : <?=$alb_id?>
			}, 
			
			function(){
				//$('#target').Jcrop()
			}
		);
		
		
		$( "#dialog" ).dialog({
			modal: true,
			width	: 'auto',
			//maxWidth : 1000,
			title : 'Обрезка изображения',
			position: [0,0],
			close: function( event, ui ) {location.reload();}
		});
		
		 
		
		return false
	})
	
	
})

</script>



</head>


<body bgcolor="#FFFFFF">
<a href="?page=photo_alb">Все альбомы</a>
<?
$sql = "SELECT alb_title FROM `".$_VARS['tbl_photo_alb_name']."` 
		WHERE id = '".$alb_id."'";
//echo $sql;
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>
<h3>Фотоальбом "<?=$row['alb_title']?>" <a id="reload" href="#"><img src="/cms9/icon/reload_active.png" width="16" height="16" /></a></h3>
<?

if(!isset($error))$error="";
if(!isset($name))$name=""; 

?>
<fieldset><legend><strong>Новая картинка</strong></legend>
<?=$error?>
<form action="#new" method="post" enctype="multipart/form-data" name="newform">
    <table border="0" cellspacing="2" cellpadding="2">
        <tr class="formField">
            <td>Выберите картинку:</td>
			<td>
				<input type="file" name="small[]">&nbsp;<a class='del' href="">Удалить</a>
			</td>
        </tr>
		       
		<tr>
            <td colspan=2>				
                <input type="hidden" name="alb_id" id="alb_id" value="<?=$alb_id?>">
				<a class='add' href="">Добавить поле</a>&nbsp
                <input type="submit" name="loadNew" value="Загрузить">
            </td>
        </tr>
    </table>	 
</form>
</fieldset>

<p>&nbsp;</p>

<table border="0" cellspacing="0" cellpadding="4">
 <?
 
/*~~~ вывод всех картинок ~~~*/
/*if(!isset($p) || $p < 0) 
	$p = 0;
	
$start = $in_page * $p;

$sql = "SELECT * FROM `$tbl_alb` 
		WHERE 1";
$res = mysql_query($sql);

$total = mysql_num_rows($res);

$uurl = "?page=$page&alb_id=$alb_id&p=";*/

$sql = "SELECT * FROM `".SITE_PREFIX.'_pic'."` 
		WHERE alb_id = ".$alb_id."
		ORDER BY `order_by` ASC";
$r = mysql_query($sql);
/*~~~ /вывод всех картинок ~~~*/

while($e = mysql_fetch_array($r)) 
{
	//printArray($e);
	if(!isset($fon) || $fon =="#ffffff") 
		$fon="#eee"; 
	else 
		$fon="#ffffff";
?>
    <form action="?page=<?=$page?>&alb_id=<?=$alb_id?>" method="post" enctype="multipart/form-data" name="form<?=$e['id']?>">
        <tr bgcolor="<?=$fon?>" valign="top">			
            <td rowspan=4 width="10"><?=$e['id']?></td>
			<td rowspan=4 align="center" width=45>
				<a href="?page=<?=$page?>&alb_id=<?=$alb_id?>&id=<?=$e["id"]?>&move&dir=asc"><img src='<?=$_ICON["down"]?>' alt="down"></a>
				<a href="?page=<?=$page?>&alb_id=<?=$alb_id?>&id=<?=$e["id"]?>&move&dir=desc"><img src='<?=$_ICON["up"]?>' alt="up"></a>
			</td>
            <td rowspan=4 width=100>
			<?
						
			$img = new Image();
			$img -> imgCatalogId 	= $alb_id;
			$img -> imgId 			= $e["id"];
			$img -> imgAlt 			= "";
			$img -> imgWidthMax 	= 200;
			$img -> imgHeightMax 	= 100;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "resize";
			//printArray($img);
			$html = $img -> showPic();
			echo $html;
			
			$f_name = '/pic_catalogue/gs_pic_'.$alb_id.'/'.$e["id"].'.'.$e['file_ext'];
			
			?><br />
			<a class="edit-link" href="<?=$f_name?>">редактировать</a>
            </td>
            <td>Название картинки : <!--(<a href="<?=$f_name?>" target="_blank"><?=$f_name?></a>)--></td>
            <td><input type="text" name="name" value="<?=htmlspecialchars($e["name"])?>" style="width:300px;">
                &nbsp;</td>
            <td align="right" valign="top" rowspan=2>
				<a href="javascript:if(confirm('Удалить эту картинку?'))location.replace('?page=<?=$page?>&alb_id=<?=$alb_id?>&id=<?=$e["id"]?>&del=del');" style="color:red;font-size:10px;" title="Удалить картинку" >
					<img src='<?=$_ICON["del"]?>' alt="del">
				</a> 
			</td>
        </tr>
        <tr bgcolor="<?=$fon?>">
            <td>Теги</td>
            <td>
				<input type="text" name="tags" value="<?=htmlspecialchars($e["tags"])?>" style="width:300px;">
			</td>
        </tr>
		<tr bgcolor="<?=$fon?>">
            <td>Ссылка:</td>
			<td colspan="2">
				<input type="text" name="url" value="<?=htmlspecialchars(@$e["url"])?>" style="width:300px;">
			</td>
        </tr>
        <tr bgcolor="<?=$fon?>">
            <td>Перенести в альбом</td>
            <td>
				<select name="removeTo">
					<option value=""></option>
				<?
				foreach($arrAlb as $k => $v)
				{
					$sel = '';
					if($alb_id == $k) continue;
					?><option value="<?=$k?>"><?=$v?></option>
					<?
				}
				?> 
				</select>
            </td>
            <td align=right>
            	<input name="id" 		type="hidden" value="<?=$e["id"]?>">
                <input name="alb_id" 	type="hidden" id="alb_id" value="<?=$alb_id?>">
                <input name="upd" 		type="hidden" id="upd" value="upd">
				<input type="submit" 	name="Submit" value="Сохранить">
             </td>
        </tr>
    </form>
    <?
}

?>
</table>

<div id="dialog"></div>




<br>
</body>
</html>











