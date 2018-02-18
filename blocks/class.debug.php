<?
class DEBUG
{

	public function logging()
	{
		$f = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', 'a+');
		return $f;		
	}



	public function allVars()
	{
		$arr = array($_POST, $_GET);
		$f = $this -> logging();
		
		foreach($arr as $k)
		{
			//echo '<pre>'."$k".'<br>';
			$str = "";
			foreach($k as $k1 => $v1)
			{
				$str .= $k1.' => '.$v1."\n";
			}			
			
			fwrite($f, $str);
			//echo '</pre>';
		}
	}
}
?>