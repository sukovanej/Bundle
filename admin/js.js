$(document).ready(function() {
	var path = window.location.pathname;
	var p = path.split("/")[2];
	var obj = $('.nav a[href$="' + p + '"]').parent().addClass("active");

	$(window).keypress(function(event) {
		if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
		$("input[type=submit]").click();
		event.preventDefault();
		return false;
	});

	$(".close").click(function() {
		$(this).parent().fadeOut(400);
	});
});

function changeImg() {
    $('#admin_img_icon').attr('src', ($("input[name=icon]").val()));
}

function set_content(page) {
	$("#dynamic-content").load("./admin/" + page);
}
