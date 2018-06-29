/*
* @Author: zhaowg
* @Date:   2016-09-13 10:26:39
* @Last Modified by:   zhaowg
* @Last Modified time: 2016-09-13 11:13:05
*/

'use strict';
$(function() {
		var telNum;
		$('html').delegate('.btnSubmit,.btnLeft', 'click', function() {
			hideAlert();
		});
		$('html').delegate('#getCodeBtn', 'click', function() {
			setTime($(this));
		});
	});
	/**
	 * 创建alert弹框
	 * @param  {[type]} options [description]
	 */
	function showAlert(options){
		var btnHtml;
		var inputHtml;
		switch(options.alertType){
			case 1:
			if(options.goUrl){
				btnHtml = '<a class="btnSubmit" href="'+ options.goUrl +'">'+ options.singleBtnText +'</a>';
			}else{
				btnHtml = '<a class="btnSubmit">'+ options.singleBtnText +'</a>';
			}			
			inputHtml ='';
			break;
			case 2:
			btnHtml = '<a class="btnLeft">'+ options.doubleBtnText1 +'</a><a class="btnRight" href="'+ options.goUrl +'">'+ options.doubleBtnText2 +'</a>';
			inputHtml ='';
			break;
			case 3:
			btnHtml = '<a class="btnLeft">'+ options.doubleBtnText1 +'</a><a class="btnRight" href="'+ options.goUrl +'">'+ options.doubleBtnText2 +'</a>';
			inputHtml = '<div class="codeText"><input type="text" value="" placeholder="请输入短信验证码" id="myCode"><button id="getCodeBtn" type="button" >发送</button></div>';
			break;
			case 4:
			btnHtml = '<a class="btnLeft">'+ options.doubleBtnText1 +'</a><a class="btnRight">'+ options.doubleBtnText2 +'</a>';
			inputHtml ='';
			break;
			case 5:
			btnHtml = '<a class="btnLeft">'+ options.doubleBtnText1 +'</a><a class="btnRight">'+ options.doubleBtnText2 +'</a>';
			inputHtml = '<div class="passwordText"><input type="text" value="" placeholder="请输入转让价格" id="myPassword"><span>转让价格</span></div>';
			break;
			case 6:
			btnHtml = '<a class="btnLeft" href="javascript:void(0)" onclick="location.reload()">'+ options.doubleBtnText1 +'</a><a class="btnRight" href="'+ options.goUrl +'">'+ options.doubleBtnText2 +'</a>';
			inputHtml ='';
		}
		var alertHtml = '<div class="z-alert"><div class="in-alert"><h4>'
			+options.alertTitle+'</h4><p class="z-content" style="margin-bottom: 15px;">'
			+options.alertContent+'</p>'+ inputHtml +'<div class="btngroup">'+ btnHtml +'</div></div></div>';

		$('body').append(alertHtml);
	};
	/**
	 * 移除alert弹框
	 * @return {[type]} [description]
	 */
	function hideAlert(){
		$('.z-alert').remove();
	};
	/**
	 * 封装的倒计时函数
	 */
	function setTime(btn) {
	    if (!btn.hasClass('sended')) {
	        btn.addClass('sended').attr('disabled', true);
	        var sendCodeTime = 10;
	        btn.text(sendCodeTime + 'S');
	        var sendCodeInterval;
	        sendCodeInterval = setInterval(function () {
	            if (sendCodeTime > 1 && sendCodeTime <= 10) {
	                sendCodeTime--;
	                btn.text( sendCodeTime + 'S');
	            } else {
	                btn.text('重新发送');
	                clearInterval(sendCodeInterval);
	                btn.removeClass('sended').attr('disabled', false);
	            }
	        }, 1000);
	    };
	};