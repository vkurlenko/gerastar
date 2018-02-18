<?
session_start();
error_reporting(E_ALL);

include_once $_SERVER['DOC_ROOT']."/config.php" ;
include_once $_SERVER['DOC_ROOT']."/fckeditor/fckeditor.php" ;
include_once $_SERVER['DOC_ROOT']."/db.php";
include_once "settings.functions.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.html.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.image.php";

/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/
$_MODULE_PARAM = array(
	"name"			=> "presets",
	"tableName" 	=> $_VARS['tbl_prefix']."_presets",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Настройки сайта",
	"TEXT_ADD_ITEM"	=> "Добавить новый параметр",
	"TEXT_EDIT_ITEM"=> "Редактировать параметр"		
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
	"var_name" 			=> "text", 	// имя переменной
	"var_value" 		=> "text", 	// значение
	"var_default"		=> "text", 	// по умолчанию
	"var_note" 			=> "text", 	// описание	
	"var_protected"		=> "enum('0', '1') not null" // признак защищенной
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
	"var_name"	=> array(
						"name"	=> "var_name", 	
						"title" => "Имя переменной", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"var_value"	=> array(
						"name"	=> "var_value", 	
						"title" => "Значение", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"var_default"	=> array(
						"name"	=> "var_default", 	
						"title" => "По умолчанию", 	
						"type"	=> "inputText", 		
						"value" => "",						
						"class" => "inputText"),
	"var_note"	=> array(
						"name"	=> "var_note", 	
						"title" => "Описание", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	
	"var_protected"		=> array(
						"name"	=> "var_protected", 	
						"title" => "Защищенная", 	
						"type"	=> "inputCheckbox", 		
						"value" => false)
);
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* /структура формы редактирования записи */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

// создание новой таблицы БД
$db_Table = new DB_Table();
$db_Table -> tableName = $_MODULE_PARAM['tableName'];
$db_Table -> tableFields = $arrTableFields;
/*$db_Table -> debugMode = true;*/
$db_Table -> create();
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


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
td{padding:10px 5px}
input.inputText{width:300px}
textarea.inputText{width:300px; height:100px}

</style>

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