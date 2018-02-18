<?php
/*~~~~~~~~~~~~~~~*/
/* ЖУРНАЛЫ       */
/*~~~~~~~~~~~~~~~*/

session_start();
error_reporting(E_ALL);

include_once $_SERVER['DOC_ROOT']."/config.php" ;
include_once $_SERVER['DOC_ROOT']."/db.php";
include_once "mag.functions.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.html.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.image.php";

/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/

$_MODULE_PARAM = array(
	"name"			=> "mag",
	"tableName" 	=> $_VARS['tbl_prefix']."_mag",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Журналы",
	"TEXT_ADD_ITEM"	=> "Добавить журнал",
	"TEXT_EDIT_ITEM"=> "Редактировать журнал"		
);
/*~~~~~~~~~~~~~~~~~~~*/
/* /параметры модуля */
/*~~~~~~~~~~~~~~~~~~~*/

check_access($_MODULE_PARAM['userAccess']);

$tags = "<strong><a><br><span><img><embed><em>";


/*~~~~~~~~~~~~~~~~~~~~~~*/
/* структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~*/
$arrTableFields = array(
	"id"			=> "int auto_increment primary key",
	"mag_title" 	=> "text",			// заголовок
	"mag_year"		=> "int default 0",	// дата публикации	
	"mag_num"		=> "int default 0",	// номер
	"mag_img" 		=> "int default 0",	// картинка\
	"mag_link"		=> "text",			// ссылка на журнал
	"mag_code" 		=> "text",			// код вставки журнала
	"mag_mark" 		=> "enum('0', '1') not null",	// пометить как архивную
	"mag_show" 		=> "enum('1', '0') not null"	// показывать на сайте	
);
/*~~~~~~~~~~~~~~~~~~~~~~~*/
/* /структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~~*/


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* структура формы редактирования записи */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
$arrFormFields = array(
	"id"			=> array(
						"name"	=> "id", 				
						"title" => "id", 					
						"type"	=> "inputHidden", 		
						"value" => ""),
	
	"mag_title"	=> array(
						"name"	=> "mag_title", 	
						"title" => "Заголовок журнала", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"mag_year"	=> array(
						"name"	=> "mag_year", 	
						"title" => "Год выпуска", 	
						"type"	=> "selectNumber", 		
						"value" => date("Y"),
						"param" => array(date("Y") - 5, date("Y") + 5)),
	"mag_num"	=> array(
						"name"	=> "mag_num", 	
						"title" => "Номер", 	
						"type"	=> "selectNumber", 		
						"value" => "",
						"param" => array(1, 12)),	
	/*"mag_img"	=> array(
						"name"	=> "mag_img", 	
						"title" => "Обложка", 	
						"type"	=> "selectPicAjax", 		
						"value" => 0,
						"alb"	=> 0,
						"param" => array(100, 149)),*/
	"mag_link"	=> array(
						"name"	=> "mag_link", 	
						"title" => "Ссылка на объект", 	
						"type"	=> "textareaText", 		
						"value" => "",
						"class" => "inputText"),
												
	"mag_code"	=> array(
						"name"	=> "mag_code", 	
						"title" => "Код виджета", 	
						"type"	=> "textareaText", 		
						"value" => "",
						"class" => "inputText"),
	
	"mag_mark"	=> array(
						"name"	=> "mag_mark", 	
						"title" => "Пометить как архивный", 	
						"type"	=> "inputCheckbox", 		
						"value" => false),
	"mag_show"	=> array(
						"name"	=> "mag_show", 	
						"title" => "Показывать на сайте", 	
						"type"	=> "inputCheckbox", 		
						"value" => true)
);
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* /структура формы редактирования записи */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/




// создание новой таблицы БД
$db_Table = new DB_Table();
$db_Table -> tableName = $_MODULE_PARAM['tableName'];
$db_Table -> tableFields = $arrTableFields;
/*$db_Table -> debugMode = true;*/
$db_Table -> createTestRecord = false;
$db_Table -> create();
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


/*if(isset($_POST['mag_num']))
{
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."`
			WHERE mag_num = ".$_POST['mag_num']." 
			AND	mag_year = ".$_POST['mag_year'];
			
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$sql = "UPDATE `".$_MODULE_PARAM['tableName']."`
				SET `mag_num` = `mag_num` + 1
				WHERE (mag_num > ".$_POST['mag_num']." OR mag_num = ".$_POST['mag_num'].")
				AND mag_year = ".$_POST['mag_year'];
		$res = mysql_query($sql);
	}
}*/


// добавленние новой записи
if(isset($addItem))
{
	// предварительно удалим ненужные элементы
	$arrData = delArrayElem($_POST, array("addItem", "id", "alb_id_mag_img"));
	
	// обработка checkbox'а
	foreach($arrFormFields as $k => $v) 
	{
		if($v["type"] == "inputCheckbox")
		{
			@$arrData[$k] != "" ? $arrData[$k] = 1 : $arrData[$k] = 0;
		}
	}
	
	$db_Table -> tableData = $arrData;
	$db_Table -> addItem();		
}


// удаление записи
if(isset($delItem) and isset($id))
{
	// параметры запроса на удаление
	$db_Table -> tableWhere = array("id" => $id);
	
	// удаление записи
	$db_Table -> delItem();	
}



// изменение записи
if(isset($updateItem) and isset($id))
{	
	// предварительно удалим ненужные в запросе элементы
	$arrData = delArrayElem($_POST, array("updateItem", "id", "alb_id_mag_img"));
	
	// обработка checkbox'а
	foreach($arrFormFields as $k => $v) 
	{
		if($v["type"] == "inputCheckbox")
		{
			@$arrData[$k] != "" ? $arrData[$k] = 1 : $arrData[$k] = 0;
		}
	}
	
	
	
	// по какому условию будем делать запрос	
	$db_Table -> tableWhere = array("id" => $id);
	
	// запрос к БД
	$db_Table -> tableData = $arrData;
	$db_Table -> updateItem();	
}
?>


<?
include_once "head.php";
?>


<body>
<style>
tr{vertical-align:top}
td{padding:5px 5px}
input.inputText{width:300px}
input.inputDate{width:auto}
textarea.inputText{width:300px; height:100px}
</style>
<script src="/ckeditor/ckeditor.js"></script>
<script language="javascript">
var cfg = {
	filebrowserBrowseUrl 		: '/ckeditor/kcfinder-2.51/browse.php?type=file'	,
	filebrowserImageBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=image',
	filebrowserFlashBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=flash',
	
	filebrowserUploadUrl 		: '/ckeditor/kcfinder-2.51/upload.php?type=file'	,
	filebrowserImageUploadUrl 	: '/ckeditor/kcfinder-2.51/upload.php?type=image',
	filebrowserFlashUploadUrl 	: '/ckeditor/kcfinder-2.51/upload.php?type=flash'
}
$(document).ready(function(){
	
})
</script>

<?
if(!isset($editItem) && !isset($setItem))
{
	?>
	<fieldset><legend><?=$_TEXT['TEXT_HEAD']?></legend>
		<a class="serviceLink" href="<?=$_SERVER['REQUEST_URI'];?>&setItem"><img src='<?=$_ICON["add_item"]?>'><?=$_TEXT['TEXT_ADD_ITEM']?></a>
		<?
		GetItems($_MODULE_PARAM['tableName']);
		?>
		<a class="serviceLink" href="<?=$_SERVER['REQUEST_URI'];?>&setItem"><img src='<?=$_ICON["add_item"]?>'><?=$_TEXT['TEXT_ADD_ITEM']?></a>
	</fieldset>
	<?
}

else
{

	$elem = new FormElement();

	$caption = $_TEXT['TEXT_ADD_ITEM'];
	$submit = array('addItem', 'Создать');
	
	if(isset($editItem) && isset($id))
	{		
		$caption = $_TEXT['TEXT_EDIT_ITEM'];
		$submit = array('updateItem', 'Сохранить');
		
		$row = readItem($id);		
		
		foreach($arrFormFields as $k => $v)
		{
			$arrFormFields[$k]["value"] = $row[$k];
		}			
	}
	?>
	<fieldset><legend><?=$caption?></legend>
	
		<form method="post" enctype="multipart/form-data" action="" name="form1" id="form1">
		<table>
		
			<?
			foreach($arrFormFields as $k => $v)
			{
				
				$elem -> fieldProperty = $v;	
				
				if($v['type'] == 'inputHidden')
				{
					$elem -> createFormElem();	
				}
				else
				{						
					?>
					<tr>
						<td><?=$v['title']?></td>
						<td>
							<?
							$elem -> createFormElem();	
							?>
						</td>
					</tr>
					<?
				}
			}		
			?>	
					
		</table>		
		
		<input type="submit" name="<?=$submit[0]?>" value='<?=$submit[1]?>' />
		<!--<input type="submit" name="test" id="previewPage" value="Предварительный просмотр">-->
		
		</form>
	</fieldset>
	<?
}
?>

<?
//include "banners_info.php";
?>


</body>
</html>
