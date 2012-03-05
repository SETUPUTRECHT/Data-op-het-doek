//Form validator	
$.validator.setDefaults({
	submitHandler: function(form) { 
		console.log("Validation passed, sending form...");
		
		//Check if tinyMCE is in the form
		/*if(tinyMCE.activeEditor.isDirty()){
			console.log("Transferring tinyMCE content since it's changed...");
		}*/
		
		form.submit();
	}
});

//On document loaded
$(document).ready(function(){
	
	var fv_formInputs = $("form.validateme :input").filter(":visible");
	var fv_buttons = $("form.validateme :input").filter(":submit");
	
	console.log("Found "+(fv_formInputs.length - fv_buttons.length)+" fields in current form..");
	
	fv_formInputs.each(function(i){
		if(!$(this).is(":submit")){
			console.log((i+1)+": "+$(this).attr("name"));
		}
	});
	
	// validate signup form on keyup and submit
	$("form.validateme").validate({
		rules: {
			stoelnummer: {
			required: true,
			range: [1, 81]
			},
			rijnummer: {
			required: true,
			range: [1, 8]
			}
		}
	});
	
});