function clickme() {
	alert("You have clicked on me");
}
function navMan(URL) {
	window.location = URL;
}
function removeFromCart(id,URL) {
	$.ajax({
		url: 'functions.php?action=removeFromCart',
		dataType: 'html',
		type: 'POST',
		data: {id},
		success: function(datos)
		{
			alert(datos);
			window.location = URL;
		}
	});
}
function addToCart(id,showAlert = true) {	
	let qty = document.getElementById("fldQTY-"+id).value;
	let name = document.getElementById("fldName-"+id) ? document.getElementById("fldName-"+id).value : '';
	$.ajax({
		url: 'functions.php?action=addToCart',
		dataType: 'html',
		type: 'POST',
		data: {id,qty,name},
		success: function(datos)
		{
			if(showAlert)
				alert(datos);
		}

	});
}
function setTotal(total) {
	$.ajax({
		url: 'functions.php?action=setTotal',
		dataType: 'html',
		type: 'POST',
		data: {total},
		success: function(datos)
		{
			//
		}

	});
}
function do_login() {
	document.getElementById("frmLogin").submit();
}
jQuery.fn.center = function (container) {
	this.css("position","absolute");
	if(container) {
		this.css("top", Math.max(0, (($(container).height() - $(this).outerHeight()) / 2) +
		$(container).scrollTop()) + "px");
		this.css("left", Math.max(0, (($(container).width() - $(this).outerWidth()) / 2) +
		$(container).scrollLeft()) + "px");
	} else {
		this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
		$(window).scrollTop()) + "px");
		this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
		$(window).scrollLeft()) + "px");
	}
	return this;
};
jQuery.fn.cs_draggable = function(Prams) {
	var handleVal=true;
	var handle=false;
	var container = "window";
	var jObj = JSON.stringify(Prams);
	if(jObj) {
		JSON.parse(jObj, function (k, v) {
			if (k == "handle") { handleVal = v; }
			if (k == "container") { container = v; }
		});
	}
	if(handleVal == true) { handle = ".cs-gen-dialog-header"; }
	else if(handle == false) {
		if(handleVal) {
			handle = handleVal;
		} else {
			handle = false;
		}
	}
	this.draggable({
		handle      : handle,
		containment : container,
		opacity     : 0.50,
		scroll      : false
	}).css({});
	$(handle).css({
		cursor : "move"
	});
};
$(document).ready(function(){
	$(".menu-item").each((key,element) => {
		if(window.location.href.includes(element.dataset.ref))
		$(element).addClass("active");
		else
		$(element).removeClass("active");
	});	
});