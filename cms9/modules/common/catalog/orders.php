<?
/*include $_SERVER['DOCUMENT_ROOT'].'/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/db.php';*/
include $_SERVER['DOCUMENT_ROOT'].'/modules/shop/shop.class.php';

$orders = new ORDER();

$orders -> tbl = $_VARS['tbl_prefix'].'_order';
$orders -> order_by = 'id DESC';
$orders -> order_dir = '';
$orders -> limit	= 100;
$orders -> pg		= 0;

if(isset($_GET['pg']))
	$orders -> pg		= $_GET['pg'];

if(isset($_GET['del']))
{
	$orders -> id = $_GET['id'];
	
	$orders -> delOrder();
}

if(isset($_GET['client_id']))
	$orders -> user_id = $_GET['client_id'];
	
if(isset($_GET['filter_field']) && isset($_GET['filter_value']))
{
	$orders -> filter_field = $_GET['filter_field'];
	$orders -> filter_value = $_GET['filter_value'];
}

$orders -> order_by = 'order_date DESC, order_status ASC, id DESC';
$arr = $orders -> getAllOrders();

//printArray($arr);

$status = array(
	0 => 'новый',
	4 => 'оплачен',
	1 => 'выполнен'
);

$orders -> status_f = $status;
$orders -> status_f[9] = 'все';
?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/cms9/head.php";
?>


<body>
<style>
tr{vertical-align:top}
td{padding:5px 5px}
input.inputText{width:300px}
input.inputDate{width:auto}
textarea.inputText{width:300px; height:100px}

.selectSalonLink{display:block; position:relative}
.selectSalonDiv{display:block; width:200px; padding:5px; position:absolute; top:20; left:20; z-index:5; background:#fff; border:1px solid #ccc; text-align:left}

.order-block{display:none}

.delimiter{/*background:#eee;*/ font-weight:bold}
.filter{text-align:right}
.filter a{padding:0 20px}
.filter a.selected{font-weight:bold}
.status-0{background:#AEFFC9}
.status-1{/*background:#FFFFCC*/}
.status-4{background:#FFDDFF}

.navBar{padding:10px}
.navBar span{display:block; float:left; margin:5px; }
.navBar span a{padding:5px; border:1px solid #666; background:#fff; display:block; width:15px; text-align:center }
.navBar span a.active{padding:5px; /*border:1px solid #eee;*/ background:#ddd; }
</style>

<script language="javascript">
$(document).ready(function()
{
	$('.show-order').click(function()
	{	
		$(this).next('div').toggle()
		

		$('.order-block').each(function()
		{
			if($(this).css('display') == 'block')
			{
				$(this).prev('.show-order').text('Скрыть содержание заказа')
				$(this).parents('tr').css('background', '#eeeeee')
			}
			else
			{
				$(this).prev('.show-order').text('Открыть содержание заказа')
				$(this).parents('tr').css('background-color', '')
			}
			
		})
		
		return false
	})
	
	$('.setSelect').change(function()
	{
		
		$(this).parents('tr').eq(0).attr('class', 'status-'+$(this).attr('value'))
	})

})
</script>


<fieldset>
	<legend>Заказы</legend>
	
	<?
	$orders -> printNavBar();
	?>
	
	<table class="list">
		<tr>
			<td colspan="11">
			<div class="filter">
			<?
			
			$orders -> printStatusFilter();
			?>
			
			</div>
			</td>
		</tr>
		<tr>
			<th>id</th>
			<th>№ заказа</th>
			<th>дата</th>
			<!--<th>id клиента</th>-->
			<th>[id] имя клиента</th>
			<th>контакты</th>
			<th>заказ</th>
			<th>общая стоимость</th>
			<th>оплачено</th>
			<th>статус заказа</th>
			<th>редактировать</th>
			<th>удалить</th>
			
		</tr>
		<?
		$d = '';
		foreach($arr as $k => $v)
		{
			if($v['order_date'] != $d)
			{
				$d = $v['order_date'];
				?>
				<tr class="delimiter"><td colspan=11><?=$d?></td></tr>
				<?
			}
		?>
			<tr class="status-<?=$v['order_status']?>">
				<td align="center"><?=$v['id']?></td>
				<td><?=$v['order_num']?></td>
				<td><?=$v['order_date']?></td>
				<!--<td><?=$v['client_id']?></td>-->
				<td><?='['.$v['client_id'].']&nbsp;'.$v['client_name']?></td>
				<td><?=$v['client_contact']?></td>
				<td><a  class="show-order" href="">Открыть содержание заказа</a><div class="order-block"><?
					$orders -> order_list = $v['order_list'];
					echo $orders -> printOrder();
					?></div></td>
				<td align="center"><?=$v['sum_full']?></td>
				<td align="center"><?=$v['sum_payed']?></td>
				<td align="center"><?
					/*
					if($v['order_status'] == 5)
						echo 'отменен';
					else
						echo iconChkBox($_VARS['tbl_prefix'].'_order', 'order_status', $v['order_status'], $v['id']);
					*/
					?>					
					<?
					// статус заказа
					echo ajaxSelect($_VARS['tbl_prefix'].'_order', 'order_status', $v['order_status'], $v['id'], $status);
					?> 
					</td>
				
				<td align="center"><a href="javascript:if (confirm('Внимание! Все данные текущей сессии будут удалены!')){document.location='?page=order&editItem&id=<?=$v['id'];?>'}"><img src='<?=$_ICON["edit"]?>'></a></td>
				
				<td align="center"><a href="javascript:if (confirm('Удалить заказ?')){document.location='?page=orders&del<? if(isset($_GET['client_id'])) echo '&client_id='.$_GET['client_id']; ?>&id=<?=$v['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
				
			</tr>
		<?
		}
		?>
		
	</table>
	
	<?
	$orders -> printNavBar();
	?>
	
</fieldset>
</body>

</html>