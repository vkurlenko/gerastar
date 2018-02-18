<link rel="stylesheet" href="/js/refineslide-master/refineslide.css" />
<script language="javascript" type="text/javascript" src="/js/refineslide-master/modernizr.js"></script>
<script src="/js/refineslide-master/jquery.refineslide.js"></script>
<script src="/js/refineslide-master/ios-orientation-change-fix.js"></script>

<script language="javascript">
<?
if(isset($_VARS['env']['delay']))
	$delay = $_VARS['env']['delay'];
else
	$delay = 5000;
	
if(isset($_VARS['env']['delay_top']))
	$delay_top = $_VARS['env']['delay_top'];
else
	$delay_top = 5000;
?>






$(document).ready(function()
{

	$('#images').refineSlide(
	{
		transition			: 'cubeV',  // String (default 'cubeV'): Transition type ('random', 'cubeH', 'cubeV', 'fade', 'sliceH', 'sliceV', 'slideH', 'slideV', 'scale', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV')
		fallback3d			: 'sliceV', // String (default 'sliceV'): Fallback for browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms)
		controls			: 'null', // String (default 'thumbs'): Navigation type ('thumbs', 'arrows', null)
		thumbMargin			: 0,        // Int (default 3): Percentage width of thumb margin
		autoPlay			: true,    // Int (default false): Auto-cycle slider
		delay				: 5000, //<?=$delay?>,     // Int (default 5000) Time between slides in ms
		transitionDuration	: 1000,      // Int (default 800): Transition length in ms
		startSlide			: 0,        // Int (default 0): First slide
		keyNav				: false,     // Bool (default true): Use left/right arrow keys to switch slide
		captionWidth		: 50,       // Int (default 50): Percentage of slide taken by caption
		arrowTemplate		: '',//'<div class="rs-arrows"><a href="#" class="rs-prev"><</a><a href="#" class="rs-next">></a></div>', // String: The markup used for arrow controls (if arrows are used). Must use classes '.rs-next' & '.rs-prev'
		onInit				: function () {}, // Func: User-defined, fires with slider initialisation
		onChange			: function () {}, // Func: User-defined, fires with transition start
		afterChange			: function () {}  // Func: User-defined, fires after transition end
	});
	
	$('#images2').refineSlide(
	{
		transition			: 'fade',  // String (default 'cubeV'): Transition type ('random', 'cubeH', 'cubeV', 'fade', 'sliceH', 'sliceV', 'slideH', 'slideV', 'scale', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV')
		fallback3d			: 'sliceV', // String (default 'sliceV'): Fallback for browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms)
		controls			: 'null', // String (default 'thumbs'): Navigation type ('thumbs', 'arrows', null)
		thumbMargin			: 0,        // Int (default 3): Percentage width of thumb margin
		autoPlay			: true,    // Int (default false): Auto-cycle slider
		delay				: <?=$delay_top?>,     // Int (default 5000) Time between slides in ms
		transitionDuration	: 1000,      // Int (default 800): Transition length in ms
		startSlide			: 0,        // Int (default 0): First slide
		keyNav				: false,     // Bool (default true): Use left/right arrow keys to switch slide
		captionWidth		: 50,       // Int (default 50): Percentage of slide taken by caption
		arrowTemplate		: '',//'<div class="rs-arrows"><a href="#" class="rs-prev"><</a><a href="#" class="rs-next">></a></div>', // String: The markup used for arrow controls (if arrows are used). Must use classes '.rs-next' & '.rs-prev'
		onInit				: function () {}, // Func: User-defined, fires with slider initialisation
		onChange			: function () {}, // Func: User-defined, fires with transition start
		afterChange			: function () {}  // Func: User-defined, fires after transition end
	});
})
</script>