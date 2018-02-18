<?
/*
проверим необходимость рассылки
прочитаем шаблон
сформируем текст письма
отправим очередную пачку
*/


error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT']."/config.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/db.php";
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.subscribe.php";


// интервал рассылки пачек писем по умолчанию (сек.)
// может быть переопределен в настройках системы
if(!isset($_VARS['env']['subscribe_period']))
	$_VARS['env']['subscribe_period'] = 30;

// кол-во писем за период по умолчанию
// может быть переопределен в настройках системы
if(!isset($_VARS['env']['subscribe_count']))
	$_VARS['env']['subscribe_count'] = 50;
	
if(!isset($_VARS['env']['subscribe_subject']))
	$_VARS['env']['subscribe_subject'] = '';

if(!isset($_VARS['env']['subscribe_from']))
	$_VARS['env']['subscribe_from'] = '';
	
if(!isset($_VARS['env']['subscribe_mail_test']))
	$_VARS['env']['subscribe_mail_test'] = '';

$send = new Subscribe;

$send -> tblPrefix	= $_VARS['tbl_prefix'];
$send -> period 	= $_VARS['env']['subscribe_period'];
$send -> mailCount 	= $_VARS['env']['subscribe_count'];
$send -> subject 	= $_VARS['env']['subscribe_subject'];
$send -> from	 	= $_VARS['env']['subscribe_from'];;

//$send -> f = $_SERVER['DOCUMENT_ROOT']."/templates/cbr/tpl_subscribe.php";
$send -> f = 'http://'.$_SERVER['HTTP_HOST'].'/subscribe/'; // url страницы-листа рассылки

if(isset($mode) && $mode == 'test')
{
	$send -> modeTest = true;
}


if($send -> modeTest == false)
{
	// проверим время предыдущей рассылки 
	$send -> getLastSend(); 
	
	// получим в массив адреса для новой рассылки
	if($send -> lastSend)
	{	
		$send -> getTo();
	}
}
else
	$send -> arrTo[] = array(
		'id' 	=> 0,
		'email' => $_VARS['env']['subscribe_mail_test']
	);



// если массив адресов не пустой, то сделаем рассылку по этим адресам
if(!empty($send -> arrTo))
{
	$send -> doSend();	
}


if(!empty($send -> error))
{
	/*foreach($send -> error as $k)
		echo '<span class="msgError">'.$k.'</span>';*/
}

?> 