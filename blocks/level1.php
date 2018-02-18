<?
if($_SESSION['lang'] != 'ru')
{
	$lang = '_'.$_SESSION['lang'];
}
else
{
	$lang = '';
}
?>
<div class="cover-list">
	<?
	$i = 0;
	foreach($arrChild as $k => $v)
	{
		$page -> img_id = $v['p_img'];
		$page -> img_w  = 200;
		$page -> img_h  = 200;				
		
		$img = $page -> getImg();
		?>
		<div class="cover article-cover">
								
			<div>
				<span class="myriad-pro-regular"><?=$v['p_title'.$lang]?></span>
				<a class="cover-img article-img" href="/<?=$v['p_url']?>/">
					<?=$img?>
				</a>						
			</div>							
			
		</div>
		<?	
		$i++;
		if($i > 2)				
		{
			?><div style="clear:both"></div><?
			$i = 0;
		}
	}				
	?>
	<div style="clear:both"></div>
</div>

<div style="clear:both"></div>

<!-- text -->
<div class="textPromo">		
	
	<img src="/img/pic/gerastar.png" />
	
	<div class="promo-title myriad-pro-regular"><?=$arrPage['p_title'.$lang]?></div>
	<div class="promo-text myriad-pro-regular"><?=$arrPage['p_content'.$lang]?></div>
	
	<div style="clear:both"></div>
</div>
<!-- /text -->