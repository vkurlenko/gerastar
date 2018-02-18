// x1, y1, x2, y2 - ���������� ��� ������� �����������
// crop - ����� ��� ���������� �����������
var x1, y1, x2, y2, crop = '/upload/image/';
var jcrop_api;

//var flag = false

jQuery(function($){             

	$('#target').Jcrop({		
		onChange:   showCoords,
		onSelect:   showCoords,
		boxWidth: 1500, 
		boxHeight: 1500
	},function(){		
		jcrop_api = this;		
	});
	// ����� ���������	
    $('#release').click(function(e) {		
		release();
    });   
	// ��������� ���������
    $('#ar_lock').change(function(e) {
		//alert(this.checked)
		jcrop_api.setOptions(this.checked?
			{ aspectRatio: 4/3 }: { aspectRatio: 0 });
		jcrop_api.focus();
    });
	
	
	$('#overwrite').change(function(e) {
									
		if(this.checked)
		{
			var flag = true
		}
		else
			var flag = false			
									
		/*jcrop_api.setOptions(this.checked?
			{ aspectRatio: 4/3 }: { aspectRatio: 0 });
		jcrop_api.focus();*/
    });
	
   // ��������� �����������/������������ ������ � ������
   $('#size_lock').change(function(e) 
	{
		jcrop_api.setOptions(this.checked? {
			minSize: [ 80, 80 ],
			maxSize: [ 350, 350 ]
			//maxSize: [ $('#wmax').attr('value'),  $('#hmax').attr('value') ]
		}: {
			minSize: [ 0, 0 ],
			maxSize: [ 0, 0 ]
		});
		jcrop_api.focus();
    });
   
	// ��������� ���������
	function showCoords(c){
		x1 = c.x; $('#x1').val(c.x);		
		y1 = c.y; $('#y1').val(c.y);		
		x2 = c.x2; $('#x2').val(c.x2);		
		y2 = c.y2; $('#y2').val(c.y2);
		
		$('#w').val(c.w);
		$('#h').val(c.h);
		
		if(c.w > 0 && c.h > 0){
			$('#crop').show();
		}else{
			$('#crop').hide();
		}
		
	}	
});

function release(){
	jcrop_api.release();
	$('#crop').hide();
}

// ������� ����������� � ����� ����������
jQuery(function($){
	$('#crop').click(function(e) {
		var img = $('#target').attr('src');
		
		//alert(flag)
		//alert($('#alb').attr('value'))
		
		alb = $('#alb').attr('value');
		crop2 = '/pic_catalogue/gs_pic_'+alb+'/';
		
		
		$.post('/js/jcrop/action.php', {'x1': x1, 'x2': x2, 'y1': y1, 'y2': y2, 'img': img, 'crop': crop, 'crop2': crop2, 'alb':alb}, function(file) 
		{
			$('#cropresult').append('<img src="'+crop+file+'" class="mini">');
			release();
			
		});
		
    });   
});