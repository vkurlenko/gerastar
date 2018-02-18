<?
$tableName = $_VARS['tbl_prefix']."_subscribe_content";

function getItems()
{
	global $tableName, $_ICON;
	$sql = "select * from `".$tableName."` where 1 order by id desc";
	$res = mysql_query($sql);
	if($res)
	{
		?>
		<table border=0 cellpadding=5  class="list">
			<tr>	
				<th></th>			
				<th>Заголовок</th>
				<th></th>
				<th>edit</th>
				<th>del</th>
			</tr>
		<?
		$i = 1; 
		while($row = mysql_fetch_array($res))
		{
			?><tr>
				<td><?=$i++?></td>
				<td><?=$row['text_title'];?></td>
				<td><? if($row['text_active']){?><img src='<?=$_ICON['tick']?>'><? }?></td>
				<td><a href="?page=subscribe_content&editItem&id=<?=$row['id'];?>"><img src='<?=$_ICON["edit"]?>'></a></td>
				<td><a style='color:red' href="javascript:if (confirm('Удалить текст?')){document.location='?page=subscribe_content&delItem&id=<?=$row['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
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
	global $tableName;
	$sql = "select * from `".$tableName."` where id = ".$id;
	$res = mysql_query($sql);
	if(mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
	}	
	else $row = array();
	
	return $row;
}
?> 