<?
// иконка checkbox 
function iconChkBox($tbl, $param, $flag, $id)
{
	global $arrIcon;
	$path = '/cms9/icon/';
	
	if(!isset($arrIcon[$param]))
		$p = 'default';
	else 
		$p = $param;
		
	$flag == true ? $img = $arrIcon[$p][1] : $img = $arrIcon[$p][0];
	$flag == true ? $alt = '+' : $alt = '-';
			
	$tag = '<a class="setFlag" href=""><img class="'.$flag.'" param="'.$param.'" name="'.$id.'" tbl="'.$tbl.'" title="'.$alt.'"  src="'.$path.$img.'"></a>';
		
	return $tag;
}


function ajaxSelect($tbl, $param, $value, $id, $arr)
{
	
	$tag = '<select class="setSelect" param="'.$param.'" name="'.$id.'" tbl="'.$tbl.'">';
	
	foreach($arr as $k => $v)
	{
		$value == $k ? $ch = 'selected="1"' : $ch = '';
		$tag.= '<option value="'.$k.'" '.$ch.' >'.$v.'</option>';
	}
	
	$tag.= '</select>';
		
	return $tag;
}
?>