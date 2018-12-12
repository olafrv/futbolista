
/* Menucool Video Plugin v2013.12.15. Copyright www.menucool.com */

var McVideo=function(){var a=[],b=function(b,e){if(!b.vPlayer)for(var c=0;c<a.length;c++){var d=new a[c](b,e);if(typeof d.play!=="undefined"){b.vPlayer=d;break}}};return{plugin:function(b){a.push(b)},register:function(c,a){b(c,a)},play:function(a,e,d,c){if(a.vPlayer){var b=a.vPlayer.play(a,e,d,c);if(b==1)return 1}return 0},stop:function(a){a.vPlayer&&a.vPlayer.stop(a)}}}(),VimeoPlayer=function(a,d){if(a.nodeName!="A"||a.getAttribute("href").toLowerCase().indexOf("vimeo.com")==-1)return null;var c=function(){var a;if(window.addEventListener)window.addEventListener("message",b,false);else window.attachEvent("onmessage",b,false);function b(b){try{var a=JSON.parse(b.data);switch(a.event){case"ready":f();break;case"finish":e()}}catch(b){}}function c(e,b){var c={method:e};if(b!==undefined)c.value=b;if(window.JSON){var d=a.contentWindow||a.contentDocument;d.postMessage(window.JSON.stringify(c),a.getAttribute("src").split("?")[0])}}function f(){c("addEventListener","finish")}function e(){var b=a.parentNode.parentNode.getAttribute("data-autonext");if(b=="replay")c("play");else b!="false"&&d.To(1,1)}return{a:function(b){a=b},stop:function(c){var a=c.getElementsByTagName("iframe");if(a.length){a=a[0];a.src="";var b=a.parentNode.parentNode.removeChild(a.parentNode);b=null}}}},b=new c;function e(f,k,j,e){var g="&loop=0&autoplay=1&wmode=opaque&color=bbbbbb&"+(new Date).getTime(),d=f.getAttribute("href"),h=d.toLowerCase().indexOf("vimeo.com"),c='<iframe id="mcVideo'+e+'" src="http://player.vimeo.com/video/'+d.substring(h+10)+"?api=1&player_id=mcVideo"+e+g+'" webkitAllowFullScreen mozallowfullscreen allowFullScreen';c+=' frameborder="0" width="'+k+'" height="'+j+'"></iframe>';var a=document.createElement("div");a.innerHTML=c;var i=a.childNodes[0];b.a(i);f.appendChild(a);return 1}return{play:function(b,d,c,a){return e(b,d,c,a)},stop:function(a){b.stop(a)}}};McVideo.plugin(VimeoPlayer);var YoutubePlayer=function(b,a){if(b.nodeName!="A"||b.getAttribute("href").toLowerCase().indexOf("youtube.com")==-1)return null;var c=function(){var e=document.createElement("script");e.src="http://www.youtube.com/player_api";var c=document.getElementsByTagName("script")[0];c.parentNode.insertBefore(e,c);var h,i,d=0,b=function(a){if(typeof YT!=="undefined"&&typeof YT.Player!=="undefined")h=new YT.Player(a,{events:{onReady:g,onStateChange:f}});else if(d<30){setTimeout(function(){b(a)},50);d++}};function f(c){if(c.data==0){var d=document.getElementById("mcVideo"+a.Id),b=d.parentNode.parentNode.getAttribute("data-autonext");if(b=="replay")c.target.d();else b!="false"&&a.To(1,1)}}function g(){}return{a:function(a){b(a)}}},d=new c;function e(e,j,i,c){var f="&loop=0&start=0&wmode=opaque&autohide=1&showinfo=0&iv_load_policy=3&modestbranding=1&showsearch=0",b=e.getAttribute("href"),h=b.toLowerCase().indexOf("v="),g='<iframe id="mcVideo'+c+'" src="http://www.youtube.com/embed/'+b.substring(h+2)+"?enablejsapi=1&autoplay=1"+f+'" frameborder="0" width="'+j+'" height="'+i+'"></iframe>',a=document.createElement("div");a.innerHTML=g;var k=a.childNodes[0];e.appendChild(a);d.a("mcVideo"+c);return 1}return{play:function(b,d,c,a){return e(b,d,c,a)},stop:function(c){var a=c.getElementsByTagName("iframe");if(a.length){a=a[0];a.src="";var b=a.parentNode.parentNode.removeChild(a.parentNode);b=null}}}};McVideo.plugin(YoutubePlayer);var McVAHelper={b:function(c){var a=c.parentNode.getElementsByTagName("div"),b=a.length;while(b--)if(a[b].className=="sliderInner"){a[b].innerHTML="";break}},c:function(){var c=50,b=navigator.userAgent,a;if((a=b.indexOf("MSIE "))!=-1)c=parseInt(b.substring(a+5,b.indexOf(".",a)));return c<9},a:function(a,c,b){if(a.addEventListener)a.addEventListener(c,b,false);else a.attachEvent&&a.attachEvent("on"+c,b)},d:function(c,h,g,a,e){if(a.style.display=="none"){if(this.c())return 0;var b=a.getElementsByTagName("source"),d=b.length,f=1;while(d--)if(!b[d].getAttribute("src")){f=0;b[d].setAttribute("src",b[d].getAttribute("data-src"))}a.style.display="block";if(e=="image")a.style.background=c.parentNode.style.background;else c.parentNode.style.background=e;if(!(a.getAttribute("width")&&a.offsetWidth<c.parentNode.offsetWidth)){a.style.width=h+"px";a.style.height=g+"px"}this.b(c);!f&&a.load();a.play()}return 1},e:function(d,c){var b=function(a){if(a&&a.stopPropagation)a.stopPropagation();else wdl.event.cancelBubble=true},a=d.getElementsByTagName(c);if(a.length){a=a[0];a.onclick=b;return a}else return null},f:function(a){var b=a.parentNode.parentNode;if(a.getAttribute("width")&&a.offsetWidth<b.offsetWidth){a.style.top=parseInt((b.offsetHeight-a.offsetHeight)/2)+"px";a.style.left=parseInt((b.offsetWidth-a.offsetWidth)/2)+"px"}}},Html5VideoPlayer=function(b,d){if(b.nodeName!="DIV"||!b.getElementsByTagName("video").length)return null;var a=McVAHelper.e(b,"video");if(a==null)return null;var c=a.getAttribute("data-autonext");if(c=="replay")McVAHelper.a(a,"ended",function(){a.play()});else if(c=="false")McVAHelper.a(a,"ended",function(){});else McVAHelper.a(a,"ended",function(){d.To(1,1);a.style.display="none"});McVAHelper.a(a,"loadedmetadata",function(){McVAHelper.f(a)});return{play:function(b,d,c){return McVAHelper.d(b,d,c,a,"black")},stop:function(b){a.currentTime=0;a.pause();a.style.display=b.style.diaplay="none"}}};McVideo.plugin(Html5VideoPlayer);var Html5AudioPlayer=function(b,d){if(b.nodeName!="DIV"||!b.getElementsByTagName("audio").length)return null;var a=McVAHelper.e(b,"audio");if(a==null)return null;var c=a.getAttribute("data-autonext");if(c=="replay")McVAHelper.a(a,"ended",function(){a.play()});else if(c=="false")McVAHelper.a(a,"ended",function(){});else McVAHelper.a(a,"ended",function(){d.To(1,1);a.style.display="none"});return{play:function(b,d,c){return McVAHelper.d(b,d,c,a,"image")},stop:function(b){a.currentTime=0;a.pause();a.style.display=b.style.diaplay="none"}}};McVideo.plugin(Html5AudioPlayer)