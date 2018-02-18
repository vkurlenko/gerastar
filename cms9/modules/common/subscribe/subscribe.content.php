<?php
/*~~~~~~~~~~~~~~~*/
/*~~~~~~~~~~~~~~~*/

session_start();
error_reporting(E_ALL);

include_once $_SERVER['DOC_ROOT']."/config.php" ;
include_once $_SERVER['DOC_ROOT']."/fckeditor/fckeditor.php" ;
include_once $_SERVER['DOC_ROOT']."/db.php";
include_once "subscribe.content.func.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.html.php";

/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/
$_MODULE_PARAM = array(
	"name"			=> "subscribe_content",
	"tableName" 	=> $_VARS['tbl_prefix']."_subscribe_content",
	"userAccess" 	=> array("admin", "editor"),
	/*"arrConstr"		=> $_VARS['item_constr'],
	"arrColor1"		=> $_VARS['arrColor1'],
	"arrColor2"		=> $_VARS['arrColor2'],*/
	"thisId"		=> 0
);

if(isset($_GET['id'])) $_MODULE_PARAM['thisId'] = $_GET['id'];

$_TEXT = array(
	"TEXT_HEAD"		=> "Тексты рассылки",
	"TEXT_ADD_ITEM"	=> "Добавить текст",
	"TEXT_EDIT_ITEM"=> "Редактировать текст"		
);
/*~~~~~~~~~~~~~~~~~~~*/
/* /параметры модуля */
/*~~~~~~~~~~~~~~~~~~~*/

check_access($_MODULE_PARAM['userAccess']);

$tags = "<strong><a><br><span><img><embed><em>";

$jumpCount = 4;


/*~~~~~~~~~~~~~~~~~~~~~~*/
/* структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~*/
$arrTableFields = array(
	"id" 				=> "int auto_increment primary key",	
	"text_title" 		=> "text",						// заголовок
	"text_content" 		=> "text",						// контент (html)
	"text_active" 		=> "enum('0', '1') not null",	// текст активен (используется в рассылке)
	"text_order" 		=> "int default '0' not null"	// сортировка
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
	"text_active"		=> array(
						"name"	=> "text_active", 	
						"title" => "Использовать в рассылке (другие тексты станут неактивны)", 	
						"type"	=> "inputCheckbox", 		
						"value" => true),	
	
	"text_title"		=> array(
						"name"	=> "text_title", 	
						"title" => "Заголовок", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
						
	"text_content"		=> array(
						"name"	=> "text_content", 	
						"title" => "Текст", 	
						"type"	=> "textHTML", 		
						"value" => "",
						"class" => "")	
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

//printArray($_POST);

// добавленние новой записи
if(isset($addItem))
{
	// предварительно удалим ненужные элементы
	$arrData = delArrayElem($_POST, array("addItem", "id"));
	
	// обработка checkbox и select:multiselect	
	foreach($arrFormFields as $k => $v) 
	{
		if($v["type"] == "inputCheckbox")
		{
			if(@$arrData[$k] != "") $arrData[$k] = 1;
			else $arrData[$k] = 0;
		}		
	}	
	
	if(isset($_POST['text_active']))
	{
		$sql = "UPDATE `".$_MODULE_PARAM['tableName']."`
				SET text_active = '0'
				WHERE 1";
		$res = mysql_query($sql);
	}
	
	$db_Table -> tableData = $arrData;
	$db_Table -> addItem();	
	
	unset($arrData);
	
	
	
	// по какому условию будем делать запрос	
	$db_Table -> tableWhere = array("id" => mysql_insert_id());
	$sql = "SELECT text_order FROM `".$_MODULE_PARAM['tableName']."`
			WHERE 1
			ORDER BY text_order DESC 
			LIMIT 1";
	//echo $sql;
	$res_max = mysql_query($sql);
	$row_max = mysql_fetch_array($res_max);
	
	$arrData["text_order"] = $row_max["text_order"] + 1;
	
	
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


//printArray($_POST);
// изменение записи
if(isset($_POST['updateItem']) and isset($_POST['id']))
{	
	// предварительно удалим ненужные в запросе элементы
	$arrData = delArrayElem($_POST, array("updateItem", "id"));	
	
	
	// обработка checkbox и select:multiselect	
	foreach($arrFormFields as $k => $v) 
	{
		if($v["type"] == "inputCheckbox")
		{
			if(@$arrData[$k] != "") $arrData[$k] = 1;
			else $arrData[$k] = 0;
		}		
	}
	
	if(isset($_POST['text_active']))
	{
		$sql = "UPDATE `".$_MODULE_PARAM['tableName']."`
				SET text_active = '0'
				WHERE 1";
		$res = mysql_query($sql);
	}
	
	
	// по какому условию будем делать запрос	
	$db_Table -> tableWhere = array("id" => $_POST['id']);
	
	// запрос к БД
	$db_Table -> tableData = $arrData;
	$db_Table -> updateItem();	
}

if(isset($_GET['move']) and isset($_GET['dir']) and isset($_GET['id']))
{
	
	$db_Table -> tableOrderField = "text_order";
	$db_Table -> tableWhere = array("id" => $_GET['id'], "dir" => $_GET['dir']);
	
	if(isset($jump))
	{
		$db_Table -> tableFieldJump = $jump;
		$db_Table -> tableJumpCount = $jumpCount;
		$db_Table -> reOrderItemJump();	
	}
	else 
		$db_Table -> reOrderItem();	
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



<?
if(!isset($editItem) && !isset($setItem))
{
	?>
	<fieldset><legend><?=$_TEXT['TEXT_HEAD']?></legend>
		<a class="serviceLink" href="<?=$_SERVER['REQUEST_URI'];?>&setItem"><img src='<?=$_ICON["add_item"]?>'><?=$_TEXT['TEXT_ADD_ITEM']?></a>
		<?
		GetItems($_MODULE_PARAM['tableName'], $orderBy = "text_order", $orderDir = "DESC");
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
		$row = readItem($id);		
		
		foreach($arrFormFields as $k => $v)
		{
			$arrFormFields[$k]["value"] = $row[$k];
		}		
		
		$submit = array('updateItem', 'Сохранить');
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
		
		
		
		<input type="submit" id="submit" name="<?=$submit[0]?>" value='<?=$submit[1]?>' />
		<!--<input type="submit" name="test" id="previewPage" value="Предварительный просмотр">-->
		
		</form>
	</fieldset>
	<?
}
?>

</body>
</html>
