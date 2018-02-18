<?
//fwrite($f, 'подключаем config \n');
//error_reporting(E_ALL);

/************************************/
/*				Define 				*/
/************************************/

define('SITE_PREFIX', 'gs');

define('DIR_ROOT'	, dirname(__FILE__));
define('HOST'		, $_SERVER['HTTP_HOST']);
define('SL'			, '/');
define('NL'			, "\n");
define('BR'			, "<br>");


/******* Directory ********/

define('DIR_CSS'	, SL.'css');
define('DIR_IMG'	, SL.'img');
define('DIR_BLOCKS'	, DIR_ROOT.SL.'blocks');
define('DIR_PIC'	, SL.'pic_catalogue');
define('DIR_JS'		, SL.'js');
define('DIR_ICON'	, SL.'cms9/icon');
define('DIR_FRAMEWORK', DIR_ROOT.SL.'cms9/modules/framework');


$class_cfg = array(
	'gerastar' 	=> 'z:/home/interface/class',
	'magazine.gerastar.ru'	=> DIR_ROOT.'/class'
);

define('DIR_CLASS'	, $class_cfg[HOST]);

/******* /Directory ********/


/******* Mysql ******/
include_once DIR_CLASS.'/class.db.php';

$db_cfg = array(
	'gerastar' => array(
		'localhost', 			// db host
		SITE_PREFIX.'_db', 			// db name
		SITE_PREFIX.'_user', 	// db user
		SITE_PREFIX.'_pwd'		// db_pwd
	),
	'magazine.gerastar.ru' => array(
		'vinci-1.mysql', 			// db host
		'vinci-1_gerastar2', 			// db name
		'vinci-1_gerastar', 	// db user
		'9e9dy8do'		// db_pwd
	)
);


DB::db_set($db_cfg);
DB::db_connect();
DB::db_set_names();


/************************************/
/*				/ Define			*/
/************************************/

$_SERVER['DOC_ROOT'] = DIR_ROOT;

$_VARS = array(
	'cms_dir' 			=> 'cms9', 		// папка с CMS 
	'cms_modules'		=> 'modules',	// функциональные модули  
	'cms_pic_in_page'	=> 100, 		// кол-во выводимых картинок в менеджере картинок

	'multi_lang'		=> true,		// многоязычный сайт

	'mail_admin'		=> "victor@vincinelli.com", 	// e-mail администратора

	'tbl_prefix'		=> SITE_PREFIX,					// префикс для уникальных таблиц CMS
	'tbl_pages_name'	=> SITE_PREFIX."_pages",		// имя таблицы страниц
	'tbl_cms_users'		=> SITE_PREFIX."_cms_users",	// имя таблицы разделов
	'tbl_template_name' => SITE_PREFIX."_templates",	// имя таблицы шаблонов 
	'tbl_photo_alb_name'=> SITE_PREFIX."_pic_catalogue",// имя таблицы фотоальбомов
	'tbl_photo_name'	=> SITE_PREFIX."_pic_",			// префикс таблицы фотоальбома
	'tbl_news'			=> SITE_PREFIX."_news",			// имя таблицы новостей
	'tbl_iblocks'		=> SITE_PREFIX."_iblocks",		// имя таблицы инфоблоков

	'tpl_dir'			=> "templates/".SITE_PREFIX,	// папка с шаблонами
	'photo_alb_dir'		=> "pic_catalogue", 			// папка с фотоальбомами
	'photo_alb_sub_dir' => SITE_PREFIX."_pic_",
	
	'audio_alb_dir'		=> "files/audio", 				// папка с аудио-файлами
	'video_alb_dir'		=> "files/video", 				// папка с видео-файлами 
	
	// места баннеров
	'banners_place' 	=> array(	
							"banner_line_1" => "пара баннеров (линейка 1)",
							"banner_line_2" => "большой баннер (линейка 2)",
							"banner_line_3" => "пара баннеров (линейка 3)",
							"banner_line_4" => "большой баннер (линейка 4)",
							"banner_line_5" => "пара баннеров (на текстовой странице 1)",
							"banner_line_6" => "пара баннеров (на текстовой странице 2)",
							"banner_line_7" => "пара баннеров (на текстовой странице 3)",
							"banner_line_8" => "пара баннеров (на текстовой странице 4)"
						),
	
	// группы пользователей CMS 
	'arrGroups' 		=> array(
							"admin" 	=> array("Администраторы"),
							"manager" 	=> array("Менеджеры"),	
							"editor"	=> array("Контент-менеджеры"),
							"finans"	=> array("Бухгалтеры")
						)
);






// иконки cms
$_ICON = array(
	"down"		=> DIR_ICON."/down2.png"	,
	"up" 		=> DIR_ICON."/up.png"		,
	"del" 		=> DIR_ICON."/stop.png"		,
	"edit" 		=> DIR_ICON."/hdsave.png"	,
	"next" 		=> DIR_ICON."/file copy.png",
	"next_empty"=> DIR_ICON."/file.png"	,
	"main_menu"	=> DIR_ICON."/actions.png"	,
	"image"		=> DIR_ICON."/image.png"	,
	"user_ok"	=> DIR_ICON."/accept.png"	,
	"user_block"=> DIR_ICON."/delete.png"	,
	"add_item"	=> DIR_ICON."/addd.png"		,
	"tpl_index"	=> DIR_ICON."/flag_green.png",
	"tpl_def"	=> DIR_ICON."/flag_blue.png",
	"redo"		=> DIR_ICON."/redo.png"		,
	"lock"		=> DIR_ICON."/lock.png",
	"money"		=> DIR_ICON."/creditcard.png",
	"tick"		=> DIR_ICON."/tick.png"		,
	"users1"	=> DIR_ICON."/users1.png"	,
	"pictures"  => DIR_ICON."/pictures.png",
	"info"		=> DIR_ICON."/info.png",
	"warning"		=> DIR_ICON."/file warning.png"
);

$arrIcon = array(
	'default' 		=> array('empty.png', 'tick.png'),	
	'p_main_menu' 	=> array('empty.png', 'actions.png'),
	'p_protect' 	=> array('empty.png', 'lock.png'),
	'user_block' 	=> array('empty.png', 'lock.png')	
);
/*~~~~~~~~~ /параметры CMS ~~~~~~~~~~~*/

/* // группы пользователей CMS 
$_VARS['arrGroups'] = array(
	"admin" 	=> array("Администраторы"),
	"manager" 	=> array("Менеджеры"),	
	"editor"	=> array("Контент-менеджеры"),
	"finans"	=> array("Бухгалтеры")
); */


// статусы заказа
/* $_VARS['order_status'] = array(
	'raw' 		=> 'не обработан', 
	'confirmed'	=> 'подтвержден', 
	'accepted'	=> 'принят', 
	'shipped'	=> 'отгружен', 
	'paid'		=> 'оплачен'
); */

// типовые формы
/*$_VARS['item_constr'] 	= array(
	"circle" => "круглая", 
	"square" => "квадратная"
	);
	

	
$_VARS['arrColor1']		= array(
	"white" => "белый", 
	"cream" => "кремовый", 
	"choko" => "шоколадный"
	);
	
$_VARS['arrColor2']	= array(
	"white" => "белый", 
	"cream" => "кремовый", 
	"choko" => "шоколадный"
	);*/

//fwrite($f, 'config  считаем из БД ассоциативные массивы \n');


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* считаем из БД ассоциативные массивы  */	
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
// имя массива => имя таблицы БД	
$arr = array(
	'item_constr' 	=> 'item_constr', // формы
	'arrColor1'		=> 'item_color_1',// цвет массива
	'arrColor2'		=> 'item_color_2' // цвет декора
);

foreach($arr as $k => $v)
{
	$_VARS[$k] = array();

	$sql = "SELECT * FROM `".SITE_PREFIX."_".$v."`
			WHERE elem_show = '1'
			ORDER BY elem_order ASC";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_assoc($res))
		{
			$_VARS[$k][$row['elem_key']] = $row['elem_value'];
		}
	}
}
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* /считаем из БД ассоциативные массивы  */	
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


//fwrite($f, 'config переменные, редактируемые через cms \n');


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*~~~ переменные, редактируемые через cms ~~~*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
$_VARS['env'] = array();

// по умолчанию
$_VARS['env']['photo_alb_other'] = 1; // фотоальбом "Разное"

 // переопределение из БД
$sql = "SELECT * FROM `".SITE_PREFIX."_presets` 
		WHERE 1";
$res = mysql_query($sql);
if($res)
{
	while($row = mysql_fetch_array($res))
	{
		$_VARS['env'][$row['var_name']] = $row['var_value'];
	}
}
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*~~~/переменные, редактируемые через cms ~~~*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
//fwrite($f, 'конец config \n');
?>