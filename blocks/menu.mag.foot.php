<?
$arrMenu = array(
	'ru' => array(
		'Лайк',
		'Поделиться',
		'Обратная связь'),
	'en' => array(
		'Like',
		'Follow',
		'Feedback')
);
?>

<div class="menu-foot myriad-pro-regular">
	<ul>
		<!--<li><a href="">Follow</a></li>-->
		<li><a href="#"><?=$arrMenu[$_SESSION['lang']][0]?></a></li>
		<li><a href="#"><?=$arrMenu[$_SESSION['lang']][1]?></a></li>
		<li><a class="feedback" href="/feedback/"><?=$arrMenu[$_SESSION['lang']][2]?></a></li>
		<div style="clear:both"></div>
	</ul>
</div>

<div class="tools-foot">
	<a class="fb" href="#"><img src="/img/tpl/fb.png" alt="" /></a>
	<div class="tools-field">
		<?
		
		?>
		<span class="page-number"><span class="cur-page">1</span>/<? if(isset($num)) echo $num;?> </span>
		<span><img src="/img/tpl/tool-tumbs.png" alt="" /></span>
		<span><img src="/img/tpl/tool-search.png" alt="" /></span>
		<span><img src="/img/tpl/tool-onepage.png" alt="" /></span>
	</div>
</div>
<div style="clear:both"></div>