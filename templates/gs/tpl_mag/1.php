<style>

.tpl-1 .text-1{top:3%; height:3.5%;}
.tpl-1 .text-1 > div{/*font-size:26px;*/font-size:100%; text-transform:uppercase; text-align:center; height:100%;}

.text-2{top:36%; height:4%;}
.text-2 > div{width:100%; text-align:center; height:100%;font-size:100%  /*font-size:26px;*/}

.text-3{top:40%;  height:40%; /*font-size:12px;*/ font-size:50%}
.text-3 > div{width:67%; height:100%; margin:auto; position:relative;  /*line-height:1.3em*/}

.img-1{top:10%;  text-align:center; height:24%}
.img-1 > div{textalign:center; height:100%; width:30%;  position:relative; margin:auto}

.img-2{top:82%; height:12%; text-align:center}
.img-2 > div{width:30%;  height:100%;  position:relative; margin:auto}

</style>



	<div class="tpl-1 p-main">
		<? echo 'text'?>
		<div class="text-1 text myriad-pro-regular">
			<div>:::p_text_1:::</div>
		</div>
		<div class="img-1 image"><div class="img-area">:::p_img_1:::</div></div>
		<div class="text-2 text myriad-pro-regular"><div>:::p_text_2:::</div></div>
		<div class="text-3 text myriad-pro-regular"><div>:::p_text_3:::</div></div>
		<div class="img-2 image"><div class="img-area">:::p_img_2:::</div></div>
		
		<div class="img-bg" style="position:absolute; height:100%; width:100%; z-index:1; top:0">:::p_bg:::</div>
		
		
	
	</div>