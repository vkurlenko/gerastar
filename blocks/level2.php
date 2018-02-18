<?
if($_SESSION['lang'] != 'ru')
{
	$lang = '_'.$_SESSION['lang'];
}
else
{
	$lang = '';
}

foreach($arrChild as $k => $v)
{
	$page -> img_id = $v['p_img'];
	$page -> img_w  = 200;
	$page -> img_h  = 200;				
	
	$img = $page -> getImg();
	?>
	
	
	<div class="article-level2">						
		
		<a class="article-level2-img" href="/<?=$v['p_url']?>/">
			<?=$img?>
		</a>						
		
		<div class="article">
			<span class="article-level2-title myriad-pro-regular"><?=$v['p_title'.$lang]?></span>
			<div class="article-level2-text"><?=strip_tags(mb_substr($v['p_content'.$lang], 0, 800), '<p>')?></div>						
		</div>					

	</div>
	
	<div style="clear:both"></div>
	
	<?						
}	
?>
<br><br>
<div class="textPromo">		
	
	<img src="/img/pic/gerastar.png" />
	
	<div class="promo-title myriad-pro-regular"><?=$arrPage['p_title'.$lang]?></div>
	<div class="promo-text myriad-pro-regular"><?=$arrPage['p_content'.$lang]?></div>
	
	<div style="clear:both"></div>
</div>
