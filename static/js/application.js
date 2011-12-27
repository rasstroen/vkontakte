$(function(){
	VK.init(function() {
		init();
	});
});

var current_user = {};
var map;
function getVar(n,dft){
	if(variables && variables[n])
		return variables[n];
	return dft;
}

function init(){
	if(!current_user.id)
		authorize();
}

function authorize(){
	// get user profile from api
	// send profile to server
	// receive profile from server
	api_getFriends();
}

function sapi_init(data,fdata,callback){
	var data = {
		'profile':data.response[0],
		'friends':fdata.response,
		'rnd':Math.random()
	};
	call_server('Init',data ,callback);
}

function call_server(action,data,callback){
	data.action = action;
	data.viewer_id = getVar('viewer_id',0);
	data.auth_key = getVar('auth_key','');
	$.post(exec_url, data, function(data){
		if(callback)
			callback(data);
	},'json');
}

function api_getViewerProfile(fdata,callback){
	VK.api('getProfiles',{
		'uids':getVar('viewer_id',0),
		'fields':'uid,first_name,last_name,nickname,screen_name,sex,bdate,city,country,timezone,photo,photo_medium,photo_big,education'
	},function(data){
		sapi_init(data,fdata,initDone);
	})
}

function api_getFriends(){
	VK.api('getAppFriends',{
		'uids':getVar('viewer_id',0),
	},function(data){
		api_getViewerProfile(data,initDone);
	})
}

function initDone(data){
	current_user.data = data.data.result.profile;
	map = new YMaps.Map(document.getElementById("YMapsID"));
	map.disableDblClickZoom();
	map.disableHotKeys();
	map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 6);
	
	setBaloon(37.64, 55.76, current_user.data.photo,current_user.data.first_name+' '+current_user.data.nickname+' '+current_user.data.last_name)
	setBaloon(34.64, 56.86, current_user.data.photo,current_user.data.first_name+' '+current_user.data.nickname+' '+current_user.data.last_name)
	
	_getE('preloader').style.display = 'none';
	drawUserInterface();
}

function drawUserInterface(){
	var userPlank = _addE(_getE('container'),_createE('DIV'));
	userPlank.className = 'userPlank';
	userPlank.id = 'userPlank';
	
	var userImage = _addE(_getE('userPlank'),_createE('DIV'));
	userImage.className = 'userImage';
	userImage.style.background = 'url('+current_user.data.photo+') center center no-repeat';
	
	var userName = _addE(_getE('userPlank'),_createE('DIV'));
	userName.className = 'userName';
	userName.innerHTML = current_user.data.first_name+' '+current_user.data.nickname+' '+current_user.data.last_name;
}

function setBaloon(x,y,photo,name){
	var point = new YMaps.GeoPoint(x, y)
	// Создание стиля для значка метки
	var s = new YMaps.Style();
	
	s.iconStyle = new YMaps.IconStyle();
	s.iconStyle.href = photo;
	s.iconStyle.size = new YMaps.Point(50, 50);
	s.iconStyle.offset = new YMaps.Point(-9, -49);
	
	s.iconStyle.shadow = new YMaps.IconShadowStyle();
	s.iconStyle.shadow.href = "/shadow.jpg";
	s.iconStyle.shadow.size = new YMaps.Point(50, 50);
	s.iconStyle.shadow.offset = new YMaps.Point(-7, -47);
           
	s.balloonStyle = {
		template: new YMaps.LayoutTemplate(NormalBalloonLayout)
	};
	
	// Создание метки с созданным стилем и добавление ее на карту
	var placemark = new YMaps.Placemark(point, {
		style: s
	});
	placemark.description = photo;
	placemark.name = name;
	map.addOverlay(placemark);
}

function _getE(id){
	return document.getElementById(id);
}

function _createE(tagName){
	return document.createElement(tagName);
}

function _addE(_parent,child){
	_parent.appendChild(child);
	return child;
}

function NormalBalloonLayout() {
	this.element = YMaps.jQuery(
		"<div class=\"baloon\">\n\
 <div class=\"content\"></div>\
                    <div class=\"close\"></div>\
                    <div class=\"tail\"></div>\n\
	</div>");

	this.close = this.element.find(".close");
	this.content = this.element.find(".content");
	this.tail = this.element.find(".tail");

	// Отключает кнопку закрытия балуна
	this.disableClose = function(){
		this.close.unbind("click").css("display", "none");
	};

	// Включает кнопку закрытия балуна
	this.enableClose = function(callback){
		this.close.bind("click", callback).css("display", "");
		return false;
	};
	

	// Добавляет макет на страницу
	this.onAddToParent = function (parentNode) {
		YMaps.jQuery(parentNode).append(this.element);
		this.update();
	};

	// Удаляет макет со страницы
	this.onRemoveFromParent = function () {
		this.element.remove();
	};

	// Устанавливает содержимое
	this.setContent = function (content) {
		if(content){
			content._text = (content._text.replace(/\<b\>(.+)\<\/b\>\<div\>(.+)\<\/div\>/, "<div class=\"baloonname\">$1</div><div class=\"baloonimg\" style=\"background:url($2) center center no-repeat\"></div>"))
			content.onAddToParent(this.content[0]);
		}
	};

	// Обработка обновления
	this.update = function () {
		this.element.css("margin-top", this.getOffset().getY());
	};

	// Возвращает сдвиг макета балуна относительно его точки позиционирования
	this.getOffset = function () {
		return new YMaps.Point(0, -this.content.height() - 45);
	};

	// Устанавливает максимально допустимый размер содержимого балуна
	this.setMaxSize = function (maxWidth, maxHeight) {};
};