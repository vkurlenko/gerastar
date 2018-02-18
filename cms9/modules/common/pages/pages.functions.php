<?
function GetItems($tableName,$p_parent_id)
{
	global $_MODULE_PARAM, $_ICON;
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."` 
			WHERE p_parent_id = ".$p_parent_id."
			ORDER BY p_order ASC";
	//echo $sql;
	$res = mysql_query($sql);
	
	?>
	<table border=0 cellpadding=5 class="list">
		<tr>		
			<th>id</th>
			<th></th >	
			<th><img src='<?=$_ICON["main_menu"];?>' title="В главном меню"></th >	
			<th align="center">Название</th >
			<th align="center">URL</th >				
			<th align="center">шаблон</th >				
			<th>показывать на сайте</th >
			<th>закрытый раздел</th >				
			<th>подразделы</th >
			<th>изменить</th >
			<th>удалить</th >
		</tr>
	<?	
	if(mysql_num_rows($res) > 0)
	{		
		while($row = mysql_fetch_array($res))
		{
		
		$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."` 
				WHERE p_parent_id = ".$row['id'];
		$r = mysql_query($sql);
		
		/*mysql_num_rows($r) > 0 ? $icon = 'next' : $icon = 'next_empty';*/
		
		$url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['p_url'].'/';
		?>
		<tr>
			<td align="center"><?=$row['id'];?></td>
			<td>		
					<a href='?page=<?=$_MODULE_PARAM['name']?>&id=<?=$row['id'];?>&move&parent=<?=$row['p_parent_id'];?>&dir=asc#<?=$row['id'];?>'><img src='<?=$_ICON["down"]?>' alt='down'></a><a href='?page=<?=$_MODULE_PARAM['name']?>&id=<?=$row['id'];?>&move&parent=<?=$row['p_parent_id'];?>&dir=desc#<?=$row['id'];?>'><img src='<?=$_ICON["up"]?>' alt='up'></a>
					
			
			</td>
			<td align="center"><?=iconChkBox($_MODULE_PARAM['tableName'], 'p_main_menu', $row['p_main_menu'], $row['id']);?></td>
			<td><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><?=$row['p_title'];?></a></td>
			<td><a href="<?=$url?>" target="_blank"><?=$url?></a></td>
			<td><?=$row['p_tpl'];?></td>
			<td align="center"><?=iconChkBox($_MODULE_PARAM['tableName'], 'p_show', $row['p_show'], $row['id']);?></td>
			<td align="center"><?=iconChkBox($_MODULE_PARAM['tableName'], 'p_protect', $row['p_protect'], $row['id']);?></td>
			<td align="center"><?
			if(mysql_num_rows($r) > 0)
			{
				 $icon = 'next';
				 ?><a href="?page=<?=$_MODULE_PARAM['name']?>&p_parent_id=<?=$row['id'];?>" title='Перейти к дочерним разделам'><img src='<?=$_ICON[$icon]?>'></a><?
			}
			
			?></td>
			<td align="center"><a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>"><img src='<?=$_ICON["edit"]?>' title='Редактировать'></a></td>
			<td align="center"><a style='color:red' href="javascript:if (confirm('Удалить статью?')){document.location='?page=<?=$_MODULE_PARAM['name']?>&delItem&id=<?=$row['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
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
	global $_MODULE_PARAM;
	
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."`
			WHERE id = ".$id;
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	return $row;
}
?>