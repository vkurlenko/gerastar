<?
// создадим таблицу учета заказов
$sql = "CREATE TABLE `".$_VARS['tbl_prefix']."_order`
		(
			id 				INT(11) NOT NULL AUTO_INCREMENT,
			order_num		TEXT,
			client_id		INT,
			client_name 	TEXT,
			client_contact 	TEXT,
			order_list		TEXT,
			sum_full		TEXT,
			sum_payed		INT NOT NULL DEFAULT 0,
			order_status	ENUM('0', '1', '2', '3', '4', '5'),
			order_date		DATETIME,
			PRIMARY KEY (id)
		)";
		
$res = mysql_query($sql);

// создадим таблицу учета данных для квитанции безналичного расчета

/*
order_id		id заказа из таблицы *_orders,	
client_urname	наименование заказчика,
client_payer 	плательщик,
order_list		список заказа (array),
sum_full		полная стоимость,
order_date		дата формирования квитанции,
*/
$sql = "CREATE TABLE `".$_VARS['tbl_prefix']."_order_blank`
		(
			id 				INT(11) NOT NULL AUTO_INCREMENT,
			order_id		INT,	
			client_urname	TEXT,
			client_payer 	TEXT,
			order_arr_goods	TEXT,
			sum_full		TEXT,
			order_date		DATETIME,
			PRIMARY KEY (id)
		)";
		
$res = mysql_query($sql);



class ORDER
{
	public $tbl;	// имя таблицы учета заказов
	
	public $id; 					// id заказа
	public $order_num		= '';	// номер заказа
	public $client_id		= 0;	// id клиента 
	public $client_name		= '';	// имя клиента
	public $client_urname	= '';	// наименование организации
	public $client_contact	= '';	// контактные данные клиента
	public $order_list		= '';	// текст заказа
	public $order_arr_goods = array();	// массив позиций заказа
	public $sum_full		= '0';	// сумма заказа полная
	public $sum_payed		= 0;	// внесенная предоплата
	public $order_status 	= '0';	// статус заказа (0 - новый, 1 - выполнен, 2 - не оплачен, 3 - внесена предоплата, 4 - оплачен полностью, 5 - отменен)
	public $order_date 		= '';	// дата заказа
	public $order_by		= 'order_date';	// сортировка списка заказов по дате
	public $order_dir		= 'DESC';	// сортировка списка заказов по дате
	public $make_blank		= false;
	public $filter_field	= '1';
	public $filter_value	= '1';
	public $limit			= 100;
	public $status_f		= array(); // массив статусов
	
	// robokassa
	public $mrh_login 		= 'cakeberry';
	public $mrh_pass1 		= 'DvqIi6O4';
	public $mrh_pass2 		= 'Vpa2uj2y';
	public $order_desc		= '';
	public $pay_mode		= 'real'; // режим (тестовый / рабочий)
	
	public $pay_url = array(
		'test' => 'http://test.robokassa.ru/Index.aspx',
		'real' => 'https://auth.robokassa.ru/Merchant/Index.aspx'
	);
	
	
	// получим ссылку на оплату
	public function getPayUrl()
	{
		// your registration data
		$mrh_login = $this -> mrh_login;    // your login here
		$mrh_pass1 = $this -> mrh_pass1;   	// merchant pass1 here	
		
		// order properties
		$inv_id    = $this -> id;        	// id заказа в БД
		$inv_desc  = $this -> order_desc;  	// описание заказа
		$out_summ  = $this -> sum_full;  	// сумма к оплате
		
		// build CRC value
		$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
		
		// build URL
		$url = $this -> pay_url[$this -> pay_mode]."?MrchLogin=$mrh_login&".
			"OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";
		
		return $url;	
	
	}
	
	// проверим результат транзакции
	function getPayResult()
	{
		// as a part of ResultURL script

		// your registration data
		$mrh_pass2 = $this -> mrh_pass2;   // merchant pass2 here
		
		// HTTP parameters:
		$out_summ 	= $_REQUEST["OutSum"];
		$inv_id 	= $_REQUEST["InvId"];
		$crc 		= $_REQUEST["SignatureValue"];
		
		// HTTP parameters: $out_summ, $inv_id, $crc
		$crc = strtoupper($crc);   // force uppercase
		
		// build own CRC
		$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2"));
		
		if (strtoupper($my_crc) != strtoupper($crc))
		{
		  return "bad sign\n";
		  exit();
		}
		
		// print OK signature
		return "OK$inv_id\n";
	}
	
	
	
	function getPaySuccess()
	{
		// as a part of SuccessURL script

		// your registration data
		$mrh_pass1 = $this -> mrh_pass1;  // merchant pass1 here
		
		// HTTP parameters:
		$out_summ 	= $_REQUEST["OutSum"];
		$inv_id 	= $_REQUEST["InvId"];
		$crc 		= $_REQUEST["SignatureValue"];
		
		$crc = strtoupper($crc);  // force uppercase
		
		// build own CRC
		$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1"));
		
		if (strtoupper($my_crc) != strtoupper($crc))
		{
		  //echo "bad sign\n";
		  //exit();
		  
		  return false;
		}
		else
		{
			/*$this -> sum_payed = $_REQUEST["OutSum"];
			$this -> setSumPayed();*/
			return true;
		}
		
		// you can check here, that resultURL was called 
		// (for better security)
		
		// OK, payment proceeds
		//echo "Оплата заказа №$inv_id на сумму $out_summ прошла успешно.\n";
	}
	
	
	public function getOrderNum()
	{
		global $_VARS;
		
		isset($_VARS['env']['orderPrefix']) ? $order_num = $_VARS['env']['orderPrefix'] : $order_num = '';
		$order_num .= '-'.date('Ymd').'-'.sprintf('%07d', $this -> id);
		
		return $order_num;
	}
	
	// запись заказа в БД
	public function addOrder()
	{
		
		$sql = "INSERT INTO `".$this -> tbl."`
				(
					
					client_id,
					client_name,
					client_contact,
					order_list,
					sum_full,
					sum_payed,
					order_status,
					order_date
				)
				VALUES
				(
					
					". $this -> client_id.",
					'".$this -> client_name."',
					'".$this -> client_contact."',
					'".$this -> order_list."',
					'".$this -> sum_full."',
					". $this -> sum_payed.",
					'".$this -> order_status."',
					NOW()				
				)";	
		$res = mysql_query($sql);
		
		
		
		if(!$res)
			echo $sql;
		else
		{
			
			$this -> id = $this -> order_num = mysql_insert_id();
			
			$sql = "UPDATE `".$this -> tbl."`
					SET order_num = '".$this -> getOrderNum()."'
					WHERE id = ".$this -> id;
			
			$res = mysql_query($sql);
			
			if($this -> make_blank == true)
			{
				
				
				// запишем данные для формирования квитанции
				$sql = "INSERT INTO `".$this -> tbl."_blank`
					(
						order_id,	
						client_urname,
						client_payer,
						order_arr_goods,
						sum_full,
						order_date
					)
					VALUES
					(
						".$this -> id.",
						'".$this -> client_urname."',
						'".$this -> client_urname."',
						'".serialize($this -> order_arr_goods)."',
						'".$this -> sum_full."',
						NOW()				
					)";	
				$res = mysql_query($sql);
				//echo $sql;
			}
			
		}
		
		return $res;
	}
	
	function editOrder()
	{
		$sql = "UPDATE `".$this -> tbl."`
				SET order_list = '".$this -> order_list."',
				sum_full = '".$this -> sum_full."'
				WHERE id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		if(!$res)
			echo $sql;
	}
	
	
	// удаление заказа из БД
	public function delOrder()
	{
		$sql = "DELETE FROM `".$this -> tbl."`
				WHERE id = ".$this -> id;
				
		$res = mysql_query($sql);
		
		if(!$res)
			echo $sql;
	}
	
	
	// изменение статуса заказа
	public function setOrderStatus()
	{
		$sql = "UPDATE `".$this -> tbl."`
				SET order_status = '".$this -> order_status."'
				WHERE id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		if(!$res)
			echo $sql;
	}
	
	// изменение статуса заказа
	public function setSumPayed()
	{
		$s = 0;
		
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE id = ".$this -> id;
				
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_assoc($res);
			$s = $row['sum_payed'];			
		}
	
		$sql = "UPDATE `".$this -> tbl."`
				SET sum_payed = ".($s + $this -> sum_payed).",
				order_status = '4'
				WHERE id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		//if(!$res)
			//echo $sql;
	}
	
	
	// прочитаем заказ по его id 
	public function getOrder()
	{
		$arr = array();
		
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		if($res)
		{
			$row = mysql_fetch_assoc($res);
			$arr = $row;
		}
		
		return $arr;
	}
	
	// прочитаем заказ по его id 
	public function getOrderBlank()
	{
		$arr = array();
		
		$sql = "SELECT * FROM `".$this -> tbl."_blank`
				WHERE order_id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		if($res)
		{
			$row = mysql_fetch_assoc($res);
			$arr = $row;
		}
		
		return $arr;
	}
	
	
	public function getOrdersNum()
	{
		$arr = array();
		
		$where = 1;
		
		if(isset($this -> user_id))
			$where = "client_id = ".$this -> user_id;
			
		if(isset($this -> filter_field) && isset($this -> filter_value))
			$where .= " AND ".$this -> filter_field." = '".$this -> filter_value."'";
			
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE ".$where; //." ".$this -> order_dir;
		//echo $sql;
		$res = mysql_query($sql);
		
		return mysql_num_rows($res);
	}
	
	
	// считывание всех заказов из БД
	public function getAllOrders()
	{
		$arr = array();
		
		$where = 1;
		
		if(isset($this -> user_id))
			$where = "client_id = ".$this -> user_id;
			
		if(isset($this -> filter_field) && isset($this -> filter_value))
			$where .= " AND ".$this -> filter_field." = '".$this -> filter_value."'";
			
		if(isset($this -> limit) && isset($this -> pg))
			$lim = ' LIMIT '.($this -> pg * $this -> limit).', '.$this -> limit;
		else
			$lim = '';
		
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE ".$where."
				ORDER BY ".$this -> order_by." ".$lim; //." ".$this -> order_dir;
		//echo $sql;
		$res = mysql_query($sql);
		
		if($res)
		{
			while($row = mysql_fetch_assoc($res))
			{
				$arr[] = $row;
			}
		}
		
		return $arr;		
	}
	
	public function printOrder()
	{
		$text = '';
		
		$a = @unserialize($this -> order_list);
		
		//printArray($a);
		
		if(is_array($a))
		{
			$p = '+7&nbsp;('.substr($a['orderClientPhoneNum'], 0, 3).')&nbsp;'.substr($a['orderClientPhoneNum'], 3);
		
			$text .= '
			Вид доставки: '.		$a['orderDelivVar'].'<br>
			Расстояние: '.			$a['orderDelivRange'].'<br>
			Способ дегустации: '.	$a['orderTestVar'].'<br>
			Дата доставки: '.		$a['orderDelivDay'].' '.$a['orderDelivMonth'].' '.$a['orderDelivTime'].'<br>
			Имя клиента: '.			$a['orderClientName'].'<br>
			Адрес доставки: '.		$a['orderClientAddress'].'<br>
			Телефон: '.				$p.'<br>
			Комментарий к заказу: '.$a['orderClientComment'].'<br>
			Номер карты клиента: '.	$a['orderClientCard'].'<br>	
			Стоимость заказа с доставкой и скидками: '.$a['orderFullPriceDiscount'].'<br>
			Способ оплаты: '.		$a['orderPayVar'].'<br>		
			';
			
			$text .= '
				<strong class="user-order-table-head">Содержание заказа</strong>
					<table border=1 class="user-order-table" >
									<tr valign=top>
										<th>№п/п</th>
										<th>Наименование</th>
										<th>Код</th>
										<th>Вес</th>
										<th>Габариты</th>
										<th>Форма</th>
										<th>Цвет <br>массива</th>
										<th>Цвет <br>декора</th>
										<th>Начинка</th>
										<th>Количество</th>
										<th>Цена</th>
									</tr>
									
						';
				$i = 1;
				foreach($a['items'] as $k => $v)
				{
					$text .= "
						<tr align=center>
							<td>".$i++."</td>
							<td>".$v['item_name']."</td>
							<td>".$v['item_code']."</td>
							<td>".$v['item_weight']." кг</td>
							<td>".$v['item_size']."</td>
							<td>".$v['item_constr']."</td>
							<td>".$v['item_color_1']."</td>
							<td>".$v['item_color_2']."</td>
							<td>".$v['item_material']."</td>
							<td>".$v['item_count']."</td>
							<td>".$v['item_price']."</td>
						</tr>
					";
				}
						
				$text .= '</table>';
			
		}
		else
			$text = $this -> order_list;	
		
		
		return $text;			
	}	
	
	// разбивка на страницы
	public function printNavBar()
	{
		?>
		<fieldset class="navBar">
			<?
			for($i = 0; $i < ceil($this -> getOrdersNum() / $this -> limit); $i++)
			{
				if($this -> pg == $i)
					$cls = 'active';
				else
					$cls = '';
					
									
				if(isset($this -> user_id))
					$client = "&client_id=".$this -> user_id;
				else
					$client = '';
					
					
				if($this -> filter_field != '1')
					$filt = "&filter_field=".$this -> filter_field."&filter_value=".$this -> filter_value;
				else
					$filt = '';
					
				?>
				<span><a class="<?=$cls?>" href="?page=orders&pg=<?=$i?><?=$client?><?=$filt?>"><?=$i+1?></a></span>	
				<?
			}
			?>	
			<div style="clear:both"></div>		
		</fieldset>
		<?
	}
	
	// фильтр по статусам заказа
	public function printStatusFilter()
	{
		if(isset($this -> user_id))
			$client = '&client_id='.$this -> user_id; 
		else
			$client = '';
		
		foreach($this -> status_f as $k => $v)
		{
			$class = '';
			
			if($this -> filter_field != '1')
				$k == $this -> filter_value ? $class = 'selected' : $class = '';
			
			if($k != 9)
				$filt = '&filter_field=order_status&filter_value='.$k;
			else
			{
				$class = 'selected';
				$filt = '';
			}
				
			?><a class="<?=$class?>" href="/cms9/workplace.php?page=orders&pg=0<?=$client?><?=$filt?>"><?=$v?></a><?
		}
	}
}

	

?>