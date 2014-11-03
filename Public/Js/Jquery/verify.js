// JavaScript Document
//网址
function isURL(str_url){ 
	var strRegex = "^((https|http|ftp|rtsp|mms)?://)"  
	+ "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@  
	+ "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184  
	+ "|" // 允许IP和DOMAIN（域名） 
	+ "([0-9a-z_!~*'()-]+\.)*" // 域名- www.  
	+ "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名  
	+ "[a-z]{2,6})" // first level domain- .com or .museum  
	+ "(:[0-9]{1,4})?" // 端口- :80  
	+ "((/?)|" // a slash isn't required if there is no file name  
	+ "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";  
	var re = new RegExp(strRegex);  
	//re.test() 
	if (re.test(str_url)){ 
		return (true);  
	}else{  
		return (false);  
	} 
} 
//验证手机号
function isMobile(str_mobile) {
	var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
	return reg.test(str_mobile);
}
//验证QQ号
function isqq(str_qq) {
	var reg = /^([1-9]{1})+\d{4}$/;
	return reg.test(str_qq);	
}
//验证邮箱
function isEmail(str_email) {
	var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;	
	return reg.test(str_email);	
}
//验证纯数字
function isNumber(str) {
	var reg = /^[0-9]*$/;
	return reg.test(str);	
}
//验证正整数
function isPositiveInteger(str) {
	var reg = /^[1-9]+[0-9]{1,10}$/;
	return reg.test(str);
}
//验证金额，保留两位小数
function istMoney(str) {
		var reg =/^[1-9]\d*\.\d{2}$/;
		return reg.test(str);	
	}
//验证金额，无小数限制        
function isMoney(str) {
		var reg =/^[1-9]\d*(\.\d+)*$|^0(\.\d+)*$/;
		return reg.test(str);	
	}        
//用户名规则（中英文数字，"-"，"_"）
function nameRule(str) {
	var reg = /^[\u4e00-\u9fa5_a-zA-Z0-9\-]+$/;
	return reg.test(str);	
}
//中英文字符串长度
function getstrLegth(str) {
	return str.replace(/[^\x00-\xff]/g,"aa").length;
}
//检测@字符
function isEstr(str) {
	var reg = /[@]+/;
	return reg.test(str);	
}
//验证电话
function isTel(str_tel) {
	var reg = /^\d{7,12}$/;
	return reg.test(str_tel);	
}
//收货地址
function isAddress(str) {
	var reg = /^[\u4e00-\u9fa5a-zA-Z0-9\-]{5,}$/;
	return reg.test(str);	
}
//收货人姓名
function isConsignee(str) {
	var reg = /^[\u4e00-\u9fa5a-zA-Z0-9]{2,}$/;
	return reg.test(str);	
}
//营业执照
function isLicense(str) {
	var reg = /^\d{15}$/;
	return reg.test(str);	
}
function isIdCard(str) {
	var reg = /^\d{18}$/;
	return reg.test(str);	
}
function isWord(str){
	var reg = /^[\u4e00-\u9fa5a-zA-Z0-9]+$/;
	return reg.test(str);	
}

//检测是否安装了flash
function IsFlashEnabled() {
   try{
        //IE
        var swf1 = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
   }catch(e){
        try{
            //FireFox,Chrome
             var swf2=navigator.plugins["Shockwave Flash"];
             if(swf2==undefined){
                 alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
             }
        }catch(ee){
            alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");                       
        }
    }
 }