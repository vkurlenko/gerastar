<?
include $_SERVER['DOCUMENT_ROOT'].'/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/cms9/modules/framework/class.image.php';


//printArray($_POST);


// получим теги <a/> всех картинок их данного альбома в массив
function getPics($alb_id, $pic_id)
{
	
	$arr = array();
		
	$arr[] = array(
				'pic_tag' => '<a href="#" class="" id="0"><img src="/cms9/img/no_img.png" width="70" height="70" title="Без картинки"></a>'
				
			);

	$sql = "SELECT * FROM `".SITE_PREFIX."_pic`
			WHERE alb_id = ".$alb_id;
	$res = mysql_query($sql);
	
	//echo $sql;
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_assoc($res))
		{
			$img = new Image();
			$img -> imgCatalogId 	= $alb_id;
			$img -> imgId 			= $row['id'];
			$img -> imgTitle		= $row['name'];
			$img -> imgWidthMax 	= 70;
			$img -> imgHeightMax 	= 70;	
			$img -> imgTransform	= "crop";
			$img_html = $img -> showPic();
			
			if($pic_id == $row['id'])
				$cls = "selected";
			else
				$cls = '';
			
			
			$arr[] = array(
				'pic_tag' => '<a href="#" class="'.$cls.'" id="'.$row['id'].'">'.$img_html.'</a>'
				
			);
		}
	}
	
	return $arr;


}

// выпадающий список альбомов
function getAlbList($alb_id, $field_id)
{
	global $_VARS;
	
	$html_ = "
	<select name='alb_id_".$field_id."' id='alb_id-".$field_id."'>
		<option value='0'>Без альбома</option>";			
		$sql = "SELECT * FROM `".$_VARS['tbl_photo_alb_name']."` 
				WHERE 1";
		$res = mysql_query($sql);
		  
		while($row = mysql_fetch_array($res))
		{		
			if($alb_id == $row['id'])
			{
				$selected = " selected='selected' ";
			}
			else $selected = "";			  	
			$html_ .= "
			<option value='".$row['id']."' ".$selected." >".$row['alb_title']."</option>";
		}
	$html_ .= "</select>";	
	
	return $html_;
}
?>

<style>
a{display:block; float:left; margin:5px}
a.selected img{border:1px solid red;}
</style>

<script language="javascript">
$(document).ready(function()
{
	// выбор картинки из альбома по клику
	$('#picList-<?=$_POST['field_id']?> a').click(function()
	{
		$('#pic_id-<?=$_POST['field_id']?>').attr('value', $(this).attr('id'))
		$('a').removeClass('selected')
		$(this).addClass('selected')
		return false		
	})
	
	// запомним выбранную картинку
	$('#select-<?=$_POST['field_id']?>').click(function()
	{
		$('#<?=$_POST['field_id']?>').attr('value', $('#pic_id-<?=$_POST['field_id']?>').attr('value'))
		$('.selectPic-<?=$_POST['field_id']?>').html($('#'+$('#pic_id-<?=$_POST['field_id']?>').attr('value')).html())
		$('#picList-<?=$_POST['field_id']?>').hide()
		
		return false		
	})
	
	// закроем окно выбора картинок
	$('.close').click(function()
	{
		//alert('close')
		//$(this).parents('.picList').hide()
		$('#picList-<?=$_POST['field_id']?>').hide()
	})
	
	
	// смена альбома
	$('#alb_id-<?=$_POST['field_id']?>').change(function()
	{
		$('#picList-<?=$_POST['field_id']?>').load('/cms9/modules/common/img_man/ajax.image.selector.php',
		{
			'alb_id' 	: $(this).attr('value'),
			'field_id'	: '<?=$_POST['field_id']?>',
			'pic_id'	: $('#<?=$_POST['field_id']?>').attr('value')
			
		}).show()
	})
	
	
})
</script>


<?
if(isset($_POST['alb_id']))
{

	$alb_id = $_POST['alb_id'];
	
	if(isset($_POST['pic_id']))
		$pic_id = $_POST['pic_id'];
	else
		$pic_id = 0;
		
	$arr = getPics($alb_id, $pic_id);	
	?>
	
	
	<!-- выпадающий список альбомов -->
	<div>
		<?=getAlbList($alb_id, $_POST['field_id'])?>
	</div>
	<!-- /выпадающий список альбомов -->
	
	
	
	<!-- картинки данного альбома -->
	<div>
		<?
		foreach($arr as $k => $v)
		{
			echo $v['pic_tag'];
		}
		?>
	</div>
	<!-- /картинки данного альбома -->
	<?	
}
?>

<div style="clear:both"></div>
<div>
	<input id='pic_id-<?=$_POST['field_id']?>'	type="hidden"  value="<?=$pic_id?>">
	<input class="close" type="button" value='Отмена' >
	<input id="select-<?=$_POST['field_id']?>"  type="button" value='Выбрать' >	
</div>



