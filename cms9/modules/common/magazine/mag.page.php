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
	"name"			=> "mag_page",
	"tableName" 	=> $_VARS['tbl_prefix']."_mag_page",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Страницы журнала",
	"TEXT_ADD_ITEM"	=> "Добавить страницу",
	"TEXT_EDIT_ITEM"=> "Редактировать страницу"		
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
	"p_mag" 		=> "int default 0",	// id журнала
	"p_num"			=> "int default 0",	// номер страницы по порядку	
	"p_tpl"			=> "int default 0",	// id шаблона страницы
	"p_bg" 			=> "int default 0",	// картинка - фон страницы
	"p_show" 		=> "enum('1', '0') not null", // показывать на сайте	
	"p_text_1"		=> "text",			// текстовые блоки
	"p_text_2"		=> "text",
	"p_text_3"		=> "text",
	"p_text_4"		=> "text",
	"p_text_5"		=> "text",
	"p_text_6"		=> "text",
	"p_img_1"		=> "int default 0",	// графические блоки
	"p_img_2"		=> "int default 0",
	"p_img_3"		=> "int default 0",
	"p_img_4"		=> "int default 0",
	"p_img_5"		=> "int default 0",
	"p_img_6"		=> "int default 0"
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
	
	"p_mag"		=> array(
						"name"	=> "p_mag", 	
						"title" => "Номер журнала", 	
						"type"	=> "selectObject", 		
						"value" => "",
						"order" => "mag_year DESC, mag_num ASC", 
						"order_dir" => "",
						"field" => array("/", "mag_num", "mag_year"),
						"mode"	=> "",
						"table" => $_VARS['tbl_prefix']."_mag"),
						
	"p_num"	=> array(
						"name"	=> "p_num", 	
						"title" => "Номер страницы", 	
						"type"	=> "selectNumber", 		
						"value" => "1",
						"param" => array(1, 50)
						),	
						
	"p_tpl"		=> array(
						"name"	=> "p_tpl", 	
						"title" => "Шаблон страницы", 	
						"type"	=> "selectObject", 		
						"value" => "0",
						"order" => "tpl_order", 
						"order_dir" => "ASC",
						"field" => "id",
						"mode"	=> "",
						"table" => $_VARS['tbl_prefix']."_mag_tpl"),
						
	"p_bg"	=> array(
						"name"	=> "p_bg", 	
						"title" => "Фоновое изображение", 	
						"type"	=> "selectPicAjax", 		
						"value" => 0,
						"alb"	=> 0),	
						
	"p_show"	=> array(
						"name"	=> "p_show", 	
						"title" => "Показывать на сайте", 	
						"type"	=> "inputCheckbox", 		
						"value" => true)/*,
	"p_text_1"	=> array(
						"name"	=> "p_text_1", 	
						"title" => "Текстовый блок 1", 	
						"type"	=> "inputHidden", 		
						"value" => "",
						"class" => "")*/
			
			
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
	$arrData = delArrayElem($_POST, array("addItem", "id", "alb_id_p_bg", "alb_id_p_img_1", "alb_id_p_img_2"));
	
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
	$arrData = delArrayElem($_POST, array("updateItem", "id", "alb_id_p_bg", "alb_id_p_img_1", "alb_id_p_img_2"));
	
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


#tplArea{border:#999999 1px dashed; position:relative; height:1080px; max-width:820px}
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
		GetItemsPages($_MODULE_PARAM['tableName']);
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
	$html = '';
	
	if(isset($editItem) && isset($id))
	{		
		$caption = $_TEXT['TEXT_EDIT_ITEM'];
		$submit = array('updateItem', 'Сохранить');
		
		$row = readItem($id);		
		
		foreach($arrFormFields as $k => $v)
		{
			$arrFormFields[$k]["value"] = $row[$k];
		}	
		
		
		//$arrFormFields['p_text_1']["value"] = $row['p_text_1'];
		
		
		
		/* прочитаем шаблон */	
		
		if($arrFormFields['p_tpl']["value"] != 0)	
		{
			$t = new TPL();
			$t -> tbl_name 	= $_VARS['tbl_prefix']."_mag_tpl";
			$t -> id 		= $arrFormFields['p_tpl']["value"];
			
			$arrTpl = $t -> getTplInfo();
			
			$t -> data = $row;			
			$t -> html = $arrTpl['tpl_code'];
			$html = $t -> replaceBlocks();
		}
		/* /прочитаем шаблон */
		
		
		
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
		
		
		<script language="javascript">
		$(document).ready(function()
		{
			$('.img-area div a').click(function()
			{			
				$('.picList').hide()
				//alert('click')
				$(this).parent('div').eq(0).find('.picList').load('http://<?=$_SERVER['HTTP_HOST']?>/cms9/modules/common/img_man/ajax.image.selector.php',
				//$(this).find('.picList').load('http://<?=$_SERVER['HTTP_HOST']?>/cms9/modules/common/img_man/ajax.image.selector.php',
				//$(this).find('.picList').load('http://<?=$_SERVER['HTTP_HOST']?>/cms9/modules/common/img_man/ajax.image.selector.php',
				{
					'alb_id' 	: 0,
					'field_id'	: $(this).parent('div').eq(0).find('input').attr('id'),
					'pic_id'	: $('#' + $(this).find('input').attr('id')).attr('value')
					
				}).show()
				
				//alert($(this).find('input').attr('id'))
				
				return false
			})
		})
		</script>
		
		<style>
		.p-main{font-size:26px}
		.p-main textarea{border:0; background:#ccc; resize: none; font-family:inherit; line-height:inherit; text-transform:inherit; font-size:inherit;  height:100%; width:100%; text-align:inherit}
		#tplArea .image > div{background:#0f0}
		#tplArea .img-bg{display:none}/* img{width:100%; height:100%}*/
		/*.p-main img{display:block; width:100%; height:100%}*/
		</style>
		<div id="tplArea">
		<?
		echo $html;
		?>
		</div>
		
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
