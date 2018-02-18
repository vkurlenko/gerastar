<?php
/*~~~~~~~~~~~~~~~*/
/* CMS    */
/*~~~~~~~~~~~~~~~*/

session_start();
//error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT']."/config.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/fckeditor/fckeditor.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/db.php";
include_once "uarray.functions.php";
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.html.php";
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.image.php";

/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/
/*$_MODULE_PARAM = array(
	"name"			=> $ID,
	"tableName" 	=> $_VARS['tbl_prefix']."_".$ID,
	"userAccess" 	=> array("admin", "editor")	
);
*/
/*$_TEXT = array(
	"TEXT_HEAD"		=> "Формы",
	"TEXT_ADD_ITEM"	=> "Добавить форму",
	"TEXT_EDIT_ITEM"=> "Редактировать форму"		
);*/
/*~~~~~~~~~~~~~~~~~~~*/
/* /параметры модуля */
/*~~~~~~~~~~~~~~~~~~~*/

check_access($_MODULE_PARAM['userAccess']);

$tags = "<strong><a><br><span><img><embed><em>";


/*~~~~~~~~~~~~~~~~~~~~~~*/
/* структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~*/
$arrTableFields = array(
	"id" 		=> "int auto_increment primary key",
	"elem_key"	=> "text",
	"elem_value"=> "text",
	"elem_descr"=> "text",
	"elem_show"	=> "enum('1', '0') not null",
/*	"elem_flag"	=> "enum('0', '1') not null",
*/	"elem_order"=> "int default '0' not null"
);
/*~~~~~~~~~~~~~~~~~~~~~~~*/
/* /структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~~*/


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* структура формы редактирования записи */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*$arrFormFields = array(
	"id"			=> array(
						"name"	=> "id", 				
						"title" => "id", 					
						"type"	=> "inputHidden", 		
						"value" => ""),
	"elem_value"	=> array(
						"name"	=> "elem_value", 	
						"title" => "Название формы", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),							
	"elem_descr"	=> array(
						"name"	=> "elem_descr", 	
						"title" => "Описание", 	
						"type"	=> "textHTML", 		
						"value" => ""),
	"elem_show"		=> array(
						"name"	=> "elem_show", 	
						"title" => "Показывать на сайте", 	
						"type"	=> "inputCheckbox", 		
						"value" => true)	
);*/
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
	$arrData = delArrayElem($_POST, array("addItem", "id"));
	
	// обработка checkbox'а	
	foreach($arrFormFields as $k => $v) 
	{
		if($v["type"] == "inputCheckbox")
		{
			@$arrData[$k] != "" ? $arrData[$k] = 1 : $arrData[$k] = 0;		
		}
	}
	
	//метка не должна меняться!!! 
	$arrData['elem_key'] = translit($_POST['elem_value']);
	
	$db_Table -> tableData = $arrData;
	$db_Table -> addItem();	
	
	unset($arrData);
	
	
	// по какому условию будем делать запрос	
	$db_Table -> tableWhere = array("id" => mysql_insert_id());
	$arrData["elem_order"] = mysql_insert_id();
	
	
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
	$arrData = delArrayElem($_POST, array("updateItem", "id"));
	
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

if(isset($move) and isset($dir) and isset($id))
{
	$db_Table -> tableOrderField = "elem_order";
	$db_Table -> tableWhere = array("id" => $id, "dir" => $dir);
	$db_Table -> reOrderItem();	
}
?>


<?
include_once "head.php";
?>


<body>

<?
if(!isset($editItem) && !isset($setItem))
{
	?>
	<fieldset><legend><?=$_TEXT['TEXT_HEAD']?></legend>
		<a class="serviceLink" href="<?=$_SERVER['REQUEST_URI'];?>&setItem"><img src='<?=$_ICON["add_item"]?>'><?=$_TEXT['TEXT_ADD_ITEM']?></a>
		<?
		GetItems($_MODULE_PARAM['tableName'], $orderBy = "elem_order", $orderDir = "ASC");
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
		
		<input type="submit" name="<?=$submit[0]?>" value='<?=$submit[1]?>' />
		
		</form>
	</fieldset>
	<?
}
?>

</body>
</html>
