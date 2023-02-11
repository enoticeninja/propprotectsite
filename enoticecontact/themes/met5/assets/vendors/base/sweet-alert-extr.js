
(function(t){function z(){for(var a=0;a<g.length;a++)g[a][0](g[a][1]);g=[];m=!1}function n(a,b){g.push([a,b]);m||(m=!0,A(z,0))}function B(a,b){function c(a){p(b,a)}function h(a){k(b,a)}try{a(c,h)}catch(d){h(d)}}function u(a){var b=a.owner,c=b.state_,b=b.data_,h=a[c];a=a.then;if("function"===typeof h){c=l;try{b=h(b)}catch(d){k(a,d)}}v(a,b)||(c===l&&p(a,b),c===q&&k(a,b))}function v(a,b){var c;try{if(a===b)throw new TypeError("A promises callback cannot return that same promise.");if(b&&("function"===
typeof b||"object"===typeof b)){var h=b.then;if("function"===typeof h)return h.call(b,function(d){c||(c=!0,b!==d?p(a,d):w(a,d))},function(b){c||(c=!0,k(a,b))}),!0}}catch(d){return c||k(a,d),!0}return!1}function p(a,b){a!==b&&v(a,b)||w(a,b)}function w(a,b){a.state_===r&&(a.state_=x,a.data_=b,n(C,a))}function k(a,b){a.state_===r&&(a.state_=x,a.data_=b,n(D,a))}function y(a){var b=a.then_;a.then_=void 0;for(a=0;a<b.length;a++)u(b[a])}function C(a){a.state_=l;y(a)}function D(a){a.state_=q;y(a)}function e(a){if("function"!==
typeof a)throw new TypeError("Promise constructor takes a function argument");if(!1===this instanceof e)throw new TypeError("Failed to construct 'Promise': Please use the 'new' operator, this object constructor cannot be called as a function.");this.then_=[];B(a,this)}var f=t.Promise,s=f&&"resolve"in f&&"reject"in f&&"all"in f&&"race"in f&&function(){var a;new f(function(b){a=b});return"function"===typeof a}();"undefined"!==typeof exports&&exports?(exports.Promise=s?f:e,exports.Polyfill=e):"function"==
typeof define&&define.amd?define(function(){return s?f:e}):s||(t.Promise=e);var r="pending",x="sealed",l="fulfilled",q="rejected",E=function(){},A="undefined"!==typeof setImmediate?setImmediate:setTimeout,g=[],m;e.prototype={constructor:e,state_:r,then_:null,data_:void 0,then:function(a,b){var c={owner:this,then:new this.constructor(E),fulfilled:a,rejected:b};this.state_===l||this.state_===q?n(u,c):this.then_.push(c);return c.then},"catch":function(a){return this.then(null,a)}};e.all=function(a){if("[object Array]"!==
Object.prototype.toString.call(a))throw new TypeError("You must pass an array to Promise.all().");return new this(function(b,c){function h(a){e++;return function(c){d[a]=c;--e||b(d)}}for(var d=[],e=0,f=0,g;f<a.length;f++)(g=a[f])&&"function"===typeof g.then?g.then(h(f),c):d[f]=g;e||b(d)})};e.race=function(a){if("[object Array]"!==Object.prototype.toString.call(a))throw new TypeError("You must pass an array to Promise.race().");return new this(function(b,c){for(var e=0,d;e<a.length;e++)(d=a[e])&&"function"===
typeof d.then?d.then(b,c):b(d)})};e.resolve=function(a){return a&&"object"===typeof a&&a.constructor===this?a:new this(function(b){b(a)})};e.reject=function(a){return new this(function(b,c){c(a)})}})("undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this);

!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.Sweetalert2=t()}(this,function(){"use strict";var e={title:"",titleText:"",text:"",html:"",type:null,toast:!1,customClass:"",target:"body",backdrop:!0,animation:!0,allowOutsideClick:!0,allowEscapeKey:!0,allowEnterKey:!0,showConfirmButton:!0,showCancelButton:!1,preConfirm:null,confirmButtonText:"OK",confirmButtonAriaLabel:"",confirmButtonColor:"#3085d6",confirmButtonClass:null,cancelButtonText:"Cancel",cancelButtonAriaLabel:"",cancelButtonColor:"#aaa",cancelButtonClass:null,buttonsStyling:!0,reverseButtons:!1,focusConfirm:!0,focusCancel:!1,showCloseButton:!1,closeButtonAriaLabel:"Close this dialog",showLoaderOnConfirm:!1,imageUrl:null,imageWidth:null,imageHeight:null,imageAlt:"",imageClass:null,timer:null,width:500,padding:20,background:"#fff",input:null,inputPlaceholder:"",inputValue:"",inputOptions:{},inputAutoTrim:!0,inputClass:null,inputAttributes:{},inputValidator:null,grow:!1,position:"center",progressSteps:[],currentProgressStep:null,progressStepsDistance:"40px",onBeforeOpen:null,onOpen:null,onClose:null,useRejections:!1,expectRejections:!1},t=["useRejections","expectRejections"],n=function(e){var t={};for(var n in e)t[e[n]]="swal2-"+e[n];return t},o=n(["container","shown","iosfix","popup","modal","no-backdrop","toast","toast-shown","overlay","fade","show","hide","noanimation","close","title","content","contentwrapper","buttonswrapper","confirm","cancel","icon","image","input","has-input","file","range","select","radio","checkbox","textarea","inputerror","validationerror","progresssteps","activeprogressstep","progresscircle","progressline","loading","styled","top","top-left","top-right","center","center-left","center-right","bottom","bottom-left","bottom-right","grow-row","grow-column","grow-fullscreen"]),r=n(["success","warning","info","question","error"]),i="SweetAlert2:",a=function(e,t){(e=String(e).replace(/[^0-9a-f]/gi,"")).length<6&&(e=e[0]+e[0]+e[1]+e[1]+e[2]+e[2]),t=t||0;for(var n="#",o=0;o<3;o++){var r=parseInt(e.substr(2*o,2),16);n+=("00"+(r=Math.round(Math.min(Math.max(0,r+r*t),255)).toString(16))).substr(r.length)}return n},s=function(e){console.warn(i+" "+e)},l=function(e){console.error(i+" "+e)},u=[],c=function(e){-1===u.indexOf(e)&&(u.push(e),s(e))},d={previousActiveElement:null,previousBodyPadding:null},p=function(){return"undefined"==typeof window||"undefined"==typeof document},f=function(e){var t=b();t&&(t.parentNode.removeChild(t),V(document.body,o["no-backdrop"]),V(document.body,o["has-input"]),V(document.body,o["toast-shown"]));{if(!p()){var n=document.createElement("div");n.className=o.container,n.innerHTML=m;("string"==typeof e.target?document.querySelector(e.target):e.target).appendChild(n);var r=h(),i=O(r,o.input),a=O(r,o.file),s=r.querySelector("."+o.range+" input"),u=r.querySelector("."+o.range+" output"),c=O(r,o.select),d=r.querySelector("."+o.checkbox+" input"),f=O(r,o.textarea);r.setAttribute("aria-live",e.toast?"polite":"assertive");var g=function(){Y.isVisible()&&Y.resetValidationError()};return i.oninput=g,a.onchange=g,c.onchange=g,d.onchange=g,f.oninput=g,s.oninput=function(){g(),u.value=s.value},s.onchange=function(){g(),s.previousSibling.value=s.value},r}l("SweetAlert2 requires document to initialize")}},m=('\n <div role="dialog" aria-modal="true" aria-labelledby="'+o.title+'" aria-describedby="'+o.content+'" class="'+o.popup+'" tabindex="-1">\n   <ul class="'+o.progresssteps+'"></ul>\n   <div class="'+o.icon+" "+r.error+'">\n     <span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span>\n   </div>\n   <div class="'+o.icon+" "+r.question+'">?</div>\n   <div class="'+o.icon+" "+r.warning+'">!</div>\n   <div class="'+o.icon+" "+r.info+'">i</div>\n   <div class="'+o.icon+" "+r.success+'">\n     <div class="swal2-success-circular-line-left"></div>\n     <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>\n     <div class="swal2-success-ring"></div> <div class="swal2-success-fix"></div>\n     <div class="swal2-success-circular-line-right"></div>\n   </div>\n   <img class="'+o.image+'" />\n   <div class="'+o.contentwrapper+'">\n   <h2 class="'+o.title+'" id="'+o.title+'"></h2>\n   <div id="'+o.content+'" class="'+o.content+'"></div>\n   </div>\n   <input class="'+o.input+'" />\n   <input type="file" class="'+o.file+'" />\n   <div class="'+o.range+'">\n     <output></output>\n     <input type="range" />\n   </div>\n   <select class="'+o.select+'"></select>\n   <div class="'+o.radio+'"></div>\n   <label for="'+o.checkbox+'" class="'+o.checkbox+'">\n     <input type="checkbox" />\n   </label>\n   <textarea class="'+o.textarea+'"></textarea>\n   <div class="'+o.validationerror+'" id="'+o.validationerror+'"></div>\n   <div class="'+o.buttonswrapper+'">\n     <button type="button" class="'+o.confirm+'">OK</button>\n     <button type="button" class="'+o.cancel+'">Cancel</button>\n   </div>\n   <button type="button" class="'+o.close+'">×</button>\n </div>\n').replace(/(^|\n)\s*/g,""),b=function(){return document.body.querySelector("."+o.container)},h=function(){return b()?b().querySelector("."+o.popup):null},g=function(e){return b()?b().querySelector("."+e):null},v=function(){return g(o.title)},y=function(){return g(o.content)},w=function(){return g(o.image)},C=function(){return g(o.progresssteps)},x=function(){return g(o.validationerror)},k=function(){return g(o.confirm)},S=function(){return g(o.cancel)},A=function(){return g(o.buttonswrapper)},B=function(){return g(o.close)},P=function(){var e=Array.from(h().querySelectorAll('[tabindex]:not([tabindex="-1"]):not([tabindex="0"])')).sort(function(e,t){return e=parseInt(e.getAttribute("tabindex")),t=parseInt(t.getAttribute("tabindex")),e>t?1:e<t?-1:0}),t=Array.prototype.slice.call(h().querySelectorAll('button, input:not([type=hidden]), textarea, select, a, [tabindex="0"]'));return function(e){var t=[];for(var n in e)-1===t.indexOf(e[n])&&t.push(e[n]);return t}(e.concat(t))},E=function(){return!document.body.classList.contains(o["toast-shown"])},L=function(e,t){return!!e.classList&&e.classList.contains(t)},T=function(e){if(e.focus(),"file"!==e.type){var t=e.value;e.value="",e.value=t}},q=function(e,t){if(e&&t){t.split(/\s+/).filter(Boolean).forEach(function(t){e.classList.add(t)})}},V=function(e,t){if(e&&t){t.split(/\s+/).filter(Boolean).forEach(function(t){e.classList.remove(t)})}},O=function(e,t){for(var n=0;n<e.childNodes.length;n++)if(L(e.childNodes[n],t))return e.childNodes[n]},j=function(e,t){t||(t=e===h()||e===A()?"flex":"block"),e.style.opacity="",e.style.display=t},N=function(e){e.style.opacity="",e.style.display="none"},M=function(e){return e.offsetWidth||e.offsetHeight||e.getClientRects().length},H=function(){if(p())return!1;var e=document.createElement("div"),t={WebkitAnimation:"webkitAnimationEnd",OAnimation:"oAnimationEnd oanimationend",animation:"animationend"};for(var n in t)if(t.hasOwnProperty(n)&&void 0!==e.style[n])return t[n];return!1}(),R="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},I=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(e[o]=n[o])}return e},D=I({},e),U=[],W=void 0,z=void 0;"undefined"==typeof Promise&&l("This package requires a Promise library, please include a shim to enable it in this browser (See: https://github.com/limonte/sweetalert2/wiki/Migration-from-SweetAlert-to-SweetAlert2#1-ie-support)");var K=function(e){for(var t in e)Y.isValidParameter(t)||s('Unknown parameter "'+t+'"'),Y.isDeprecatedParameter(t)&&c('The parameter "'+t+'" is deprecated and will be removed in the next major release.')},Z=function(t){("string"==typeof t.target&&!document.querySelector(t.target)||"string"!=typeof t.target&&!t.target.appendChild)&&(s('Target parameter is not valid, defaulting to "body"'),t.target="body");var n=void 0,i=h(),a="string"==typeof t.target?document.querySelector(t.target):t.target;n=i&&a&&i.parentNode!==a.parentNode?f(t):i||f(t);var u=t.width===e.width&&t.toast?"auto":t.width;n.style.width="number"==typeof u?u+"px":u;var c=t.padding===e.padding&&t.toast?"inherit":t.padding;n.style.padding="number"==typeof c?c+"px":c,n.style.background=t.background;for(var d=n.querySelectorAll("[class^=swal2-success-circular-line], .swal2-success-fix"),p=0;p<d.length;p++)d[p].style.background=t.background;var m=b(),g=v(),x=y(),P=A(),E=k(),L=S(),T=B();if(t.titleText?g.innerText=t.titleText:g.innerHTML=t.title.split("\n").join("<br />"),t.backdrop||q(document.body,o["no-backdrop"]),t.text||t.html){if("object"===R(t.html))if(x.innerHTML="",0 in t.html)for(var O=0;O in t.html;O++)x.appendChild(t.html[O].cloneNode(!0));else x.appendChild(t.html.cloneNode(!0));else t.html?x.innerHTML=t.html:t.text&&(x.textContent=t.text);j(x)}else N(x);if(t.position in o&&q(m,o[t.position]),t.grow&&"string"==typeof t.grow){var M="grow-"+t.grow;M in o&&q(m,o[M])}t.showCloseButton?(T.setAttribute("aria-label",t.closeButtonAriaLabel),j(T)):N(T),n.className=o.popup,t.toast?(q(document.body,o["toast-shown"]),q(n,o.toast)):q(n,o.modal),t.customClass&&q(n,t.customClass);var H=C(),I=parseInt(null===t.currentProgressStep?Y.getQueueStep():t.currentProgressStep,10);t.progressSteps.length?(j(H),function(e){for(;e.firstChild;)e.removeChild(e.firstChild)}(H),I>=t.progressSteps.length&&s("Invalid currentProgressStep parameter, it should be less than progressSteps.length (currentProgressStep like JS arrays starts from 0)"),t.progressSteps.forEach(function(e,n){var r=document.createElement("li");if(q(r,o.progresscircle),r.innerHTML=e,n===I&&q(r,o.activeprogressstep),H.appendChild(r),n!==t.progressSteps.length-1){var i=document.createElement("li");q(i,o.progressline),i.style.width=t.progressStepsDistance,H.appendChild(i)}})):N(H);for(var D=h().querySelectorAll("."+o.icon),U=0;U<D.length;U++)N(D[U]);if(t.type){var W=!1;for(var z in r)if(t.type===z){W=!0;break}if(!W)return l("Unknown alert type: "+t.type),!1;var K=n.querySelector("."+o.icon+"."+r[t.type]);if(j(K),t.animation)switch(t.type){case"success":q(K,"swal2-animate-success-icon"),q(K.querySelector(".swal2-success-line-tip"),"swal2-animate-success-line-tip"),q(K.querySelector(".swal2-success-line-long"),"swal2-animate-success-line-long");break;case"error":q(K,"swal2-animate-error-icon"),q(K.querySelector(".swal2-x-mark"),"swal2-animate-x-mark")}}var Z=w();t.imageUrl?(Z.setAttribute("src",t.imageUrl),Z.setAttribute("alt",t.imageAlt),j(Z),t.imageWidth?Z.setAttribute("width",t.imageWidth):Z.removeAttribute("width"),t.imageHeight?Z.setAttribute("height",t.imageHeight):Z.removeAttribute("height"),Z.className=o.image,t.imageClass&&q(Z,t.imageClass)):N(Z),t.showCancelButton?L.style.display="inline-block":N(L),t.showConfirmButton?function(e,t){e.style.removeProperty?e.style.removeProperty(t):e.style.removeAttribute(t)}(E,"display"):N(E),t.showConfirmButton||t.showCancelButton?j(P):N(P),E.innerHTML=t.confirmButtonText,L.innerHTML=t.cancelButtonText,E.setAttribute("aria-label",t.confirmButtonAriaLabel),L.setAttribute("aria-label",t.cancelButtonAriaLabel),t.buttonsStyling&&(E.style.backgroundColor=t.confirmButtonColor,L.style.backgroundColor=t.cancelButtonColor),E.className=o.confirm,q(E,t.confirmButtonClass),L.className=o.cancel,q(L,t.cancelButtonClass),t.buttonsStyling?(q(E,o.styled),q(L,o.styled)):(V(E,o.styled),V(L,o.styled),E.style.backgroundColor=E.style.borderLeftColor=E.style.borderRightColor="",L.style.backgroundColor=L.style.borderLeftColor=L.style.borderRightColor=""),!0===t.animation?V(n,o.noanimation):q(n,o.noanimation),t.showLoaderOnConfirm&&!t.preConfirm&&s("showLoaderOnConfirm is set to true, but preConfirm is not defined.\nshowLoaderOnConfirm should be used together with preConfirm, see usage example:\nhttps://limonte.github.io/sweetalert2/#ajax-request")},_=function(){null===d.previousBodyPadding&&document.body.scrollHeight>window.innerHeight&&(d.previousBodyPadding=document.body.style.paddingRight,document.body.style.paddingRight=function(){if("ontouchstart"in window||navigator.msMaxTouchPoints)return 0;var e=document.createElement("div");e.style.width="50px",e.style.height="50px",e.style.overflow="scroll",document.body.appendChild(e);var t=e.offsetWidth-e.clientWidth;return document.body.removeChild(e),t}()+"px")},Q=function(){if(/iPad|iPhone|iPod/.test(navigator.userAgent)&&!window.MSStream&&!L(document.body,o.iosfix)){var e=document.body.scrollTop;document.body.style.top=-1*e+"px",q(document.body,o.iosfix)}},Y=function e(){for(var t=arguments.length,n=Array(t),r=0;r<t;r++)n[r]=arguments[r];if("undefined"!=typeof window){if(void 0===n[0])return l("SweetAlert2 expects at least 1 attribute!"),!1;var i=I({},D);switch(R(n[0])){case"string":i.title=n[0],i.html=n[1],i.type=n[2];break;case"object":if(K(n[0]),I(i,n[0]),i.extraParams=n[0].extraParams,"email"===i.input&&null===i.inputValidator){var s=function(e){return new Promise(function(t,n){/^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]{2,24}$/.test(e)?t():n("Invalid email address")})};i.inputValidator=i.expectRejections?s:e.adaptInputValidator(s)}if("url"===i.input&&null===i.inputValidator){var u=function(e){return new Promise(function(t,n){/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/.test(e)?t():n("Invalid URL")})};i.inputValidator=i.expectRejections?u:e.adaptInputValidator(u)}break;default:return l('Unexpected type of argument! Expected "string" or "object", got '+R(n[0])),!1}Z(i);var c=b(),p=h();return new Promise(function(t,n){var r=function(n){e.closePopup(i.onClose),t(i.useRejections?n:{value:n})},s=function(o){e.closePopup(i.onClose),i.useRejections?n(o):t({dismiss:o})},u=function(t){e.closePopup(i.onClose),n(t)};i.timer&&(p.timeout=setTimeout(function(){return s("timer")},i.timer));var f=function(e){if(!(e=e||i.input))return null;switch(e){case"select":case"textarea":case"file":return O(p,o[e]);case"checkbox":return p.querySelector("."+o.checkbox+" input");case"radio":return p.querySelector("."+o.radio+" input:checked")||p.querySelector("."+o.radio+" input:first-child");case"range":return p.querySelector("."+o.range+" input");default:return O(p,o.input)}};i.input&&setTimeout(function(){var e=f();e&&T(e)},0);for(var m=function(t){if(i.showLoaderOnConfirm&&e.showLoading(),i.preConfirm){e.resetValidationError();var n=Promise.resolve().then(function(){return i.preConfirm(t,i.extraParams)});i.expectRejections?n.then(function(e){return r(e||t)},function(t){e.hideLoading(),t&&e.showValidationError(t)}):n.then(function(n){M(x())?e.hideLoading():r(n||t)},function(e){return u(e)})}else r(t)},g=function(t){var n=t||window.event,o=n.target||n.srcElement,r=k(),l=S(),c=r&&(r===o||r.contains(o)),d=l&&(l===o||l.contains(o));switch(n.type){case"mouseover":case"mouseup":i.buttonsStyling&&(c?r.style.backgroundColor=a(i.confirmButtonColor,-.1):d&&(l.style.backgroundColor=a(i.cancelButtonColor,-.1)));break;case"mouseout":i.buttonsStyling&&(c?r.style.backgroundColor=i.confirmButtonColor:d&&(l.style.backgroundColor=i.cancelButtonColor));break;case"mousedown":i.buttonsStyling&&(c?r.style.backgroundColor=a(i.confirmButtonColor,-.2):d&&(l.style.backgroundColor=a(i.cancelButtonColor,-.2)));break;case"click":if(c&&e.isVisible())if(e.disableButtons(),i.input){var p=function(){var e=f();if(!e)return null;switch(i.input){case"checkbox":return e.checked?1:0;case"radio":return e.checked?e.value:null;case"file":return e.files.length?e.files[0]:null;default:return i.inputAutoTrim?e.value.trim():e.value}}();if(i.inputValidator){e.disableInput();var b=Promise.resolve().then(function(){return i.inputValidator(p,i.extraParams)});i.expectRejections?b.then(function(){e.enableButtons(),e.enableInput(),m(p)},function(t){e.enableButtons(),e.enableInput(),t&&e.showValidationError(t)}):b.then(function(t){e.enableButtons(),e.enableInput(),t?e.showValidationError(t):m(p)},function(e){return u(e)})}else m(p)}else m(!0);else d&&e.isVisible()&&(e.disableButtons(),s("cancel"))}},I=p.querySelectorAll("button"),D=0;D<I.length;D++)I[D].onclick=g,I[D].onmouseover=g,I[D].onmouseout=g,I[D].onmousedown=g;B().onclick=function(){s("close")},i.toast?p.onclick=function(t){t.target!==p||i.showConfirmButton||i.showCancelButton||i.allowOutsideClick&&(e.closePopup(i.onClose),s("overlay"))}:c.onclick=function(e){e.target===c&&i.allowOutsideClick&&s("overlay")};var U=A(),K=k(),Y=S();i.reverseButtons?K.parentNode.insertBefore(Y,K):K.parentNode.insertBefore(K,Y);var $=function(e,t){for(var n=P(i.focusCancel),o=0;o<n.length;o++){(e+=t)===n.length?e=0:-1===e&&(e=n.length-1);var r=n[e];if(M(r))return r.focus()}};i.toast&&z&&(window.onkeydown=W,z=!1),i.toast||z||(W=window.onkeydown,z=!0,window.onkeydown=function(t){var n=t||window.event;if("Enter"!==n.key||n.isComposing)if("Tab"===n.key){for(var o=n.target||n.srcElement,r=P(i.focusCancel),a=-1,l=0;l<r.length;l++)if(o===r[l]){a=l;break}n.shiftKey?$(a,-1):$(a,1),n.stopPropagation(),n.preventDefault()}else-1!==["ArrowLeft","ArrowRight","ArrowUp","ArrowDown","Left","Right","Up","Down"].indexOf(n.key)?document.activeElement===K&&M(Y)?Y.focus():document.activeElement===Y&&M(K)&&K.focus():"Escape"!==n.key&&"Esc"!==n.key||!0!==i.allowEscapeKey||s("esc");else if(n.target===f()){if("textarea"===n.target.tagName.toLowerCase())return;e.clickConfirm(),n.preventDefault()}}),i.buttonsStyling&&(K.style.borderLeftColor=i.confirmButtonColor,K.style.borderRightColor=i.confirmButtonColor),e.hideLoading=e.disableLoading=function(){i.showConfirmButton||(N(K),i.showCancelButton||N(A())),V(U,o.loading),V(p,o.loading),p.removeAttribute("aria-busy"),K.disabled=!1,Y.disabled=!1},e.getTitle=function(){return v()},e.getContent=function(){return y()},e.getInput=function(){return f()},e.getImage=function(){return w()},e.getButtonsWrapper=function(){return A()},e.getConfirmButton=function(){return k()},e.getCancelButton=function(){return S()},e.enableButtons=function(){K.disabled=!1,Y.disabled=!1},e.disableButtons=function(){K.disabled=!0,Y.disabled=!0},e.enableConfirmButton=function(){K.disabled=!1},e.disableConfirmButton=function(){K.disabled=!0},e.enableInput=function(){var e=f();if(!e)return!1;if("radio"===e.type)for(var t=e.parentNode.parentNode.querySelectorAll("input"),n=0;n<t.length;n++)t[n].disabled=!1;else e.disabled=!1},e.disableInput=function(){var e=f();if(!e)return!1;if(e&&"radio"===e.type)for(var t=e.parentNode.parentNode.querySelectorAll("input"),n=0;n<t.length;n++)t[n].disabled=!0;else e.disabled=!0},e.showValidationError=function(e){var t=x();t.innerHTML=e,j(t);var n=f();n&&(n.setAttribute("aria-invalid",!0),n.setAttribute("aria-describedBy",o.validationerror),T(n),q(n,o.inputerror))},e.resetValidationError=function(){var e=x();N(e);var t=f();t&&(t.removeAttribute("aria-invalid"),t.removeAttribute("aria-describedBy"),V(t,o.inputerror))},e.getProgressSteps=function(){return i.progressSteps},e.setProgressSteps=function(e){i.progressSteps=e,Z(i)},e.showProgressSteps=function(){j(C())},e.hideProgressSteps=function(){N(C())},e.enableButtons(),e.hideLoading(),e.resetValidationError(),i.input&&q(document.body,o["has-input"]);for(var J=["input","file","range","select","radio","checkbox","textarea"],X=void 0,F=0;F<J.length;F++){var G=o[J[F]],ee=O(p,G);if(X=f(J[F])){for(var te in X.attributes)if(X.attributes.hasOwnProperty(te)){var ne=X.attributes[te].name;"type"!==ne&&"value"!==ne&&X.removeAttribute(ne)}for(var oe in i.inputAttributes)X.setAttribute(oe,i.inputAttributes[oe])}ee.className=G,i.inputClass&&q(ee,i.inputClass),N(ee)}var re=void 0;switch(i.input){case"text":case"email":case"password":case"number":case"tel":case"url":(X=O(p,o.input)).value=i.inputValue,X.placeholder=i.inputPlaceholder,X.type=i.input,j(X);break;case"file":(X=O(p,o.file)).placeholder=i.inputPlaceholder,X.type=i.input,j(X);break;case"range":var ie=O(p,o.range),ae=ie.querySelector("input"),se=ie.querySelector("output");ae.value=i.inputValue,ae.type=i.input,se.value=i.inputValue,j(ie);break;case"select":var le=O(p,o.select);if(le.innerHTML="",i.inputPlaceholder){var ue=document.createElement("option");ue.innerHTML=i.inputPlaceholder,ue.value="",ue.disabled=!0,ue.selected=!0,le.appendChild(ue)}re=function(e){for(var t in e){var n=document.createElement("option");n.value=t,n.innerHTML=e[t],i.inputValue.toString()===t&&(n.selected=!0),le.appendChild(n)}j(le),le.focus()};break;case"radio":var ce=O(p,o.radio);ce.innerHTML="",re=function(e){for(var t in e){var n=document.createElement("input"),r=document.createElement("label"),a=document.createElement("span");n.type="radio",n.name=o.radio,n.value=t,i.inputValue.toString()===t&&(n.checked=!0),a.innerHTML=e[t],r.appendChild(n),r.appendChild(a),r.for=n.id,ce.appendChild(r)}j(ce);var s=ce.querySelectorAll("input");s.length&&s[0].focus()};break;case"checkbox":var de=O(p,o.checkbox),pe=f("checkbox");pe.type="checkbox",pe.value=1,pe.id=o.checkbox,pe.checked=Boolean(i.inputValue);var fe=de.getElementsByTagName("span");fe.length&&de.removeChild(fe[0]),(fe=document.createElement("span")).innerHTML=i.inputPlaceholder,de.appendChild(fe),j(de);break;case"textarea":var me=O(p,o.textarea);me.value=i.inputValue,me.placeholder=i.inputPlaceholder,j(me);break;case null:break;default:l('Unexpected type of input! Expected "text", "email", "password", "number", "tel", "select", "radio", "checkbox", "textarea", "file" or "url", got "'+i.input+'"')}"select"!==i.input&&"radio"!==i.input||(i.inputOptions instanceof Promise?(e.showLoading(),i.inputOptions.then(function(t){e.hideLoading(),re(t)})):"object"===R(i.inputOptions)?re(i.inputOptions):l("Unexpected type of inputOptions! Expected object or Promise, got "+R(i.inputOptions))),function(e,t,n){var r=b(),i=h();null!==t&&"function"==typeof t&&t(i),e?(q(i,o.show),q(r,o.fade),V(i,o.hide)):V(i,o.fade),j(i),r.style.overflowY="hidden",H&&!L(i,o.noanimation)?i.addEventListener(H,function e(){i.removeEventListener(H,e),r.style.overflowY="auto"}):r.style.overflowY="auto",q(document.documentElement,o.shown),q(document.body,o.shown),q(r,o.shown),E()&&(_(),Q()),d.previousActiveElement=document.activeElement,null!==n&&"function"==typeof n&&setTimeout(function(){n(i)})}(i.animation,i.onBeforeOpen,i.onOpen),i.toast||(i.allowEnterKey?i.focusCancel&&M(Y)?Y.focus():i.focusConfirm&&M(K)?K.focus():$(-1,1):document.activeElement&&document.activeElement.blur()),b().scrollTop=0})}};return Y.isVisible=function(){return!!h()},Y.queue=function(e){U=e;var t=function(){U=[],document.body.removeAttribute("data-swal2-queue-step")},n=[];return new Promise(function(e,o){!function o(r,i){r<U.length?(document.body.setAttribute("data-swal2-queue-step",r),Y(U[r]).then(function(a){void 0!==a.value?(n.push(a.value),o(r+1,i)):(t(),e({dismiss:a.dismiss}))})):(t(),e({value:n}))}(0)})},Y.getQueueStep=function(){return document.body.getAttribute("data-swal2-queue-step")},Y.insertQueueStep=function(e,t){return t&&t<U.length?U.splice(t,0,e):U.push(e)},Y.deleteQueueStep=function(e){void 0!==U[e]&&U.splice(e,1)},Y.close=Y.closePopup=Y.closeModal=Y.closeToast=function(e){var t=b(),n=h();if(n){V(n,o.show),q(n,o.hide),clearTimeout(n.timeout),document.body.classList.contains(o["toast-shown"])||(!function(){if(d.previousActiveElement&&d.previousActiveElement.focus){var e=window.scrollX,t=window.scrollY;d.previousActiveElement.focus(),void 0!==e&&void 0!==t&&window.scrollTo(e,t)}}(),window.onkeydown=W,z=!1);var r=function(){t.parentNode&&t.parentNode.removeChild(t),V(document.documentElement,o.shown),V(document.body,o.shown),V(document.body,o["no-backdrop"]),V(document.body,o["has-input"]),V(document.body,o["toast-shown"]),E()&&(null!==d.previousBodyPadding&&(document.body.style.paddingRight=d.previousBodyPadding,d.previousBodyPadding=null),function(){if(L(document.body,o.iosfix)){var e=parseInt(document.body.style.top,10);V(document.body,o.iosfix),document.body.style.top="",document.body.scrollTop=-1*e}}())};H&&!L(n,o.noanimation)?n.addEventListener(H,function e(){n.removeEventListener(H,e),L(n,o.hide)&&r()}):r(),null!==e&&"function"==typeof e&&setTimeout(function(){e(n)})}},Y.clickConfirm=function(){return k().click()},Y.clickCancel=function(){return S().click()},Y.showLoading=Y.enableLoading=function(){var e=h();e||Y(""),e=h();var t=A(),n=k(),r=S();j(t),j(n,"inline-block"),q(t,o.loading),q(e,o.loading),n.disabled=!0,r.disabled=!0,e.setAttribute("aria-busy",!0),e.focus()},Y.isValidParameter=function(t){return e.hasOwnProperty(t)||"extraParams"===t},Y.isDeprecatedParameter=function(e){return-1!==t.indexOf(e)},Y.setDefaults=function(e){if(!e||"object"!==(void 0===e?"undefined":R(e)))return l("the argument for setDefaults() is required and has to be a object");K(e);for(var t in e)Y.isValidParameter(t)&&(D[t]=e[t])},Y.resetDefaults=function(){D=I({},e)},Y.adaptInputValidator=function(e){return function(t,n){return e.call(this,t,n).then(function(){},function(e){return e})}},Y.noop=function(){},Y.version="7.1.0",Y.default=Y,"object"===R(window._swalDefaults)&&Y.setDefaults(window._swalDefaults),Y}),"undefined"!=typeof window&&window.Sweetalert2&&(window.sweetAlert=window.swal=window.Sweetalert2);
//== Set defaults
swal.setDefaults({
	width: 400,
	padding: '2.5rem',
	buttonsStyling: false,
	confirmButtonClass: 'btn btn-success m-btn m-btn--custom',
	confirmButtonColor: null,
	cancelButtonClass: 'btn btn-secondary m-btn m-btn--custom',
	cancelButtonColor: null
});