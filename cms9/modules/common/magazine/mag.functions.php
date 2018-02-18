<?
include_once 'class.tpl.php';


function saveTpl($id, $tpl_code)
{
	global $_VARS;
	
	$dir = chdir($_SERVER['DOCUMENT_ROOT']."/".$_VARS['tpl_dir'].'/tpl_mag');
	$file = fopen($id.'.php', "w");
	$fw = fwrite($file, stripslashes($tpl_code));
	return $fw;
}

function GetItems($tableName)
{
	global $_MODULE_PARAM, $_ICON;
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."` 
			WHERE 1
			ORDER BY mag_year DESC, mag_num ASC";
	//echo $sql;
	$res = mysql_query($sql);
	
	if(mysql_num_rows($res) > 0)
	{
		?>
		<table border=0 cellpadding=5 class="list">
			<tr>		
				<th>id</th>
				<th>Заголовок журнала</th>
				<th>Номер</th>
				<th>Показывать на сайте</th>
				<th>edit</th>
				<th>del</th>
			</tr>
		<?
		while($row = mysql_fetch_array($res))
		{
			?><tr>
				<td><?=$row['id'];?></td>
				<td><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><?=$row['mag_title'];?></a></td>
				<td><?=$row['mag_num'].'/'.$row['mag_year'];?></td>
				<td align="center"><?=iconChkBox($_MODULE_PARAM['tableName'], 'mag_show', $row['mag_show'], $row['id']);?></td>
				<td><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><img src='<?=$_ICON["edit"]?>'></a></td>
				<td><a style='color:red' href="javascript:if (confirm('Удалить журнал?')){document.location='?page=<?=$_MODULE_PARAM['name']?>&delItem&id=<?=$row['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}	
}

function GetMagInfo($id)
{
	global $_VARS;
	$arr[] = array(
			'id' => 0,
			'mag_num' => 0,
			'mag_year' => 0,
			'mag_title' => ''
		);
		
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_mag` 
			WHERE id = ".$id;
	//echo $sql;
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_assoc($res);
		$arr = array(
			'id' => $row['id'],
			'mag_num' => $row['mag_num'],
			'mag_year' => $row['mag_year'],
			'mag_title' => $row['mag_title']
		);
	}
	
	return $arr;
}

function GetItemsPages($tableName)
{
	global $_MODULE_PARAM, $_ICON;
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."` 
			WHERE 1
			ORDER BY p_mag ASC, p_num ASC";
	//echo $sql;
	$res = mysql_query($sql);
	
	if(mysql_num_rows($res) > 0)
	{
		?>
		<table border=0 cellpadding=5 class="list">
			<tr>		
				<th>id</th>
				<th>Журнал</th>
				<th>Номер страницы</th>
				<th>Показывать на сайте</th>
				<th>edit</th>
				<th>del</th>
			</tr>
		<?
		while($row = mysql_fetch_array($res))
		{
		
			$arr = GetMagInfo($row['p_mag']);
		//printArray($arr);
			?><tr>
				<td><?=$row['id'];?></td>
				<td>№<?=$arr['mag_num'].'/'.$arr['mag_year']?></td>
				<td align="center"><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><?=$row['p_num'];?></a></td>
				
				<td align="center"><?=iconChkBox($_MODULE_PARAM['tableName'], 'p_show', $row['p_show'], $row['id']);?></td>
				<td><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><img src='<?=$_ICON["edit"]?>'></a></td>
				<td><a style='color:red' href="javascript:if (confirm('Удалить страницу?')){document.location='?page=<?=$_MODULE_PARAM['name']?>&delItem&id=<?=$row['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}	
}

function GetItemsTpl($tableName)
{
	global $_MODULE_PARAM, $_ICON;
	
	
	
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."` 
			WHERE 1
			ORDER BY tpl_order ASC";
	//echo $sql;
	$res = mysql_query($sql);
	
	if(mysql_num_rows($res) > 0)
	{
		?>
		<table border=0 cellpadding=5 class="list">
			<tr>		
				<th>id</th>
				<th>Схема</th>
				<th>Показывать на сайте</th>
				<th>edit</th>
				<th>del</th>
			</tr>
		<?
		while($row = mysql_fetch_array($res))
		{
		
			$tpl = new MAGAZINE();
			$tpl -> cover = $row['tpl_icon'];
			$tpl -> cover_w = 100;
			$tpl -> cover_h = 149;
			$pic = $tpl -> getCover();
			//$arr = GetMagInfo($row['p_mag']);
		//printArray($arr);
			?><tr>
				<td><?=$row['id'];?></td>
				<td align="center"><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><?=$pic?></a></td>
				
				<td align="center"><?=iconChkBox($_MODULE_PARAM['tableName'], 'tpl_show', $row['tpl_show'], $row['id']);?></td>
				<td><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><img src='<?=$_ICON["edit"]?>'></a></td>
				<td><a style='color:red' href="javascript:if (confirm('Удалить шаблон?')){document.location='?page=<?=$_MODULE_PARAM['name']?>&delItem&id=<?=$row['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}	
}


function readItem($id)
{
	global $_MODULE_PARAM, $_VARS;
	
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."`
			WHERE id = ".$id;
	$res = mysql_query($sql);
	$row = mysql_fetch_assoc($res);	
	
	$dir = chdir($_SERVER['DOCUMENT_ROOT']."/".$_VARS['tpl_dir'].'/tpl_mag');
	
	if(file_exists($row['id'].'.php'))
	{
		$code = file($row['id'].'.php');
	
		//printArray($code);
		$tpl_code = "";
		foreach($code as $str)
		{
			$tpl_code .= htmlspecialchars($str); 
		}
		
		//echo htmlspecialchars($str);
		
		$row['tpl_code'] = $tpl_code;
	}
	
	return $row;
}


?>