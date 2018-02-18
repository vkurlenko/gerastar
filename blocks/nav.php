<?
/*$page -> p_parent_id = $arrPage['p_parent_id'];
$arrNav = $page -> getPageNav();*/

//printArray($arrNav);
if($_SESSION['lang'] != 'ru')
{
	$lang = '_'.$_SESSION['lang'];
	$cur = 'Home';
}
else
{
	$lang = '';
	$cur = 'Главная';
}
	


?>
<a href="/"><?=$cur?></a><?
foreach(array_reverse($arrNav) as $k => $v)
{
	?> -> <a href="/<?=$v['p_url']?>/"><?=$v['p_title'.$lang]?></a><?
}
?> -> <a href="#" class="current"><?=$arrPage['p_title'.$lang]?></a>