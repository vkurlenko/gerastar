<?

$alb = new GALLERY();
$alb -> tbl = $_VARS['tbl_prefix'].'_pic_catalogue';
//$alb -> alb_mark = $arr_mark[3];
$alb -> alb_mark = $arr_mark[$_PAGE['p_url']];
$arr = $alb -> getAlbList();

$alb -> thumb_w = 100;
$alb -> thumb_h = 100;

?>
<style>
.my-simple-gallery img{width:60px; height:60px}
</style>
<?
foreach($arr as $k => $v)
{

	$alb -> pic_alb_id = $v['alb_name'];	
	$arr_pic = $alb -> getAlbItems();
	
	$n = substr(count($arr_pic), strlen(count($arr_pic)) - 1);	
	if(intval($n) == 1) $end = 'ия';
	elseif(in_array(intval($n), array(2,3,4)) && (strlen(count($arr_pic)) == 1 || count($arr_pic) > 20)) $end = 'ии';
	else $end = 'ий';
	
	

	?>
	<div class="gAlbum">
	
		<p class="gTitle"><?=$v['alb_title']?></p>
		<span class="gPhotoNum"><?=count($arr_pic)?> фотограф<?=$end?></span>
		
	
			<div class="my-simple-gallery" itemscope itemtype="http://schema.org/ImageGallery">	
				<?
				$i = 0;
				foreach($arr_pic as $k1 => $v1)
				{
					?>
					<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
					  <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$v1['pic_orig']?>" itemprop="contentUrl" data-size="<?=$v1['pic_size']?>">
						  <!--<img src="https://farm3.staticflickr.com/2567/5697107145_3c27ff3cd1_m.jpg" itemprop="thumbnail" alt="Image description" />-->
						  <?=$v1['pic_thumb']?>
					  </a>
					  <figcaption itemprop="caption description"><?=$v1['pic_title']?></figcaption>
					</figure>
					<?
					$i++;
					if($i == 4)
						break;
				}			
				?>		
				
				<!--<a class="gAlbMore" href="#">еще</a>-->
				
					
			</div>
			
			<a class="gAlbLink" href="/album/<?=$v['alb_name']?>/"><span><img src="/img/m/arrow-right-grey.png"></span></a>
			
			
			<div style="clear:both"></div>
	</div>
	
	<?
	
	//break;
}


include_once 'psw.php';
?>






