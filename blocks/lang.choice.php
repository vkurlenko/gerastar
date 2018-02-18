<?
$lang = '';

if(isset($_SESSION['lang']) && $_SESSION['lang'] != 'ru')
	$lang = $_SESSION['lang'];
	

?>

<div class="lang">
	<a href="/ru/" <? if($lang == '') echo 'class="active"'?>><img src="/img/tpl/ru.png" /></a>
	<a href="/en/" <? if($lang == 'en') echo 'class="active"'?>><img src="/img/tpl/en.png" /></a>
</div>