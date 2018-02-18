<?
session_start();
error_reporting(E_ALL);

include_once $_SERVER['DOC_ROOT']."/config.php" ;
include_once $_SERVER['DOC_ROOT']."/fckeditor/fckeditor.php" ;
include_once $_SERVER['DOC_ROOT']."/db.php";
include_once "pages.functions.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.html.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.image.php";

/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/




$_MODULE_PARAM = array(
	"name"			=> "pages2",
	"tableName" 	=> $_VARS['tbl_prefix']."_pages",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Страницы сайта",
	"TEXT_ADD_ITEM"	=> "Добавить страницу",
	"TEXT_EDIT_ITEM"=> "Редактировать страницу"		
);
/*~~~~~~~~~~~~~~~~~~~*/
/* /параметры модуля */
/*~~~~~~~~~~~~~~~~~~~*/


check_access($_MODULE_PARAM['userAccess']);

/*~~~~~~~~~~~~~~~~~~~~~~*/
/* структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~*/
$arrTableFields = array(
	"id"				=> "int auto_increment primary key",
	"p_title"			=> "text",
	"p_title_en"		=> "text",	
	"p_url"				=> "text",
	"p_redirect"		=> "text",
	"p_content"			=> "text",
	"p_content_en"		=> "text",
	"p_add_text_1"		=> "text",
	"p_add_text_1_en"	=> "text",
	"p_add_text_2"		=> "text",
	"p_add_text_2_en"	=> "text",
	"p_parent_id"		=> "int",
	"p_nosearch"		=> "enum('0','1') not null",	
	"p_order"			=> "int",
	"p_tags"			=> "text",
	"p_show"			=> "enum('1','0') not null",
	"p_meta_title"		=> "text",
	"p_meta_title_en"	=> "text",	
	"p_meta_kwd"		=> "text",
	"p_meta_kwd_en"		=> "text",
	"p_meta_dscr"		=> "text",
	"p_meta_dscr_en"	=> "text",
	"p_tpl"				=> "text",
	"p_main_menu"		=> "enum('0','1') not null",	
	"p_video"			=> "text",
	"p_photo_alb"		=> "int",
	"p_photo_alb_2"		=> "int",
	"p_img"				=> "int",
	"p_protect"			=> "enum('0','1') not null",
	"p_site_map"		=> "enum('1','0') not null"
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
	"p_title"	=> array(
						"name"	=> "p_title", 	
						"title" => "Название", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_title_en"	=> array(
						"name"	=> "p_title_en", 	
						"title" => "Название (eng)", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_url"	=> array(
						"name"	=> "p_url", 	
						"title" => "URL", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_redirect"	=> array(
						"name"	=> "p_redirect", 	
						"title" => "URL редиректа", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_parent_id"		=> array(
						"name"	=> "p_parent_id", 				
						"title" => "Родительский раздел", 					
						"type"	=> "selectParentId", 		
						"value" => 0,
						"thisId"=> isset($_GET['id']) ? $_GET['id'] : 0,
						"table" => array("table_name" => $_MODULE_PARAM['tableName'], "parent_field" => "p_parent_id", "order_by" => "p_order", "order_dir" => "ASC", "item_title" => "p_title")),
							
	"p_content"	=> array(
						"name"	=> "p_content", 	
						"title" => "Текст страницы", 	
						"type"	=> "textHTML", 		
						"value" => "",
						"class" => ""),	
	"p_content_en"	=> array(
						"name"	=> "p_content_en", 	
						"title" => "Текст страницы (eng)", 	
						"type"	=> "textHTML", 		
						"value" => "",
						"class" => ""),		
	/*"p_add_text_1"	=> array(
						"name"	=> "p_add_text_1", 	
						"title" => "Нижнее меню", 	
						"type"	=> "textHTML", 		
						"value" => "",
						"class" => ""),	*/
	
						
	/*"p_tags"	=> array(
						"name"	=> "p_tags", 	
						"title" => "Теги", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),*/
	"p_nosearch"	=> array(
						"name"	=> "p_nosearch", 	
						"title" => "Не включать в поиск", 	
						"type"	=> "inputCheckbox", 		
						"value" => false),						
	"p_show"	=> array(
						"name"	=> "p_show", 	
						"title" => "Показывать на сайте", 	
						"type"	=> "inputCheckbox", 		
						"value" => true),
	"p_site_map"	=> array(
						"name"	=> "p_site_map", 	
						"title" => "Показывать в карте сайта", 	
						"type"	=> "inputCheckbox", 		
						"value" => true),
	"p_protect"	=> array(
						"name"	=> "p_protect", 	
						"title" => "Закрытый раздел", 	
						"type"	=> "inputCheckbox", 		
						"value" => false),
	"p_main_menu"	=> array(
						"name"	=> "p_main_menu", 	
						"title" => "Показывать в главном меню", 	
						"type"	=> "inputCheckbox", 		
						"value" => false),		
	"p_tpl"	=> array(
						"name"	=> "p_tpl", 	
						"title" => "Шаблон", 	
						"type"	=> "selectTpl", 		
						"value" => ""),
	"p_img"	=> array(
						"name"	=> "p_img", 	
						"title" => "Фоновое изображение (для текстовой страницы)", 	
						"type"	=> "selectPicAjax", 		
						"value" => 0,
						"alb"	=> 2),
					
	"p_photo_alb"	=> array(
						"name"	=> "p_photo_alb", 	
						"title" => "Альбом картинок по теме", 	
						"type"	=> "selectAlb", 		
						"value" => 0),
	/*"p_photo_alb_2"	=> array(
						"name"	=> "p_photo_alb_2", 	
						"title" => "Альбом картинок по теме 2", 	
						"type"	=> "selectAlb", 		
						"value" => 0),		*/	
	"p_meta_title"	=> array(
						"name"	=> "p_meta_title", 	
						"title" => "Мета TITLE", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_meta_title_en"	=> array(
						"name"	=> "p_meta_title_en", 	
						"title" => "Мета TITLE (eng)", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_meta_kwd"	=> array(
						"name"	=> "p_meta_kwd", 	
						"title" => "Мета KEYWORDS", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_meta_kwd_en"	=> array(
						"name"	=> "p_meta_kwd_en", 	
						"title" => "Мета KEYWORDS (eng)", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
					
	"p_meta_dscr"	=> array(
						"name"	=> "p_meta_dscr", 	
						"title" => "Мета DESCRIPTION", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"p_meta_dscr_en"	=> array(
						"name"	=> "p_meta_dscr_en", 	
						"title" => "Мета DESCRIPTION (eng)", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText")
);
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* /структура формы редактирования записи */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

// создание новой таблицы БД
$db_Table = new DB_Table();
$db_Table -> tableName = $_MODULE_PARAM['tableName'];
$db_Table -> tableFields = $arrTableFields;
$db_Table -> create();
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


// добавленние новой записи
if(isset($addItem))
{
	// предварительно удалим ненужные элементы
	$arrData = delArrayElem($_POST, array("addItem", "id", "alb_id_p_img"));
	
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
	
	unset($arrData);
	
	
	// по какому условию будем делать запрос	
	$db_Table -> tableWhere = array("id" => mysql_insert_id());
	$sql = "SELECT p_order FROM `".$_MODULE_PARAM['tableName']."`
			WHERE 1
			ORDER BY p_order DESC 
			LIMIT 1";
	//echo $sql;
	$res_max = mysql_query($sql);
	$row_max = mysql_fetch_array($res_max);
	
	$arrData["p_order"] = $row_max["p_order"] + 1;
	
	
	// запрос к БД
	$db_Table -> tableData = $arrData;
	$db_Table -> updateItem();		
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
	$arrData = delArrayElem($_POST, array("updateItem", "id", "alb_id_p_img"));
	
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

if(isset($_GET['move']) and isset($_GET['dir']) and isset($_GET['id']))
{
	
	$db_Table -> tableOrderField = "p_order";
	$db_Table -> tableWhere = array("id" => $_GET['id'], "dir" => $_GET['dir'], "parent_id" => 'p_parent_id');
	
	/*if(isset($jump))
	{
		$db_Table -> tableFieldJump = $jump;
		$db_Table -> tableJumpCount = $jumpCount;
		$db_Table -> reOrderItemJump();	
	}
	else */
	$db_Table -> reOrderItem();	
	
	header('Location:/cms9/workplace.php?page='.$_MODULE_PARAM['name'].'&parent='.$_GET['parent'].'&id='.$_GET['id'].'#'.$_GET['id']);
}
?>

<?
include_once "head.php";
?>


<body>
<script src="/ckeditor/ckeditor.js"></script>


<script language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->

var cfg = {
	filebrowserBrowseUrl 		: '/ckeditor/kcfinder-2.51/browse.php?type=file'	,
	filebrowserImageBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=image',
	filebrowserFlashBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=flash',
	
	filebrowserUploadUrl 		: '/ckeditor/kcfinder-2.51/upload.php?type=file'	,
	filebrowserImageUploadUrl 	: '/ckeditor/kcfinder-2.51/upload.php?type=image',
	filebrowserFlashUploadUrl 	: '/ckeditor/kcfinder-2.51/upload.php?type=flash'
}

/*function cke(obj)
{
	CKEDITOR.replace(obj,cfg);
}*/
</script>



<script language="javascript">
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
		if(!isset($_GET['p_parent_id'])) 
			$p_parent_id = 0;
			
		GetItems($_MODULE_PARAM['tableName'], $p_parent_id);
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
							if($v['name'] == 'p_url' && $v['value'] == '')
							{
								$sql = "select MAX(id) as max_id from `".$_VARS['tbl_pages_name']."` where 1";
								//echo $sql;
								$res = mysql_query($sql);
								$row = mysql_fetch_array($res);
								$max_id = $row['max_id'];
								
								
								$v['value'] = $max_id + 1;
								$elem -> fieldProperty = $v;	
							}
							
							
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
		
		</form>
	</fieldset>
	<?
}
?>


</body>
</html>