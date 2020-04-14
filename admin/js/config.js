/*----------------------------------------------------------------------*/
/* Set some Standards
/* This file not required! It just demonstrate how you can define
/* standards in one configuration file
/*----------------------------------------------------------------------*/

var config = {
	tooltip :{
		gravity: 'nw',
		fade: false,
		opacity: 1,
		offset: 0
	}
};



if($.fn.wl_Alert) $.fn.wl_Alert.defaults = {
	speed: 500,
	sticky: false,
	onBeforeClose: function (element) {},
	onClose: function (element) {}
};

if($.fn.wl_Autocomplete) $.fn.wl_Autocomplete.defaults = {
	//check http://jqueryui.com/demos/autocomplete/ for all options
};

if($.fn.wl_Breadcrump) $.fn.wl_Breadcrump.defaults = {
	start: 0,
	numbers: false,
	allownextonly: false,
	disabled: false,
	connect: null,
	onChange: function () {}
};

if($.fn.wl_Calendar) $.fn.wl_Calendar.defaults = {
	//check http://arshaw.com/fullcalendar/ for all options
};

if($.fn.wl_Chart) $.fn.wl_Chart.defaults = {
	width: null,
	height: 300,
	hideTable: true,
	data: {},
	stack: false,
	type: 'lines',
	points: null,
	shadowSize: 2,
	fill: null,
	fillColor: null,
	lineWidth: null,
	legend: true,
	legendPosition: "ne", // or "nw" or "se" or "sw"
	tooltip: true,
	tooltipGravity: 'n',
	tooltipPattern: function (value, legend, label, id) {
		return "A quantidade de votos é " + value + " para a resposta " + legend;
	},
	//tooltipPattern: "value is %1 from %2 at %3 (%4)", //also possible
	orientation: 'horizontal',
	colors: ['#67d1ff', '#6aa5bf', '#217ea6', '#bfbfbf', '#7b9ba9', '#ace8ed'],
	flot: {},
	onClick: function (value, legend, label, id) {}
};

if($.fn.wl_Color) $.fn.wl_Color.defaults = {
	mousewheel: true,
	onChange: function (hsb, rgb) {}
};


if($.fn.wl_Date) $.fn.wl_Date.defaults = {
	value: null,
	mousewheel: true,
	
	//some datepicker standards
	dayNames : ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
	dayNamesMin : ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
	dayNamesShort : ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
	firstDay: 0,
	nextText: 'próximo',
	prevText: 'prev',
	currentText: 'Hoje',
	showWeek: true,
	dateFormat: 'dd/mm/yy'
};


if($.confirm) $.confirm.defaults = {
	text:{
		header: 'Por favor, confirme',
		ok: 'Ok',
		cancel: 'Cancelar'
	}
};
if($.prompt) $.prompt.defaults = {
	text:{
		header: 'Please prompt',
		ok: 'OK',
		cancel: 'Cancel'
	}
};
if($.alert) $.alert.defaults = {
	nativ: false,
	resizable: false,
	modal: true,
	text:{
		header: 'Notificação',
		ok: 'OK'
	}
};

if($.fn.wl_Editor) $.fn.wl_Editor.defaults = {
	css: 'css/light/editor.css',
	buttons: 'bold|italic|underline|strikeThrough|justifyLeft|justifyCenter|justifyRight|justifyFull|highlight|indent|outdent|subscript|superscript|undo|redo|insertOrderedList|insertUnorderedList|insertHorizontalRule|createLink|insertImage|h1|h2|h3|h4|h5|h6|paragraph|rtl|ltr|cut|copy|paste|increaseFontSize|decreaseFontSize|html|code|removeFormat|insertTable',
	initialContent: ""
};

if($.fn.wl_File) $.fn.wl_File.defaults = {
	url: 'upload.php',
	autoUpload: true,
	paramName: 'files',
	multiple: false,
	allowedExtensions: ['jpg','jpeg','gif','png','doc','zip','docx','txt','pdf'],
	maxNumberOfFiles: 100,
	maxFileSize: 10000000,
	minFileSize: 1,
	sequentialUploads: true,
	dragAndDrop: true,
	formData: {},
	text: {
		ready: 'pronto',
		cancel: 'cancelar',
		remove: 'remover',
		uploading: 'uploading...',
		done: 'feito',
		start: 'iniciar upload',
		add_files: 'adicionar arquivos',
		cancel_all: 'cancelar upload',
		remove_all: 'remover tudo'
	},
	onAdd: function (e, data) {},
	onSend: function (e, data) {},
	onDone: function (e, data) {},
	onFinish: function (e, data) {},
	onFail: function (e, data) {},
	onAlways: function (e, data) {},
	onProgress: function (e, data) {},
	onProgressAll: function (e, data) {},
	onStart: function (e) {},
	onStop: function (e) {},
	onChange: function (e, data) {},
	onDrop: function (e, data) {},
	onDragOver: function (e) {},
	onFileError: function (error, fileobj) {
		$.msg('arquivo não é permitido: ' + fileobj.name, {
			header: error.msg + ' (' + error.code + ')'
		});
	}
};

if($.fn.wl_Fileexplorer) $.fn.wl_Fileexplorer.defaults = {
	url: 'elfinder/php/connector.php',
	toolbar: [
		['back', 'reload', 'open', 'select', 'quicklook', 'info', 'rename', 'copy', 'cut', 'paste', 'rm', 'mkdir', 'mkfile', 'upload', 'duplicate', 'edit', 'archive', 'extract', 'resize', 'icons', 'list', 'help']
	]
};

if($.fn.wl_Form) $.fn.wl_Form.defaults = {
	submitButton: 'button:last',
	method: 'post',
	action: null,
	ajax: true,
	serialize: false,
	parseQuery: true,
	dataType: 'text',
	status: true,
	sent: true,
	confirmSend: true,
	text: {
		required: 'Este campo é obrigatório',
		valid: 'Este campo é inválido',
		password: 'Esta senha é curta',
		passwordmatch: 'Esta senha não é correspondente',
		fileinqueue: 'Há pelo menos um arquivo na fila',
		incomplete: 'Por favor preencha o formulário corretamente!',
		send: 'salvando dados informados ...',
		sendagain: 'Salvar dados?',
		success: 'Dados salvos com sucesso!',
		error: 'erro ao salvar dados!',
		parseerror: 'Can\'t unserialize query string:\n %e'
	},
	tooltip: {
		gravity: 'nw'
	},
	onRequireError: function (element) {},
	onValidError: function (element) {},
	onPasswordError: function (element) {},
	onFileError: function (element) {},
	onBeforeSubmit: function (data) {},
	onComplete: function (textStatus, jqXHR) {},
	onError: function (textStatus, error, jqXHR) {},
	onSuccess: function (data, textStatus, jqXHR) {}
};


if($.fn.wl_Gallery) $.fn.wl_Gallery.defaults = {
	group: 'wl_gallery',
	fancybox: {},
	onEdit: function (element, href, title) {},
	onDelete: function (element, href, title) {}
};

if($.fn.wl_Number) $.fn.wl_Number.defaults = {
	step: 1,
	decimals: 0,
	start: 0,
	min: null,
	max: null,
	mousewheel: true,
	onChange: function (value) {},
	onError: function (value) {}
};

if($.fn.wl_Password) $.fn.wl_Password.defaults = {
	confirm: true,
	showStrength: true,
	words: ['muito curto', 'ruim', 'médio', 'bom', 'muito bom', 'excelente'],
	minLength: 3,
	text: {
		confirm: 'Por favor, confirme',
		nomatch: 'Senhas não conferem'
	}
};

if($.fn.wl_Slider) $.fn.wl_Slider.defaults = {
	min: 0,
	max: 5,
	step: 1,
	animate: false,
	disabled: false,
	orientation: 'horizontal',
	range: false,
	mousewheel: true,
	connect: null,
	onSlide: function (value) {},
	onChange: function (value) {}
};

if($.fn.wl_Time) $.fn.wl_Time.defaults = {
	step: 5,
	timeformat: 24,
	roundtime: true,
	time: null,
	value: null,
	mousewheel: true,
	onDateChange: function (offset) {},
	onHourChange: function (offset) {},
	onChange: function (value) {}
};

if($.fn.wl_Valid) $.fn.wl_Valid.defaults = {
	errorClass: 'error',
	instant: true,
	regex: /.*/,
	minLength: 0,
	onChange: function ($this, value) {},
	onError: function ($this, value) {}
};

if($.fn.wl_Mail) $.fn.wl_Mail.defaults = {
	regex: /^([\w-]+(?:\.[\w-]+)*)\@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$|(\[?(\d{1,3}\.){3}\d{1,3}\]?)$/i,
	onChange: function (element, value) {
		element.val(value.toLowerCase());
	}
};

if($.fn.wl_URL) $.fn.wl_URL.defaults = {
	regex: /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w]))*\.+(([\w#!:.?+=&%@!\-\/]))?/,
	instant: false,
	onChange: function (element, value) {
		if (!/^(ftp|http|https):\/\//.test(value)) element.val('http://' + value).trigger('change.wl_Valid');
	}
};

if($.fn.wl_Widget) $.fn.wl_Widget.defaults = {
	collapsed: false,
	load: null,
	reload: false,
	removeContent: true,
	collapseable: true,
	sortable: true,
	text: {
		loading: 'loading...',
		reload: 'reload',
		collapse: 'collapse widget',
		expand: 'expand widget'
	},
	onDrag: function () {},
	onDrop: function () {},
	onExpand: function () {},
	onCollapse: function () {}
};

