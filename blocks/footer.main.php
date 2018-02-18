<?
$arr = array(
	'url_facebook' 	=> "/img/pic/icon_fb.png", 
	'url_twitter'	=> "/img/pic/icon_tw.png",
	'url_in' 		=> "/img/pic/icon_in.png",
	'url_ok' 	=> "/img/pic/icon_ok.png"/*,
	'url_rss'		=> "/img/pic/icon_rss.png"*/
	);
?>

<div class="social">

	<?
	foreach($arr as $k => $v)
	{
		if(isset($_VARS['env'][$k]))
		{
			?><a target="_blank" href="<?=$_VARS['env'][$k]?>"><img src="<?=$v?>" /></a><?
		}
	}
	?>
</div>

<div class="textFooter">:::iblock_footer_text:::</div>

<div class="creator" style="text-align:center">&nbsp;<br /><a href="#">Web-design: VINCINELLI</a><br /><br />

<!--LiveInternet logo--><a href="//www.liveinternet.ru/click"
target="_blank"><img src="//counter.yadro.ru/logo?25.5"
title="LiveInternet: показано число посетителей за сегодня"
alt="" border="0" width="88" height="15"/></a><!--/LiveInternet-->

</div>

