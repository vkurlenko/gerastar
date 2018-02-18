<?

class MAGAZINE
{
	public $tbl_mag;
	public $tbl_mag_pages;
	public $id;		// id журнала
	public $cover;	// обложка
	public $cover_w = 245;
	public $cover_h = 365;
	public $page_data;
	
	
	
	
	
	/* html - код страницы */
	public function getPage()
	{
		global $_VARS;
		$arr = $this -> page_data;
		
		$tpl = new TPL();		
		$tpl -> tbl_name = $_VARS['tbl_prefix']."_mag_tpl";
		$tpl -> id 		= $arr['p_tpl'];
		
		$arr_tpl = $tpl -> getTplInfo();
		
		$this -> cover = $arr['p_bg'];
		$this -> cover_w = 820;
		$this -> cover_h = 1080;
		$arr['p_bg'] = $this -> getCover();
		
		$this -> cover = $arr['p_img_1'];
		$arr['p_img_1'] = $this -> getCover();
		
		$this -> cover = $arr['p_img_2'];
		$arr['p_img_2'] = $this -> getCover();
		
		$arr_r = array(
			':::p_text_1:::' => $arr['p_text_1'],
			':::p_text_2:::' => $arr['p_text_2'],
			':::p_text_3:::' => $arr['p_text_3'],
			':::p_text_4:::' => $arr['p_text_4'],
			
			':::p_img_1:::'	 => $arr['p_img_1'],
			':::p_img_2:::'	 => $arr['p_img_2'],		
			
			':::p_bg:::'	 => $arr['p_bg']
		);
		
		
		foreach($arr_r as $k => $v)
		{
			
			$arr_tpl['tpl_code'] = str_replace($k, $v, $arr_tpl['tpl_code']);
		}
		
		
		
		
		/*
		
		Array
(
    [id] => 4
    [p_mag] => 3
    [p_num] => 2
    [p_tpl] => 1
    [p_bg] => 6
    [p_show] => 1
    [p_text_1] => Слово пастыря
    [p_text_2] => Приветствие
    [p_text_3] => Основной текст
    [p_text_4] => 
    [p_text_5] => 
    [p_text_6] => 
    [p_img_1] => 10
    [p_img_2] => 12
    [p_img_3] => 0
    [p_img_4] => 0
    [p_img_5] => 0
    [p_img_6] => 0
)

*/	return $arr_tpl['tpl_code'];
		
	}
	/* /html - код страницы */
	
	
	/* все страницы журнала */
	public function getMagPages()
	{
		$arr = array();
	
		$sql = "SELECT * FROM `".$this -> tbl_mag_pages."`
				WHERE p_mag = ".$this -> id."
				AND p_show = '1'
				ORDER BY p_num ASC";
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_assoc($res))
			{
				$this -> page_data = $row;
				$arr[] = $this -> getPage();
			}
		}
		
		return $arr;				
	}
	/* /все страницы журнала */
	
	
	
	/* данные журнала */
	public function getMagInfo()
	{
		$arr = array();
		
		$sql = "SELECT * FROM `".$this -> tbl_mag."`
				WHERE id = ".$this -> id."
				AND mag_show = '1'";
				
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_assoc($res))
			{
				$this -> cover = $row['mag_img'];
				
				$arr = array(
					'id'	=> $row['id'],
					'num' 	=> $row['mag_num'],
					'year'	=> $row['mag_year'],
					'title'	=> $row['mag_title'],
					'cover'	=> $this -> getCover()					
				);
			}
		}
		
		return $arr;
	}
	/* /данные журнала */
	
	
	// все активные журналы
	public function getAllMag()
	{
		
		$arr = array();
	
		$sql = "SELECT * FROM `".$this -> tbl_mag."`
				WHERE mag_show = '1'
				AND mag_mark = '0'
				ORDER BY mag_year DESC, mag_num ASC";
				
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_assoc($res))
			{
				$this -> cover = $row['mag_img'];
				
				$arr[] = array(
					'id'	=> $row['id'],
					'num' 	=> $row['mag_num'],
					'year'	=> $row['mag_year'],
					'title'	=> $row['mag_title'],
					/*'cover'	=> $this -> getCover(),*/
					'mag_code' => $row['mag_code'],
					'mag_link' => $row['mag_link']					
				);
			}
		}
		
		return $arr;
	}
	
	
	// обложка журнала
	public function getCover()
	{
		global $_VARS;
		$pic = '';	
				
		if($this -> cover != 0)
		{
		
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic`
					WHERE id = ".$this -> cover;
					
			$res = mysql_query($sql);
			
			if($res && mysql_num_rows($res) > 0)
			{
				$row = mysql_fetch_assoc($res);
			}
		
		
			$img = new Image();
			$img -> imgCatalogId 	= $row['alb_id'];
			$img -> imgId 			= $this -> cover;
			$img -> imgAlt 			= '';
			$img -> imgWidthMax 	= $this -> cover_w;
			$img -> imgHeightMax 	= $this -> cover_h;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "crop";
			$pic = $img -> showPic();
		}
		
		return $pic;			
	}
	
	
}
?>