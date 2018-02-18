<?
/*~~~~~~~~~~~~~~~~~~~~~~~*/
/* класс элементов формы */
/*~~~~~~~~~~~~~~~~~~~~~~~*/

function getAlbId($pic_id)
{
	global $_VARS;
	
	$sql = "SELECT * FROM `".SITE_PREFIX."_pic`
			WHERE id = ".$pic_id;
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_assoc($res);
		$alb_id = $row['alb_id'];
	}
	
	else
		$alb_id = 2;
	
	return $alb_id;
	
}
	
class FormElement
{
	public $fieldName; 		// имя поля
	public $fieldType; 		// тип элемента
	public $fieldValue;		// значение
	public $fieldClass;		// класс стиля
	public $fieldProperty; 	// массив параметров элементов формы
	public $picCatalogue;	// id каталога картинок 
	public $dis = '';
	public $dis_level = 0;



	/*~~~~~~~~~~~~~~~~~~~~~~~*/					
	/* создаем элемент формы */
	/*~~~~~~~~~~~~~~~~~~~~~~~*/						
	function createFormElem()
	{		
		switch($this -> fieldProperty["type"])
		{
			case "inputText" 		: $this -> createInputText(); 	break; // однострочное поле ввода
			case "inputDate" 		: $this -> createInputDate(); 	break; // поле ввода даты
			case "inputTime" 		: $this -> createInputTime(); 	break; // поле ввода даты
			case "inputHidden" 		: $this -> createInputHidden(); break; // скрытое поле
			case "selectTpl" 		: $this -> createSelectTpl(); 	break; // выпадающий список выбора шаблона
			case "selectPic" 		: $this -> createSelectPicScroll(); 	break; // выпадающий список выбора картинки
			case "selectPicAjax" 	: $this -> createSelectPicAjax(); 	break; //
			case "selectAlb" 		: $this -> createSelectAlb(); 	break; // выпадающий список выбора картинки
			case "selectParentId"	: $this -> createSelectParentId();break; // выпадающий список выбора родительского объекта
			case "selectParentIdCatalog": $this -> createselectParentIdCatalog();break; // выпадающий список выбора родительского объекта в каталоге
			case "selectSpec"		: $this -> createSelectSpec();	break; // выпадающий список выбора специализации мастера
			case "selectSalon"		: $this -> createSelectSalon();	break; // выпадающий список выбора салона
			case "selectObject"		: $this -> createSelectObject();break; // выпадающий список выбора объекта из таблицы БД
			case "selectObjectArr"	: $this -> createSelectObjectArray();break; // выпадающий список выбора объекта из массива
			case "selectObjectArrCheckbox"	: $this -> createSelectObjectArrayCheckbox();break; // список выбора объектов из массива в виде checkbox 
			case "selectFaqType"	: $this -> createselectFaqType();break; // выпадающий список выбора объекта
			case "textareaText" 	: $this -> createTextarea(); 	break; // многострочное поле ввода plane text
			case "textHTML" 		: $this -> createTextHtml(); 	break; // многострочное поле ввода html text
			case "inputCheckbox" 	: $this -> createCheckbox(); 	break; // checkbox
			case "inputCardNumber"	: $this -> createInputCardNumber(); 	break; // номер карты (4 группы цифр)
			case "inputPhone"		: $this -> createInputPhone(); break;
			case "selectNumber"		: $this -> createSelectNumber(); break;
			case "selectYear"		: $this -> createSelectYear(); break;
			default : break;
		}			
	}
	
	
	public function createSelectNumber()
	{
		$from = $this -> fieldProperty["param"][0];
		$to = $this -> fieldProperty["param"][1];
		$html = "
			<select name='".$this -> fieldProperty["name"]."'>";			
			for($i = $from; $i < $to + 1; $i++)
			{		
				$this -> fieldProperty["value"] == $i ? $selected = " selected='selected' " : $selected = "";
				$html .= "<option value='".$i."' ".$selected." >".$i."</option>";
			}
			$html .= "</select>";
			
			echo $html;
	}
	
	public function createSelectYear()
	{
		$from = $this -> fieldProperty["param"][0];
		$to = $this -> fieldProperty["param"][1];
		$html = "
			<select name='".$this -> fieldProperty["name"]."'>";			
			for($i = $from; $i < $to + 1; $i++)
			{		
				$this -> fieldProperty["value"] == $i ? $selected = " selected='selected' " : $selected = "";
				$html .= "<option value='".$i."' ".$selected." >".$i."</option>";
			}
			$html .= "</select>";
			
			echo $html;
	}
	
	
	public function createSelectMonthNumber()
	{
		
		$html = "
			<select name='".$this -> fieldProperty["name"]."'>";			
			for($i = 1; $i < 13; $i++)
			{		
				$this -> fieldProperty["value"] == $i ? $selected = " selected='selected' " : $selected = "";
				$html .= "<option value='".$i."' ".$selected." >".$i."</option>";
			}
			$html .= "</select>";
			
			echo $html;
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора шаблона SELECT:PARENT */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	
	// построим дерево родительских разделов
	function selectLevel($parent, $level)
	{		
		global $html, $dis, $dis_level;
		$sql = "SELECT * FROM `".$this -> fieldProperty["table"]["table_name"]."`
				WHERE ".$this -> fieldProperty["table"]["parent_field"]." = ".$parent." 
				ORDER BY ".$this -> fieldProperty["table"]["order_by"]." ASC";
				//echo $sql;
		${"res$level"} = mysql_query($sql);		
		
				
		while(${"row$level"} = mysql_fetch_array(${"res$level"}))
		{
			$space = $selected = "";		
			
			for($i = 0; $i < $level; $i++) $space .= "&nbsp;";
			
			if(@$this -> fieldProperty["mode"] == 'multiSelect')
			{
				if($this -> fieldProperty["value"] != '')
				{
					$val = unserialize($this -> fieldProperty["value"]);
					if(is_array($val) && in_array(${"row$level"}['id'], unserialize($this -> fieldProperty["value"])))
					{
						$selected = " selected='selected' ";
					}
					else $selected = "";
				}
				else $selected = "";
				
				$html .= "<option value='".${"row$level"}['id']."' ".$selected.">".$space.$space.${"row$level"}[$this -> fieldProperty["table"]["item_title"]]."</option>";			
	
			}
			else
			{
				if($this -> fieldProperty["value"] == ${"row$level"}['id'])
				{
					$selected = " selected='selected' ";
				}
				else $selected = "";
				
				// сделаем недоступными эту позицию и дочерние к ней
				if(${"row$level"}[$this -> fieldProperty["table"]["parent_field"]] == $this -> fieldProperty["thisId"]
					||
					${"row$level"}['id'] == $this -> fieldProperty["thisId"]
					) 
					{
						$dis = 'disabled';
						$dis_level = $level;
					}
				
				// доступными оставим позиции одного уровня с одинаковым родителем
				if($level < $dis_level 
					|| ($level == $dis_level 
						&& ${"row$level"}[ $this -> fieldProperty["table"]["parent_field"] ] == $this -> fieldProperty["value"]) 
						&& ${"row$level"}['id'] != $this -> fieldProperty["thisId"]) $dis = '';
				// /сделаем недоступными эту позицию и дочерние к ней
				
				$html .= "<option value='".${"row$level"}['id']."' ".$dis." ".$selected.">".$space.$space.${"row$level"}[$this -> fieldProperty["table"]["item_title"]]."</option>";			
			}
			
			$tab = $level+1;
			$this -> selectLevel(${"row$level"}['id'], $tab);			
		}
	}
	
	// построим дерево родительских разделов в каталоге
	function selectLevelCatalog($parent, $level)
	{		
		global $html, $dis, $dis_level;
		
		$sql = "SELECT * FROM `".$this -> fieldProperty["table"]["table_name"]."`
				WHERE ".$this -> fieldProperty["table"]["parent_field"]." = ".$parent." 
				AND item_show = '1'
				ORDER BY ".$this -> fieldProperty["table"]["order_by"]." ASC";

		${"res$level"} = mysql_query($sql);		
		
				
		while(${"row$level"} = mysql_fetch_array(${"res$level"}))
		{
			$space = $selected = "";		
			
			for($i = 0; $i < $level; $i++) $space .= "&nbsp;";
			
			if(@$this -> fieldProperty["mode"] == 'multiSelect')
			{
				if($this -> fieldProperty["value"] != '')
				{
					$val = unserialize($this -> fieldProperty["value"]);
					if(is_array($val) && in_array(${"row$level"}['id'], unserialize($this -> fieldProperty["value"])))
					{
						$selected = " selected='selected' ";
					}
					else $selected = "";
				}
				else $selected = "";
				
				$html .= "<option value='".${"row$level"}['id']."' ".$selected.">".$space.$space.${"row$level"}[$this -> fieldProperty["table"]["item_title"]]."</option>";			
	
			}
			else
			{
				if($this -> fieldProperty["value"] == ${"row$level"}['id'])
				{
					$selected = " selected='selected' ";				
				}
				else $selected = "";
				
				// сделаем недоступными эту позицию и дочерние к ней
				if(${"row$level"}[$this -> fieldProperty["table"]["parent_field"]] == $this -> fieldProperty["thisId"]
					||
					${"row$level"}['id'] == $this -> fieldProperty["thisId"]
					) 
					{
						$dis = 'disabled';
						$dis_level = $level;
					}
				
				// доступными оставим позиции одного уровня с одинаковым родителем
				if($level < $dis_level 
					|| ($level == $dis_level 
						&& ${"row$level"}[ $this -> fieldProperty["table"]["parent_field"] ] == $this -> fieldProperty["value"]) 
						&& ${"row$level"}['id'] != $this -> fieldProperty["thisId"]) $dis = '';
				// /сделаем недоступными эту позицию и дочерние к ней
				
				$html .= "<option value='".${"row$level"}['id']."' ".$dis." ".$selected.">".$space.$space.${"row$level"}[$this -> fieldProperty["table"]["item_title"]]."</option>";			
			}
			
			$tab = $level+1;
			$this -> selectLevelCatalog(${"row$level"}['id'], $tab);			
		}
	}
	
	
	
	function createSelectParentId()
	{
		global $_VARS, $html;			
		
		if(@$this -> fieldProperty["mode"] == 'multiSelect')
		{
			$html = "
			<select name='".$this -> fieldProperty["name"]."[]' multiple size=10>";
		}
		else
		{
			$html = "
			<select name='".$this -> fieldProperty["name"]."' size=10>";
		}
		
		$html .= "<option value='0' selected>---</option>";			
		$this -> selectLevel(0, 0);				
		$html .= "</select>";	
			
		echo $html;		
	}
	
	function createSelectParentIdCatalog()
	{
		global $_VARS, $html;			
		
		if(@$this -> fieldProperty["mode"] == 'multiSelect')
		{
			$html = "
			<select name='".$this -> fieldProperty["name"]."[]' multiple size=10>";
		}
		else
		{
			$html = "
			<select name='".$this -> fieldProperty["name"]."' size=10>";
		}
		
		$html .= "<option value='0'>---</option>";			
		$this -> selectLevelCatalog(0, 0);				
		$html .= "</select>";	
			
		echo $html;		
	}
	
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора специализации мастера SELECT:SPEC */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectSpec()
	{
		global $_VARS;
		
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_masters_spec`
				WHERE 1
				ORDER BY spec_order ASC";
		$res = mysql_query($sql);
		
		$_VARS['master_spec'] = array();
		while($row = mysql_fetch_array($res))
		{
			$_VARS['master_spec'][] = array($row['spec_label'], $row['spec_name']);
		}
		
		$html = "
		<select name='".$this -> fieldProperty["name"]."'>";	
			
			foreach($_VARS['master_spec'] as $k)
			{
				if($this -> fieldProperty["value"] == $k[0])
				{
					$selected = " selected='selected' ";
				}
				else $selected = "";
				
				$html .= "<option value='".$k[0]."' ".$selected." >".$k[1]."</option>";
			}				
			
		$html .= "</select>";		
		echo $html;
	}
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора салона SELECT:SALON */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectSalon()
	{
		global $_VARS;
		
		$sql = "SELECT * FROM `".$_VARS['tbl_pages_name']."` 
				WHERE p_parent_id = ".$this -> fieldProperty["p_parent_id"];
		$res = mysql_query($sql);
		
		if(@$this -> fieldProperty["mode"] == 'multiSelect')
		{
			
			$html = "
			<select name='".$this -> fieldProperty["name"]."[]' multiple size='".mysql_num_rows($res)."'>	
				";
				while($row = mysql_fetch_array($res))
				{		
					$val = unserialize($this -> fieldProperty["value"]);
					if(is_array($val) && in_array($row['p_url'], unserialize($this -> fieldProperty["value"])))
					{
						$selected = " selected='selected' ";
					}
					else $selected = "";			  	
					$html .= "
					<option value='".$row['p_url']."' ".$selected." >".$row['p_title']."</option>";
				}		
		}
		else
		{
			$html = "
			<select name='".$this -> fieldProperty["name"]."'>";
			while($row = mysql_fetch_array($res))
			{		
				if($this -> fieldProperty["value"] == $row['p_url'])
				{
					$selected = " selected='selected' ";
				}
				else $selected = "";			  	
				$html .= "
				<option value='".$row['p_url']."' ".$selected." >".$row['p_title']."</option>";
			}		
		}				  
		
		$html .= "</select>";		
		echo $html;
	}
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора шаблона SELECT:TPL */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectTpl()
	{
		global $_VARS;
		
		$html = "
		<select name='".$this -> fieldProperty["name"]."'>
			<option value='0'>Без шаблона</option>";			
			$sql = "SELECT * FROM `".$_VARS['tbl_template_name']."` 
					WHERE 1";
			$res = mysql_query($sql);
			  
			while($row = mysql_fetch_array($res))
			{		
				if($this -> fieldProperty["value"] == $row['tpl_marker'])
				{
					$selected = " selected='selected' ";
				}
				else $selected = "";			  	
				$html .= "
				<option value='".$row['tpl_marker']."' ".$selected." >".$row['tpl_name']."</option>";
			}
		$html .= "</select>";		
		echo $html;
	}
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора объекта SELECT:OBJECT:DB */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectObject()
	{
		global $_VARS;
		
		
		
		
		
		if(@$this -> fieldProperty["mode"] == 'multiSelect')
		{
			$sql = "SELECT * FROM `".$this -> fieldProperty["table"]."` 
					WHERE 1
					ORDER BY ".$this -> fieldProperty["order"]." ".$this -> fieldProperty["order_dir"];
			
			$res = mysql_query($sql);
		
			$html = "
			<select name='".$this -> fieldProperty["name"]."[]' multiple size='".(mysql_num_rows($res) + 1)."'>
				<option value='0'>Без привязки</option>";				
				while($row = mysql_fetch_array($res))
				{		
					//$val = unserialize($this -> fieldProperty["value"]);
					if(trim($this -> fieldProperty["value"]) != '' 
						&& is_array(@unserialize($this -> fieldProperty["value"])) 
						&& in_array($row['id'], unserialize($this -> fieldProperty["value"])))
						{
							$selected = " selected='selected' ";
						}
						
					elseif(trim($this -> fieldProperty["value"]) != '' && $this -> fieldProperty["value"] == 'selectAll')
						$selected = " selected='selected' ";
					else 
						$selected = "";		
						
					$str = '';					
					if(is_array($this -> fieldProperty["field"]))
					{
						$a = $this -> fieldProperty["field"];
						foreach($a as $k => $v)
						{
							if($k == 0)
								$delimiter = $v;
							else
								$str .= $row[$v].$delimiter;							
						}						
					}
					else
						$str = $row[$this -> fieldProperty["field"]];
						
							  	
					$html .= "
					<option value='".$row['id']."' ".$selected." >".$str."</option>";
				}	
				
			$html .= "</select>";	
		}
		else
		{
			$html = "
			<select name='".$this -> fieldProperty["name"]."'>
				<option value='0'>Без привязки</option>";			
				$sql = "SELECT * FROM `".$this -> fieldProperty["table"]."` 
						WHERE ";
				if(isset($this -> fieldProperty["where"])) $sql .= $this -> fieldProperty["where"];
				else $sql .= "1";
						
				$sql .= " ORDER BY ".$this -> fieldProperty["order"]." ".$this -> fieldProperty["order_dir"];
				//echo $sql;
				$res = mysql_query($sql);
				  
				while($row = mysql_fetch_array($res))
				{		
					
					if(isset($this -> fieldProperty["save"])) $value = $row[$this -> fieldProperty["save"]];		
					else $value = $row['id'];	
				
					if($this -> fieldProperty["value"] == $value)
					{
						$selected = " selected='selected' ";
					}
					else $selected = "";
					
					$str = '';
					
					if(is_array($this -> fieldProperty["field"]))
					{
						$a = $this -> fieldProperty["field"];
						foreach($a as $k => $v)
						{
							if($k == 0)
								$delimiter = $v;
							else
								$str .= $row[$v].$delimiter;							
						}						
					}
					else
						$str = $row[$this -> fieldProperty["field"]];
					  	
					$html .= "
					<option value='".$value."' ".$selected." >".$str."</option>";
				}
			$html .= "</select>";	
		}
		
			
		echo $html;
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора объекта SELECT:OBJECT:ARRAY */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectObjectArray()
	{
		global $_VARS;
		
		$size = count($this -> fieldProperty["arrData"]) + 1;
		if($size > 10) 
			$size = 10;
		
		if(@$this -> fieldProperty["mode"] == 'multiSelect')
		{			
			$html = "
			<select name='".$this -> fieldProperty["name"]."[]' multiple size='".$size."'>";
		}
		else
		{
			$html = "
			<select name='".$this -> fieldProperty["name"]."'>";
		}
		
							
			
		foreach($this -> fieldProperty["arrData"] as $k => $v)
		{		
			$val = $this -> fieldProperty["value"];
			if(trim($val) != '' && $k == $val)
			{
				$selected = " selected='selected' ";
			}
			elseif(trim($val) != '' && is_array(@unserialize($val)) && in_array($k, @unserialize($val)))
			{
				$selected = " selected='selected' ";
			}	
			else 
				$selected = "";			  	
			
			$html .= "
			<option value='".$k."' ".$selected." >".$v."</option>";
		}	
			
		$html .= "</select>";	
		
			
		echo $html;
	}
	
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора объекта SELECT:OBJECT:ARRAY */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectObjectArrayCheckbox()
	{
		global $_VARS;
		
				
		$html = '<div style="float:left; padding-right:20px">';	
		$i = 0;
		foreach($this -> fieldProperty["arrData"] as $k => $v)
		{		
			$val = $this -> fieldProperty["value"];
			
			if(trim($val) != '' && $k == $val)
			{
				$selected = " checked ";
			}
			elseif(trim($val) != '' && is_array(@unserialize($val)) && in_array($k, @unserialize($val)))
			{
				$selected = " checked ";
			}	
			else 
				$selected = "";			  	
			
			$label = $this -> fieldProperty["name"].$k;
			
			$html .= "
			<input type='checkbox' name='".$this -> fieldProperty["name"]."[]' id='".$label."' value = '".$k."' ".$selected." ><label for='".$label."'>".$v."</label><br>";
			$i++;
			
			if($i > 5)
			{
				$html .= '</div><div style="float:left;  padding-right:20px">';
				$i = 0;
			}		
			
		}	
			
		$html .= '</div>';
		
			
		echo $html;
	}
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора элемента массива SELECT:FAQTYPE */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectFaqType()
	{
		global $_VARS;
		
		$html = "
		<select name='".$this -> fieldProperty["name"]."'>";
			
			 foreach($this -> fieldProperty["arrData"] as $k => $v)
			 {
				if($this -> fieldProperty["value"] == $k)
				{
					$selected = " selected='selected' ";
				}
				else $selected = "";
			 	$html .= "
				<option value='".$k."' ".$selected." >".$v."</option>";
			 }  
			
		$html .= "</select>";		
		echo $html;
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* элемент поля выбора шаблона SELECT:ALB */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	function createSelectAlb()
	{
		global $_VARS;
		
		$html = "
		<select name='".$this -> fieldProperty["name"]."'>
			<option value='0'>Без альбома</option>";			
			$sql = "SELECT * FROM `".$_VARS['tbl_photo_alb_name']."` 
					WHERE 1";
			$res = mysql_query($sql);
			  
			while($row = mysql_fetch_array($res))
			{		
				if($this -> fieldProperty["value"] == $row['id'])
				{
					$selected = " selected='selected' ";
				}
				else $selected = "";			  	
				$html .= "
				<option value='".$row['id']."' ".$selected." >".$row['alb_title']."</option>";
			}
		$html .= "</select>";	
		
		echo $html;
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	/* элемент поля выбора картинки SELECT:IMAGE */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/		
	function createSelectPic()
	{
		global $_VARS;
		if(!isset($this -> picCatalogue)) $this -> picCatalogue = $this -> fieldProperty["alb"];
		// покажем картинку
		if($this -> fieldProperty["value"] != "")
		{
			$pic_width 	= 100;	// заданная ширина итогового изображения
			$pic_height = 50;	// заданная высота итогового изображения
			
			$img_alb_id	= $this -> picCatalogue;		// id альбома в БД
			$img_id	= $this -> fieldProperty["value"];	// id изображения в БД	
			$pic_align 	= "right";						// способ выравнивания тега <IMG>
			$pic_transform = "resize";
			if($this -> fieldProperty["value"] > 0)
			{
				include $_SERVER['DOC_ROOT']."/modules/img/image.inc.php";	
			}					
		}
		
		
		$sql = "SELECT `alb_title` FROM `".$_VARS['tbl_photo_alb_name']."`
				WHERE alb_name = ".$this -> picCatalogue;
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		
		$html = "<p>Изображение из альбома <a target='_blank' href='/cms9/workplace.php?page=photo&zhanr=".$this -> picCatalogue."'>".$row['alb_title']."</a></p>";

		$html .= "
		<select name='".$this -> fieldProperty["name"]."'>";					
				
			$sql = "SELECT * FROM `".$_VARS['tbl_photo_name'].$this -> picCatalogue."` 
					ORDER BY `id` DESC";
			$res = mysql_query($sql);
			
			if(mysql_num_rows($res) == 0)  $html .=  "<option value='0' selected>Без картинки</option>";
			else $html .=  "<option value='0'>Без картинки</option>";
			  
			while($row = mysql_fetch_array($res))
			{		
				if($this -> fieldProperty["value"] == $row['id'])
				{
					$selected = " selected='selected' ";
				}
				else $selected = "";			  	
				
				$html .= "
				<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
			}
			
		$html .= "
		</select>";	
		
		
		
		echo $html;
	}
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	/* элемент выбора картинки SELECT:IMAGE:GALLERY */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/		
	function createSelectPicScroll()
	{
		global $_VARS;
		
		// скрытое поле, в котором будет передаваться id выбранной картинки
		$html = "<input id='imgId' type='hidden' name='".$this -> fieldProperty["name"]."' value='".$this -> fieldProperty["value"]."'  />";
		
		// читаем картинки из каталога
		if(!isset($this -> picCatalogue)) $this -> picCatalogue = $this -> fieldProperty["alb"];		
		
		$sql = "SELECT `alb_title` FROM `".$_VARS['tbl_photo_alb_name']."`
				WHERE alb_name = ".$this -> picCatalogue;
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		
		$html .= "<fieldset class='gallery'><legend>Изображение из альбома <a target='_blank' href='/cms9/workplace.php?page=photo&zhanr=".$this -> picCatalogue."'>".$row['alb_title']."</a></legend>";

		// строим scroll-галерею картинок
		$html .= "<div id='gallery'>
					<a class='prev browse left'></a>	
						<div class='scrollable'>   
							<div class='items'>";
		
		$sql = "SELECT * FROM `".$_VARS['tbl_photo_name'].$this -> picCatalogue."` 
				ORDER BY `order_by` ASC";
		$res = mysql_query($sql);
		
		$html .= "<div class='item'>";
		$html .= "<a rel='fancy' href='' title='0' class='view'><img src='/cms9/img/no_img.png'></a>";	
			
		$i = 1;
		$j = 0;
		$index = 0;
		while($row = mysql_fetch_array($res))
		{
			
			$img = new Image();
			$img -> imgCatalogId 	= $this -> picCatalogue;
			$img -> imgId 			= $row['id'];
			$img -> imgAlt 			= "";
			$img -> imgWidthMax 	= 50;
			$img -> imgHeightMax 	= 50;	
			$img -> imgTransform	= "crop";
			$img_html = $img -> showPic();
			
			$cls = "";
			
			if($this -> fieldProperty["value"] == $row['id']) 
			{
				$cls = "selected";
				$index = $j;
			}
			
			$html .= "<a rel='fancy' href='' title='".$row['id']."' class='view ".$cls."'>".$img_html."</a>";
			$i++;
			$j++;
			if($i > 3)
			{
				$html .= "</div>
						<div class='item'>";
				$i = 0;
			}
			
			
			
		}
		
		$html .= "</div></div>
			</div>
			<a class='next browse right'></a>
			<div style='clear:left'></div>
		</div>
		</fieldset>
		
		<script language='javascript'>
			$(document).ready(function()
			{
				var api = $('.scrollable').data('scrollable');
				api.seekTo(".floor($index/4).", 1000);
			})
		</script>		
		";		
		echo $html;
		//echo floor($index / 4);
	}
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	/* элемент поля выбора картинки SELECT:IMAGE:AJAX */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/		
	function createSelectPicAjax()
	{
		global $_VARS;		
		?>
		<style>
		.picDiv{position:relative}		
		.picList{border:3px solid #CCCCCC; padding:5px; display:none; position: absolute; top:0; left:60px; background: #fff; -webkit-border-radius: 8px;-moz-border-radius: 8px; border-radius: 8px; z-index: 100; padding: 20px;}
		.selectPic{cursor:pointer}
		</style>
		
		<script language="javascript">
		$(document).ready(function()
		{
			$('.selectPic-<?=$this -> fieldProperty["name"]?>').click(function()
			{
			
				$('.picList').hide()
				
				$(this).parent('div').eq(0).find('.picList').load('http://<?=$_SERVER['HTTP_HOST']?>/cms9/modules/common/img_man/ajax.image.selector.php',
				{
					'alb_id' 	: <?=getAlbId($this -> fieldProperty["value"])?>,
					'field_id'	: '<?=$this -> fieldProperty["name"]?>',
					'pic_id'	: $('#<?=$this -> fieldProperty["name"]?>').attr('value')
					
				}).show()
				
				return false
			})
		})
		
		</script>
		
	
		<div class="picDiv" id="picDiv-<?=$this -> fieldProperty["name"]?>">
			<?
			// покажем картинку
			if($this -> fieldProperty["value"] != "")
			{
				if(@isset($this -> fieldProperty["param"][0]))
					$w = $this -> fieldProperty["param"][0];
				else
					$w = 70;
					
				if(@isset($this -> fieldProperty["param"][1]))
					$h = $this -> fieldProperty["param"][1];
				else
					$h = 70;
				
				$img = new Image();
				$img -> imgCatalogId 	= getAlbId($this -> fieldProperty["value"]);
				$img -> imgId 			= $this -> fieldProperty["value"];
				$img -> imgAlt 			= "";
				$img -> imgWidthMax 	= $w;
				$img -> imgHeightMax 	= $h;	
				$img -> imgTransform	= "crop";
				$img -> imgClass		= "selectPic";
				$img_html = $img -> showPic();
				
				echo '<a class="selectPic-'.$this -> fieldProperty["name"].'" href="#">'.$img_html.'</a>';
			}
			else
				echo '<a class="selectPic-'.$this -> fieldProperty["name"].'" href="#">Выбрать картинку</a>';
			?>
			
			
			<input id='<?=$this -> fieldProperty["name"]?>' type='hidden' name='<?=$this -> fieldProperty["name"]?>' value='<?=$this -> fieldProperty["value"]?>'  />
			
			<div class="picList" id="picList-<?=$this -> fieldProperty["name"]?>"></div>
			
		</div>


<?

	}

	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/		
	/* элемент поля ввода текста INPUT:TEXT */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/			
	function createInputText()
	{
		global $_ICON;
	
		$readonly = '';
		$html = '<div class="div-input-text">';
		if(isset($this -> fieldProperty["readonly"]) && $this -> fieldProperty["readonly"] == true)
			$readonly = 'disabled';
		

		$html .= "<input type='text' class='".$this -> fieldProperty["class"]."' name='".$this -> fieldProperty["name"]."' value='".$this -> fieldProperty["value"]."' ".$readonly." />";
		if($readonly == 'disabled')
			$html .= '<img title="Редактировать" alt="Редактировать" class="icon-edit" src="'.$_ICON['edit'].'">';
		
		$html .= '</div>';
		if(isset($this -> fieldProperty["note"]))
			$html .= $this -> comment();
			
		
		
		echo $html;
	}
	
	
	
	function comment()
	{
		global $_ICON;
		
		$commentHtml = '<span class="comment"><img  src="'.$_ICON[$this -> fieldProperty["note"][0]].'">'.$this -> fieldProperty["note"][1].'</span>';
		return $commentHtml;
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/		
	/* элемент поля ввода текста TEXT:HTML  */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	
	
	function createInputCardNumber()
	{
		global $_ICON;
	
		$html = '';
		
		if(!isset($this -> fieldProperty["value"]) || trim($this -> fieldProperty["value"]) == '')
			$this -> fieldProperty["value"] = strval('0000000000000000');
			
		$arr = str_split($this -> fieldProperty["value"], 4);
		//printArray($arr);
		for($i = 0; $i < 4; $i++)
		{
			$html .= "<input type='text' class='".$this -> fieldProperty["class"]."' name='".$this -> fieldProperty["name"]."[$i]' value='".strval($arr[$i])."' size=2 maxlength=4 />";
		}
		
		
		if(isset($this -> fieldProperty["note"]))
			$html .= $this -> comment();
			
		
		
		echo $html;
	}
	
	
	
	function createInputPhone()
	{
		global $_ICON;
	
		$html = '';
		
		if(!isset($this -> fieldProperty["value"]) || trim($this -> fieldProperty["value"]) == '')
			$this -> fieldProperty["value"] = strval('');
			
		$arr = array(
			'code' 	=> substr($this -> fieldProperty["value"], 0, 3),
			'num'	=> substr($this -> fieldProperty["value"], 3)
		);
		//printArray($arr);
		foreach($arr as $k => $v)
		{
			switch($k)
			{
				case 'code' : $maxlength = 3; $size = 3; break;
				default : $maxlength = 7; $size = 7; break;
			}
			$html .= "<input type='text' class='".$this -> fieldProperty["class"]."' name='".$this -> fieldProperty["name"]."[$k]' value='".$v."' size=$size maxlength=$maxlength />";
		}
		
		
		if(isset($this -> fieldProperty["note"]))
			$html .= $this -> comment();
			
		
		
		echo $html;
	}
	
			
	function createTextHtml()
	{
		global $_VARS;
		/*$editor_text_edit = $this -> fieldProperty["value"];
		$editor_text_name = $this -> fieldProperty["name"];
		$editor_height = 400;
		$editor_width = 700;
		
		
		include $_SERVER['DOC_ROOT']."/fckeditor/fckeditor.php";

		$text = "";
		if(isset($editor_text_edit))
		{
			//$text = eregi_replace("<p>[[:space:]]*</p>","<p>&nbsp;</p>", $editor_text_edit);
			//$text = preg_replace("<p>[:space:]*</p>","<p>&nbsp;</p>", $editor_text_edit);
			$text = $editor_text_edit;
		}
		$oFCKeditor	-> BasePath = '/fckeditor/editor/';
		$sBasePath 	= '/fckeditor/';
		$oFCKeditor = new FCKeditor($editor_text_name);
		$oFCKeditor	-> BasePath	= $sBasePath ;
		if(isset($editor_height))
		{
			$oFCKeditor -> Height = $editor_height;
		}
		
		if(isset($editor_width))
		{
			$oFCKeditor -> Width = $editor_width;
		}
		$oFCKeditor	-> Value	= $text;
		$oFCKeditor	-> Create();	*/
		
		
		?>
		<!--<textarea name="p_content"><?=$this -> fieldProperty["value"]?></textarea>
	
		<script language="javascript">
			cke('<?=$this -> fieldProperty["name"]?>')		
		</script>
		</fieldset>-->
		<?
		$editor_text_edit = $this -> fieldProperty["value"];
		$editor_text_name = $this -> fieldProperty["name"];
		$text = "";
		if(isset($editor_text_edit))
		{
			//$text = eregi_replace("<p>[[:space:]]*</p>","<p>&nbsp;</p>", $editor_text_edit);
			$text = $editor_text_edit;
		}
		
		
		?>
		<textarea name="<?=$this -> fieldProperty["name"]?>"><?=$text?></textarea>
		<script src="/ckeditor/ckeditor.js"></script>
		<script language="javascript">
			var cfg = {
				filebrowserBrowseUrl 		: '/ckeditor/kcfinder-2.51/browse.php?type=file'	,
				filebrowserImageBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=image',
				filebrowserFlashBrowseUrl 	: '/ckeditor/kcfinder-2.51/browse.php?type=flash',
				
				filebrowserUploadUrl 		: '/ckeditor/kcfinder-2.51/upload.php?type=file'	,
				filebrowserImageUploadUrl 	: '/ckeditor/kcfinder-2.51/upload.php?type=image',
				filebrowserFlashUploadUrl 	: '/ckeditor/kcfinder-2.51/upload.php?type=flash'
			}
			
			function cke(obj)
			{
				CKEDITOR.replace(obj,cfg);
			}
			cke('<?=$this -> fieldProperty["name"]?>')		
		</script>
		<?
		
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/		
	/* элемент поля ввода текста INPUT:DATE */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/			
	function createInputDate()
	{
		$html = "<input type='text' class='datepicker' size='10' class='".$this -> fieldProperty["class"]."' name='".$this -> fieldProperty["name"]."' value='".$this -> fieldProperty["value"]."' />";
		
		echo $html;
	}
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/		
	/* элемент поля ввода текста INPUT:TIME */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/			
	function createInputTime()
	{
		//$html = "<input type='text' id='datepicker' size='10' class='".$this -> fieldProperty["class"]."' name='".$this -> fieldProperty["name"]."' value='".$this -> fieldProperty["value"]."' />";
		$html = "<input type='text' id='timepicker' name='".$this -> fieldProperty["name"]."' value='".$this -> fieldProperty["value"]."' size='10' />";
		echo $html;
	}

	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/			
	/* элемент поля ввода текста INPUT:HIDDEN */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/				
	function createInputHidden()
	{
		$html = "<input type='hidden' name='".$this -> fieldProperty["name"]."' value='".$this -> fieldProperty["value"]."' />";
		echo $html;
	}

	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/				
	/* элемент поля ввода текста TEXTAREA */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/					
	function createTextarea()
	{
		$html = "<textarea name='".$this -> fieldProperty["name"]."' class='".$this -> fieldProperty["class"]."'>".$this -> fieldProperty["value"]."</textarea>";
		if(isset($this -> fieldProperty["note"]))
			$html .= $this -> comment();
			
		echo $html;
	}

	/*~~~~~~~~~~~~~~~~~~*/					
	/* элемент CHECKBOX */
	/*~~~~~~~~~~~~~~~~~~*/						
	function createCheckbox()
	{
		if($this -> fieldProperty["value"] == true) $checked = "checked";
		else $checked = "";
		$html = "<input type='checkbox' name='".$this -> fieldProperty["name"]."' ".$checked." />";
		echo $html;
	}	
}


?>