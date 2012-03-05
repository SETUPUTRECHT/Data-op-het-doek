$(document).ready(function(){

getLog();
	
});

function getLog(){

$.post("/ajx/", { action : "getLog" },
function(request){
	if(request.succes){
		
		$("#logprogress").html(request.loglist);
		$("#logprogress").listview("refresh");
		
		setInterval(function(){ getLog(); }, 3000);
	}
}, "json");

}