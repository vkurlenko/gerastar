<?
/*

	работа с шаблонами страниц

*/

class TPL
{
	public $id;
	public $tbl_name;
	public $html;
	public $data; 
	public $image_id;
	
	/* прочитаем данные шаблона */
	public function getTplInfo()
	{
		global $_VARS;
	
		$arr = array();
		$sql = "SELECT * FROM `".$this -> tbl_name."` 
				WHERE id = ".$this ->id;
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$arr = mysql_fetch_assoc($res);
		}
		
		// прочитаем код шаблона из файла
		$dir = chdir($_SERVER['DOCUMENT_ROOT']."/".$_VARS['tpl_dir'].'/tpl_mag');
	
		if(file_exists($this -> id.'.php'))
		{
			$code = file($this -> id.'.php');
		
			$tpl_code = "";
			foreach($code as $str)
			{
				$tpl_code .= $str; 
			}			
			
			$arr['tpl_code'] = $tpl_code;
		}
		// /прочитаем код шаблона из файла
		
		return $arr;
	}
	/* /прочитаем данные шаблона */
	
	
	public function getImage()
	{
		global $_VARS;
		$pic = '';
		$alb = 0;	
				
		/*if($this -> image_id != 0)
		{*/
		
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic`
					WHERE id = ".$this -> image_id;
					
			$res = mysql_query($sql);
			
			if($res && mysql_num_rows($res) > 0)
			{
				$row = mysql_fetch_assoc($res);
				$alb = $row['alb_id'];
			}
		
		
			$img = new Image();
			$img -> imgCatalogId 	= $alb;
			$img -> imgId 			= $this -> image_id;
			$img -> imgAlt 			= '';
			$img -> imgWidthMax 	= 70;
			$img -> imgHeightMax 	= 70;
			$img -> imgClass		= 'selectPic';
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "crop";
			$pic = $img -> showPic();
		/*}*/
		
		return $pic;			
	}
	
	
	
	public function replaceBlocks()
	{
	
		$arr_img = array('p_img_1', 'p_img_2', 'p_bg');
		
		/*foreach($arr_img as $k)
		{*/
			$this -> image_id = $this -> data['p_bg'];
			$p_bg = $this -> getImage();
		/*}*/
	
		
	
		$arr = array(
			':::p_text_1:::' => '<textarea name="p_text_1">'.$this -> data['p_text_1'].'</textarea>',
			':::p_text_2:::' => '<textarea name="p_text_2">'.$this -> data['p_text_2'].'</textarea>',
			':::p_text_3:::' => '<textarea name="p_text_3">'.$this -> data['p_text_3'].'</textarea>',
			':::p_text_4:::' => '<textarea name="p_text_4">'.$this -> data['p_text_4'].'</textarea>',
			
			':::p_img_1:::'	 => '',
			':::p_img_2:::'	 => '',
			
			':::p_bg:::'	 => $p_bg
		);
		
		$a = $this -> html;
		foreach($arr as $k => $v)
		{
			
			if($k == ':::p_img_1:::' || $k == ':::p_img_2:::' )
			{
				$mark = str_replace(':::', '', $k);
				$this -> image_id = $this -> data[$mark];
				
				$code = '<div class="picDiv" id="picDiv-'.$mark.'">
							<a class="selectPic-'.$mark.'" href="#">'.$this -> getImage().'</a>				
							<input id="'.$mark.'" name="'.$mark.'" value="'.$this -> image_id.'" type="hidden">	
							<div class="picList" id="picList-'.$mark.'"></div>	
						</div>';
				
				$a = str_replace($k, $code, $a);				
			}
			else		
				$a = str_replace($k, $v, $a);
		}
		
		return $a;				
	}
	
	
	/* удалим файл шаблона */
	public function delTpl()
	{
		global $_VARS;
		
		$dir = chdir($_SERVER['DOCUMENT_ROOT']."/".$_VARS['tpl_dir'].'/tpl_mag');	
		if(file_exists($this -> id.'.php'))
			$del_f = unlink($this -> id.'.php');
	}
	/* /удалим файл шаблона */
}
?>