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
include_once $_SERVER['DOC_ROOT']."/blocks/class.mag.php";

/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/

$_MODULE_PARAM = array(
	"name"			=> "mag_tpl",
	"tableName" 	=> $_VARS['tbl_prefix']."_mag_tpl",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Шаблоны полос журнала",
	"TEXT_ADD_ITEM"	=> "Добавить шаблон",
	"TEXT_EDIT_ITEM"=> "Редактировать шаблон"		
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
	"tpl_icon" 		=> "int default 0",	// картинка - фон страницы
	"tpl_show" 		=> "enum('1', '0') not null", // показывать на сайте	
	"tpl_code"		=> "text",			// текстовые блоки
	"tpl_order"		=> "int default 0"
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
	
	"tpl_icon"	=> array(
						"name"	=> "tpl_icon", 	
						"title" => "Схема шаблона", 	
						"type"	=> "selectPicAjax", 		
						"value" => 0,
						"alb"	=> 0,
						"param" => array(100, 149)),						
	"tpl_show"	=> array(
						"name"	=> "tpl_show", 	
						"title" => "Показывать на сайте", 	
						"type"	=> "inputCheckbox", 		
						"value" => true),
						
	"tpl_code"	=> array(
						"name"	=> "tpl_code", 	
						"title" => "Код шаблона", 	
						"type"	=> "textareaText", 		
						"value" => "",
						"class" => "t")
				
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



// добавленние новой записи
if(isset($addItem))
{
	// предварительно удалим ненужные элементы
	$arrData = delArrayElem($_POST, array("addItem", "id", "alb_id_tpl_icon", "tpl_code"));
	
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
	
	saveTpl(mysql_insert_id(), $_POST['tpl_code']);
}


// удаление записи
if(isset($delItem) and isset($id))
{
	// параметры запроса на удаление
	$db_Table -> tableWhere = array("id" => $id);
	
	// удаление записи
	$db_Table -> delItem();	
	
	$t = new TPL();
	$t -> tbl_name 	= $_VARS['tbl_prefix']."_mag_tpl";
	$t -> id 		= $id;
	$t -> delTpl();
}



// изменение записи
if(isset($updateItem) and isset($id))
{	
	// предварительно удалим ненужные в запросе элементы
	$arrData = delArrayElem($_POST, array("updateItem", "id", "alb_id_tpl_icon", "tpl_code"));
	
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
	
	saveTpl($_POST['id'], $_POST['tpl_code']);
}
?>


<?
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/head.php";
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
	filebrowserBrowseUrl 		: '/ckeditor/kcfinder-2.51/browse.php?type=file',
	filebrowserImageBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=image',
	filebrowserFlashBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=flash',
	
	filebrowserUploadUrl 		: '/ckeditor/kcfinder-2.51/upload.php?type=file',
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
		GetItemsTpl($_MODULE_PARAM['tableName']);
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
		
		//printArray($row);
		
		foreach($arrFormFields as $k => $v)
		{
			$arrFormFields[$k]["value"] = $row[$k];
		}			
	}
	?>
	<fieldset><legend><?=$caption?></legend>
	
		<form method="post" enctype="multipart/form-data" action="" name="form1" id="form1">
		<table >
		
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
						<td width="100%">
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
