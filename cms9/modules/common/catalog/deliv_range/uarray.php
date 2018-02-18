<?
/*~~~~~~~~~~~~~~~~~~*/
/* параметры модуля */
/*~~~~~~~~~~~~~~~~~~*/
$_MODULE_PARAM = array(
	"name"			=> 'deliv_range',
	"tableName" 	=> $_VARS['tbl_prefix']."_deliv_range",
	"userAccess" 	=> array("admin", "editor")	
);

$_TEXT = array(
	"TEXT_HEAD"		=> "Расстояние для доставки",
	"TEXT_ADD_ITEM"	=> "Добавить вариант",
	"TEXT_EDIT_ITEM"=> "Редактировать вариант"		
);
/*~~~~~~~~~~~~~~~~~~~*/
/* /параметры модуля */
/*~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* структура формы редактирования записи */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
$arrFormFields = array(
	"id"			=> array(
						"name"	=> "id", 				
						"title" => "id", 					
						"type"	=> "inputHidden", 		
						"value" => ""),
	"elem_value"	=> array(
						"name"	=> "elem_value", 	
						"title" => "Расстояние для доставки", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText"),	
	"elem_key"	=> array(
						"name"	=> "elem_key", 	
						"title" => "Метка", 	
						"type"	=> "inputText", 		
						"value" => "",
						"class" => "inputText",
						"readonly"=> true,
						"note"	=> array("warning", "Метку можно задать вручную (символы a-z), иначе она будет создана автоматически. Изменение метки может привести к некорректной работе сайта!")
						),						
	"elem_descr"	=> array(
						"name"	=> "elem_descr", 	
						"title" => "Стоимость (руб.)", 	
						"type"	=> "inputText", 
						"class" => "inputText",		
						"value" => "0"),
	"elem_show"		=> array(
						"name"	=> "elem_show", 	
						"title" => "Показывать на сайте", 	
						"type"	=> "inputCheckbox", 		
						"value" => true)	
);
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* /структура формы редактирования записи */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

include $_SERVER['DOCUMENT_ROOT'].'/cms9/modules/common/array/uarray.php';
?>