<?
include DIR_FRAMEWORK.'/class.image.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
HTML::insertMeta();

include $_SERVER['DOCUMENT_ROOT'].'/blocks/class.page.php';

$page = new PAGE();

$page -> tbl = $_VARS['tbl_prefix'].'_pages';
$page -> p_url = $_PAGE['p_url'];
$arrPage = $page -> getPageInfo();
$page -> id = $arrPage['id'];
/*$page -> p_parent_id = $arrPage['p_parent_id'];
$arrNav = $page -> getPageNav();*/

$page -> p_parent_id = $arrPage['p_parent_id'];
$arrNav = $page -> getPageNav();

//printArray($arrNav);

$arrChild = $page -> getPageChild();
//printArray($arrChild);
?>
<link rel="stylesheet" href="/css/style.css" type="text/css" />


<script language="javascript" type="text/javascript" src="/js/jquery.1.7.1.min.js"></script>

<link rel="stylesheet" href="/js/refineslide-master/refineslide.css" />
<script language="javascript" type="text/javascript" src="/js/refineslide-master/modernizr.js"></script>
<script src="/js/refineslide-master/jquery.refineslide.js"></script>
<script src="/js/refineslide-master/ios-orientation-change-fix.js"></script>

<!-- js scroll -->
<!--<link rel="stylesheet" href="/js/jscroll/jquery.jscrollpane.css" type="text/css" />
<script language="javascript" type="text/javascript" src="/js/jscroll/jquery.mousewheel.js"></script>
<script language="javascript" type="text/javascript" src="/js/jscroll/mwheelIntent.js"></script>
<script language="javascript" type="text/javascript" src="/js/jscroll/jquery.jscrollpane.min.js"></script>-->
<style>

/* Styles specific to this particular page */
.scroll-pane
{
	width: auto;
	height: 500px;
	overflow: auto;
}
/*.scroll-pane img{display:none}*/

/*.fit{height:200px; width:100%}*/
/*.fit img{max-height:100px}*/
</style>
<script language="javascript" type="text/javascript" src="/js/imagefit.js"></script>

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
		
	
	
	$('.article .fit').imagefit();
		
	//$('.scroll-pane').jScrollPane();
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
	
	
	
	
	window.onload = function () {		
		$("#loading-page").fadeToggle("slow");
	}
	
	//$('.scroll-pane img').css('display', 'block');	$('#test, .article p').imagefit();
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
	
	<div id="loading-page"></div>

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
		
			<div class="nav myriad-pro-regular"><?			
			include $_SERVER['DOCUMENT_ROOT'].'/blocks/nav.php';	
			?></div>
		
			
			<?
			
			
				//echo 'второй уровень';
				include $_SERVER['DOCUMENT_ROOT'].'/blocks/level2.php';					
						
			?> 
			
				
			
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