<?
/* слайдер - призма */

if(isset($arrPage))
{

	if(intval($arrPage['p_photo_alb']) != 0)
	{
		$_VARS['env']['alb_banner3d'] = intval($arrPage['p_photo_alb']);
	}
	else
	{
		foreach($arrNav as $k => $v)
		{
			if(intval($v['p_photo_alb']) != 0)
			{
				$_VARS['env']['alb_banner3d'] = intval($v['p_photo_alb']);
				break;
			}
		}			
	}
}

$arr = array();

if(isset($_VARS['env']['alb_banner3d']))
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic`
			WHERE alb_id = ".$_VARS['env']['alb_banner3d']."
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
			//$img -> imgWidthMax 	= 700;
			$img -> imgHeightMax 	= 310;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "crop";
			$img -> imgAlign	= "right";
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
	<ul id="images" class="rs-slider">
		<?
		foreach($arr as $k => $v)
		{
			?>
			<li class="group">
				<a href="<?=$v['url']?>">
					<?=$v['img']?>
				</a>
				<div class="rs-caption-my">
					<div class="rs-caption-content">
						<div class="rs-caption-title"><?=$v['title']?></div>
						<div class="rs-caption-text"><a href="<?=$v['url']?>"><img src="/img/pic/banner-logo.png" /></a></div>
					</div>
				</div>
				<img class="rs-caption-bg" src="/img/pic/banner-bg.png" alt=""/>
			</li>
			<?
		}
		?>
	</ul>
	<?
}
?>
