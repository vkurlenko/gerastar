<?
class MENU
{
	public $p_parent_id = 0;
	public $mainMenu    = false;
	public $lang		= '';
	
	/*
	**********************************
	Simple one-level page menu,
	
	RETURNS: 
	
	Array([page_id], [page_url], [page_title]);
	
	EXAMPLE:
	
	$arrMenu = new MENU;
	$arrMenu -> mainMenu = true;	// для формирования главного меню
	$arrMenu -> p_parent_id = 5;	// id родительского раздела
	$arrMenu = $arrMenu -> menuSimple();
	
	**********************************
	*/
	
	public function menuSimple()
	{
		$a = array();
		
		
		if($this -> mainMenu)
			$mainMenu = " AND p_main_menu = '1' ";
		else
			$mainMenu = '';
			
		$sql = "SELECT * FROM `".SITE_PREFIX."_pages`
				WHERE p_show = '1'".
				$mainMenu.
				"AND p_parent_id = ".$this -> p_parent_id."
				ORDER BY p_order ASC";
				
		$res = mysql_query($sql);
		
	
		if($res && mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_assoc($res))
			{
				$url = '';

				if(trim($row['p_redirect']) != '')
					$url = trim($row['p_redirect']);
				else 
					$url = trim($row['p_url']);
					
				// попробуем сформировать подменю
				$arrSub = new MENU;
				$arrSub -> mainMenu = false;			
				$arrSub -> p_parent_id = $row['id'];	// id родительского раздела
				$arrSub = $arrSub -> menuSimple();
				
				$a[] = $row;	
				/*$a[] = array(
					'id' 		=> $row['id'], 
					'p_url' 	=> $url, 
					'p_tpl'		=> $row['p_tpl'],
					'p_title' 	=> $row['p_title'.$this -> lang],
					'p_child'	=> $arrSub,
					'p_show'	=> $row['p_show'],
					'p_site_map'=> $row['p_site_map'],
					'p_main_menu'=>$row['p_main_menu']
				);*/
			}
		}
		
		return $a;
	}
	
	
	
	public function menuIsChild($p_parent_id)
	{
		$sql = "SELECT * FROM `".SITE_PREFIX."_pages`
				WHERE p_parent_id = ".$p_parent_id;
		
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
			return true;
		else 
			return false;
	}
	
	
	
	
}
?>