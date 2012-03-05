$(document).ready(function() {
	
	/*$("body").live('pageshow',function(event, ui){
		//Find the active page based on the location hash
		var activePage = location.hash;
		if(activePage.substr(0, 1) == '#'){
			activePage = activePage.replace("#/", '');
		}else if(activePage == ''){
			activePage = 'home';
		}
		
		//Add active marker to this page
		$("#mainmenu a").each(function(i, val){	
			//alert($(this).attr("href")+"/"+activePage);
			if($(this).attr("href") == "/"+activePage){
				$(this).addClass("active");
			}
		});
		
	});
	
	//Get all menuoptions in var
	var menuButtons = $("#mainmenu").find("a");
		
	//Add swipe event to body
	$("body").live('swipeleft swiperight',function(event){
			
		if (event.type == "swipeleft") {
			if($("#mainmenu a.active").attr("href") != $(menuButtons[(menuButtons.length-1)]).attr("href")){
				$("#mainmenu a.active").removeClass("active").next().addClass("active");
				$.mobile.changePage($("#mainmenu a.active").attr('href'));
			}else{
				$("#mainmenu a.active").removeClass("active");
				$(menuButtons[0]).addClass("active");
				$.mobile.changePage($(menuButtons[0]).attr('href'));
			}
		}
		if (event.type == "swiperight") {
			if($("#mainmenu a.active").attr("href") != $(menuButtons[0]).attr("href")){
				$("#mainmenu a.active").removeClass("active").prev().addClass("active");
				$.mobile.changePage($("#mainmenu a.active").attr('href'));
			}else{
				$("#mainmenu a.active").removeClass("active");
				$(menuButtons[(menuButtons.length-1)]).addClass("active");
				$.mobile.changePage($(menuButtons[(menuButtons.length-1)]).attr('href'));
			}
		}
	});*/
	 
});