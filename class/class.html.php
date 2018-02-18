<?
class HTML
{
	public $text;		// text or html fragment
	public $length; 	// crop text to this length
	public $stop = '.'; // delimiter simbol (for example '.', ' ' etc.)
	

	/*
		Crop text or html to some length with striptags
	*/
	public function textCrop()
	{
		if(mb_strlen($this->text) > $this->length)
		{
			$t 	= mb_substr(strip_tags($this->text), 0, $this->length);
			//echo $t;
			$s 	= mb_strrpos($t, $this->stop);
			$t 	= mb_substr($t, 0, $s).'...';
		}
		else
			$t = $this->text;
		
		return $t;
	}
	
	
	
	/* public function textHTMLCrop()
	{
		$arrAvailTags = ('<i><em><a><b><strong>')
		$t 	= mb_substr($this->text, 0, $this->length);
		$s 	= mb_strrpos($t, $this->stop) + 1;
		$t 	= mb_substr($t, 0, $s);
		
		return $t;
	} */
	
	
	
	/**********************************/
	/* вставка стандартных мета-тегов */
	/**********************************/
	public function insertMeta()
	{
		global $_PAGE;
		
		$lang = '';
		if(isset($_SESSION['lang']) && $_SESSION['lang'] != 'ru')
			$lang = '_'.$_SESSION['lang'];
		
		$arrMeta = array(
			'title' => $_PAGE['p_meta_title'.$lang],
			'kwd' 	=> $_PAGE['p_meta_kwd'.$lang],
			'dscr' 	=> $_PAGE['p_meta_dscr'.$lang]
		);
		
		/*if(trim($_PAGE['p_meta_title']) == '')
			$arrMeta['title'] = trim($_PAGE['p_title']);
			
		if(trim($_PAGE['p_meta_kwd']) == '')
			$arrMeta['kwd'] = trim($_PAGE['p_title']);
			
		if(trim($_PAGE['p_meta_dscr']) == '')
			$arrMeta['dscr'] = trim($_PAGE['p_title']);	*/
		?>		
		
		<title><?=$arrMeta['title']?></title>
		<meta http-equiv="keywords" content="<?=$arrMeta['kwd']?>" />
		<meta http-equiv="description" content="<?=$arrMeta['dscr']?>" />
		<?
	}
	/***********************************/
	/* /вставка стандартных мета-тегов */
	/***********************************/
	
	/*public function inc()
	{
		
	}*/
	
}
?>