<?php
/*~~~~~~~~~~~~~~~*/
/* CMS ВИДЖЕТЫ   */
/*~~~~~~~~~~~~~~~*/

session_start();
error_reporting(E_ALL);

include_once $_SERVER['DOC_ROOT']."/config.php" ;
include_once $_SERVER['DOC_ROOT']."/fckeditor/fckeditor.php" ;
include_once $_SERVER['DOC_ROOT']."/db.php";
//include_once "users.functions.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.html.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.image.php";

//printArray($_POST);



/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/
$_MODULE_PARAM = array(
	"name"			=> "order",
	"tableName" 	=> $_VARS['tbl_prefix']."_order",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Заказ",
	"TEXT_ADD_ITEM"	=> "",
	"TEXT_EDIT_ITEM"=> "Редактировать заказ"		
);
/*~~~~~~~~~~~~~~~~~~~*/
/* /параметры модуля */
/*~~~~~~~~~~~~~~~~~~~*/

check_access($_MODULE_PARAM['userAccess']);

$tags = "<strong><a><br><span><img><embed><em>";

function readItem($id)
{
	global $_MODULE_PARAM;
	
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."`
			WHERE id = ".$id;
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	return $row;
}

/*~~~~~~~~~~~~~~~~~~~~~~*/
/* структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~*/
$arrTableFields = array(
	"id"			=> "int auto_increment primary key",
	"order_num" 	=> "text", 		
	"client_id" 	=> "int default '0' not null", 		
	"client_name"	=> "text", 		
	"client_contact"=> "text", 		
	"order_list" 	=> "text", 		
	"sum_full"		=> "date not null", 
	"sum_payed"		=> "int default '0' not null", 		
	"order_status"	=> "enum('0', '1', '2', '3', '4', '5')", 		
	"order_date" 	=> "datetime not null"		
	
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
	"order_num"	=> array(
						"name"	=> "order_num", 	
						"title" => "Номер заказа", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
						
	"order_list"	=> array(
						"name"	=> "order_list", 	
						"title" => "Содержимое заказа", 	
						"type"	=> "textareaText", 		
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
		
		
		
		if(isset($v["mode"]) && $v["mode"] == "multiSelect")
		{
			if(!isset($_POST[$k])) $arrData[$k] = serialize(array(0 => 'none'));
			else $arrData[$k] = serialize($_POST[$k]);
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
		
		
		
		if(isset($v["mode"]) && $v["mode"] == "multiSelect")
		{
			if(!isset($_POST[$k])) $arrData[$k] = serialize(array(0 => 'none'));
			else $arrData[$k] = serialize($_POST[$k]);
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

<script language="javascript">
$(document).ready(function()
{
	

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
							
							if($k == 'order_list')
							{
								$arr = unserialize($v['value']);
								//printArray($arr);
								foreach($arr as $a => $b)
								{
									switch($a)
									{
										case 'orderDelivVar' : $elem -> fieldProperty = array(
																	"name"	=> "orderDelivVar", 	
																	"title" => "Способ доставки", 	
																	"type"	=> "selectObject", 		
																	"value" => "",
																	"order" => "id", 
																	"order_dir" => "asc",
																	"field" => "elem_value",																	
																	"table" => $_VARS['tbl_prefix']."_deliv_variant");	
																	$elem -> createFormElem();
																	break;
										case 'orderDelivRange' : $elem -> fieldProperty = array(
																	"name"	=> "orderDelivRange", 	
																	"title" => "Расстояние доставки", 	
																	"type"	=> "selectObject", 		
																	"value" => "",
																	"order" => "id", 
																	"order_dir" => "asc",
																	"field" => "elem_value",																	
																	"table" => $_VARS['tbl_prefix']."_deliv_range");
																	$elem -> createFormElem();
																	break;
																	
										case 'orderTestVar' : $elem -> fieldProperty = array(
																	"name"	=> "orderTestVar", 	
																	"title" => "Способ дегустации", 	
																	"type"	=> "selectObject", 		
																	"value" => "",
																	"order" => "id", 
																	"order_dir" => "asc",
																	"field" => "elem_value",																	
																	"table" => $_VARS['tbl_prefix']."_test_variant");
																	$elem -> createFormElem();
																	break;	
																	
										case 'orderDelivDay' : $elem -> fieldProperty = array(
																	"name"	=> "orderDate", 	
																	"title" => "Дата", 	
																	"type"	=> "inputDate", 	
																	"class" => "inputDate",			
																	"value" => ""
																	);
																	$elem -> createFormElem();
																	break;	
																	
										case 'orderDelivTime' : $elem -> fieldProperty = array(
																	"name"	=> "orderTime", 	
																	"title" => "Время", 	
																	"type"	=> "inputTime", 
																	"class" => "inputDate",		
																	"value" => ""
																	);
																	$elem -> createFormElem();
																	break;	
										/*case 'orderTestVar' : $elem -> fieldProperty = array(
																	"name"	=> "orderTestVar", 	
																	"title" => "Способ дегустации", 	
																	"type"	=> "selectObject", 		
																	"value" => "",
																	"order" => "id", 
																	"order_dir" => "asc",
																	"field" => "elem_value",																	
																	"table" => $_VARS['tbl_prefix']."_test_variant");
																	$elem -> createFormElem();
																	break;	*/
																
										
										default : break;
									}
									
									
								}
								
							}
							else
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
