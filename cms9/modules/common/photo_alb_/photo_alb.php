<?
session_start();
include_once "../config.php" ;
//include_once "../fckeditor/fckeditor.php";
include_once "../db.php";
include_once 'f.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/cms9/modules/framework/class.html.php';

check_access(array("admin", "editor"));



$table_name = $_VARS['tbl_photo_alb_name'];

/*$sql = "SELECT * FROM `$table_name`
		WHERE 1";

$res = mysql_query($sql);

while($row = mysql_fetch_array($res))
{
	//$newId = intval($row['alb_name']);
	$sql = "UPDATE `$table_name` 
			SET alb_order = ".$row['id']."
			WHERE id = '".$row['id']."'";
	echo $sql."<br>";
	$res1 = mysql_query($sql);
}*/



$arrAlbMark = array(
	"none" 		=> "без метки",
	"gallery" 	=> "галерея",	
	"collection"=> "коллекция"
);





CreateTable();


// создать новый альбом
if(isset($set_item))
{
	$res = AddItem(@$alb_name, $alb_parent, @$alb_title, @$alb_video, @$alb_text, @$alb_img, @$alb_mark);
	
	if($res)
		header('location:/'.$_VARS['cms_dir'].'/workplace.php?page=photo_alb');
}

// редактировать альбом
if(isset($update_item) and isset($id))
{	
	$res = UpdateItem($id, $alb_parent, $alb_title, @$alb_video, @$alb_text, $alb_img, $alb_mark);

	if($res)
		header('location:/'.$_VARS['cms_dir'].'/workplace.php?page=photo_alb');
}

// перемещение (сортировка)
if(isset($move) and isset($dir) and isset($id))
{
	MoveItem($id, $dir, $parent);
}

// удалить альбом
if(isset($del_item) and isset($alb_name))
{
	DelItem($alb_name);
	DropTable($alb_name);
	DeleteDir($alb_name);
}


?>
<?
include_once "head.php";
?>

<body>
<?

function mysql_check_table()
{
	global $_VARS;
	
	$table_list = mysql_query("SHOW TABLES FROM `gantil_db`");
	
	while($row = mysql_fetch_row($table_list))
	{
		if(strpos($row[0], 'gantil_pic') !== false) 
		{
			insert_record($row[0]);			
		}
		//exit;
	}
}

function insert_record($tbl_name)
{
	global $_VARS;
	
	$a = explode('_', $tbl_name);
	$alb_id = $a[2];
	
	if(is_numeric($alb_id))
	{
	
		$sql = "SELECT * FROM `$tbl_name`
				WHERE 1";
		
		$res = mysql_query($sql);
		
		echo $tbl_name.' ('.mysql_num_rows($res).')<br>';
		$i = 0;
		
		if($res && mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_assoc($res))
			{
			
				if($row['pub'] == '')
					$row['pub'] = 0;
			
				$sql = "INSERT INTO `gantil_pic` 
						(pic_id, alb_id, file_ext, name, tags, pub,  url, order_by)
						VALUES (".$row['id'].", ".$alb_id.", '".$row['file_ext']."', '".$row['name']."', '".$row['tags']."', ".$row['pub'].", '".$row['url']."', ".$row['order_by'].")";
				//echo $sql."<br>";		
				$res1 = mysql_query($sql);
				if($res1)
					$i++;
				else
					echo $sql.'<br>';
			}
		}
		echo 'скопировано '.$i.'<br><br>';
	}
}


//mysql_check_table();

if(isset($edit_item) and isset($id))
{
	$sql = "SELECT * FROM `$table_name` 
			WHERE id='$id'";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	?>
	<strong style="padding:20px; display:block">Редактирование</strong>
	<form method="post" enctype="multipart/form-data" action="" name="form2" id="form2">
	<table>
		<tr>
			<td>Название</td>
			<td>
			<input type="text" name="alb_title" value="<?=$row['alb_title']?>" size="83" />
			<input type="hidden" name="id" value="<?=$row['id']?>" />
			</td>
		</tr>
		<tr>
			<td>Родительский альбом</td>
			<td><!--<select name="alb_parent">-->
			<?
			$select = new FormElement();
			$select -> fieldProperty["name"] = 'alb_parent'; // атрибут NAME поля SELECT
			$select -> fieldProperty["value"] = $row['alb_parent'];		// id родительского альбома
			$select -> thisId = $row['id'];					// id данного альбома
				
			$html = $select -> createSelectAlb();
			
			echo $html;
			?>
			</td>
		</tr>
		<tr>
			<td>Картинка превью</td>
			<td><select name="alb_img" >
			<?			
			$r = mysql_query("select * from `".$_VARS['tbl_photo_name'].$row['alb_name']."` order by `id` desc ");
			
			if($row['alb_img'] == 0) echo "<option value='0' selected>Без картинки\n";
			else echo "<option value='0'>Без картинки\n";
			while($res = mysql_fetch_array($r))
			{
				if ($row['alb_img'] == $res['id']) $selected = " selected";
				else $selected = " ";
				echo "<option value='".$res['id']."' ".$selected.">".$res['name']."\n";
			}
			?>
			</select> <span style="font-size:10px;">(название картинки из фотобанка "<a href="/<?=$_VARS['cms_dir'];?>/workplace.php?page=photo&zhanr=<?=$row['alb_name']?>" target="_self"><?=$row['alb_title']?></a>")</span></td>
		</tr>
		<tr>
			<td>Метка альбома</td>
			<td><select name="alb_mark" >
			<?			
			foreach($arrAlbMark as $k => $v)
			{
				$sel = "";
				if($k == $row['alb_mark']) $sel = " selected ";
				?>
				<option value="<?=$k?>" <?=$sel?>><?=$v?></option>
				<?
			}
			?>
			</select>
			</td>
		</tr>
		
		<tr>
			<td>
				Код вставки видео</td><td>
				<textarea name="alb_video" cols="60" rows="10" /><?=$row['alb_video'];?></textarea><span style="font-size:10px;">(вставляется html-код видео-ролика)</span>
			</td>
		</tr>
		<tr>
			<td>
				Текст</td><td>
				<textarea name="alb_text" cols="60" rows="10" ><?=$row['alb_text']?></textarea>
			</td>
		</tr>
	</table>
			<input type="submit" name="update_item" value="Сохранить" />	
	</form>
	<?	
}
elseif(isset($add_item))
{
?> 

<strong style="padding:20px; display:block">Добавить альбом</strong>
<form method=post enctype=multipart/form-data action="" name="form2" id="form2"><table>
	<table>
		<tr>
			<td>Название</td>
			<td>
			<input type="text" name="alb_title" value="" size="40" />
			</td>
		</tr>	
		<tr>
			<td>Родительский альбом</td>
			<td>
			<?
			
			$select = new FormElement();
			$select -> fieldProperty["name"] = 'alb_parent'; // атрибут NAME поля SELECT
			$select -> fieldProperty["value"] = 0;		// id родительского альбома
			//$select -> thisId = $row['id'];					// id данного альбома
				
			$html = $select -> createSelectAlb();
			
			echo $html;
			?>
			
			</td>
		</tr>
		<tr>
			<td>Текст</td>
			<td>
			<textarea style="width:500" name="alb_text"></textarea>
			</td>
		</tr>		
	</table>
		
	<input type="submit" name="set_item" value="Добавить" />
</form>
<?
}
else
{
	if(isset($_GET['parent']) && intval($_GET['parent']) > 0)
		$alb_parent = intval($_GET['parent']);
	else
		$alb_parent = 0;

	$sql = "SELECT * FROM `$table_name` 
			WHERE alb_parent = ".$alb_parent."
			ORDER BY alb_order ASC";
	//echo $sql;
	$res = mysql_query($sql);
	?>
	<fieldset><legend>Фотоальбомы</legend>
		<a class="serviceLink" href="?page=photo_alb&add_item"><img src='<?=$_ICON["add_item"]?>'>Добавить новый альбом</a>
		<table cellpadding="5"  class="list">
			<tr>
				<th>id</th>
				<th></th>
				<th><strong>Название</strong></th>
				<th>Вложенных <br>альбомов</th>
				<th><strong>Изображений<br>в альбоме</strong></th>
				<th><strong>Папка</strong></th>
				
				<th><strong>Метка</strong></th>
				<th><strong>Создан</strong></th>
				<th><strong>Обновлен</strong></th>
				<th><strong>edit</strong></th>
				<th><strong>del</strong></th>				
			</tr>
			<?
			if($res && mysql_num_rows($res) > 0)
			{
				while($row = mysql_fetch_array($res))
				{
				
					$sql = "SELECT * FROM `$table_name`
							WHERE alb_parent = ".$row['id'];
					$res1 = mysql_query($sql);
					
					if($res1 && mysql_num_rows($res1) > 0)
						$parent = $row['id'];
					else
						$parent = 0;
				
				?>
				<tr>
					<td align="center"><?=$row['id'];?></td>
					<td align="center"><a href="?page=photo_alb&move&dir=asc&id=<?=$row['id']?>&parent=<?=$alb_parent?>"><img src='<?=$_ICON["down"]?>'></a><a href="?page=photo_alb&move&dir=desc&id=<?=$row['id']?>&parent=<?=$alb_parent?>"><img src='<?=$_ICON["up"]?>'></a></td>	
					<td><a href="?page=photo&zhanr=<?=$row['alb_name'];?>&p=0"><?=$row['alb_title'];?></a></td>
					<td align="center"><? if($parent > 0) {?><a href="?page=photo_alb&parent=<?=$parent?>"><strong><?=mysql_num_rows($res1)?></strong></a><? } ?></td>
					<td align="center"><?
					$sql = "SELECT id, file_ext FROM `".$_VARS['tbl_prefix']."_pic_".$row['alb_name']."` 
							WHERE 1";
					$res1 = mysql_query($sql);
					
					if($res1)
					{
						// кол-во картинок в таблице
						$nDB = mysql_num_rows($res1);
						echo $nDB;
						
						// кол-во картинок физически
						$i = 0;
						while($row1 = mysql_fetch_assoc($res1))
						{
							$p = $_SERVER['DOCUMENT_ROOT'].'/pic_catalogue/'.$_VARS['tbl_prefix']."_pic_".$row['alb_name'].'/'.$row1['id'].'.'.$row1['file_ext'];
							/*echo $p;
							exit;*/
							if(file_exists($p))
							{
								$i++;
							}
						}
						
						if($nDB != $i)
							echo "($i)";
						
					}
					else
						echo '-';
					?>
					</td>
					<td><?="/".$_VARS['tbl_photo_name'].$row['alb_name'];?>
					<?
					// проверим существование папки
						$d = $_SERVER['DOCUMENT_ROOT'].'/pic_catalogue/'.$_VARS['tbl_prefix']."_pic_".$row['alb_name'];
						if(!is_dir($d))
						{
							echo '<br><strong style="color:red">Нет папки!</strong>';
							$create = mkdir($d);
							chmod($d, 0777);
							if($create)
								echo '<br><strong style="color:green">Создана.</strong>';
						}
							
					?>
					
					</td>
					<td align="center">
					<? 
						if($row['alb_mark'] != 'none' && $row['alb_mark'] != '')
							echo $arrAlbMark[$row['alb_mark']];
					?>
					</td>
					<td><?=$row['alb_create'];?></td>
					<td><?=$row['alb_update'];?></td>
					
					<td align="center"><a href="?page=photo_alb&edit_item&id=<?=$row['id']?>"><img src='<?=$_ICON["edit"]?>'></a></td>
					<td align="center"><a href="javascript:if (confirm('Удалить раздел?')){document.location='?page=photo_alb&del_item&alb_name=<?=$row['alb_name']?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
					
				</tr>
				<?
				}
			}
			?>		
		</table>
		<a class="serviceLink" href="?page=photo_alb&add_item"><img src='<?=$_ICON["add_item"]?>'>Добавить новый альбом</a>
	</fieldset>
	<?
}


?>
</body>
</html>
