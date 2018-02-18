<?
/*~~~~~~~~~~~~~~*/
/*	модули CMS	*/
/*~~~~~~~~~~~~~~*/

$_MODULES = array(
	//"pages" 			=> array("Структура сайта",				$_SERVER['DOC_ROOT']."/".$_VARS['cms_dir']."/razdel/index.php", 			true),
	"pages2" 			=> array("Структура сайта",				$_VARS['cms_modules']."/common/pages/pages.php", 			true),
	"mag" 				=> array("Журналы",						$_VARS['cms_modules']."/common/magazine/mag.php", 			true),
		/*"mag_page" 			=> array("Страницы журнала",			$_VARS['cms_modules']."/common/magazine/mag.page.php", 			true, 'mag'),
		"mag_tpl" 			=> array("Шаблоны страниц",				$_VARS['cms_modules']."/common/magazine/mag.tpl.php", 			true, 'mag'),*/
	"news" 				=> array("Новости",						$_VARS['cms_modules']."/common/news/news.php", 				false),
	"actions"			=> array("Статьи",						$_VARS['cms_modules']."/common/news/actions.php", 			false),
	"conf"				=> array("Конференции",					$_VARS['cms_modules']."/common/news/conf.php", 				false),
	
	"banners" 			=> array("Баннеры",						$_VARS['cms_modules']."/common/banners/banners.php", 		false),
	"widgets" 			=> array("Виджеты",						$_VARS['cms_modules']."/common/widgets/widgets.php", 		false),
	"masters" 			=> array("Сотрудники",					$_VARS['cms_modules']."/common/masters/masters.php", 		false),
	"master_spec"		=> array("Специализации",				$_VARS['cms_modules']."/common/masters/spec.php",	 		false),
	"vacancy" 			=> array("Вакансии",					$_VARS['cms_modules']."/common/vacancy/vacancy.php", 		false),
	"photo_alb" 		=> array("Альбомы изображений",			$_VARS['cms_modules']."/common/photo_alb/photo_alb.php", 	true),
	"photo" 			=> array("Альбом изображений",			$_VARS['cms_modules']."/common/img_man/photo3.php", 		false),
	"catalog"			=> array("Каталог",						$_VARS['cms_modules']."/common/catalog/catalog.php", 		false),
	"item_material" 	=> array("Начинки",						$_VARS['cms_modules']."/common/catalog/item_material/item.material.php", 	true, 'catalog'),
	"item_size" 		=> array("Габариты",					$_VARS['cms_modules']."/common/catalog/item_size/item.size.php", 	true, 'catalog'),
	"item_constr" 		=> array("Формы",						$_VARS['cms_modules']."/common/catalog/item_constr/uarray.php", 	true, 'catalog'),
	"item_color_1" 		=> array("Цвет массива",				$_VARS['cms_modules']."/common/catalog/item_color_1/uarray.php", 	true, 'catalog'),
	"item_color_2" 		=> array("Цвет декора",					$_VARS['cms_modules']."/common/catalog/item_color_2/uarray.php", 	true, 'catalog'),
	"test_variant" 		=> array("Способы дегустации",			$_VARS['cms_modules']."/common/catalog/test_variant/uarray.php", 	true, 'catalog'),
	"deliv_range" 		=> array("Расстояние для доставки",		$_VARS['cms_modules']."/common/catalog/deliv_range/uarray.php", 	true, 'catalog'),
	"deliv_variant" 	=> array("Способ доставки",				$_VARS['cms_modules']."/common/catalog/deliv_variant/uarray.php", 	true, 'catalog'),
	"orders" 			=> array("Заказы",		 				$_VARS['cms_modules']."/common/catalog/orders.php", 				true, 'catalog'),
	//"order_edit" 		=> array("Редактирование заказа",		$_VARS['cms_modules']."/common/catalog/order.edit.php", 			true, 'catalog'),
	"order" 			=> array("Редактирование заказа",		$_VARS['cms_modules']."/common/catalog/order.php", 			false, 'catalog'),




	
									
	"blocks" 			=> array("Инфоблоки",					$_VARS['cms_modules']."/common/info_blocks/blocks2.php", 	true),
	"users" 			=> array("Клиенты",						$_VARS['cms_modules']."/common/users/users.php", 			false),
	"notes" 			=> array("Заметки",						$_VARS['cms_modules']."/common/notes/notes.php", 			false),
	"contests" 			=> array("Конкурсы",					$_VARS['cms_modules']."/common/contests/contests.php", 		false),
	"templates" 		=> array("Шаблоны",						$_VARS['cms_modules']."/common/templates/templates.php", 	true),
	"links" 			=> array("Ссылки",						$_VARS['cms_modules']."/common/links/links.php", 			false),
	"links_cat" 		=> array("Категории ссылок",			$_VARS['cms_modules']."/common/links/links_cat.php", 		false),	
	"encyc" 			=> array("Энциклопедия",				$_VARS['cms_modules']."/common/encyc/encyc.php", 			false),
	"audio" 			=> array("Аудио файлы",					$_VARS['cms_modules']."/common/audio/audio.php", 			false),
	"video" 			=> array("Видео файлы",					$_VARS['cms_modules']."/common/video/video.php", 			false),
	"ref_master" 		=> array("Отзыв о мастере",				$_VARS['cms_modules']."/common/faq/ref_master.php",			false),
	"ref_salon" 		=> array("Отзыв о продукте",			$_VARS['cms_modules']."/common/faq/ref_salon.php",			false),
	"subscribe" 		=> array("Рассылка",					$_VARS['cms_modules']."/common/subscribe/subscribe.php", 	false),
	"subscribe_content"	=> array("Тексты рассылки",				$_VARS['cms_modules']."/common/subscribe/subscribe.content.php", 	false),
	"subscribe_news" 	=> array("Подписка на новости",			$_VARS['cms_modules']."/common/subscribe_news/subscribe_news.php", 	false),
	"sertif" 			=> array("Сертификаты",					$_VARS['cms_modules']."/maki/sertif/sertif.php", 			false),
	"spec" 				=> array("Спецпредложения",				$_VARS['cms_modules']."/maki/spec/spec.php", 				false),
	"auth" 				=> array("Администраторы",				$_VARS['cms_modules']."/common/auth/auth.php", 				true),
	"presets" 			=> array("Настройка сайта", 			$_VARS['cms_modules']."/common/settings/settings.php", 		true)

);

?>