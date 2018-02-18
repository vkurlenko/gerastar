<?
class PAGE
{
	public $tbl;
	public $id;
	public $p_parent_id;
	public $img_id;
	public $img_w;
	public $img_h;
	public $arrNav = array();
	
	
	
	// прочитаем страницу
	public function getPageInfo()
	{
		$arr = array();
		
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE p_url = '".$this -> p_url."'
				ORDER BY p_order ASC";
				
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{		
			while($row = mysql_fetch_assoc($res))
			{
				$arr = $row;
			}
		}
		
		return $arr;
	}
	
	// навигационная цепочка
	public function getPageNav()
	{
		$sql = "SELECT id, p_parent_id, p_title, p_title_en, p_url, p_photo_alb 
				FROM `".$this -> tbl."`
				WHERE id = ".$this -> p_parent_id;
		//echo $sql;		
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_assoc($res);
			$this -> arrNav[] = $row;
			$this -> p_parent_id = $row['p_parent_id'];
			$this -> getPageNav();
		}		
		
		return $this -> arrNav;		
	}
	
	// получим массив дочерних страниц
	public function getPageChild()
	{
		$arr = array();
		
		$sql = "SELECT id, p_url, p_redirect, p_img, p_title, p_title_en, p_content, p_content_en, p_parent_id
				FROM `".$this -> tbl."`
				WHERE p_parent_id = ".$this -> id."
				AND p_show = '1'
				ORDER BY p_order ASC";
				
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{		
			while($row = mysql_fetch_assoc($res))
			{
				$arr[] = $row;
			}
		}
		
		return $arr;		
	}
	
	
	// картинка
	public function getImg()
	{
		global $_VARS;
		$pic = '';	
				
		if($this -> img_id != 0)
		{		
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic`
					WHERE id = ".$this -> img_id;
					
			$res = mysql_query($sql);
			
			if($res && mysql_num_rows($res) > 0)
			{
				$row = mysql_fetch_assoc($res);
				$img = new Image();
				$img -> imgCatalogId 	= $row['alb_id'];
				$img -> imgId 			= $this -> img_id;
				$img -> imgAlt 			= '';
				$img -> imgWidthMax 	= $this -> img_w;
				$img -> imgHeightMax 	= $this -> img_h;	
				$img -> imgMakeGrayScale= false;
				$img -> imgGrayScale 	= false;
				$img -> imgTransform	= "crop";
				$pic = $img -> showPic();
			}
		
		
			
		}
		
		return $pic;			
	}
}
?>