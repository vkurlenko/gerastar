<?


class SITE_MAP
{
	public  $table_name,	
			$parent_field,
			$order_by_field,
			$page_title,
			$lang;
			
	public $h = '';
	
	public $arr = array();
			
	function selectLevel($parent, $level)
	{
		global $h;
		
		$sql = "SELECT * FROM `".$this -> table_name."`
				WHERE ".$this -> parent_field." = ".$parent."
				AND p_show = '1' 
				AND p_site_map = '1' 
				ORDER BY ".$this -> order_by_field." ASC";
				

		${"res$level"} = mysql_query($sql);		
		
		echo '<ul>';
		while(${"row$level"} = mysql_fetch_array(${"res$level"}))
		{
			if(${"row$level"}['p_redirect'] != '')
				$url = ${"row$level"}['p_redirect'];
			else
				$url = ${"row$level"}['p_url'];
				
			echo "<li><a href='/".$url."/'>".${"row$level"}[$this -> page_title]."</a></li>";			
			$tab = $level+1;
			$this -> selectLevel(${"row$level"}['id'], $tab);			
		}
		echo '</ul>';
	}
	
	/*function getArrSiteMap($parent, $level)
	{
		global $h;
		
		
		
		$sql = "SELECT * FROM `".$this -> table_name."`
				WHERE ".$this -> parent_field." = ".$parent."
				AND p_show = '1' 
				AND p_site_map = '1' 
				ORDER BY ".$this -> order_by_field." ASC";
				

		${"res$level"} = mysql_query($sql);		
		
		//echo '<ul>';
		while(${"row$level"} = mysql_fetch_array(${"res$level"}))
		{
			if(${"row$level"}['p_redirect'] != '')
			{
				$url = ${"row$level"}['p_redirect'];				
			}
			else
				$url = ${"row$level"}['p_url'];
				
			$this -> arr[${"row$level"}['id']] = array($url, ${"row$level"}[$this -> page_title]);
				
			//echo "<li><a href='/".$url."/'>".${"row$level"}[$this -> page_title]."</a></li>";			
			$tab = $level+1;
			$this -> getArrSiteMap(${"row$level"}['id'], $tab);			
		}
		//echo '</ul>';
		
		return $this -> arr;
	}*/
}
?>