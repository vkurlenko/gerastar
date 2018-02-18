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
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>

		<script type="text/javascript" src="/modules/book/booklet/jquery-2.1.0.min.js"></script>
		<script type="text/javascript" src="/modules/book/booklet/jquery.easing.1.3.js" ></script>
		<script type="text/javascript" src="/modules/book/booklet/jquery.booklet.latest.min.js"></script>

		<link rel="stylesheet" href="/modules/book/booklet/jquery.booklet.latest.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/modules/book/css/style.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen"/>
		
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
		
		<script language="javascript">
		$(document).ready(function()
		{
			$('#cover img, .img-bg img, .img-area img').attr({'width' : '', 'height' : ''})			
			$('.img-bg img, .img-area img').css({'width' : '100%', 'height' : '100%'})	
			
			$('#menu-book-top a, .feedback').click(function()
			{
				$('#loadBox').load($(this).attr('href'))
				//alert($(this).attr('href'))
				//Получаем смещение сверху для целевой секции
					var target_offset = $('#underBlock').offset();
					var target_top = target_offset.top;
							
					//Переходим в целевую секцию установкой позиции прокрутки страницы в позицию целевой секции
					$('html, body').animate({scrollTop:target_top}, 1000);
				return false
			})
		
		})
		</script>
		
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
		<div class="box" style="position:relative; overflow:hidden;">
			<div class="book_wrapper" style='color:#fff'>
				<?
				include $_SERVER['DOCUMENT_ROOT'].'/blocks/mag.booklet.php';
				?>			
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
        <!-- The JavaScript -->

        <script type="text/javascript">
		
		function getRealSize(value)
		{	
			if(value != null)
			{
				value = value.replace("px", "") * 1;
			}
			else value = 0;
			
			return value;
		}
		
		
		/* прокрутка страницы вниз */
		function scrl()
		{			
			//Получаем смещение сверху для целевой секции
			var target_offset = $('.book_wrapper').offset();
			var target_top = target_offset.top;
					
			//Переходим в целевую секцию установкой позиции прокрутки страницы в позицию целевой секции
			$('html, body').animate({scrollTop:target_top}, 1000);
		}
		
		
		/* масштабирование текста */
		function scale()
		{
			wDef = 1080;
			hDef = 820;
			
			var k = Math.ceil(wDef / getBookWrapperH() * 10);
			
			var s = 26;
			
			if(k <= 25){s = 14}
			if(k <= 23){s = 15}
			if(k <= 20){s = 18}
			if(k <= 18){s = 21}
			if(k <= 16){s = 23}
			if(k <= 14){s = 26}
			if(k <= 12){s = 31}
			if(k <= 10){s = 37}
			if(k <= 7){	s = 53}
			
			$('.p-main').css('font-size', s)
		}
		
		
		
		/* отцентрируем обложку */
		function center()
		{
			//alert('create')
			$('.booklet').css('left', '75%')
		}
		
		
		/* номер текущей страницы */
		function currrentPage()
		{
			var hash = location.hash;														
			var arr = hash.split('/');
			var p = arr[2];
			
			return p;
		}
		
		
		function myBook() 
		{
				
				$('.book_wrapper').height(getBookWrapperH())
				
				var $mybook 		= $('#mybook');
				var $bttn_next		= $('#next_page_button');
				var $bttn_prev		= $('#prev_page_button');
				var $loading		= $('#loading');
				var $mybook_images	= $mybook.find('img');
				var cnt_images		= $mybook_images.length;
				var loaded			= 0;
				var m			= $('.p');
				//preload all the images in the book,
				//and then call the booklet plugin
				
				
				
				var h = $('.book_wrapper').height(); // высота страницы
				var w = Math.floor((h / 1.3) * 2)	 // ширина полного разворота
				$('.book_wrapper').width(w);
				
				var ml = Math.floor(w / 2);			// отступ влево

				// сместим закрытый журнал к центру
				$('#mybook').css('margin-left', -ml)
				
				scale()
				
				//$(".text-2 > div").fitText(0.5);

				$mybook_images.each(function(){
					var $img 	= $(this);
					var source	= $img.attr('src');
					$('<img/>').load(function(){
						++loaded;
						if(loaded == cnt_images){
							$loading.hide();
							$bttn_next.show();
							$bttn_prev.show();
							$mybook.show().booklet({
								name:               null,                            // name of the booklet to display in the document title bar
								width:              w,                         	    // container width
								height:             h,                         	    // container height
								speed:              600,                             // speed of the transition between pages
								direction:          'LTR',                           // direction of the overall content organization, default LTR, left to right, can be RTL for languages which read right to left
								startingPage:       0,                               // index of the first page to be displayed
								easing:             'easeInOutQuad',                 // easing method for complete transition
								easeIn:             'easeInQuad',                    // easing method for first half of transition
								easeOut:            'easeOutQuad',                   // easing method for second half of transition
								
								
								closed:             true,                           // start with the book "closed", will add empty pages to beginning and end of book
								closedFrontTitle:   null,                            // used with "closed", "menu" and "pageSelector", determines title of blank starting page
								closedFrontChapter: null,                            // used with "closed", "menu" and "chapterSelector", determines chapter name of blank starting page
								closedBackTitle:    null,                            // used with "closed", "menu" and "pageSelector", determines chapter name of blank ending page
								closedBackChapter:  null,                            // used with "closed", "menu" and "chapterSelector", determines chapter name of blank ending page
								covers:             false,                           // used with  "closed", makes first and last pages into covers, without page numbers (if enabled)
								autoCenter:			true,

								pagePadding:        0,                              // padding for each page wrapper
								pageNumbers:        false,                            // display page numbers on each page

								hovers:             false,                            // enables preview pageturn hover animation, shows a small preview of previous or next page on hover
								overlays:           false,                            // enables navigation using a page sized overlay, when enabled links inside the content will not be clickable
								tabs:               false,                           // adds tabs along the top of the pages
								tabWidth:           60,                              // set the width of the tabs
								tabHeight:          20,                              // set the height of the tabs
								arrows:             false,                           // adds arrows overlayed over the book edges
								cursor:             'pointer',                       // cursor css setting for side bar areas

								hash:               true,                           // enables navigation using a hash string, ex: #/page/1 for page 1, will affect all booklets with 'hash' enabled
								keyboard:           true,                            // enables navigation with arrow keys (left: previous, right: next)
								next:               $bttn_next,          			 // selector for element to use as click trigger for next page
								prev:               $bttn_prev,          			 // selector for element to use as click trigger for previous page

								menu:               null,                            // selector for element to use as the menu area, required for 'pageSelector'
								pageSelector:       false,                           // enables navigation with a dropdown menu of pages, requires 'menu'
								chapterSelector:    false,                           // enables navigation with a dropdown menu of chapters, determined by the "rel" attribute, requires 'menu'

								shadows:            true,                            // display shadows on page animations
								shadowTopFwdWidth:  166,                             // shadow width for top forward anim
								shadowTopBackWidth: 166,                             // shadow width for top back anim
								shadowBtmWidth:     50,                              // shadow width for bottom shadow

								before:             function(){/*alert('before')*/},                    // callback invoked before each page turn animation
								after:              function(){/*alert('after')*/},                     // callback invoked after each page turn animation
								create:				function(){
														var p = currrentPage();
														if(p == 1 || p == ($('.b-page').size() - 1))
															center();
													},
								change:				function(){
														
														var p = currrentPage();
														if(p == 1 || p == ($('.b-page').size() - 1))
															center();
															
														$('.cur-page').text(p)
														
													
													},
								start: 				function() 
													{
														$('.booklet').css('left', '50%')
														$('#mybook').css('margin-left', -ml)
														scrl();
														
														scale();
													
													}
							});
							Cufon.refresh();
						}
					}).attr('src',source);
				});								
			};
		
		
		
		function getBookWrapperH()
		{
			var h = $('.main').height()
			var height = h - $('.book-top').height() - getRealSize($('.book-top').css('padding-top')) - $('.book-foot').height() - 60;
			
			//alert(h)
			return height;
		}
		
		function resize()
		{			
			$('.main').height($(window).height())
			$('#mybook').hide()
			$('#mybook').booklet("destroy");			

			Cufon.refresh();
			
			$('input').attr('value', $('body').width())
			
			myBook();
			
		}
		
		
		
		
		/* zoom журнала и смещение за курсором мыши */
		function zoom(hDef, Xdef, Ydef, trigger, w, h)
		{
			//if(getRealSize($('.main').css('height')) != hDef)
			if(!trigger)
			{
				$('#mybook').unbind("mousemove")
				
				$('.main').css({
					'height' : '100%',
					'overflow' : ''
				})
				
				$('.book_wrapper').css({
						'left' 	: 0,
						'top'	: 0
					})
					
				$('.box').css('height', '')
				
				resize()
				
			}
			else
			{
				$('.main').css({
					'height' : '200%',
					'overflow' : 'hidden'
				})
				
				$('.book_wrapper').css({
					'left' 	: 0,
					'top'	: 0
				})	
					
				
				resize()
				
				maxLeft = 50
				maxRight = ($('.book_wrapper').width() - $(document).width() + 50) * -1
				/*alert($('.book_wrapper').width() + ' - ' + $(document).width() + ' - ' + 50 +' = '+ maxRight)
				alert(maxRight)*/
					
				
					
				$('#mybook').bind("mousemove", function(e)
				{									
					dX = (Xdef - e.pageX);
					
					if(dX < maxRight)
					{
						dX = maxRight
					}
						
					if(dX > maxLeft)
					{
						dX = maxLeft								
					}
						
					dY = (Ydef - e.pageY)*3;
					
					if(dY > 0)
					{
						dY = 0
						
					}
					
					
					/*$('.book_wrapper').css({
						'left' 	: dX,
						'top'	: dY
					})	*/	
					
					$('.book_wrapper').moveto(dX, dY)	
					
					$('#debug').html('Xdef = '+Xdef+ ' Ydef = '+Ydef + " <br> dX : " + (dX) + " dY : " +(dY) + '<br> e.pageX = '+e.pageX + ' e.pageY = '+e.pageY);						
				});					
			}
			
			
			//resize()
			
			$('.main').css({
				'height' : hDef
			})
			
			resize()
			//alert($('.book_wrapper').width())
		}
		/* /zoom журнала и смещение за курсором мыши */
		
		
		
		$(document).ready(function()
		{
			$('.main').height($(window).height())
		
			var counter = 0;
			var trigger = false;
			
			// высота по умолчанию (исходный масштаб)
			var hDef = getRealSize($('.main').css('height'));			
			
			
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
			
			
			$('.box').css({
				'height' : getBookWrapperH()/*,
				'width'  : $('.book_wrapper').width()*/
			})
			
			/*$('#mybook').click(function(e)
			{
				if(trigger)
					trigger = false;
				else
					trigger = true;	
					
				var w = getRealSize($(this).css('width'))
				var h = getRealSize($(this).css('height'))				
					
					
				var offset = $(this).offset();
				
				var Xdef = e.pageX.toFixed() - offset.left.toFixed();	// X курсора мыши относительно #mybook
				var Ydef = e.pageY.toFixed() - offset.top.toFixed();	// Y курсора мыши относительно #mybook
				
				zoom(hDef, Xdef, Ydef, trigger, w, h)
			})*/
			
			
			/*
			if(trigger)
			{
				$('.box').mouseleave(function(){$('#mybook').unbind("mousemove")})
				$('.box').mouseover(function()
				{
					var offset = $(this).offset();
					var Xdef = e.pageX.toFixed() - offset.left.toFixed();
					var Ydef = e.pageY.toFixed() - offset.top.toFixed();
					zoom(hDef, Xdef, Ydef, trigger)
				})
			}
			*/
			
			
			
		})
		
			
        </script>
		
		<div id="debug" style="position:fixed; z-index:10; top:0; color:red "></div>
    </body>
</html>