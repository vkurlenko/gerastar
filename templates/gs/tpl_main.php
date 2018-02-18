<?
include DIR_FRAMEWORK.'/class.image.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
HTML::insertMeta();
?>
<link rel="stylesheet" href="/css/style.css" type="text/css" />


<script language="javascript" type="text/javascript" src="/js/jquery.1.7.1.min.js"></script>


<?
// js cufon fonts
include $_SERVER['DOCUMENT_ROOT'].'/blocks/js.font.php';
?>


<script language="javascript" type="text/javascript">
/* формат главного меню */
function formatMenuMain()
{
	$('#menuFoot > ul').css('width', 'auto')
	
	$('#menuFoot > ul > li').css({
		'margin': '0',
		'float' : 'left'
	})
	
	// ширина гл.меню
	var divMainWidth = $('#menuFoot').width()
	
	var menuMainWidth = $('#menuFoot > ul').width()
	
	var free = divMainWidth - menuMainWidth
	
	var n = $('#menuFoot > ul > li').size()
	
	var margin_right = free / (n - 1);
	
	if(margin_right < 10)
	{
		$('#menuFoot > ul > li').css('float', 'none')
	}
	else
	{
		$('#menuFoot > ul').css('width', '100%')
		$('#menuFoot > ul > li').css('margin-right', margin_right)		
		$('#menuFoot > ul > li').eq(n - 2).css({'margin-right' : 0})	
		$('#menuFoot > ul > li').last().css({'margin-right' : 0, 'float' : 'right'})
	}	
	
	if(divMainWidth < 500)
	{
		$('.textPromo img, .promo-title, .promo-text, #menuFoot ul').addClass('min')
	}
	else
		$('.textPromo img, .promo-title, .promo-text, #menuFoot ul').removeClass('min')
}

$(document).ready(function()
{
	formatMenuMain()
		
	var resizeTimer = null;
	
	$(window).bind('resize', function()
	{
		
		if (resizeTimer != null) 
		{
			clearTimeout(resizeTimer);
			resizeTimer = null;
		}
 	   resizeTimer = setTimeout(formatMenuMain, 100);
	})	
})
</script>

</head>

<body>

<?
// счетчик Yandex
include $_SERVER['DOCUMENT_ROOT'].'/blocks/ya.php';

// счетчик Google
include $_SERVER['DOCUMENT_ROOT'].'/blocks/ga.php';

// счетчик liveinternet
include $_SERVER['DOCUMENT_ROOT'].'/blocks/li.php';
?>


<div id="mainDiv" class="tplMain">

	<?
	include $_SERVER['DOCUMENT_ROOT'].'/blocks/lang.choice.php';
	?>
	
	<!-- баннер в шапке -->
	<div id="header">
		<div class="bannerTop inner">
		<?
		include $_SERVER['DOCUMENT_ROOT'].'/blocks/slide.top.php';
		?>
		</div>
	</div>
	<!-- /баннер в шапке -->

	<!-- слайдер 3D -->
	<div id="banner3d">
		<div class="banner inner">
		<?
		include $_SERVER['DOCUMENT_ROOT'].'/blocks/slide.3d.php';
		?>
		</div>
	</div>
	<!-- /слайдер 3D -->
	
	
	<!-- контент -->
	<div id="content">
		<div class="main-content inner " >
			
			<!-- журналы -->
			<?
			include $_SERVER['DOCUMENT_ROOT'].'/blocks/mag.list.php';
			?>
			<!-- /журналы -->
			
			
			
			<!-- промотекст -->
			<div class="textPromo">		
				
				<img src="/img/pic/gerastar.png" />
				
				<div class="promo-title myriad-pro-regular">:::iblock_promo_title:::</div>
				<div class="promo-text myriad-pro-regular">:::iblock_promo:::</div>
				
				<div style="clear:both"></div>
			</div>
			<!-- /промотекст -->		
			
			<div style="clear:both"></div>
			
			
			<div id="menuFoot">
				<?
				include $_SERVER['DOCUMENT_ROOT'].'/blocks/menu.main.foot.php';
				?>				
				<div style="clear:both"></div>
			</div>
			
			
			
		</div>			
	</div>
	<!-- /контент -->
	
	
	
	
	<div id="footer">
		<div class="inner">
			
			<?
			include $_SERVER['DOCUMENT_ROOT'].'/blocks/footer.main.php';
			?>
			
			<div style="clear:both"></div>
		
		</div>
	</div>
</div>
	
	<?
	// js slider
	include $_SERVER['DOCUMENT_ROOT'].'/blocks/js.refineslide.php';
	?>

</body>
</html>