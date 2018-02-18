// JavaScript Document
$(document).ready(function()
{
	/* установка checkbox ajax'ом */
	$('.setFlag').click(function()
	{
		var img = $(this).find('img');
		
		img.attr('src', '/cms9/icon/reload.png')
		
		$(this).load('/cms9/modules/framework/ajax.set.param.php', {
			'func'	: 'tool',
			'tbl'	: img.attr('tbl'),
			'id'	: img.attr('name'),	
			'flag' 	: img.attr('class'),
			'param'	: img.attr('param')					
		})
		return false
	})
	/* /установка checkbox ajax'ом */	
	
	/* сделаем возможным редактирование поля ввода, если оно было readonly */
	$('img.icon-edit').click(function()
	{
		$(this).prev('input').attr('disabled', false)
			
	})
	
	
	$('.setSelect').change(function()
	{
		//alert($(this).attr('value'))
		
		$(this).load('/cms9/modules/framework/ajax.set.param.php', {
			'func'	: 'toolSelect',
			'tbl'	: $(this).attr('tbl'),
			'id'	: $(this).attr('name'),	
			'value'	: $(this).attr('value'),
			'param'	: $(this).attr('param')					
		})
	})
	
	
	
	/*$('input').each(function()
 	{
	 	$(this).focus(function()
		{
	
			$(this).select()
			return false
		})
	})*/
	
	
})