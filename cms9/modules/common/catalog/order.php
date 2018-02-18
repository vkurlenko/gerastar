<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/modules/shop/shop.class.php';

/*

удаляем текущую сессию 

по id считывает данные заказа и пишем их в новую сессию
Array
(
    [lang] => ru
    [user_card] => 1
    [cms_user_login] => victor
    [cms_user_pwd] => kvv
    [cms_user_group] => admin
    [cms_user_access] => 1
    [user_access] => 1
    [user_id] => 39
    [orderDelivVar] => client
    [orderTestVar] => net
    [item] => Array
        (
            [23] => Array
                (
                    [item_count] => 1
                    [item_constr] => circle
                    [item_color_1] => choko
                    [item_color_2] => white
                    [item_material] => 2
                    [item_size] => 1
                    [item_weight] => 3
                    [item_price_one] => 9300
                    [item_price_all] => 9300
                    [item_in_basket] => 1
                )

            [7] => Array
                (
                    [item_count] => 4
                    [item_constr] => circle
                    [item_color_1] => white
                    [item_color_2] => white
                    [item_material] => 1
                    [item_size] => 3
                    [item_weight] => 1
                    [item_price_one] => 223
                    [item_price_all] => 892
                    [item_in_basket] => 1
                )

        )

)


Array
(
    [id] => 71
    [order_num] => CB-20140620-0000096
    [client_id] => 39
    [client_name] => Иванов Иван Иванович
    [client_contact] => ivan@ivan.ru

(903) 1234567
    [order_list] => 
			[orderDelivVar] => курьер
			[orderDelivRange] => в пределах Третьего кольца
			[orderTestVar] => нет
			[orderDelivDay] => 4
			[orderDelivMonth] => мая
			[orderDelivTime] => 17:00
			[orderClientName] => Иванов Иван Иванович
			[orderClientAddress] => москва
			[orderClientMail] => ivan@ivan.ru
			[orderClientPhoneNum] => 9031234567
			[orderClientComment] => 
			[orderClientCard] => 1234 5678 1234 5678
			[orderFullPriceDiscount] => 19125
			[orderPayVar] => Наличными курьеру
			[items] => Array
				(
					[1] => Array
						(
							[item_name] => Rehau
							[item_code] => 0
							[item_weight] => 3
							[item_size] => 2 яруса (H по 20 см, D 30/20 см)
							[item_constr] => круглая
							[item_color_1] => белый
							[item_color_2] => белый
							[item_material] => малиновое варенье
							[item_count] => 2
							[item_price] => 9500
						)
		
					[2] => Array
						(
							[item_name] => Рыбки
							[item_code] => 0
							[item_weight] => 1
							[item_size] => 1 ярус (H 20 см, D 30 см)
							[item_constr] => круглая
							[item_color_1] => белый
							[item_color_2] => белый
							[item_material] => малиновое варенье
							[item_count] => 1
							[item_price] => 3500
						)
		
				)
 [sum_full] => 19125
    [sum_payed] => 0
    [order_status] => 0
    [order_date] => 2014-06-20 18:32:01
)

*/

class DATA
{
	public $tbl;
	public $k;
	public $value;
	public $field;
	public $result;
	
	
	public function getResult()
	{
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE ".$this -> field." = '".$this -> value."'";
		//echo $sql;		
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_assoc($res);
			
			$this -> result = $row[$this -> k];
		}
		
		return $this -> result;
	}
}



$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_order`
		WHERE id = ".$_GET['id'];
		
//echo $sql;
$res = mysql_query($sql);

if($res && mysql_num_rows($res) > 0)
{
	$row = mysql_fetch_assoc($res);
	
	//printArray(unserialize($row['order_list']));
	$detail = unserialize($row['order_list']);
	
	$_SESSION['order_mode']			= 'edit';		// укажем, что режим редактирования заказа
	$_SESSION['order_id']			= $row['id'];	// номер заказа
	$_SESSION['user_access'] 		= 1;
	$_SESSION['user_id'] 			= $row['client_id'];
	$_SESSION['orderClientName']	= $detail['orderClientName'];
	$_SESSION['orderClientAddress']	= $detail['orderClientAddress'];
	$_SESSION['orderClientMail']	= $detail['orderClientMail'];
	$_SESSION['orderClientPhoneNum']= $detail['orderClientPhoneNum'];
	$_SESSION['orderClientComment']	= $detail['orderClientComment'];
	$_SESSION['orderDelivDay']		= $detail['orderDelivDay'];
	$_SESSION['orderDelivMonth']	= $detail['orderDelivMonth'];
	$_SESSION['orderDelivTime']		= $detail['orderDelivTime'];
	$_SESSION['orderClientCard']	= $detail['orderClientCard'];
	$_SESSION['orderPayVar'] 		= $detail['orderPayVar'];
	
	$d = new DATA;
	
	// вариант доставки
	$d -> tbl 	= $_VARS['tbl_prefix']."_deliv_variant";
	$d -> field = 'elem_value';
	$d -> k 	= 'elem_key';
	$d -> value = $detail['orderDelivVar'];	
	$_SESSION['orderDelivVar'] 	= $d -> getResult();
	
	// рассояние доставки
	$d -> tbl 	= $_VARS['tbl_prefix']."_deliv_range";
	$d -> field = 'elem_value';
	$d -> k 	= 'elem_key';
	$d -> value = $detail['orderDelivRange'];
	$_SESSION['orderDelivRange'] = $d -> getResult();
	
	
	// способ дегустации
	$d -> tbl 	= $_VARS['tbl_prefix']."_test_variant";
	$d -> field = 'elem_value';
	$d -> k 	= 'elem_key';
	$d -> value = $detail['orderTestVar'];
	$_SESSION['orderTestVar'] 	= $d -> getResult();
	
	
	
	
	$_SESSION['item'] 			= array();
	
	foreach($detail['items'] as $k => $v)
	{
		
		$arr = array(
			'item_constr',
			'item_color_1',
			'item_color_2'				
			);
			
		foreach($arr as $a)	
		{
			$d -> tbl 	= $_VARS['tbl_prefix']."_".$a;
			$d -> field = 'elem_value';
			$d -> k 	= 'elem_key';
			$d -> value = $v[$a];
			$v[$a] = $d -> getResult();
		}
		
		$d -> tbl 	= $_VARS['tbl_prefix']."_catalog_material";
		$d -> field = 'mat_name';
		$d -> k 	= 'id';
		$d -> value = $v['item_material'];
		$v['item_material'] = $d -> getResult();
		
		$d -> tbl 	= $_VARS['tbl_prefix']."_catalog_size";
		$d -> field = 'size_name';
		$d -> k 	= 'id';
		$d -> value = $v['item_size'];
		$v['item_size'] = $d -> getResult();
	
	
		$_SESSION['item'][$v['item_id']] = array(
			'item_count' 	=> $v['item_count'],
			'item_constr' 	=> $v['item_constr'],
			'item_color_1' 	=> $v['item_color_1'],
			'item_color_2' 	=> $v['item_color_2'],
			'item_material' => $v['item_material'],
			'item_size' 	=> $v['item_size'],
			'item_weight' 	=> $v['item_weight'],
			'item_price_one' => $v['item_price'],
			'item_price_all' => $v['item_price'] * $v['item_count'],
			'item_in_basket' => 1		
		);
	}
	
		
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/order/');
}
else
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/order/');



?>