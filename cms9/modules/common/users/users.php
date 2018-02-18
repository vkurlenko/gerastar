<?php
/*~~~~~~~~~~~~~~~*/
/* CMS ВИДЖЕТЫ   */
/*~~~~~~~~~~~~~~~*/

session_start();
error_reporting(E_ALL);

include_once $_SERVER['DOC_ROOT']."/config.php" ;
include_once $_SERVER['DOC_ROOT']."/fckeditor/fckeditor.php" ;
include_once $_SERVER['DOC_ROOT']."/db.php";
include_once "users.functions.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.html.php";
include_once $_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.image.php";

//printArray($_POST);


/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/
$_MODULE_PARAM = array(
	"name"			=> "users",
	"tableName" 	=> $_VARS['tbl_prefix']."_users",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Пользователи сайта",
	"TEXT_ADD_ITEM"	=> "Добавить нового пользователя",
	"TEXT_EDIT_ITEM"=> "Редактировать пользователя"		
);
/*~~~~~~~~~~~~~~~~~~~*/
/* /параметры модуля */
/*~~~~~~~~~~~~~~~~~~~*/


/*$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."`
		WHERE 1";
		
$res = mysql_query($sql);

while($row = mysql_fetch_assoc($res))
{
	
	$sql = "UPDATE `".$_MODULE_PARAM['tableName']."`
			SET user_addr_1 = '".serialize(array($row['user_addr_1'], $row['user_addr_2']))."'
			WHERE id = ".$row['id'];
			
	$sql = "UPDATE `".$_MODULE_PARAM['tableName']."`
			SET user_addr_2 = ''
			WHERE id = ".$row['id'];
			
	$res1 = mysql_query($sql);
}*/

check_access($_MODULE_PARAM['userAccess']);

$tags = "<strong><a><br><span><img><embed><em>";


/*~~~~~~~~~~~~~~~~~~~~~~*/
/* структура таблицы БД */
/*~~~~~~~~~~~~~~~~~~~~~~*/
$arrTableFields = array(
	"id"				=> "int auto_increment primary key",
	"user_login" 		=> "text", 		// логин
	"user_pwd" 			=> "text", 		// пароль
	"user_name"			=> "text", 		// имя
	"user_patr"			=> "text", 		// фамилия
	"user_surn" 		=> "text", 		// отчество
	"user_birth_day"	=> "date not null", // дата рождения
	"user_addr_1"		=> "text", 		// адрес 1
	"user_addr_2"		=> "text", 		// адрес 2	
	"user_mail" 		=> "text", 		// почта
	"user_phone" 		=> "text", 		// телефон
	"user_card"			=> "enum('0', '1') not null", 	// наличие карты
	"user_card_number"	=> "text",
	"user_discount"		=> "int default '0' not null", 	// скидка клиента
	"user_block" 		=> "enum('1', '0') not null",
	"user_register"		=> "enum('0', '1') not null", // ?
	"user_calend"		=> "text", // календарь (array)
	"user_pay" 			=> "text", // список покупок (array)
	"user_fav_item"		=> "text", // избранные товары (array)
	"user_reg_date" 	=> "datetime not null",	// дата регистрации	
	"user_last_visit" 	=> "datetime not null"	// дата последнего посещения
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
	"user_login"	=> array(
						"name"	=> "user_login", 	
						"title" => "Login", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"user_pwd"	=> array(
						"name"	=> "user_pwd", 	
						"title" => "Password", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"user_name"	=> array(
						"name"	=> "user_name", 	
						"title" => "Имя", 	
						"type"	=> "inputText", 		
						"value" => "",						
						"class" => "inputText"),
	"user_surn"	=> array(
						"name"	=> "user_surn", 	
						"title" => "Отчество", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"user_patr"	=> array(
						"name"	=> "user_patr", 	
						"title" => "Фамилия", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	"user_birth_day"	=> array(
						"name"	=> "user_birth_day", 	
						"title" => "Дата рождения", 	
						"type"	=> "inputDate", 		
						"value" => date("Y-m-d"),
						"class" => "inputDate"),
	"user_addr_1"	=> array(
						"name"	=> "user_addr_1", 	
						"title" => "Адреса доставки", 	
						"type"	=> "textareaText", 		
						"value" => "",
						"class" => "inputText"),
	/*"user_addr_2"	=> array(
						"name"	=> "user_addr_2", 	
						"title" => "Адрес 2", 	
						"type"	=> "textareaText", 		
						"value" => "",
						"class" => "inputText"),*/
	"user_mail"	=> array(
						"name"	=> "user_mail", 	
						"title" => "Почта", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),
	
	"user_phone"	=> array(
						"name"	=> "user_phone", 	
						"title" => "Телефон", 	
						"type"	=> "inputPhone", 		
						"value" => "",
						"class" => "inputCardNumber",
						"note"	=> array("warning", "только цифры")),
	"user_card"		=> array(
						"name"	=> "user_card", 	
						"title" => "Essence Card активна", 	
						"type"	=> "inputCheckbox", 		
						"value" => false),	
	"user_card_number"	=> array(
						"name"	=> "user_card_number", 	
						"title" => "Номер карты", 	
						"type"	=> "inputCardNumber", 		
						"value" => '0000000000000000',
						"class" => "inputCardNumber"),
	"user_discount"	=> array(
						"name"	=> "user_discount", 	
						"title" => "Размер скидки (%)", 	
						"type"	=> "inputText", 		
						"value" => "15",
						"class" => "inputText"),	
	"user_register"	=> array(
						"name"	=> "user_register", 	
						"title" => "Зарегистрирован на сайте", 	
						"type"	=> "inputCheckbox", 		
						"value" => true),				
	"user_block"	=> array(
						"name"	=> "user_block", 	
						"title" => "Заблокирован", 	
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
		
		if($v["type"] == "inputCardNumber")
		{
			$arrData[$k] = implode($_POST[$k]);
		}
		
		if($v["type"] == "inputPhone")
		{
			$arrData[$k] = implode($_POST[$k]);
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
		
		if($v["type"] == "inputCardNumber")
		{
			$arrData[$k] = implode($_POST[$k]);
		}
		
		if($v["type"] == "inputPhone")
		{
			$arrData[$k] = implode($_POST[$k]);
		}
		
		if($v["name"] == "user_addr_1")
		{
			$arr = array_diff($_POST[$k], array(''));
			$arrData[$k] = serialize($arr);
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
	/* ВВОД НОМЕРА ТЕЛЕФОНА */
	//$('.inputCardNumber:eq(1)').attr('disabled', true)
		
	$('.inputCardNumber').keyup(function()
	{
		var thisLen = $(this).val().length;
		var maxLen  = $(this).attr('maxlength')
		
		if(thisLen == maxLen)
		{
			//alert(thisLen)
			$('.inputCardNumber:eq(1)').attr('disabled', false).focus()
		}
		
		//var filled = fill();
		
		/*if(filled != false)
			$('#userPhone').attr('value', '+7 '+filled)*/
	})
	/* /ВВОД НОМЕРА ТЕЛЕФОНА */

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
			$arrFormFields[$k]["value"] = $row[$k];
		
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
							
							if($v['name'] == 'user_addr_1')
							{
								
								$arr = unserialize($v['value']);
								//printArray(unserialize($arr[0]));
								$i = 0;
								$n = $elem -> fieldProperty["name"];
								if(!empty($arr))
								{
									
									foreach($arr as $k1 => $v1)
									{
										$elem -> fieldProperty["name"] = $n.'['.$i++.']';
										$elem -> fieldProperty["value"] = $v1;
										$elem -> fieldProperty["type"] = 'textareaText';
										$elem -> fieldProperty["note"]	= array("warning", "для удаления адреса просто оставьте поле пустым");
										echo 'Адрес №'.$i.'<br>';
										$elem -> createFormElem();
										echo '<br>';
									}
									
								}
								$elem -> fieldProperty["name"] = $n.'['.$i++.']';
								$elem -> fieldProperty["value"] = '';
								$elem -> fieldProperty["type"] = 'textareaText';
								echo 'Адрес №'.$i.'<br>';
								$elem -> createFormElem();
								
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
