<?
//print_r($_POST);
//echo $_POST['alb_id'];
?>


<script  src="/js/jcrop/js/crop.js"></script>


<img src="<?=$_POST['src']?>" id="target" alt="[Jcrop Example]" />

<br>

<div class="optlist offset">
	<!--<label><input type="radio" name="ar_lock" value='1x1' />Соблюдать пропорции (1:1)</label>
	<label><input type="radio" name="ar_lock" value='4x3' />Соблюдать пропорции (4:3)</label>-->
	<!--<label><input type="checkbox" id="size_lock" />min/max размер (80x80/350x350)</label>-->
	<!--<label><input type="checkbox" id="overwrite" />Заменить исходное изображение</label>-->
</div>
<div  class="inline-labels">
   <label style="display:none">X1 <input type="text" size="4" id="x1" name="x1" /></label>
   <label style="display:none">Y1 <input type="text" size="4" id="y1" name="y1" /></label>
   <label style="display:none">X2 <input type="text" size="4" id="x2" name="x2" /></label>
   <label style="display:none">Y2 <input type="text" size="4" id="y2" name="y2" /></label>
   <label>W <input type="text" size="4" id="w" name="w" /></label>
   <label>H <input type="text" size="4" id="h" name="h" /></label>
   
   <input type="hidden" size="4" id="alb" name="alb" value="<?=$_POST['alb_id']?>" />
   <button id="release">Отмена</button>
	<button id="crop">Обрезать</button>
   
   <!--<label>Wmax <input type="text" size="4" id="wmax" name="wmax" value="" /></label>
   <label>Hmax <input type="text" size="4" id="hmax" name="hmax" value="" /></label>-->
</div>
<p>Результаты:</p>
<div  id="cropresult"></div>