!function($){$.prompt=function(message,options){void 0!==$.prompt.jqib&&$.prompt.close(),void 0!==options&&void 0!=options.classes&&"string"==typeof options.classes&&(options={box:options.classes}),$.prompt.options=$.extend({},$.prompt.defaults,options),$.prompt.currentPrefix=$.prompt.options.prefix;var opts=$.prompt.options,$body=$(document.body),$window=$(window),msgbox='<div class="'+$.prompt.options.prefix+"box "+opts.classes.box+'">';msgbox+=opts.useiframe&&$("object, applet").length>0?'<iframe src="javascript:false;" style="display:block;position:absolute;z-index:-1;" class="'+opts.prefix+"fade "+opts.classes.fade+'"></iframe>':'<div class="'+opts.prefix+"fade "+opts.classes.fade+'"></div>',msgbox+='<div class="'+opts.prefix+" "+opts.classes.prompt+'"><form action="javascript:false;" onsubmit="return false;" class="'+opts.prefix+'form"><div class="'+opts.prefix+"close "+opts.classes.close+'">'+opts.closeText+'</div><div class="'+opts.prefix+'states"></div></form></div></div>',$.prompt.jqib=$(msgbox).appendTo($body),$.prompt.jqi=$.prompt.jqib.children("."+opts.prefix),$.prompt.jqif=$.prompt.jqib.children("."+opts.prefix+"fade"),message.constructor==String&&(message={state0:{title:opts.title,html:message,buttons:opts.buttons,position:opts.position,focus:opts.focus,submit:opts.submit}}),$.prompt.options.states={};var k,v;for(k in message)v=$.extend({},$.prompt.defaults.state,{name:k},message[k]),$.prompt.addState(v.name,v),""===$.prompt.currentStateName&&($.prompt.currentStateName=v.name);var $firstState=$.prompt.jqi.find("."+opts.prefix+"states ."+opts.prefix+"state").eq(0);$.prompt.goToState($firstState.data("jqi-name")),$.prompt.jqi.on("click","."+opts.prefix+"buttons button",function(e){var $t=$(this),$state=$t.parents("."+opts.prefix+"state"),stateobj=$.prompt.options.states[$state.data("jqi-name")],msg=$state.children("."+opts.prefix+"message"),clicked=stateobj.buttons[$t.text()]||stateobj.buttons[$t.html()],forminputs={};if(void 0==clicked)for(var i in stateobj.buttons)(stateobj.buttons[i].title==$t.text()||stateobj.buttons[i].title==$t.html())&&(clicked=stateobj.buttons[i].value);$.each($.prompt.jqi.children("form").serializeArray(),function(i,obj){void 0===forminputs[obj.name]?forminputs[obj.name]=obj.value:typeof forminputs[obj.name]==Array||"object"==typeof forminputs[obj.name]?forminputs[obj.name].push(obj.value):forminputs[obj.name]=[forminputs[obj.name],obj.value]});var promptsubmite=new $.Event("impromptu:submit");promptsubmite.stateName=stateobj.name,promptsubmite.state=$state,$state.trigger(promptsubmite,[clicked,msg,forminputs]),promptsubmite.isDefaultPrevented()||$.prompt.close(!0,clicked,msg,forminputs)});var fadeClicked=function(){if(opts.persistent){var offset=opts.top.toString().indexOf("%")>=0?$window.height()*(parseInt(opts.top,10)/100):parseInt(opts.top,10),top=parseInt($.prompt.jqi.css("top").replace("px",""),10)-offset;$("html,body").animate({scrollTop:top},"fast",function(){var i=0;$.prompt.jqib.addClass(opts.prefix+"warning");var intervalid=setInterval(function(){$.prompt.jqib.toggleClass(opts.prefix+"warning"),i++>1&&(clearInterval(intervalid),$.prompt.jqib.removeClass(opts.prefix+"warning"))},100)})}else $.prompt.close(!0)},keyPressEventHandler=function(e){var key=window.event?event.keyCode:e.keyCode;if(27==key&&fadeClicked(),9==key){var $inputels=$("input,select,textarea,button",$.prompt.getCurrentState()),fwd=!e.shiftKey&&e.target==$inputels[$inputels.length-1],back=e.shiftKey&&e.target==$inputels[0];if(fwd||back)return setTimeout(function(){if($inputels){var el=$inputels[back===!0?$inputels.length-1:0];el&&el.focus()}},10),!1}};return $.prompt.position(),$.prompt.style(),$.prompt.jqif.click(fadeClicked),$window.resize({animate:!1},$.prompt.position),$.prompt.jqi.find("."+opts.prefix+"close").click($.prompt.close),$.prompt.jqib.on("keydown",keyPressEventHandler).on("impromptu:loaded",opts.loaded).on("impromptu:close",opts.close).on("impromptu:statechanging",opts.statechanging).on("impromptu:statechanged",opts.statechanged),$.prompt.jqif[opts.show](opts.overlayspeed),$.prompt.jqi[opts.show](opts.promptspeed,function(){$.prompt.jqib.trigger("impromptu:loaded")}),opts.timeout>0&&setTimeout($.prompt.close,opts.timeout),$.prompt.jqib},$.prompt.defaults={prefix:"jqi",classes:{box:"",fade:"",prompt:"",close:"",title:"",message:"",buttons:"",button:"",defaultButton:""},title:"",closeText:"&times;",buttons:{Ok:!0},loaded:function(e){},submit:function(e,v,m,f){},close:function(e,v,m,f){},statechanging:function(e,from,to){},statechanged:function(e,to){},opacity:.6,zIndex:999,overlayspeed:100,promptspeed:100,show:"show",focus:0,useiframe:!1,top:"15%",position:{container:null,x:null,y:null,arrow:null,width:null},persistent:!0,timeout:0,states:{},state:{name:null,title:"",html:"",buttons:{Ok:!0},focus:0,position:{container:null,x:null,y:null,arrow:null,width:null},submit:function(e,v,m,f){return!0}}},$.prompt.currentPrefix=$.prompt.defaults.prefix,$.prompt.currentStateName="",$.prompt.setDefaults=function(o){$.prompt.defaults=$.extend({},$.prompt.defaults,o)},$.prompt.setStateDefaults=function(o){$.prompt.defaults.state=$.extend({},$.prompt.defaults.state,o)},$.prompt.position=function(e){var restoreFx=$.fx.off,$state=$.prompt.getCurrentState(),stateObj=$.prompt.options.states[$state.data("jqi-name")],pos=stateObj?stateObj.position:void 0,$window=$(window),bodyHeight=document.body.scrollHeight,windowHeight=$(window).height(),height=($(document).height(),bodyHeight>windowHeight?bodyHeight:windowHeight),top=parseInt($window.scrollTop(),10)+($.prompt.options.top.toString().indexOf("%")>=0?windowHeight*(parseInt($.prompt.options.top,10)/100):parseInt($.prompt.options.top,10));if(void 0!==e&&e.data.animate===!1&&($.fx.off=!0),$.prompt.jqib.css({position:"absolute",height:height,width:"100%",top:0,left:0,right:0,bottom:0}),$.prompt.jqif.css({position:"absolute",height:height,width:"100%",top:0,left:0,right:0,bottom:0}),pos&&pos.container){var offset=$(pos.container).offset();$.isPlainObject(offset)&&void 0!==offset.top&&($.prompt.jqi.css({position:"absolute"}),$.prompt.jqi.animate({top:offset.top+pos.y,left:offset.left+pos.x,marginLeft:0,width:void 0!==pos.width?pos.width:null},0),top=offset.top+pos.y-($.prompt.options.top.toString().indexOf("%")>=0?windowHeight*(parseInt($.prompt.options.top,10)/100):parseInt($.prompt.options.top,10)),$("html,body").animate({scrollTop:top},"slow","swing",function(){}))}else pos&&pos.width?($.prompt.jqi.css({position:"absolute",left:"50%"}),$.prompt.jqi.animate({top:pos.y||top,left:pos.x||"50%",marginLeft:pos.width/2*-1,width:pos.width},0)):$.prompt.jqi.css({position:"absolute",top:top,left:"50%",marginLeft:$.prompt.jqi.outerWidth()/2*-1},0);void 0!==e&&e.data.animate===!1&&($.fx.off=restoreFx)},$.prompt.style=function(){$.prompt.jqif.css({zIndex:$.prompt.options.zIndex,display:"none",opacity:$.prompt.options.opacity}),$.prompt.jqi.css({zIndex:$.prompt.options.zIndex+1,display:"none"}),$.prompt.jqib.css({zIndex:$.prompt.options.zIndex})},$.prompt.get=function(state){return $("."+$.prompt.currentPrefix)},$.prompt.addState=function(statename,stateobj,afterState){var k,v,state="",$state=null,arrow="",title="",opts=$.prompt.options,$jqistates=$("."+$.prompt.currentPrefix+"states"),i=0;stateobj=$.extend({},$.prompt.defaults.state,{name:statename},stateobj),null!==stateobj.position.arrow&&(arrow='<div class="'+opts.prefix+"arrow "+opts.prefix+"arrow"+stateobj.position.arrow+'"></div>'),stateobj.title&&""!==stateobj.title&&(title='<div class="lead '+opts.prefix+"title "+opts.classes.title+'">'+stateobj.title+"</div>"),state+='<div id="'+opts.prefix+"state_"+statename+'" class="'+opts.prefix+'state" data-jqi-name="'+statename+'" style="display:none;">'+arrow+title+'<div class="'+opts.prefix+"message "+opts.classes.message+'">'+stateobj.html+'</div><div class="'+opts.prefix+"buttons "+opts.classes.buttons+'"'+($.isEmptyObject(stateobj.buttons)?'style="display:none;"':"")+">";for(k in stateobj.buttons)v=stateobj.buttons[k],defbtn=stateobj.focus===i?$.prompt.currentPrefix+"defaultbutton "+opts.classes.defaultButton:"","object"==typeof v?(state+='<button class="'+opts.classes.button+" "+defbtn,"undefined"!=typeof v.classes&&(state+=" "+($.isArray(v.classes)?v.classes.join(" "):v.classes)+" "),state+='" name="'+opts.prefix+"_"+statename+"_button"+v.title.replace(/[^a-z0-9]+/gi,"")+'" id="'+opts.prefix+"_"+statename+"_button"+v.title.replace(/[^a-z0-9]+/gi,"")+'" value="'+v.value+'">'+v.title+"</button>"):state+='<button class="'+opts.classes.button+" "+defbtn+'" name="'+opts.prefix+"_"+statename+"_button"+k+'" id="'+opts.prefix+"_"+statename+"_button"+k+'" value="'+v+'">'+k+"</button>",i++;return state+="</div></div>",$state=$(state),$state.on("impromptu:submit",stateobj.submit),void 0!==afterState?$jqistates.find("#"+$.prompt.currentPrefix+"state_"+afterState).after($state):$jqistates.append($state),$.prompt.options.states[statename]=stateobj,$state},$.prompt.removeState=function(state){var $state=$.prompt.getState(state),rm=function(){$state.remove()};return 0===$state.length?!1:($state.is(":visible")?$state.next().length>0?$.prompt.nextState(rm):$.prompt.prevState(rm):$state.slideUp(0,rm),!0)},$.prompt.getState=function(state){return $("#"+$.prompt.currentPrefix+"state_"+state)},$.prompt.getStateContent=function(state){return $.prompt.getState(state)},$.prompt.getCurrentState=function(){return $.prompt.getState($.prompt.getCurrentStateName())},$.prompt.getCurrentStateName=function(){return $.prompt.currentStateName},$.prompt.goToState=function(state,subState,callback){var jqiopts=($.prompt.get(),$.prompt.options),$state=$.prompt.getState(state),stateobj=jqiopts.states[$state.data("jqi-name")],promptstatechanginge=new $.Event("impromptu:statechanging");return"function"==typeof subState&&(callback=subState,subState=!1),$.prompt.jqib.trigger(promptstatechanginge,[$.prompt.getCurrentStateName(),state]),!promptstatechanginge.isDefaultPrevented()&&$state.length>0&&($.prompt.jqi.find("."+$.prompt.currentPrefix+"parentstate").removeClass($.prompt.currentPrefix+"parentstate"),subState?($.prompt.jqi.find("."+$.prompt.currentPrefix+"substate").not($state).slideUp(jqiopts.promptspeed).removeClass("."+$.prompt.currentPrefix+"substate").find("."+$.prompt.currentPrefix+"arrow").hide(),$.prompt.jqi.find("."+$.prompt.currentPrefix+"state:visible").addClass($.prompt.currentPrefix+"parentstate"),$state.addClass($.prompt.currentPrefix+"substate")):$.prompt.jqi.find("."+$.prompt.currentPrefix+"state").not($state).slideUp(jqiopts.promptspeed).find("."+$.prompt.currentPrefix+"arrow").hide(),$.prompt.currentStateName=stateobj.name,$state.slideDown(jqiopts.promptspeed,function(){var $t=$(this);"string"==typeof stateobj.focus?$t.find(stateobj.focus).eq(0).focus():$t.find("."+$.prompt.currentPrefix+"defaultbutton").focus(),$t.find("."+$.prompt.currentPrefix+"arrow").show(jqiopts.promptspeed),"function"==typeof callback&&$.prompt.jqib.on("impromptu:statechanged",callback),$.prompt.jqib.trigger("impromptu:statechanged",[state]),"function"==typeof callback&&$.prompt.jqib.off("impromptu:statechanged",callback)}),subState||$.prompt.position()),$state},$.prompt.nextState=function(callback){var $next=$("#"+$.prompt.currentPrefix+"state_"+$.prompt.getCurrentStateName()).next();return $.prompt.goToState($next.attr("id").replace($.prompt.currentPrefix+"state_",""),callback)},$.prompt.prevState=function(callback){var $prev=$("#"+$.prompt.currentPrefix+"state_"+$.prompt.getCurrentStateName()).prev();$.prompt.goToState($prev.attr("id").replace($.prompt.currentPrefix+"state_",""),callback)},$.prompt.close=function(callCallback,clicked,msg,formvals){$.prompt.jqib.fadeOut(0,function(){callCallback&&$.prompt.jqib.trigger("impromptu:close",[clicked,msg,formvals]),$.prompt.jqib.remove(),$(window).off("resize",$.prompt.position)})},$.fn.prompt=function(options){void 0==options&&(options={}),void 0==options.withDataAndEvents&&(options.withDataAndEvents=!1),$.prompt($(this).clone(options.withDataAndEvents).html(),options)}}(jQuery);
//# sourceMappingURL=jquery-impromptu.min.js.map