<?
include DIR_CLASS.'/class.menu.php';

$arr = array();

$menu = new MENU;

$menu -> mainMenu = true;
$arr = $menu -> menuSimple();

?>

<ul class="myriad-pro-regular">

	<?
	foreach($arr as $k => $v)
	{
		if(trim($v['p_redirect']) != '')
			$v['p_url'] = trim($v['p_redirect']);
		else
			$v['p_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.trim($v['p_url']).'/';
			
		$title = $v['p_title'];
		
		if($_SESSION['lang'] != 'ru')
			$title = $v['p_title_'.$_SESSION['lang']];
		?>
		<li><a href="<?=$v['p_url']?>"><?=$title?></a></li>
		<?
	}
	?>

	
	<div style="clear:both"></div>
</ul>