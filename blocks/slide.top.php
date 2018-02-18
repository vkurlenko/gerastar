<?
/* слайдер в шапке на главной */


$arr = array();
if(isset($_VARS['env']['alb_banner_top']))
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic`
			WHERE alb_id = ".$_VARS['env']['alb_banner_top']."
			ORDER BY order_by ASC";
			
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_assoc($res))
		{
			
			$img = new Image();
			$img -> imgCatalogId 	= $row['alb_id'];
			$img -> imgId 			= $row['id'];
			$img -> imgAlt 			= $row['name'];
			$img -> imgWidthMax 	= 1040;
			$img -> imgHeightMax 	= 112;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "crop";
			$pic = $img -> showPic();
		
		
			$arr[] = array(
				'img' 	=> $pic,
				'title' => $row['name'],
				'url'	=> $row['url']
			);
		}
	}		
}

if(!empty($arr))
{
	?>
	<ul id="images2" class="rs-slider">
		<?
		foreach($arr as $k => $v)
		{
			?>
			<li class="group">
				<a target="_blank" href="<?=$v['url']?>">
					<?=$v['img']?>
				</a>				
			</li>
			<?
		}
		?>
		<!--<div style="clear:both"></div>-->
	</ul>
	<div style="clear:both"></div>
	<?
}
?>
<script type="text/javascript">
	/*var slideshow=new TINY.slider.slide('slideshow',{
		id:'slider',
		auto:3,
		resume:false,
		vertical:true,
		navid:null,
		activeclass:'current',
		position:0,
		rewind:false,
		elastic:false,
		left:null,
		right:null
	});*/
</script>

