<?
/* список журналов на главной */

include_once $_SERVER['DOCUMENT_ROOT'].'/blocks/class.mag.php';

$mag = new MAGAZINE();

$mag -> tbl_mag = $_VARS['tbl_prefix']."_mag";

$arr = $mag -> getAllMag();

?> 
<div class="cover-list">
	<?
	if(!empty($arr))
	{
		foreach($arr as $k => $v)
		{
			?>
			<div class="cover">
				<?
				/*?>
					<div>
						<a class="cover-img" href="/magazine/<?=$v['id']?>/">
							<?=$v['cover']?>
						</a>
						<div class="cover-footer">
							<span class="cover-num myriad-pro-regular">№<?=$v['num']?>/<?=$v['year']?></span>
							<a class="cover-title myriad-pro-regular" href="/magazine/<?=$v['id']?>/"><?=$v['title']?></a>
						</div>
					</div>
					<?*/
				?>
				<?=strip_tags($v['mag_code'], '<div>')?>
				<a class="link" href="<?=$v['mag_link']?>"><?=$v['mag_link']?></a>
			</div>
			<?
		}
	}
	?>
	<style>
	.i_autograph, .link{display:none !important}	
	</style>
	<script language="javascript">
	
function detectIE6()
{
  var browser = navigator.appName;
  if (browser == "Microsoft Internet Explorer"){
    var b_version = navigator.appVersion;
    var re = /\MSIE\s+(\d\.\d\b)/;
    var res = b_version.match(re);
    if (res[1] <= 6){
      return true;
    }
  }
  return false;
}

function getClientWidth()
{
  return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;
  
}
function getClientHeight()
{
  //return document.documentElement.clientHeight;
  return window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
}


window.onscroll = function(){
  if ('\v'=='v'){  
    var offset = 0; // set offset (likely equal to your css top)  
    var element = document.getElementById('popup-overlay');  
    if (element!=null){
        element.style.top = (document.body.scrollTop + offset) + 'px';  
    }
  }
}

function dw(obj,text) {  obj.innerHTML+=text; }


function alertObj(obj) { 
    var str = ""; 
    for(k in obj) { 
        str += k+": "+ obj[k]+"\r\n"; 
    } 
    alert(str); 
} 


function showwind(id,http,url){
    http = http || '';
    url = url || '';
    var a=" <a class=\"button_close\" href=\"#\" onclick=\"return false;\">X</a>";
    var div=document.createElement('div');
    div.setAttribute("id", "popup-overlay");
    var divAround=document.createElement('div');
     divAround.setAttribute("id", "around-iframe");
    var ClientHeight = getClientHeight() - 50; 
    var ClientWidth = getClientWidth()- 50;
    if ('\v'=='v'){
        //Р·Р°РґРЅРёР№ С„РѕРЅ (РїСЂРѕР·СЂР°С‡РЅС‹Р№) РІСЃС‚Р°РІР»СЏС‚СЊ СЃСЋРґР° С‚РѕР»СЊРєРѕ РґР»СЏ IE
       div.style.cssText +='position: absolute;height: 100%;left: 0;\n\
                        ;background-image: url('+http+'/public/images/transparent.png)\n\
text-align: center;top: 0; width: 100%;z-index: 999'; 
    }else{
           div.style.cssText = "background: none repeat scroll 0 0 rgba(0, 0, 0, 0.7);\n\
                        height: 100%;left: 0;\n\
                        text-align: center;top: 0; width: 100%;position: fixed;z-index: 999";
    }
        document.body.appendChild(div);
        var divW=document.createElement('div');
        divW.setAttribute("id", "view-dialog");
        divW.style.cssText = "width:"+ClientWidth+"px;height:"+ClientHeight+"px;left:0px;top:0px;margin:0 !important";
        divAround.style.cssText = "width:100%;height:100%;margin-left:0%;position:relative";
        var iframe=document.createElement('iframe');
        iframe.src = http+'/networks/collection/id/'+id;
        iframe.height = "100%";
        iframe.width = "100%";
        iframe.frameborder = "0";
        iframe.scrolling = "no";
        div.onclick=function(e){
            var t=e?e.target:window.event.srcElement;
            if(t.tagName=="A"){
                this.parentNode.removeChild(this);
            }
        }    
        window.scrollTo(0, 0);
        div.appendChild(divW);
        divW.appendChild(divAround);
        divAround.innerHTML = a;
        return divAround.appendChild(iframe);
}


function getElementsByClass(searchClass,node,tag) // С„СѓРЅРєС†РёСЏ РІРѕР·РІСЂР°С‰Р°РµС‚ РјР°СЃСЃРёРІ СЃ СЌР»РµРјРµРЅС‚Р°РјРё РїРѕ РєР»Р°СЃСЃСѓ
    {
    var classElements = [];
    if (node == null){node = document;}
    if (tag == null){tag = '*';}
    var els = node.getElementsByTagName(tag);
    var elsLen = els.length;
    var pattern = new RegExp("(^|\s)"+searchClass+"(\s|$)");
    for (var i = 0; i < elsLen; i++)
    {
      if (pattern.test(els[i].className)){
        classElements[classElements.length] = els[i];
      }
    }
    return classElements;
    }
    
window.onload = function () { 
 var arr=[];
 var p;
 var IE='\v'=='v';
 if(document.styleSheets.length==0)document.body.appendChild(document.createElement('style'));
 arr = getElementsByClass("i_autograph",document,"div");
 if (arr.length>0){
  
    if(IE) {
     for (p in arrStyle){
        document.styleSheets[0].addRule(p,arrStyle[p]);
     }
     document.styleSheets[0].addRule('html #view-dialog','position: absolute;');
     document.styleSheets[0].addRule('.i_autograph.mid img','width:120px;height:170px;');
  }else{
    for (p in arrStyle){
        document.styleSheets[0].insertRule( p+" {"+arrStyle[p]+"}" , document.styleSheets[0].cssRules.length);
    }
  }
   //collection  
  }
  
   arr = getElementsByClass("i_autograph",document,"div");
   if (arr.length>0){
        var arrStyle = { 
            '.i_autograph': 'margin: 3px; padding: 3px;', 
            '.i_autograph.mid':'display: inline-block;border: 1px solid #E1E1E1;border-radius: 4px;',
            '.i_autograph .widget-btn': 'font-size: 12px;color: #3E78FD;margin: 1px;cursor: pointer;background-image: url(http://www.i-autograph.com/images/elements/marker_sign.png);background-repeat: no-repeat;background-position: 0px 0px;font-weight: normal;text-decoration: none;padding-left: 35px;display: inline-block;font-family: Tahoma, Geneva, sans-serif;vertical-align: top;text-align: left;line-height:30px;',
            '.i_autograph .widget-btn:hover':'color: #000;text-decoration: underline;',
            '.i_autograph.mid img':'cursor:pointer;display: block;margin: 0px;padding: 3px;max-width:120px;max-height:170px;cursor: pointer;',
            '.i_autograph p':'margin: 0px;padding: 3px;font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;font-size:12px',
            '#view-dialog':'text-align: center;padding: 15px;margin-right: auto;margin-left: auto;\n\
                     overflow: hidden;width: 900px; height:750px;position: fixed; top:50%;left: 50%;margin-left: -450px; margin-top:-375px;z-index:9999;',
            '#view-dialog .button_close':'font-family: Arial, sans-serif;font-size: 11px;font-weight: bold;line-height: 16px;text-transform: uppercase;\n\
                                     color: #FFF;text-decoration: none;background-color: #666;text-align: center;margin: 0px;padding: 0px;height: 16px;\n\
                                     width: 16px;border: 2px solid #DFDFDF;border-radius: 4px;-webkit-box-shadow: 0px 0px 5px 0px #000000;box-shadow: 0px 0px 5px 0px #000000;\n\
                                     cursor: pointer;display: block;position: absolute;top:0;',
            '#view-dialog a.button_close': 'right: 0;',     
            '#view-dialog p.button_close': 'left: 0;',
            '#flashmessage':'background-color: #0E3E4A;border: 2px solid #FFFFFF;margin: 20% auto 0;padding: 50px;width: 50%;border-radius:12px;color:#fff;'
        };
        if(IE) {
            for (p in arrStyle){
                document.styleSheets[0].addRule(p,arrStyle[p]);
                 document.styleSheets[0].addRule('.i_autograph.mid img','width:120px;height:170px;');
            }
        }else{
          for (p in arrStyle){
            document.styleSheets[0].insertRule( p+" {"+arrStyle[p]+"}" , document.styleSheets[0].cssRules.length);
          }
        }
               

        for (var i = 0; i < arr.length; i++){
            var id = arr[i].getAttribute("id");
            var val_but = arr[i].getAttribute("val_but");
            var http = (arr[i].getAttribute("hs") !=null) ? "https://www.i-autograph.com" : "http://www.i-autograph.com";
            var type_button = arr[i].getAttribute("type_button");
            switch (type_button){
                case 'button':
                   dw(arr[i],"<a href=\"#\" class=\"widget-btn\" title=\""+val_but+"\" onClick=\"showwind('"+id+"','"+http+"','');return false;\">"+val_but+"</a>"); 
                   break;
                case 'book':
                    var image = arr[i].getAttribute("image");
                    image = "<img onClick=\"showwind('"+id+"','"+http+"','');\" src=\""+http+image+"\" title=\""+val_but+"\"/>";
                    arr[i].className +=" mid";
                    dw(arr[i],image);
                    break;
            }
            
            
          }
      }
}
	
	
	$(document).ready(function()
	{
		$('.i_autograph').each(function()
		{
			$(this).after('<a href="#" onclick="showwind(\''+ $(this).attr('id')+'\',\'https://www.i-autograph.com/\',\'\',\'true\');" class="widget-btn-collection" title="'+ $(this).attr('val_but') +'"><img src="https://www.i-autograph.com'+ $(this).attr('image') +'"  title="'+ $(this).attr('val_but') +'" alt="'+ $(this).attr('val_but') +'"/></a>')
			//$(this).hide()
		})
		
		/*var arr = ['f13d129246d12dc79d8684b9e251c05ebce0902ac7876c641fb05bef24507b4e',
					'67997c51308ee55f1ae0f78c617a6bc5312ccf29888c28dfab60cbeaf95390f5',
					'c00c8ab8a738bab05e61028e0039e55a71e28c2977711649f942119c941eeef9']
		*/
		/*var i = 0;
		$('.i_autograph').each(function()
		{
			var href = $(this).next('a').attr('href')
			$(this).after('<a target=_blank href="' + href + '"><img src="https://www.i-autograph.com'+ $(this).attr('image') +'" /></a>')
			//$(this).after('<a target=_blank href="https://www.i-autograph.com/guest/detail/id/'+arr[i++]+'?object=1"><img src="https://www.i-autograph.com'+ $(this).attr('image') +'" /></a>')
			//$(this).hide()
		})*/
		
		
	})	
	</script>
	
	<div style="clear:both"></div>
</div>