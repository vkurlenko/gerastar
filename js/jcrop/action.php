<?php
//include($_SERVER['DOCUMENT_ROOT'].'/blocks/class.debug.php');
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once DIR_CLASS.SL.'class.db.php';


include($_SERVER['DOCUMENT_ROOT'].'/js/jcrop/resize_crop.php');


//$d = new DEBUG();

//$d -> allVars();

function prov($per){
	if (isset($per)) {
		$per = stripslashes($per);
		$per = htmlspecialchars($per);
		$per = addslashes($per);		 
	}
	return $per;
}


if(isset($_POST)){

	
	$filenew = time().rand(100,999).'.jpg';
	
	$x1 = prov($_POST['x1']);
	$x2 = prov($_POST['x2']);
	$y1 = prov($_POST['y1']);
	$y2 = prov($_POST['y2']);
	$img = prov($_POST['img']);
	$crop = prov($_POST['crop']);
	$crop2 = prov($_POST['crop2']);
	$alb = prov($_POST['alb']);

	crop($img, $crop.$filenew, $crop2.$filenew, $alb, array($x1, $y1, $x2, $y2));	
	
	echo $filenew;
	

}




?>