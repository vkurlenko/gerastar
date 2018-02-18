<style>


.p-main{/*padding:30px 125px 60px;*/ position:relative; height:1080px}

/*.text div{background:#ccc}*/
.image > div{background:#0f0}

.p-text-1, .p-text-2, .p-text-3{overflow:hidden; position:absolute; width:100%; color:#000; font-family:arial}
.p-img-1, .p-img-2{/*overflow:hidden;*/ position:absolute; width:100%;}

.p-text-1, .p-text-1 textarea{font-size:26px; text-transform:uppercase; height:35px; top:30px; width:100%; text-align:center}

.p-text-2{top:390px; height:40px; text-align:center}
.p-text-2 > div, .p-text-2 div textarea{height:100%; width:100%; text-align:center;  font-size:26px;}

.p-text-3{top:440px;  height:435px}
.p-text-3 > div{width:560px; height:100%; left:50%; margin-left:-280px; position:relative; font-size:12px;}
.p-text-3 div textarea{height:100%; width:100%; font-size:12px;}

.p-img-1{top:110px;  text-align:center}
.p-img-1 > div{textalign:center; height:260px; width:260px; left:50%; position:relative; margin-left:-130px}
.p-img-2{top:895px; text-align:center}
.p-img-2 > div{width:260px; height:125px; left:50%; position:relative; margin-left:-130px}
</style>



	<div id="tpl-1" class="p-main">
		
		<div class="p-text-1 text">
			<div>:::p_text_1:::</div>
		</div>
		<div class="p-img-1 image"><div class="img-area">:::p_img_1:::</div></div>
		<div class="p-text-2 text"><div>:::p_text_2:::</div></div>
		<div class="p-text-3 text"><div>:::p_text_3:::</div></div>
		<div class="p-img-2 image"><div class="img-area">:::p_img_2:::</div></div>
	
	</div>