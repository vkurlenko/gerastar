<?
include_once $class_cfg[HOST].'/class.menu.php';
?>
<ul id="menu-book-top">

	<?
	$m = new MENU();
	
	$m -> mainMenu = true;
	
	$lang = '';
	if(isset($_SESSION['lang']) && $_SESSION['lang'] != 'ru')
		$lang = '_'.$_SESSION['lang'];
	$m -> lang = $lang;
	$arr = $m -> menuSimple();
	
	//printArray($arr);
	
	$i = 1;
	foreach($arr as $k => $v)
	{
		$cls = '';
		if($i == count($arr))
			$cls = 'last';
		?><li class="li<?=$i?> <?=$cls?>"><a href="/<?=$v['p_url']?>/"><?=$v['p_title']?></a></li>
		<?
		$i++;
	}
	?>
	<div style="clear:both"></div>
</ul>