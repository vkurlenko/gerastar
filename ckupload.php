<?php 
if ( $_REQUEST["array"] )
{	

		
	//debug message
	echo "Array sort completed";
	exit();
}

    $callback = $_GET['CKEditorFuncNum'];
    $file_name = $_FILES['upload']['name'];
//    $full_path = dirname(__FILE__) . '/upload/' . $file_name;
    $full_path = dirname(__FILE__) . '/userfiles/' . $file_name;
   // $http_path = '/upload/'.$file_name;
    $http_path = '/userfiles/'.$file_name;
    $error = '';
    if( move_uploaded_file($_FILES['upload']['tmp_name'], $full_path) ) {
    } else {
     $error = 'Some error occured please try again later';
     $http_path = '';
    }
    echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(".$callback.",  \"".$http_path."\", \"".$error."\" );</script>";
?>