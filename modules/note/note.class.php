<?
class NOTE
{
	public $user; 	// id пользователя
	public $day; 	// дата заметки
	public $data;	// массив заметок
	
	public $product_tbl;	// таблица продуктов
	public $product;		// id продукта
	
	// прочитаем заметки пользователя за указанную дату
	public function getUserNotes()
	{
		$arr = array();
		
		/*$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE note_user = ".$this -> user."
				AND note_date = '".$this -> day."'";*/
				
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE note_user = ".$this -> user."
				AND note_date LIKE '%".$this -> day."'";
				
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
	
	// информация о продукте
	public function getProductInfo()
	{
		$arr = array();
		
		$sql = "SELECT * FROM `".$this -> product_tbl."`
				WHERE id = ".$this -> product;
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$arr = mysql_fetch_assoc($res);			
		}
		return $arr;
	}
	
	
	// картинка продукта
	public function getProductImg()
	{
		global $_VARS;
	
		$p = $this -> getProductInfo();
	
		$img = new Image();
		$img -> imgCatalogId 	= $_VARS['env']['pic_catalogue_services'];
		$img -> imgId 			= $p['item_photo'];
		$img -> imgAlt 			= '';
		$img -> imgClass 		= "";
		$img -> imgWidthMax 	= 300;
		$img -> imgHeightMax 	= 300;	
		$img -> imgMakeGrayScale= false;
		$img -> imgGrayScale 	= false;
		$img -> imgTransform	= "resize";
		$img -> imgWaterMark	= '/img/pic/cakeberry_watermark.png';
		
		//print_r($img);
		
		$html = $img -> showPic();
		
		return $html;
	}
	
	public function delNote()
	{
		$sql = "DELETE FROM `".$this -> tbl."`
				WHERE id = ".$this -> product;
		$res = mysql_query($sql);
		
		/*if($res)
			echo 'ok';
		else
			echo 'bad';*/
		
	}
}
?>