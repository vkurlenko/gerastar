// JavaScript Document
var counter = 0;


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
	
	//var k = Math.ceil(wDef / getBookWrapperH() * 10);
	var k = Math.ceil(wDef / $('.box-inner').height() * 10);
	
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


function getBookWrapperH()
{
	var h = $(window).height();// $('.main').height()
	var height = h - $('.book-top').height() - getRealSize($('.book-top').css('padding-top')) - $('.book-foot').height() - 60;
	return height;
}


function bookRefresh()
{
	$('#mybook').hide()
	$('#mybook').booklet("destroy");			

	Cufon.refresh();
	
	//$('input').attr('value', $('body').width())
	
	myBook();	
}

function resize()
{			
	$('.box, .box-inner').height(getBookWrapperH())		
	
	$('.box-inner').css({
		'width' : ''
	})
	
	$('.book_wrapper').css({
		'top' : '',
		'left': '',
		'margin-left' : 'auto'
	})
	
	$('#mybook').unbind("mousemove")
	bookRefresh()
}


function myBook() 
{
		
	$('.book_wrapper').height($('.box-inner').height())
	
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

					before:             function(){alert('before')},                    // callback invoked before each page turn animation
					after:              function(){alert('after')},                     // callback invoked after each page turn animation
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





