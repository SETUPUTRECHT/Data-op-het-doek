var activePage = 'home';
$(document).bind("mobileinit", function () {

    // Navigation
    $.mobile.page.prototype.options.backBtnText = "Terug";
    $.mobile.page.prototype.options.addBackBtn      = false;
    $.mobile.page.prototype.options.backBtnTheme    = "d";

    // Page
    $.mobile.page.prototype.options.headerTheme = "s";  // Page header only
    $.mobile.page.prototype.options.contentTheme = "s";
    $.mobile.page.prototype.options.footerTheme = "s";

    // Listviews
    $.mobile.listview.prototype.options.headerTheme = "a";  // Header for nested lists
    $.mobile.listview.prototype.options.theme = "c";  // List items / content
    $.mobile.listview.prototype.options.dividerTheme    = "d";  // List divider

    $.mobile.listview.prototype.options.splitTheme   = "c";
    $.mobile.listview.prototype.options.countTheme   = "c";
    $.mobile.listview.prototype.options.filterTheme = "c";
    $.mobile.listview.prototype.options.filterPlaceholder = "Filter data...";
});

$("body").live('pageshow',function(event, ui){
	//setLoading("Laden...");
	//setTimeout(function(){ $.mobile.pageLoading(); },2500);
	
	activePage = window.location.pathname;
	if(activePage.substr(0, 1) == '/'){
		activePage = activePage.replace("/", '');
	}
	if(activePage == ''){
		activePage = 'home';
	}
	
	if(activePage == 'vote'){
		setLoading("Processing vote");
	}else{
		setLoading("Loading");
	}
	//console.log(activePage);
	
	barRating();
	//starRating();
	//sliderRating();
});

/*$(document).bind("pagecreate", function(event, ui) {
	$('#slider').siblings('.ui-slider').bind('tap', function(event, ui){ 
		makeAjaxChange($(this).siblings('input')); 
	}); 
	
	$('#slider').siblings('.ui-slider a').bind('taphold', function(event, ui){
		makeAjaxChange($(this).parent().siblings('input')); 
	});
});*/

function setLoading(msg){
	$.mobile.loadingMessage = msg;
}

function sliderRating(){
	console.log('slidrssss');
	//console.log( $(".slider").data("events") );
}

function makeAjaxChange( elem ) { 
	alert(elem.val()); 
}

function barRating(){

	$("div.rating").live('taphold', function(e){
		//e.stopImmediatePropagation();
		//return false;
		//console.log(print_r(this, true));
	});

	$("a.bar").click(function(e){
		//Zorg dat a href actie wordt gestopt
		e.preventDefault();
		
		//Zet geklikte element in 'clicked'
		var clicked = $(this);
		
		//Rating
		var classArr = clicked.attr("class").split(" ");
		var rating = classArr[1];
		
		//projectID
		classArr = clicked.parent().parent().attr("class").split(" ");
		var projectID = classArr[1];
		
		//Category
		classArr = clicked.parent().attr("class").split(" ");
		var category = classArr[1];
		
		//alert("Project="+projectID+" Rating="+rating);
		//Do vote for user
		doVote(clicked, rating, projectID, category);
		
	});
}

function starRating(){
	$("a.star").click(function(e){
		//Zorg dat a href actie wordt gestopt
		e.preventDefault();
		
		//Zet geklikte element in 'clicked'
		var clicked = $(this);
		
		//Rating
		var classArr = clicked.attr("class").split(" ");
		var rating = classArr[1];
		
		//projectID
		classArr = clicked.parent().parent().attr("class").split(" ");
		var projectID = classArr[1];
		
		//Category
		classArr = clicked.parent().attr("class").split(" ");
		var category = classArr[1];
		
		//alert("Project="+projectID+" Rating="+rating);
		//Do vote for user
		doVote(clicked, rating, projectID, category);
		
	});
}

function doVote(clicked, rating, projectID, category){

$.mobile.pageLoading();

$.post("/ajx/", { action : "doVote", rating : rating, projectID : projectID, category : category },
function(request){
	if(request.succes){
		//alert('found ajax connection :D');
		$.mobile.pageLoading(true);
		//alert(request.hash);
		//Remove class active en loop door alle sterretjes heen
		clicked.parent().find("a").removeClass("active").each(function(i, val){
			//console.log($(this).attr("class")+" : "+clicked.attr("class"));
			if($(this).attr("class") == clicked.attr("class")){
				$(this).addClass("active");
				return false;
			}else{
				$(this).addClass("active");
			}
			//console.log($(this).attr("class")+" : "+clicked.attr("class"));
		});
		
	}else{
		if(isset(request.err.msg)){
			var errMsg = request.err.msg;
		}else{
			var errMsg = "Unknown error..";
		}
		
		alert('ERROR: '+errMsg);
	}
}, "json");

}

/* PHP JS */

function isset () {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: FremyCompany
    // +   improved by: Onno Marsman
    // +   improved by: Rafal Kukawski
    // *     example 1: isset( undefined, true);
    // *     returns 1: false
    // *     example 2: isset( 'Kevin van Zonneveld' );
    // *     returns 2: true
    var a = arguments,
        l = a.length,
        i = 0,
        undef;

    if (l === 0) {
        throw new Error('Empty isset');
    }

    while (i !== l) {
        if (a[i] === undef || a[i] === null) {
            return false;
        }
        i++;
    }
    return true;
}

function print_r (array, return_val) {
    // http://kevin.vanzonneveld.net
    // +   original by: Michael White (http://getsprink.com)
    // +   improved by: Ben Bryan
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +      improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: echo
    // *     example 1: print_r(1, true);
    // *     returns 1: 1
    var output = '',
        pad_char = ' ',
        pad_val = 4,
        d = this.window.document,
        getFuncName = function (fn) {
            var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
            if (!name) {
                return '(Anonymous)';
            }
            return name[1];
        },
        repeat_char = function (len, pad_char) {
            var str = '';
            for (var i = 0; i < len; i++) {
                str += pad_char;
            }
            return str;
        },
        formatArray = function (obj, cur_depth, pad_val, pad_char) {
            if (cur_depth > 0) {
                cur_depth++;
            }

            var base_pad = repeat_char(pad_val * cur_depth, pad_char);
            var thick_pad = repeat_char(pad_val * (cur_depth + 1), pad_char);
            var str = '';

            if (typeof obj === 'object' && obj !== null && obj.constructor && getFuncName(obj.constructor) !== 'PHPJS_Resource') {
                str += 'Array\n' + base_pad + '(\n';
                for (var key in obj) {
                    if (Object.prototype.toString.call(obj[key]) === '[object Array]') {
                        str += thick_pad + '[' + key + '] => ' + formatArray(obj[key], cur_depth + 1, pad_val, pad_char);
                    }
                    else {
                        str += thick_pad + '[' + key + '] => ' + obj[key] + '\n';
                    }
                }
                str += base_pad + ')\n';
            }
            else if (obj === null || obj === undefined) {
                str = '';
            }
            else { // for our "resource" class
                str = obj.toString();
            }

            return str;
        };

    output = formatArray(array, 0, pad_val, pad_char);

    if (return_val !== true) {
        if (d.body) {
            this.echo(output);
        }
        else {
            try {
                d = XULDocument; // We're in XUL, so appending as plain text won't work; trigger an error out of XUL
                this.echo('<pre xmlns="http://www.w3.org/1999/xhtml" style="white-space:pre;">' + output + '</pre>');
            } catch (e) {
                this.echo(output); // Outputting as plain text may work in some plain XML
            }
        }
        return true;
    }
    return output;
}
