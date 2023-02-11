!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t("object"==typeof exports?require("jquery"):jQuery)}(function(t){function e(e,i,n){i={content:{message:"object"==typeof i?i.message:i,title:i.title?i.title:"",icon:i.icon?i.icon:"",url:i.url?i.url:"#",target:i.target?i.target:"-"}};n=t.extend(!0,{},i,n),this.settings=t.extend(!0,{},s,n),this._defaults=s,"-"==this.settings.content.target&&(this.settings.content.target=this.settings.url_target),this.animations={start:"webkitAnimationStart oanimationstart MSAnimationStart animationstart",end:"webkitAnimationEnd oanimationend MSAnimationEnd animationend"},"number"==typeof this.settings.offset&&(this.settings.offset={x:this.settings.offset,y:this.settings.offset}),this.init()}var s={element:"body",position:null,type:"info",allow_dismiss:!0,newest_on_top:!1,showProgressbar:!1,placement:{from:"top",align:"right"},offset:20,spacing:10,z_index:1031,delay:5e3,timer:1e3,url_target:"_blank",mouse_over:null,animate:{enter:"animated fadeInDown",exit:"animated fadeOutUp"},onShow:null,onShown:null,onClose:null,onClosed:null,icon_type:"class",template:'<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'};String.format=function(){for(var t=arguments[0],e=1;e<arguments.length;e++)t=t.replace(RegExp("\\{"+(e-1)+"\\}","gm"),arguments[e]);return t},t.extend(e.prototype,{init:function(){var t=this;this.buildNotify(),this.settings.content.icon&&this.setIcon(),"#"!=this.settings.content.url&&this.styleURL(),this.styleDismiss(),this.placement(),this.bind(),this.notify={$ele:this.$ele,update:function(e,s){var i={};for(var e in"string"==typeof e?i[e]=s:i=e,i)switch(e){case"type":this.$ele.removeClass("alert-"+t.settings.type),this.$ele.find('[data-notify="progressbar"] > .progress-bar').removeClass("progress-bar-"+t.settings.type),t.settings.type=i[e],this.$ele.addClass("alert-"+i[e]).find('[data-notify="progressbar"] > .progress-bar').addClass("progress-bar-"+i[e]);break;case"icon":var n=this.$ele.find('[data-notify="icon"]');"class"==t.settings.icon_type.toLowerCase()?n.removeClass(t.settings.content.icon).addClass(i[e]):(n.is("img")||n.find("img"),n.attr("src",i[e]));break;case"progress":var a=t.settings.delay-t.settings.delay*(i[e]/100);this.$ele.data("notify-delay",a),this.$ele.find('[data-notify="progressbar"] > div').attr("aria-valuenow",i[e]).css("width",i[e]+"%");break;case"url":this.$ele.find('[data-notify="url"]').attr("href",i[e]);break;case"target":this.$ele.find('[data-notify="url"]').attr("target",i[e]);break;default:this.$ele.find('[data-notify="'+e+'"]').html(i[e])}var o=this.$ele.outerHeight()+parseInt(t.settings.spacing)+parseInt(t.settings.offset.y);t.reposition(o)},close:function(){t.close()}}},buildNotify:function(){var e=this.settings.content;this.$ele=t(String.format(this.settings.template,this.settings.type,e.title,e.message,e.url,e.target)),this.$ele.attr("data-notify-position",this.settings.placement.from+"-"+this.settings.placement.align),this.settings.allow_dismiss||this.$ele.find('[data-notify="dismiss"]').css("display","none"),(this.settings.delay<=0&&!this.settings.showProgressbar||!this.settings.showProgressbar)&&this.$ele.find('[data-notify="progressbar"]').remove()},setIcon:function(){"class"==this.settings.icon_type.toLowerCase()?this.$ele.find('[data-notify="icon"]').addClass(this.settings.content.icon):this.$ele.find('[data-notify="icon"]').is("img")?this.$ele.find('[data-notify="icon"]').attr("src",this.settings.content.icon):this.$ele.find('[data-notify="icon"]').append('<img src="'+this.settings.content.icon+'" alt="Notify Icon" />')},styleDismiss:function(){this.$ele.find('[data-notify="dismiss"]').css({position:"absolute",right:"10px",top:"5px",zIndex:this.settings.z_index+2})},styleURL:function(){this.$ele.find('[data-notify="url"]').css({backgroundImage:"url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)",height:"100%",left:"0px",position:"absolute",top:"0px",width:"100%",zIndex:this.settings.z_index+1})},placement:function(){var e=this,s=this.settings.offset.y,i={display:"inline-block",margin:"0px auto",position:this.settings.position?this.settings.position:"body"===this.settings.element?"fixed":"absolute",transition:"all .5s ease-in-out",zIndex:this.settings.z_index},n=!1,a=this.settings;switch(t('[data-notify-position="'+this.settings.placement.from+"-"+this.settings.placement.align+'"]:not([data-closing="true"])').each(function(){return s=Math.max(s,parseInt(t(this).css(a.placement.from))+parseInt(t(this).outerHeight())+parseInt(a.spacing))}),1==this.settings.newest_on_top&&(s=this.settings.offset.y),i[this.settings.placement.from]=s+"px",this.settings.placement.align){case"left":case"right":i[this.settings.placement.align]=this.settings.offset.x+"px";break;case"center":i.left=0,i.right=0}this.$ele.css(i).addClass(this.settings.animate.enter),t.each(Array("webkit","moz","o","ms",""),function(t,s){e.$ele[0].style[s+"AnimationIterationCount"]=1}),t(this.settings.element).append(this.$ele),1==this.settings.newest_on_top&&(s=parseInt(s)+parseInt(this.settings.spacing)+this.$ele.outerHeight(),this.reposition(s)),t.isFunction(e.settings.onShow)&&e.settings.onShow.call(this.$ele),this.$ele.one(this.animations.start,function(t){n=!0}).one(this.animations.end,function(s){t.isFunction(e.settings.onShown)&&e.settings.onShown.call(this)}),setTimeout(function(){n||t.isFunction(e.settings.onShown)&&e.settings.onShown.call(this)},600)},bind:function(){var e=this;if(this.$ele.find('[data-notify="dismiss"]').on("click",function(){e.close()}),this.$ele.mouseover(function(e){t(this).data("data-hover","true")}).mouseout(function(e){t(this).data("data-hover","false")}),this.$ele.data("data-hover","false"),this.settings.delay>0){e.$ele.data("notify-delay",e.settings.delay);var s=setInterval(function(){var t=parseInt(e.$ele.data("notify-delay"))-e.settings.timer;if("false"===e.$ele.data("data-hover")&&"pause"==e.settings.mouse_over||"pause"!=e.settings.mouse_over){var i=(e.settings.delay-t)/e.settings.delay*100;e.$ele.data("notify-delay",t),e.$ele.find('[data-notify="progressbar"] > div').attr("aria-valuenow",i).css("width",i+"%")}t<=-e.settings.timer&&(clearInterval(s),e.close())},e.settings.timer)}},close:function(){var e=this,s=parseInt(this.$ele.css(this.settings.placement.from)),i=!1;this.$ele.data("closing","true").addClass(this.settings.animate.exit),e.reposition(s),t.isFunction(e.settings.onClose)&&e.settings.onClose.call(this.$ele),this.$ele.one(this.animations.start,function(t){i=!0}).one(this.animations.end,function(s){t(this).remove(),t.isFunction(e.settings.onClosed)&&e.settings.onClosed.call(this)}),setTimeout(function(){i||(e.$ele.remove(),e.settings.onClosed&&e.settings.onClosed(e.$ele))},600)},reposition:function(e){var s=this,i='[data-notify-position="'+this.settings.placement.from+"-"+this.settings.placement.align+'"]:not([data-closing="true"])',n=this.$ele.nextAll(i);1==this.settings.newest_on_top&&(n=this.$ele.prevAll(i)),n.each(function(){t(this).css(s.settings.placement.from,e),e=parseInt(e)+parseInt(s.settings.spacing)+t(this).outerHeight()})}}),t.notify=function(t,s){return new e(this,t,s).notify},t.notifyDefaults=function(e){return s=t.extend(!0,{},s,e)},t.notifyClose=function(e){void 0===e||"all"==e?t("[data-notify]").find('[data-notify="dismiss"]').trigger("click"):t('[data-notify-position="'+e+'"]').find('[data-notify="dismiss"]').trigger("click")}}),$.notifyDefaults({template:'<div data-notify="container" class="alert alert-{0} m-alert" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss"></button><span data-notify="icon"></span><span data-notify="title">{1}</span><span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-animated bg-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'}),("function"==typeof define&&define.amd?define:function(t,e){"undefined"!=typeof module&&module.exports?module.exports=e(require("jquery")):window.toastr=e(window.jQuery)})(["jquery"],function(t){return function(){function e(e,s){return e||(e=o()),(l=t("#"+e.containerId)).length?l:(s&&(l=function(e){return(l=t("<div/>").attr("id",e.containerId).addClass(e.positionClass)).appendTo(t(e.target)),l}(e)),l)}function s(e){for(var s=l.children(),n=s.length-1;n>=0;n--)i(t(s[n]),e)}function i(e,s,i){var n=!(!i||!i.force)&&i.force;return!(!e||!n&&0!==t(":focus",e).length||(e[s.hideMethod]({duration:s.hideDuration,easing:s.hideEasing,complete:function(){r(e)}}),0))}function n(t){d&&d(t)}function a(s){function i(t){return null==t&&(t=""),t.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function a(e){var s=e&&!1!==h.closeMethod?h.closeMethod:h.hideMethod,i=e&&!1!==h.closeDuration?h.closeDuration:h.hideDuration,a=e&&!1!==h.closeEasing?h.closeEasing:h.hideEasing;if(!t(":focus",y).length||e)return clearTimeout($.intervalId),y[s]({duration:i,easing:a,complete:function(){r(y),clearTimeout(m),h.onHidden&&"hidden"!==x.state&&h.onHidden(),x.state="hidden",x.endTime=new Date,n(x)}})}function d(){(h.timeOut>0||h.extendedTimeOut>0)&&(m=setTimeout(a,h.extendedTimeOut),$.maxHideTime=parseFloat(h.extendedTimeOut),$.hideEta=(new Date).getTime()+$.maxHideTime)}function g(){clearTimeout(m),$.hideEta=0,y.stop(!0,!0)[h.showMethod]({duration:h.showDuration,easing:h.showEasing})}function f(){var t=($.hideEta-(new Date).getTime())/$.maxHideTime*100;w.width(t+"%")}var h=o(),p=s.iconClass||h.iconClass;if(void 0!==s.optionsOverride&&(h=t.extend(h,s.optionsOverride),p=s.optionsOverride.iconClass||p),!function(t,e){if(t.preventDuplicates){if(e.message===c)return!0;c=e.message}return!1}(h,s)){u++,l=e(h,!0);var m=null,y=t("<div/>"),v=t("<div/>"),b=t("<div/>"),w=t("<div/>"),C=t(h.closeHtml),$={intervalId:null,hideEta:null,maxHideTime:null},x={toastId:u,state:"visible",startTime:new Date,options:h,map:s};return s.iconClass&&y.addClass(h.toastClass).addClass(p),function(){if(s.title){var t=s.title;h.escapeHtml&&(t=i(s.title)),v.append(t).addClass(h.titleClass),y.append(v)}}(),function(){if(s.message){var t=s.message;h.escapeHtml&&(t=i(s.message)),b.append(t).addClass(h.messageClass),y.append(b)}}(),h.closeButton&&(C.addClass(h.closeClass).attr("role","button"),y.prepend(C)),h.progressBar&&(w.addClass(h.progressClass),y.prepend(w)),h.rtl&&y.addClass("rtl"),h.newestOnTop?l.prepend(y):l.append(y),function(){var t="";switch(s.iconClass){case"toast-success":case"toast-info":t="polite";break;default:t="assertive"}y.attr("aria-live",t)}(),y.hide(),y[h.showMethod]({duration:h.showDuration,easing:h.showEasing,complete:h.onShown}),h.timeOut>0&&(m=setTimeout(a,h.timeOut),$.maxHideTime=parseFloat(h.timeOut),$.hideEta=(new Date).getTime()+$.maxHideTime,h.progressBar&&($.intervalId=setInterval(f,10))),h.closeOnHover&&y.hover(g,d),!h.onclick&&h.tapToDismiss&&y.click(a),h.closeButton&&C&&C.click(function(t){t.stopPropagation?t.stopPropagation():void 0!==t.cancelBubble&&!0!==t.cancelBubble&&(t.cancelBubble=!0),h.onCloseClick&&h.onCloseClick(t),a(!0)}),h.onclick&&y.click(function(t){h.onclick(t),a()}),n(x),h.debug&&console&&console.log(x),y}}function o(){return t.extend({},{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,closeMethod:!1,closeDuration:!1,closeEasing:!1,closeOnHover:!0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",escapeHtml:!1,target:"body",closeHtml:'<button type="button">&times;</button>',closeClass:"toast-close-button",newestOnTop:!0,preventDuplicates:!1,progressBar:!1,progressClass:"toast-progress",rtl:!1},f.options)}function r(t){l||(l=e()),t.is(":visible")||(t.remove(),t=null,0===l.children().length&&(l.remove(),c=void 0))}var l,d,c,u=0,g={error:"error",info:"info",success:"success",warning:"warning"},f={clear:function(t,n){var a=o();l||e(a),i(t,a,n)||s(a)},remove:function(s){var i=o();return l||e(i),s&&0===t(":focus",s).length?void r(s):void(l.children().length&&l.remove())},error:function(t,e,s){return a({type:g.error,iconClass:o().iconClasses.error,message:t,optionsOverride:s,title:e})},getContainer:e,info:function(t,e,s){return a({type:g.info,iconClass:o().iconClasses.info,message:t,optionsOverride:s,title:e})},options:{},subscribe:function(t){d=t},success:function(t,e,s){return a({type:g.success,iconClass:o().iconClasses.success,message:t,optionsOverride:s,title:e})},version:"2.1.4",warning:function(t,e,s){return a({type:g.warning,iconClass:o().iconClasses.warning,message:t,optionsOverride:s,title:e})}};return f}()});