<?
function GetItems($tableName)
{
	global $_MODULE_PARAM, $_ICON;
	$sql = "SELECT * FROM `".$_MODULE_PARAM['tableName']."` 
			WHERE 1
			ORDER BY id DESC";
	$res = mysql_query($sql);
	
	if(mysql_num_rows($res) > 0)
	{
		?>
		<table border=0 cellpadding=5 class="list">
			<tr>		
				<th>id</th>		
				<th>Имя параметра</th>
				
				<!--<th>Личный кабинет</th>-->	
				<th>Значение</th>			
				<th>Описание</th>
				<th>edit</th>
				<th>del</th>
			</tr>
		<?
		while($row = mysql_fetch_array($res))
		{
			?><tr>
				
				<td><?=$row['id'];?></td>
				<td>
					<a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>">
						<?=$row['var_name'];?>
					</a>
				</td>
				<td><?=$row['var_value'];?></td>
				<td><?=$row['var_note'];?></td>
				
				
				
				<td>
					<a href="?page=<?=$_MODULE_PARAM['name']?>&editItem&id=<?=$row['id'];?>">
						<img src='<?=$_ICON["edit"]?>'>
					</a>
				</td>
				<td><a href="javascript:if (confirm('Удалить переменную?')){document.location='?page=<?=$_MODULE_PARAM['name']?>&delItem&id=<?=$row['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
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