<?
include_once $_SERVER['DOCUMENT_ROOT'].'/blocks/class.mag.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/cms9/modules/common/magazine/class.tpl.php';

$mag = new MAGAZINE();

$mag -> tbl_mag = $_VARS['tbl_prefix']."_mag";
$mag -> tbl_mag_pages = $_VARS['tbl_prefix']."_mag_page";

if(isset($_GET['param2']))
	$mag -> id = intval($_GET['param2']);
else
	$mag -> id = 0;
	
$mag -> cover_w = 830;
$mag -> cover_h = 1080;

$mag_info = $mag -> getMagInfo();	

$arr = $mag -> getMagPages();

$num = count($arr); // кол-во страниц в журнале

?>

<!--<div id="cover">
	<?=$mag_info['cover']?>	
</div>-->

<?
if(!empty($arr))
{
	foreach($arr as $k => $v)
	{
		?>
		<div style="height:100%">
			<?=$v?>				
		</div>
		<?
	}
}
else
{
	?>
	<div id="cover">
		<?=$mag_info['cover']?>	
	</div>
	<?
}
?>