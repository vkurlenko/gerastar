<?
include $_SERVER['DOCUMENT_ROOT']."/config.php";
include $_SERVER['DOCUMENT_ROOT']."/functions.php";
include $_SERVER['DOCUMENT_ROOT']."/functions_sql.php";

function tool($param, $flag, $id)
{
	global $arrIcon, $_VARS;
	
	$path = '/cms9/icon/';	
	$result = false;	// результат проверки выполнения команды
	$str = '';
	
	$flag == 1 ? $value = 0 : $value = 1;
	
	// обновим запись
	$sql = "UPDATE `".$_POST['tbl']."` 
			SET ".$param." = '".$value."'
			WHERE id = ".$id;			
	$res = mysql_query($sql);
	$str .= $sql.'<br>';
	//echo $sql;

	// проверим обновление
	$sql = "SELECT * FROM `".$_POST['tbl']."`
			WHERE id = ".$id;			
	$res = mysql_query($sql);
	$str .= $sql.'<br>';
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row 	= mysql_fetch_assoc($res);
		$value 	= $row[$param];
		
		if(!isset($arrIcon[$param]))
			$p = 'default';
		else
			$p = $param;
			
		$img 	= $arrIcon[$p][$value];
	}
	else
	{
		return '<script language="javascript">alert("Ошибка запроса '.$str.'")</script>';
		exit;
	}

	$value == 1 ? $alt = '+' : $alt = '-';
	$tag = '<img class="'.$value.'" param="'.$param.'" name="'.$id.'" tbl="'.$_POST['tbl'].'" title="'.$alt.'" src="'.$path.$img.'">';
	
	return $tag;		
}

function toolSelect($param, $value, $id)
{
	global $arrIcon, $_VARS;
	
	$status = array(
		0 => 'новый',
		1 => 'выполнен',
		4 => 'оплачен'
	);
	
	
	$result = false;	// результат проверки выполнения команды
	$str = '';
	
	
	// обновим запись
	$sql = "UPDATE `".$_POST['tbl']."` 
			SET ".$param." = '".$value."'
			WHERE id = ".$id;			
	$res = mysql_query($sql);
	$str .= $sql.'<br>';
	//echo $sql;

	// проверим обновление
	$sql = "SELECT * FROM `".$_POST['tbl']."`
			WHERE id = ".$id;			
	$res = mysql_query($sql);
	$str .= $sql.'<br>';
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row 	= mysql_fetch_assoc($res);
		$value 	= $row[$param];
	}
	else
	{
		return '<script language="javascript">alert("Ошибка запроса '.$str.'")</script>';
		exit;
	}

	/*$value == 1 ? $alt = '+' : $alt = '-';
	$tag = '<img class="'.$value.'" param="'.$param.'" name="'.$id.'" tbl="'.$_POST['tbl'].'" title="'.$alt.'" src="'.$path.$img.'">';*/
	
	
	//$tag = '<select class="setSelect" param="'.$param.'" name="'.$id.'" tbl="'.$tbl.'">';
	$tag = '';
	foreach($status as $k => $v)
	{
		$value == $k ? $ch = 'selected="1"' : $ch = '';
		$tag.= '<option value="'.$k.'" '.$ch.' >'.$v.'</option>';
	}
	
	//$tag.= '</select>';
	
	return $tag;		
}




switch($_POST['func'])
{
	case 'tool' 		: echo tool($_POST['param'], $_POST['flag'] == '1' ? 1 : 0, $_POST['id']); break;
	case 'toolSelect'	: echo toolSelect($_POST['param'], $_POST['value'], $_POST['id']); break;
	default: break;

}


?>