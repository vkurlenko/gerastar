<?
###################################
####		функции				###
###################################

function CreateTable()
{
	global $table_name;
	$sql = "CREATE TABLE `$table_name` (
		id 				int auto_increment primary key,
		alb_parent		int default '0' not null,
		alb_name		text,
		alb_title		text,
		alb_video		text,
		alb_text		text,
		alb_img			int default '0' not null,
		alb_mark		text,
		alb_create		date,
		alb_update		date,
		alb_order		int
	)";
	//echo $sql;
	$res = mysql_query($sql);
}

function editFlash($code)
{
	//echo htmlspecialchars($code);
	$addParam = 'wmode=\\"opaque\\"';
	$is_param = strpos($code, $addParam);
	//echo "pos = ".$is_param;
	if($is_param !== false)
	{}
	else
	{
		$code = str_replace("<embed", "<embed ".$addParam." ", $code);
	}
	return $code;
}

function AddItem($alb_name, $alb_parent, $alb_title, $alb_video, $alb_text, $alb_img, $alb_mark)
{
	global $table_name, $_VARS;
	$alb_video = editFlash($alb_video);
	$sql = "INSERT INTO `$table_name` 
			(alb_name, alb_parent, alb_title, alb_video, alb_text, alb_img, alb_mark, alb_create, alb_update)
			VALUES 
			('$alb_name', $alb_parent, '$alb_title', '$alb_video', '$alb_text', '$alb_img', '$alb_mark', '".date('Y-m-d')."', '".date('Y-m-d')."')";
//echo $sql;
	$res = mysql_query($sql);
	$id = mysql_insert_id();
	$alb_name = $id;
	
	$sql = "UPDATE `$table_name` 
			SET alb_name='$alb_name', alb_order=$id			 
			WHERE id=$id";
	$res = mysql_query($sql);
	
	$sql = "CREATE TABLE `".$_VARS['tbl_photo_name']."$alb_name` (
		id 			int auto_increment primary key,
		file_ext	text,
		name		text,
		tags		text,
		pub			int,
		url			text,
		order_by	int,
		img_create  date
	)";
	$res = mysql_query($sql);
	
	CreateFolder($alb_name);
	
	return $res;
}

function UpdateItem($id, $alb_parent, $alb_title, $alb_video, $alb_text, $alb_img, $alb_mark)
{
	global $table_name, $_VARS;
	$alb_video = editFlash($alb_video);

	$sql = "UPDATE `$table_name` SET 
			alb_parent = $alb_parent,
			alb_title='$alb_title',
			alb_video='$alb_video',
			alb_text='$alb_text',
			alb_img='$alb_img',
			alb_mark='$alb_mark',
			alb_update='".date('Y-m-d')."'
		WHERE id=$id";
	$res = mysql_query($sql);
	return $res;
}

function MoveItem($id, $direction, $parent)
{
	global $table_name, $_VARS;
	
	if($direction == "asc") 
		$arrow = ">";
	else
		$arrow = "<";
		
	$sql = "SELECT * FROM `".$table_name."` 
			WHERE id=".$id;
			
	//echo $sql."<br>";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);

	$sql = "SELECT * FROM `".$table_name."` 
			WHERE (alb_order ".$arrow." ".$row['alb_order']." AND alb_parent = $parent) 
			ORDER BY alb_order ".$direction." 
			LIMIT 1";
	//echo $sql."<br>";
	$res = mysql_query($sql);
	$row_2 = mysql_fetch_array($res);
	
	$sql = "UPDATE `".$table_name."` 
			SET alb_order=0 where id=".$row_2['id'];
	//echo $sql."<br>";
	$res = mysql_query($sql);
	
	$sql = "UPDATE `".$table_name."` 
			SET alb_order=".$row_2['alb_order']."
			WHERE id=".$id;
	//echo $sql."<br>";
	$res = mysql_query($sql);
	
	$sql = "UPDATE `".$table_name."` 
			SET alb_order=".$row['alb_order']." 
			WHERE alb_order=0";
	//echo $sql."<br>";
	$res = mysql_query($sql);
}


function DelItem($alb_name)
{
	global $table_name;
	$sql = "DELETE FROM `$table_name` 
			WHERE alb_name=$alb_name";
	//echo $sql;
	$res = mysql_query($sql);
	return $res;
}

function DropTable($name)
{
	global $_VARS;
	/*$name = $name + 100;*/
	$name = $name;
	$sql = "DROP TABLE `".$_VARS['tbl_photo_name'].$name."`";
	//echo $sql;
	$res = mysql_query($sql);
	return $res;
}

/* удаление каталога */
function DeleteDir($directory) 
{
	global $_VARS;
	/*$directory = $_SERVER['DOCUMENT_ROOT']."/photo_alb/photo".($directory + 100);*/
	$directory = $_SERVER['DOCUMENT_ROOT']."/".$_VARS['photo_alb_dir']."/".$_VARS['tbl_photo_name'].($directory);
	echo $directory;
	$id_arr = array();
	$p = explode("/", $directory);
	$parent_url = $p[count($p) - 2];
	
	$dir = opendir($directory);
	while(($file = readdir($dir)))
	{
		if(is_file ($directory."/".$file))
			unlink ($directory."/".$file);
		elseif(is_dir ($directory."/".$file) & ($file != ".") & ($file != ".."))
		{
			DeleteDir ($table_name, $id, $directory."/".$file);
			$id_arr[] = $file;
		}
	}
	closedir ($dir);
	
	$del = rmdir($directory);
	return $del;  
}

function CreateFolder($name)
{
	global $_VARS;
	if(!is_dir($_SERVER['DOCUMENT_ROOT']."/".$_VARS['photo_alb_dir']))
	{
		mkdir($_SERVER['DOCUMENT_ROOT']."/".$_VARS['photo_alb_dir']);
		chmod($_SERVER['DOCUMENT_ROOT']."/".$_VARS['photo_alb_dir'], 0777);
	}
	
	mkdir($_SERVER['DOCUMENT_ROOT']."/".$_VARS['photo_alb_dir']."/".$_VARS['tbl_photo_name'].$name);
	chmod($_SERVER['DOCUMENT_ROOT']."/".$_VARS['photo_alb_dir']."/".$_VARS['tbl_photo_name'].$name, 0777);
}

?>