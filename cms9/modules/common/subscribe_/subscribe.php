<?
error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT']."/config.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/db.php";
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.db.php";
include_once $_SERVER['DOCUMENT_ROOT']."/".$_VARS['cms_dir']."/".$_VARS['cms_modules']."/framework/class.subscribe.php";
include_once "subscribe.func.php";

$tableName = $_VARS['tbl_prefix']."_subscribe";


// интервал рассылки пачек писем по умолчанию (сек.)
// может быть переопределен в настройках системы
if(!isset($_VARS['env']['subscribe_period']))
{
	$_VARS['env']['subscribe_period'] = 30;
}

// кол-во писем в час по умолчанию
// может быть переопределен в настройках системы
if(!isset($_VARS['env']['subscribe_count']))
{
	$_VARS['env']['subscribe_count'] = 50;
}
/*
$mail_admin = $_VARS['env']['mail_admin'];

$from = $_VARS['env']['mail_from'];*/


$arrTableFields = array(
	"id"				=> "int auto_increment primary key",
	"subscribe_mail"	=> "text",						/* e-mail подписчика */
	"subscribe_status"	=> "enum('0','1') not null",	/* статус текущей рассылки (0 - рассылка отправлена, 1 - не отправлена) */
	"subscribe_reg_date"=> "datetime not null"			/* дата/времы регистрации подписчика */
);

$db_Table = new DB_Table();
$db_Table -> debugMode = false;
$db_Table -> tableName = $tableName;
$db_Table -> tableFields = $arrTableFields;
$db_Table -> create();


// запуск новой рассылки
if(isset($_POST['do']))
{
	echo '<span class="msgOk">Начинаем новую рассылку</span>';
	
	$sql = "UPDATE `".$tableName."` 
			SET subscribe_status = '0' 
			WHERE 1";
			
	$res = mysql_query($sql);
	
	include "subscribe.mail2.php";
}
elseif(isset($_POST['doTest']))
{
	echo '<span class="msgOk">Начинаем тестовую рассылку</span>';
	
	$mode = 'test';
		
	include "subscribe.mail2.php";
}




// добавление новой записи
if(isset($addItem))
{
	// проверим на существование email в базе подписчиков
	$sql = "select * from `".$tableName."` where subscribe_mail = '".trim($_POST['subscribe_mail'])."'";
	$res = mysql_query($sql);
	if(mysql_num_rows($res) == 0)
	{
		// вносим новый адрес в базу
		
		$_POST['subscribe_reg_date'] = date("Y-m-d H:i:s");
	
		// предварительно удалим ненужные элементы
		$arrData = delArrayElem($_POST, array("addItem", "id"));	
				
		$db_Table -> tableData = $arrData;
		$db_Table -> addItem();	
	}	
}

// удаление записи
if(isset($del_item) and isset($id))
{
	// параметры запроса на удаление
	$db_Table -> tableWhere = array("id" => $id);
	
	// удаление записи
	$db_Table -> delItem();	
}

// изменение записи
if(isset($updateItem) and isset($id))
{	
	// предварительно удалим ненужные в запросе элементы
	$arrData = delArrayElem($_POST, array("updateItem", "id"));
	
		// по какому условию будем делать запрос	
	$db_Table -> tableWhere = array("id" => $id);
	
	// запрос к БД
	$db_Table -> tableData = $arrData;
	$db_Table -> updateItem();	
}
?>

<?
include_once "head.php";
?>
<body>

<form action="" method="post">
	<input type="submit" name="do" value="Запустить новую рассылку" />
	<input type="submit" name="doTest" value="Запустить тестовую рассылку" />
</form>

<fieldset><legend>Текущая рассылка</legend>
<?
$sql = "select * from `".$_VARS['tbl_prefix']."_presets` where var_name = 'subscribe_count'";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
echo "<br>Отправка последней части рассылки закончена: <strong>".date("Y-m-d H:i:s")."</strong><br><br>";
echo "Интервал рассылки: <strong>".$_VARS['env']['subscribe_period']." (сек.)</strong><br><br>";
echo "Количество писем за интервал: <strong>".$_VARS['env']['subscribe_count']."</strong><br><br>";

$sql = "select * from `".$tableName."` where subscribe_status = '0'";
$res = mysql_query($sql);
echo "Неотправленных писем осталось: <strong>".mysql_num_rows($res)."</strong>";
?> 
</fieldset>

<?
if(!isset($editItem) && !isset($setItem))
{
	?>
	<fieldset><legend>Список подписчиков новостной рассылки</legend>
	
	
	<div>
	<?
	/* генерация списка адресов */
	if(isset($_FILES['list']))
	{
		//printArray($_FILES['list']);
		
		if(file_exists($_FILES['list']['tmp_name']))
		{
			$f = file($_FILES['list']['tmp_name']);
			
			foreach($f as $k)
			{
				if(preg_match('(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})', $k))
				{
					$sql = "INSERT INTO `".$tableName."`
							(subscribe_mail, subscribe_reg_date)
							VALUES ('".trim($k)."', '".date("Y-m-d H:i:s")."')";
					$res = mysql_query($sql);
				}
			}
		}
		
	}
	?>
	<form action="" method="post" enctype="multipart/form-data">
		Загрузить список из текстового файла: <input type="file" name="list">
		<input type="submit" value="Загрузить">
	</form>
	
	</div>
	
	
	
	<a class="serviceLink" href="<?=$_SERVER['REQUEST_URI'];?>&setItem"><img src='<?=$_ICON["add_item"]?>'>Добавить новый адрес</a>
	<?
	getItems();
	?> 
	</fieldset>
	<?
}

else
{
	$caption = "Добавить новый адрес подписки";
	$id = "";
	$subscribe_mail 	= "";
	$submit = array('addItem', 'Создать');
	
	if(isset($editItem) && isset($id))
	{		
		$id 	= $_GET['id'];
		$row 	= readItem($id);
		$caption 		= "Редактирование адреса ".$row['subscribe_mail'];
		$subscribe_mail = $row['subscribe_mail'];
		$submit 		= array('updateItem', 'Изменить');
	}
	?>
	<fieldset><legend><?=$caption;?></legend>
	
		<form method=post enctype=multipart/form-data action="?page=subscribe" name="form1" id="form1">
		<table>
		
			<tr>
				<td>
					Адрес подписчика</td><td>
					<input type="hidden" name="id" value="<?=$id?>">		
					<input type="text" name="subscribe_mail" size="40" value="<?=$subscribe_mail?>" />					
				</td>
			</tr>
		</table>
		<input type="submit" name="<?=$submit[0]?>" value='<?=$submit[1]?>' />
		</form>
	</fieldset>
	<?
}




/*$path = $_SERVER['DOCUMENT_ROOT'].'/list.txt';
$f = fopen($path, 'w+');

for($i = 0; $i < 10; $i++)
{
	$addr = sprintf('%03d', $i);
	fputs($f, $addr.'@mail.ru'."\n");
}*/

?>

</body>
</html>
