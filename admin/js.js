// Mobile functions
	var i = 0;
	
	function toggle_menu() {
		$("#head").toggle(100);
		
		if (i == 0) {
			$("#mobile-menu a").html("Skrýt navigaci &lsaquo;");
			$("#mobile-menu").css("background-color", "#0E618C");
			i += 1;
		} else {
			$("#mobile-menu a").html("Zobrazit navigaci &rsaquo;");
			$("#mobile-menu").css("background-color", "#1E8BC3");
			i -= 1;
		}
	}

// ------------


$(document).ready(function() {
	var path = window.location.pathname;
	var p = path.split("/")[2];
	var obj = $('nav a[href$="' + p + '"]').parent();
	
	$(window).keypress(function(event) {
		if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
		$("input[type=submit]").click();
		event.preventDefault();
		return false;
	});
	
	obj.hover(  
		function () {
			$(this).css("background-color", "#479047");
			$(this).children().css("color", "#fff");
		}, 
		function () {
			$(this).css("background-color", "#3D833D");
	});
	
	obj.css("background-color", "#3D833D");
	
	$("#error p").append('<span class="img-after-close">&#10006;</span>');
	$("#done p").append('<span class="img-after-close">&#10006;</span>');
	
	$(".img-after-close").click(function() {
		$(this).parent().fadeOut(400);
	});
	
	$("textarea").keydown(function(e) {
		if(e.keyCode === 9) { 
			var start = this.selectionStart;
			var end = this.selectionEnd;

			var $this = $(this);
			var value = $this.val();

			$this.val(value.substring(0, start)
						+ "\t"
						+ value.substring(end));

			this.selectionStart = this.selectionEnd = start + 1;
			e.preventDefault();
		}
	});
	
	responsive();
});

function responsive() {
	var width = $(window).width();
	var nav = $("nav");
	
	if (width < 950) {
		$(".res_close").hide();
	}
	
	if (width < 700) {
		$(".header").hide();
		$(".navigation").show();
		
		nav.hide();
		nav.css("position", "absolute");
		nav.css("z-index", "100000");
		
		$("footer").hide();
		$("#content h1").css("left", "0");
		$("body").css("padding-left", "0");
		
		$(".navigation").click(function() {
			nav.toggle(100);
		});
	}
}

function CloseDialog() {
    $("#dialog-bg").hide();
    $("#dialog").hide();
}

function articleDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete článek smazat?</h1><p>Článek bude nenávratně odstraněn, skutečně si přejete\n\
        článek smazat? Pro odstranění stiskněte tlačítko \n\
        <em>Odstranit</em>.</p><form method='POST'><input type='hidden' name='article_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='submit' value='Odstranit' name='article_delete' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function contentDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete prvek smazat?</h1><p>Pokračujte stisknutím tlačítka \n\
        <em>Odstranit</em>.</p><form method='POST'><input type='hidden' name='content_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='submit' value='Odstranit' name='content_delete' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function commentDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete komentář smazat?</h1><p>Komentář bude nenávratně odstraněn, přejete si pokračovat?\n\
        </p><form method='POST'><input type='hidden' name='comment_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='submit' value='Odstranit' name='comment_delete' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function menuItemDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete položku odstranit?</h1><p>Přejete si pokračovat?\n\
        </p><form method='POST'><input type='hidden' name='menu_item_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='submit' value='Odstranit' name='menu_item_delete' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function commentSubDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete komentář označit jako nevhodný?</h1><p>Text komentáře bude nenávratně upraven\n\
        na varující text definovaný v nastavení webu.</p><form method='POST'><input type='hidden' \n\
        name='comment_id' value='" + id + "' /><input type='submit' value='Provést operaci' name='comment_subdelete' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function userRole(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Zvolte, jakou roli chcete uživateli udělit.</h1>\n\
        <form method='POST'>\n\
        <div style='margin:10px;'><input type='hidden' name='user_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='radio' name='role' value='2' checked> Uživatel <br />\n\
        <input type='radio' name='role' value='1'> Redaktor <br />\n\
        <input type='radio' name='role' value='0'> Administrátor <br /></div>\n\
        <input type='submit' value='Upravit roli' name='user_role' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function userDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete uživatelský účet smazat?</h1><p>Účet bude nenávratně odstraněn, přejete si pokračovat?\n\
        </em></p><form method='POST'><input type='hidden' name='user_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='submit' value='Odstranit' name='user_delete' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function categoryDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete kategorii smazat?</h1><p>Kategorii bude nenávratně odstraněna a s ní i \n\
        všechny vazby na články. Přejete si pokračovat?</p><form method='POST'>\n\
        <input type='hidden' name='category_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='submit' value='Odstranit' name='category_delete' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function pageDelete(id, token) {
    $("#dialog-bg").show();
    $("#dialog").html(
        "<h1>Opravdu chcete stránku smazat?</h1><p>Stránka bude nenávratně odstraněna. \n\
        Přejete si pokračovat?</p><form method='POST'>\n\
        <input type='hidden' name='page_id' value='" + id + "' />\n\
        <input type='hidden' name='token' value='" + token + "' />\n\
        <input type='submit' value='Odstranit' name='page_delete' />\n\
        <input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
    );
    $("#dialog").show();
}

function changeImg() {
    $('#admin_img_icon').attr('src', ('./' + $("input[name=icon]").val()));
}

function set_content(page) {
	$("#dynamic-content").load("./admin/" + page);
}
