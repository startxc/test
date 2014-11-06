//验证url
function isURL(str_url) {
	var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
	+ "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@
	+ "(([0-9]{1,3}\.){3}[0-9]{1,3}" //IP形式的URL- 199.194.52.184
	+ "|" //允许IP和DOMAIN（域名）
	+ "([0-9a-z_!~*'()-]+\.)*" //域名- www.
	+ "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." //二级域名
	+ "[a-z]{2,6})" //first level domain- .com or .museum
	+ "(:[0-9]{1,4})?" //端口- :80
	+ "((/?)|" //a slash isn't required if there is no file name
	+ "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
	var reg = new RegExp(strRegex);
	return reg.test(str_url);
}

//验证手机号
function isMobile(str_mobile) {
	var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
	return reg.test(str_mobile);
}

//验证QQ号
function isQq(str_qq) {
	var reg = /^([1-9]{1})+\d{4}$/;
	return reg.test(str_qq);	
}

//验证邮箱
function isEmail(str_email) {
	var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;	
	return reg.test(str_email);	
}

//验证金额，无小数限制        
function isMoney(str_money) {
	var reg =/^[1-9]\d*(\.\d+)*$|^0(\.\d+)*$/;
	return reg.test(str_money);
}

//验证电话
function isTel(str_tel) {
	var reg = /^\d{7,12}$/;
	return reg.test(str_tel);
}

//收货人姓名
function isConsignee(str_consignee) {
	var reg = /^[\u4e00-\u9fa5a-zA-Z0-9]{2,}$/;
	return reg.test(str_consignee);
}

//提示窗口
function alertPop(content, callback) {
	alert(content);
	//确定操作
	$('#sure').die().live('click', function() {
		if (callback != undefined) {
			callback();
		}
		return false;
	});
}

//确认窗口
function confirmPop(content, callback) {
	
}