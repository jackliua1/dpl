function refreshCaptcha(){$("#captchaInputLogin").val(""),$("#captchaImageLogin").attr("src","/captcha?"+$.param({module:"regCaptcha",token:captchaToken,t:(new Date).getTime()}))}function IEVersion(){var userAgent=navigator.userAgent,isIE=userAgent.indexOf("compatible")>-1&&userAgent.indexOf("MSIE")>-1,isEdge=userAgent.indexOf("Edge")>-1&&!isIE,isIE11=userAgent.indexOf("Trident")>-1&&userAgent.indexOf("rv:11.0")>-1;if(isIE){var reIE=new RegExp("MSIE (\\d+\\.\\d+);");reIE.test(userAgent);var fIEVersion=parseFloat(RegExp.$1);return 7==fIEVersion?7:8==fIEVersion?8:9==fIEVersion?9:10==fIEVersion?10:6}return isEdge?"edge":isIE11?11:-1}function hasBeenUser(){var studentSecondary=$(".js-registerTemplateType[data-type='student']");$17.getCookieWithDefault("stregscs")?(studentSecondary.find("input[name='mobile']").addClass("require").parent().show(),studentSecondary.find("input[name='ver_code']").addClass("require").parent().show(),$17.voxLog({app:"student",module:"studentRegisterFirstPopup",op:"showMobileReg2C"}),studentForm.mobileGray="showMobileReg2C"):($17.voxLog({app:"student",module:"studentRegisterFirstPopup",op:"hideMobileReg"}),studentForm.mobileGray="hideMobileReg"),$.prompt(template("T:已用过一起作业提示",{}),{buttons:{},position:{width:540},classes:{fade:"jqifade",close:"w-hide"},loaded:function(){$(document).on("click",".v-clickHasBeenUser",function(){$(".t-propClazz-box[data-type='welcome']").hide().siblings("[data-type='login']").show(),$17.voxLog({app:"student",module:"studentRegisterFirstPopup",op:"clickYes"})}),$(document).on("click",".v-clickNewRegisterUser",function(){$.prompt.close(),$17.voxLog({app:"student",module:"studentRegisterFirstPopup",op:"clickNo"})})}})}!function(){function LoginModeInit(){var _RenderMain=$("#RenderMain");$(document).on("click",".JS-login-main",function(){_RenderMain.html(template("T:LoginTemplateMain",{})),$("body").addClass("overflow-h");var _userName=$("#index_login_username"),_password=$("#index_login_password");$17.isBlank($17.getQuery("userId"))?""==_userName.val()&&_userName.focus():(_userName.val($17.getQuery("userId")),_password.focus()),"number"==typeof IEVersion()&&IEVersion()>5&&IEVersion()<9&&$(".JS-pwd").hide()}),"login"==$17.getQuery("ref")&&$(".JS-login-main").trigger("click"),$(document).on("submit",".JS-formSubmit",function(){var _userName=$("#index_login_username"),_password=$("#index_login_password");return""==_userName.val()?(ErrorInfoAlert(_userName,"请输入学号/手机号"),!1):""==_password.val()?(ErrorInfoAlert(_password,"请输入密码"),!1):void 0}),$(document).on("click",".JS-rememberMe-btn",function(){var $self=$(this),$checked=$self.find("input");$checked.prop("checked")?($checked.prop("checked",!1).val("off"),$self.removeClass("active")):($checked.prop("checked",!0).val("on"),$self.addClass("active"))})}function RegisterModeInit(){var _RenderMain=$("#RenderMain");$(document).on("click",".JS-register-main",function(){_RenderMain.html(template("T:RegisterSelectMain",{})),$("body").addClass("overflow-h")}),"register"==$17.getQuery("ref")&&$(".JS-register-main").trigger("click"),$(document).on({click:function(){return $(this).hasClass("active")?!1:void _RenderMain.html(template("T:RegisterStudentMain",{}))}},".JS-selectStudent-main"),$(document).on({click:function(){return $(this).hasClass("active")?!1:(_RenderMain.html(template("T:RegisterTeacherMain",{})),void setTimeout(function(){refreshCaptcha()},100))}},".JS-selectTeacher-main"),$(document).on("click",".JS-getClassLIst",function(){var $self=$(this),$classInput=$(".JS-classInput"),$inputVal=$.trim($classInput.val());return $(".JS-agreement").hasClass("active")?$self.hasClass("dis")?!1:!$17.isNumber($inputVal)||$inputVal.length<5?(ErrorInfoAlert($classInput,"老师账号错误"),!1):($self.addClass("dis"),void $.post("/signup/checkclazzinfo.vpage",{id:$inputVal,webSource:studentForm.webSource},function(data){data.success&&data.clazzList&&data.clazzList.length>0?(studentForm.teacherId=$inputVal,hasBeenUser(),$("#allSearchClazzItem").html(template("T:选择班级列表",{clazzList:data.clazzList})),$(".js-registerTemplateType[data-type='teacher']").hide(),$(".js-registerTemplateType[data-type='student']").show(),$("#register_template").show(),$(".JS-indexPageBox").hide(),$("body").removeClass("overflow-h")):(ErrorInfoAlert($classInput,data.info),$self.removeClass("dis"))})):($self.addClass("disabled"),!1)}).on("click",".JS-agreement",function(){var _this=$(this);_this.hasClass("active")?($(".JS-getClassLIst").addClass("disabled"),_this.removeClass("active")):($(".JS-getClassLIst").removeClass("disabled"),_this.addClass("active"))}),$(document).on("click",".JS-teacherVerMobile",function(){var $self=$(this),_teacherMobile=$(".JS-teacherMobile"),_teacherSmsCode=$(".JS-teacherSmsCode");return $(".JST-agreement").hasClass("active")?$self.hasClass("dis")?!1:$17.isMobile(_teacherMobile.val())?$17.isNumber(_teacherSmsCode.val())?($self.addClass("dis"),void $.post("/signup/validatemobileonly.vpage",{code:_teacherSmsCode.val(),mobile:_teacherMobile.val()},function(data){data.success?($(".js-registerTemplateType[data-type='teacher']").show(),$(".js-registerTemplateType[data-type='student']").hide(),$("#register_template").show(),$(".JS-indexPageBox").hide(),$("body").removeClass("overflow-h"),teacherForm.mobile=_teacherMobile.val(),teacherForm.code=_teacherSmsCode.val()):(data.info.indexOf("手机号")>-1?ErrorInfoAlert(_teacherMobile,data.info):ErrorInfoAlert(_teacherSmsCode,data.info),$self.removeClass("dis"))})):(ErrorInfoAlert(_teacherSmsCode,"短信验证码错误"),!1):(ErrorInfoAlert(_teacherMobile,"手机号错误"),!1):($self.addClass("disabled"),!1)}).on("click",".JST-agreement",function(){var _this=$(this);_this.hasClass("active")?($(".JS-teacherVerMobile").addClass("disabled"),_this.removeClass("active")):($(".JS-teacherVerMobile").removeClass("disabled"),_this.addClass("active"))})}function getCodeSwitchMode(step){step&&1==step?($(".JS-defaultMode").hide(),$(".JS-voiceMode").show()):($(".JS-defaultMode").show(),$(".JS-voiceMode").hide())}function ErrorInfoAlert(id,val){id.focus(),id.parent().addClass("error"),id.siblings(".errorTips").text(val)}function areYouTeacher(callback){0===areYouTeacherCount?$.prompt('<div style="padding: 0 38px;line-height: 22px;text-align: center;">注册后，我们将主动联系您核实老师身份。</div>',{focus:1,title:"注册老师账号",buttons:{"取消":!1,"我是老师":!0},submit:function(e,v){v&&(areYouTeacherCount++,callback())}}):callback()}function clearMain(id){id.empty(),$("body").removeClass("overflow-h")}function indexSwitch(){var _winHeight=$(window).height();$(".JS-indexSwitch-main li").height(600>=_winHeight?600:_winHeight)}$(".JS-indexSwitch-main").flexslider({animation:"slide",slideshow:!0,slideshowSpeed:5e3,directionNav:!1,animationLoop:!0,manualControls:".JS-indexSwitch-mode li",touch:!0}),indexSwitch(),LoginModeInit(),RegisterModeInit(),$(window).resize(function(){indexSwitch()}),$(document).on("click",".JS-clear-btn",function(){clearMain($("#RenderMain"))}),$(document).on("keyup",".JS-inputEvent",function(){var $self=$(this);""!=$self.val()&&$self.parent().removeClass("error")}),$(document).on("click",".JS-pwd",function(){var $self=$(this);$self.hasClass("show")?($("#index_login_password").prop("type","password"),$self.removeClass("show")):($("#index_login_password").prop("type","text"),$self.addClass("show"))}),$(document).on("click",".JS-receiveNotCode",function(){var $self=$(this),_teacherMobile=$(".JS-teacherMobile"),_teacherCaptcha=$(".JS-teacherCaptcha");return $17.isMobile(_teacherMobile.val())?!$17.isNumber(_teacherCaptcha.val())||_teacherCaptcha.val().length<4?(ErrorInfoAlert(_teacherCaptcha,"验证码不可为空"),!1):void getCodeSwitchMode("black"==$self.attr("data-type")?0:1):(ErrorInfoAlert(_teacherMobile,"请填写正确的手机号码"),!1)});var areYouTeacherCount=0;$(document).on("click",".JS-getCheckCode",function(){var $self=$(this),_teacherMobile=$(".JS-teacherMobile"),_teacherCaptcha=$(".JS-teacherCaptcha"),AllGetCheckCodeBtn=$(".JS-getCheckCode");if($self.hasClass("dis"))return!1;if(!$17.isMobile(_teacherMobile.val()))return ErrorInfoAlert(_teacherMobile,"请填写正确的手机号码"),!1;if(!$17.isNumber(_teacherCaptcha.val())||_teacherCaptcha.val().length<4)return ErrorInfoAlert(_teacherCaptcha,"验证码不可为空"),!1;var postData={mobile:_teacherMobile.val(),count:1,cid:contextId,captchaToken:captchaToken,captchaCode:_teacherCaptcha.val()};"voice"==$self.attr("data-type")&&(postData.voice=!0);var sendReq=function(){AllGetCheckCodeBtn.addClass("dis"),$.post("/signup/tmsignsvc.vpage",postData,function(data){var timerCount,timer,second=60;if(timerCount=data.success?second:data.timer||null,"voice"==$self.attr("data-type")&&getCodeSwitchMode(0),null==timerCount){var info=data.info;return info.indexOf("手机")>-1?(ErrorInfoAlert(_teacherMobile,info),$17.voxLog({module:"register-getCodeError",op:"mobile",s0:_teacherMobile.val()})):info.indexOf("验证码")>-1?(ErrorInfoAlert(_teacherCaptcha,"验证码错误"),$17.voxLog({module:"register-getCodeError",op:"captcha",s0:_teacherCaptcha.val()})):ErrorInfoAlert($self,info),refreshCaptcha(),AllGetCheckCodeBtn.removeClass("dis"),!1}AllGetCheckCodeBtn.addClass("dis").html(timerCount+"s后重发"),timer=$.timer(function(){0>=timerCount?(AllGetCheckCodeBtn.removeClass("dis"),AllGetCheckCodeBtn.text("获取验证码"),timer.stop()):(timerCount--,AllGetCheckCodeBtn.text(timerCount+"s后重发"))}),timer.set({time:1e3}),timer.play()})};areYouTeacher(sendReq)}),$(document).on("keyup blur","input:text, input:password",function(){var $self=$(this),$label=$self.siblings("label");return $self.parent().hasClass("ln-int")?!1:void($self.is(":focus")?""==$self.val()?$label.show():$label.hide():""==$self.val()?$label.show():$label.hide())}),$(document).on("click","label",function(){var $this=$(this);$this.siblings("input").focus()})}(),function(){try{var bp=document.createElement("script"),curProtocol=window.location.protocol.split(":")[0];bp.src="https"===curProtocol?"https://zz.bdstatic.com/linksubmit/push.js":"http://push.zhanzhang.baidu.com/push.js";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(bp,s)}catch(e){}}();
//# sourceMappingURL=loginv5.min.js.map