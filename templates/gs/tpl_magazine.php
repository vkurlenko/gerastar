<?
include DIR_FRAMEWORK.'/class.image.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?
			HTML::insertMeta();
			?>
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"/>

		<script type="text/javascript" src="/modules/book/booklet/jquery-2.1.0.min.js"></script>
		<script type="text/javascript" src="/modules/book/booklet/jquery.easing.1.3.js" ></script>
		<script type="text/javascript" src="/modules/book/booklet/jquery.booklet.latest.min.js"></script>

		<link rel="stylesheet" href="/modules/book/booklet/jquery.booklet.latest.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/modules/book/css/style.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen"/>
		
		<style>
		.p-main{position:relative; height:100%; font-size:26px}

		/*.image div{border:#00FF33 1px dashed}
		.text div{border:#ccc 1px dashed}*/
		
		.tpl-1 .text-1, 
		.tpl-1 .text-2, 
		.tpl-1 .text-3{overflow:hidden; position:absolute; width:100%; color:#000; font-family:arial; z-index:2}
		
		.tpl-1 .img-1,
		.tpl-1 .img-2{position:absolute; width:100%; z-index:2}
		</style>
		
		<script type="text/javascript" src="/modules/fittext/jquery.fittext.js"></script>

		
		<script type="text/javascript" src="http://cufon.shoqolate.com/js/cufon-yui.js" ></script>
		<script type="text/javascript" src="/js/myriad-pro.cufonfonts.js"></script>
		
		<script type="text/javascript">
			Cufon.replace('.myriad-pro-regular', { fontFamily: 'Myriad Pro Regular', hover: true });
			Cufon.replace('.myriad-pro-condensed', { fontFamily: 'Myriad Pro Condensed', hover: true });
			Cufon.replace('.myriad-pro-semibold-italic', { fontFamily: 'Myriad Pro Semibold Italic', hover: true });
			Cufon.replace('.myriad-pro-semibold', { fontFamily: 'Myriad Pro Semibold', hover: true });
			Cufon.replace('.myriad-pro-condensed-italic', { fontFamily: 'Myriad Pro Condensed Italic', hover: true });
			Cufon.replace('.myriad-pro-bold-italic', { fontFamily: 'Myriad Pro Bold Italic', hover: true });
			Cufon.replace('.myriad-pro-bold-condensed-italic', { fontFamily: 'Myriad Pro Bold Condensed Italic', hover: true });
			Cufon.replace('.myriad-pro-bold-condensed', { fontFamily: 'Myriad Pro Bold Condensed', hover: true });
			Cufon.replace('.myriad-pro-bold', { fontFamily: 'Myriad Pro Bold', hover: true });
		</script>
		
		<script type="text/javascript" src="/js/script.js"></script>
		
		
		
		
		<script language="javascript">		
		$(document).ready(function()
		{
			$('#cover img, .img-bg img, .img-area img').attr({'width' : '', 'height' : ''})			
			$('.img-bg img, .img-area img').css({'width' : '100%', 'height' : '100%'})	
			
			$('#menu-book-top a, .feedback').click(function()
			{
				$('#loadBox').load($(this).attr('href'))

				//Получаем смещение сверху для целевой секции
				var target_offset = $('#underBlock').offset();
				var target_top = target_offset.top;
						
				//Переходим в целевую секцию установкой позиции прокрутки страницы в позицию целевой секции
				$('html, body').animate({scrollTop:target_top}, 1000);
				return false
			})
			
			
			
			// инициализация журнала
			$('.box, .box-inner').height(getBookWrapperH())
			
			
			myBook();
			
			
			
			var resizeTimer = null;
	
			$(window).bind('resize', function()
			{				
				if (resizeTimer != null) 
				{
					clearTimeout(resizeTimer);
					resizeTimer = null;
				}
			   resizeTimer = setTimeout(resize, 100);
			})	
			
			
			
			
			var trigger = false;
			
			$('#mybook').click(function(e)
			{
				
				if(trigger == false)
				{
				
					// увеличение масштаба
					$('.box-inner').css({
						'height' : $('.box-inner').height() * 2,
						'width' : $('.box-inner').width() * 2
					})
					
					$('.book_wrapper').css({
						'top' : 0,
						'left': 50,
						'margin-left' : 0
					})
					
					trigger = true
					
					// прокрутка					
					var x0 = e.pageX;
					var y0 = e.pageY;
					
					//alert(e.pageX)
					
					
					
					
					
					$('#mybook').bind("mousemove", function(e)
					{
						/* обработка по X */
						
						dX = (e.pageX - x0) * -1
												
						/*if(dX > 50)
						{
							//dX = 50
							//x0 = e.pageX;
							
						}*/
						/*else
						{
							$('.book_wrapper').css({
								'left' 	: dX
							})
						}*/
						
						/* /обработка по X */
						
						
						
						/* обработка по Y */
						
						dY = (e.pageY - y0) * -1 * 2
						
						if(dY > 0)
						{
							dY = 0
							y0 = e.pageY;
								
						}
						
						/*var btm = $('.box').height() * -1
						
						if(dY < btm)
						{
							dY = btm
							y0 = btm
						}*/
						
						/* /обработка по Y */
						
						
						$('.book_wrapper').css({
							'left' 	: dX,
							'top'	: dY
						})	
					
						//$('#debug').html('e.pageX = '+e.pageX + ' e.pageY = '+e.pageY + '<br>dX = ' + dX + ' dY = '+ dY);					
					})
					
					
				}
				else
				{
					// уменьшение масштаба к исходному
					$('#mybook').unbind("mousemove")
					
					$('.box-inner').css({
						'height' : getBookWrapperH(),
						'width' : ''
					})
					
					$('.book_wrapper').css({
						'top' : '',
						'left': '',
						'margin-left' : 'auto'
					})
					
					trigger = false
				}
				
				// реинициализация журнала
				bookRefresh()
			})
		
		})
		</script>
		
		
		
    </head>
    <body>
	
	<div class="main tplMagazine">
	
		<?
		include $_SERVER['DOCUMENT_ROOT'].'/blocks/lang.choice.php';
		?>
			
		<!-- верхнее меню -->
		<div class="book-top myriad-pro-regular">
			<div class="half half-left" >
				<a href="/" id="book-logo"><img class="big" src="/img/tpl/book-logo.png" alt="" /><img class="mini" src="/img/tpl/book-logo-mini.png" alt="" /></a>
				<div id="form-search"><form><input type="text" /></form></div>
			</div>
			
			
			
			<div class="half menu-conteiner  half-right" style="float:right;">
				<?
				include $_SERVER['DOCUMENT_ROOT'].'/blocks/menu.mag.top.php';
				?>
			</div>
			<div style="clear:both"></div>
		</div>
		<!-- /верхнее меню -->		
		
		
		
		
		<!--<h1 class="title">Moleskine Notebook with jQuery Booklet</h1>-->
		
		<div class="box">
			<div class="box-inner">
				<div class="book_wrapper" style='color:#fff'>
					<?
					include $_SERVER['DOCUMENT_ROOT'].'/blocks/mag.booklet.php';
					?>			
				</div>
			</div>
		</div>
		
		
		
		
		
		<!-- footer журнала -->
		<div class="book-foot">
			<?
			include $_SERVER['DOCUMENT_ROOT'].'/blocks/menu.mag.foot.php';
			?>					
		</div>
		<!-- /footer журнала -->
		<div style="clear:both"></div>
	</div>
	
	<div style="clear:both"></div>
	
	<div id="underBlock">
				
		<div class="underContent">
			
			
			<div id="loadBox">
			
				<!-- журналы -->
				<div class="underTitle">Другие публикации</div>
				<?
				include $_SERVER['DOCUMENT_ROOT'].'/blocks/mag.list.php';
				?>
				<!-- /журналы -->
			
			</div>
		</div>
		
	</div>
       
	<div id="debug" style="position:fixed; z-index:10; top:0; color:white "></div>
	   
    </body>
</html>