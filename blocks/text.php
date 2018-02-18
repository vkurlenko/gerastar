<?
$page -> img_id = $arrPage['p_img'];
$page -> img_w  = 200;
$page -> img_h  = 200;					
$img = $page -> getImg();

if($_SESSION['lang'] != 'ru')
{
	$lang = '_'.$_SESSION['lang'];
}
else
{
	$lang = '';
}

?>


<div class="article-level3">
	<?
	/*if($img != '')
	{
	?> 
	<div class="article-level2-img">
		<?=$img?>
	</div>
	<?
	}*/
	?>
	
	<div class="article">
		<span class="article-level3-title myriad-pro-regular"><?=$arrPage['p_title'.$lang]?></span>
		<div class="article-level3-text fit"><?=$arrPage['p_content'.$lang]?></div>						
	</div>		
</div>