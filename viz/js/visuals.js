var visualCount = 6;
var currentVisual = 0;
var repeat;

function showViaKeypress(count) {
	//d3.select("body").remove("svg");
	preVisual = currentVisual;

	if(count ==-1) {
		if(currentVisual >0) {
			currentVisual -= 1;
		} else {
			currentVisual = visualCount-1;
		}
	} else if(count ==1) {
		if(currentVisual >=visualCount-1) {
			currentVisual =0;
		} else {
			currentVisual++;
		}
	}

	this.visual0 = function() {
		transitionOutChairs();
		startVisualC();
	}
	this.visual1 = function() {
		if(preVisual < currentVisual) {
			stopVisualC();
			visualB("avgVoteLooks");
		} else if(preVisual > currentVisual) {
			transitionBetweenChairs("avgVoteLooks");
		}

	}
	this.visual2 = function() {
		transitionBetweenChairs("avgVoteInfo");
	}
	this.visual3 = function() {
		transitionBetweenChairs("lowVoteInfo");
	}
	this.visual4 = function() {
		transitionBetweenChairs("highVoteInfo");
	}
	this.visual5 = function() {
		transitionBetweenChairs("lowVoteLooks");
	}
	this.visual6 = function() {
		if(preVisual ==0) {
			stopVisualC();
			visualB("lowVoteLooks");
		} else if(preVisual < currentVisual) {
			transitionBetweenChairs("lowVoteLooks");
		}
		
	}
	
	this["visual"+currentVisual]();
}



//Stoelen
function visualB(type) {
	var x = d3.scale.linear().domain([0,8]).range([screen.width / 2 -400, screen.width/2 + 400]),
	y = d3.scale.linear().domain([0,10]).range([screen.height/2 + 400, screen.height / 2 -400]),
	r = d3.scale.linear().domain([0,20]).range([1,15]);
	var data = new Array(0,1,2,3,4,5,6,7,8,9);

	vis.selectAll("lineHorChair")
	.data(data)
	.enter().append("svg:line")
	.attr("x1", function() {
		return x(0)
	})
	.attr("x2", function() {
		return x(0)
	})
	.attr("y1", function(d) {
		return y(d)
	})
	.attr("y2", function(d) {
		return y(d)
	})
	.attr("class","lineHorChair")
	.attr("stroke", "#999999");

	vis.selectAll(".lineHorChair").transition()
	.duration(750)
	.delay( function(d, i) {
		return i * 50;
	})
	.attr("x2", function() {
		return x(9)
	});
	
	vis.selectAll("text.descriptionChair")
		.data(data)
		.enter().append("svg:text")
		.attr("x", function() {
			return x(0)-50
		})
		.attr("y", function(d) {
			return y(d)
		})
		.attr("text-anchor", "middle")
		.attr("class","descriptionChair")
		.attr("fill", "#7f7f7f")
		.text( function(d,i) {
			return (d*8)+1;
		});
	
	vis.selectAll("text.descriptionChair2")
		.data(data)
		.enter().append("svg:text")
		.attr("x", function() {
			return x(9)+50
		})
		.attr("y", function(d) {
			return y(d)
		})
		.attr("text-anchor", "middle")
		.attr("class","descriptionChair2")
		.attr("fill", "#7f7f7f")
		.text( function(d,i) {
			return (d*8)+8;
		});
		
		//Stoelen
	vis.selectAll(".circleChair")
	.data(voters)
	.enter().append("svg:circle")
	.attr("cx", function(d) {
		//TODO uitrekenen adv van stoelnummer 1-8 9-16
		var chair = (d.chair-1)%8
		 var chairx = x(chair);
		if(chair > 3){chairx+=200;}
		return chairx;
		//return x(d.chair)
	})
	.attr("cy", function(d) {
		// console.log(d.row);
		var chairy = Math.floor((d.chair-1)/8)
		return y(chairy);
		//return y(d.row)
	})
	.attr("fill", "#db0074")
	.attr("r", function(d) {
		// return r(d.highVoteLooks)
		console.log("type"+ type);
		return r(0)
	}).attr("class","circleChair");
	
	//Stoel animatie
	vis.selectAll(".circleChair")
	.data(voters).transition()
	.duration(750)
	.delay( function(d, i) {
		return i * 50;
	})
	.attr("r", function(d) {
		// return r(d.highVoteLooks)
		console.log("type"+ type);
		return r(d[type])
	});
	
	vis.append("svg:text")
	.attr("class", "header")
	.attr("x", function(d) {
		return x(6)
	})
	.attr("y", function(d) {
		// return y(8.5)
		return y(10.5)
	}).attr("fill", "#7f7f7f")
	.attr("text-anchor", "middle")
	.text( 
		//TODO: Schrijf hier een algemene functie voor
		function(d) {
			return chairText(type); 	
	});
}

function transitionOutChairs() {
	var x = d3.scale.linear().domain([0,8]).range([screen.width / 2 -400, screen.width/2 + 400]),
	y = d3.scale.linear().domain([0,8]).range([screen.height/2 + 400, screen.height / 2 -400]),
	r = d3.scale.linear().domain([0,20]).range([1,20]);
	var data = new Array(0,1,2,3,4,5,6,7,8,9);
	
	//console.log("outChairs");
	//Stoelen
	vis.selectAll(".circleChair").data(voters).transition()
	.duration(250)
	.delay( function(d, i) {
		return i * 30;
	})
	.attr("r", function(d) {
		return r(0)
	})
	.remove();
	
	//Lijnen
	vis.selectAll(".lineHorChair").transition()
	.duration(250)
	.attr("x2", function() {
		return x(0)
	}).remove();
	
	//Text
	vis.selectAll("text.descriptionChair").remove();
	
	//Header
	vis.selectAll("text.header").remove();
}

function transitionBetweenChairs(type) {
	//console.log("betweenChairs");
	var x = d3.scale.linear().domain([0,12]).range([screen.width / 2 -400, screen.width/2 + 400]),
	y = d3.scale.linear().domain([0,8]).range([screen.height/2 + 400, screen.height / 2 -400]),
	r = d3.scale.linear().domain([0,20]).range([1,20]);
	var data = new Array(1,2,3,4,5,6,7,8);

	vis.selectAll(".circleChair").data(voters).transition()
	.duration(250)
	.delay( function(d, i) {
		return i * 50;
	})
	.attr("r", function(d) {
		console.log("type"+ type);
		return r(d[type]);
	});
	
	//Header aanpassen
	vis.selectAll(".header").transition()
	.duration(250)
	.text( 
		//TODO: Schrijf hier een algemene functie voor
		function(d) {
			return chairText(type); 	
	});
}

// kruis van informatief en aantrekkelijk
function startVisualC() {
	//console.log("Start C");
	var x = d3.scale.linear().domain([0,20]).range([screen.width / 2 -400, screen.width/2 + 400]),
	y = d3.scale.linear().domain([0,20]).range([screen.height/2 + 400, screen.height / 2 -400]);

	vis.append("svg:line")
	.attr("x1", function(d) {
		return x(10)
	})
	.attr("x2", function(d) {
		return x(10)
	})
	.attr("y1", function(d) {
		return y(10)
	})
	.attr("y2", function(d) {
		return y(10)
	})
	.attr("class","lineHor")
	.attr("stroke", "#999999");

	//anitmatie
	vis.selectAll(".lineHor").transition()
	.duration(750)
	.attr("x1", function(d) {
		return x(10)
	})
	.attr("x2", function(d) {
		return x(10)
	})
	.attr("y1", function(d) {
		return y(0)
	})
	.attr("y2", function(d) {
		return y(20)
	});
	
	//lijn verticaal
	vis.append("svg:line")
	.attr("x1", function(d) {
		return x(10)
	})
	.attr("x2", function(d) {
		return x(10)
	})
	.attr("y1", function(d) {
		return y(10)
	})
	.attr("y2", function(d) {
		return y(10)
	})
	.attr("class","lineVer")
	.attr("stroke", "#999999");

	//animatie
	vis.selectAll(".lineVer").transition()
	.duration(750)
	.attr("x1", function(d) {
		return x(0)
	})
	.attr("x2", function(d) {
		return x(20)
	})
	.attr("y1", function(d) {
		return y(10)
	})
	.attr("y2", function(d) {
		return y(10)
	});
	
	vis.selectAll(".circleVis")
	.data(visualisations)
	.enter().append("svg:circle")
	.attr("cx", function(d) {
		return x(d.avgVoteLooks)
	})
	.attr("cy", function(d) {
		return y(d.avgVoteInfo)
	})
	.attr("r", function(d,i){
		if(i==0){return 8;}else{
			return 0;
		}
	})
	.attr("id",function(d){
		return d.name;
	})
	.attr("class","circleVis")
	.attr("fill", "#db0074")
	.attr("stroke", "#db0074")
	.attr("stroke-width", "0");

	
	
	vis.selectAll(".circleVis").transition()
	.duration(200)
	.delay( function(d, i) {
		return i * 150;
	})
	.attr("r", 8);
	
	//labels
	vis.selectAll("text.label")
	.data(visualisations)
	.enter()
	.append("svg:text")
	.attr("class", "label")
	.attr("x", function(d) {
		return x(d.avgVoteLooks)+25
	})
	.attr("y", function(d) {
		return y(d.avgVoteInfo)+5
	}).attr("fill", "#000000")
	.attr("fill-opacity", 0)
	.attr("text-anchor", "right")
	.text( function(d) {
		return d.name;
	});
	

	//descriptions
	vis.append("svg:text")
	.attr("class", "description")
	.attr("x", function(d) {
		return x(0)-200
	})
	.attr("y", function(d) {
		return y(10)+5
	}).attr("fill", "#7f7f7f")
	.attr("text-anchor", "right")
	.text( function(d) {
		return "aantrekkelijkheid";
	});
	vis.append("svg:text")
	.attr("class", "description")
	.attr("x", function(d) {
		return x(10)
	})
	.attr("y", function(d) {
		return y(20)-5
	}).attr("fill", "#7f7f7f")
	.attr("text-anchor", "middle")
	.text( function(d) {
		return "informatief";
	});
	
	
	
	
	 redraw();
	 repeat = setInterval ( "redraw()", visualisations.length * 1500 );
  
    
    
}

function stopVisualC() {
	clearInterval (repeat);
	//console.log("stop C");
	var x = d3.scale.linear().domain([0,20]).range([screen.width / 2 -400, screen.width/2 + 400]),
	y = d3.scale.linear().domain([0,20]).range([screen.height/2 + 400, screen.height / 2 -400]);
	//anitmatie
	vis.selectAll(".lineHor").transition()
	.duration(150)
	.attr("x1", function(d) {
		return x(10)
	})
	.attr("x2", function(d) {
		return x(10)
	})
	.attr("y1", function(d) {
		return y(10)
	})
	.attr("y2", function(d) {
		return y(10)
	})
	.remove();

	vis.selectAll(".lineVer").transition()
	.duration(150)
	.attr("x1", function(d) {
		return x(0)
	})
	.attr("x2", function(d) {
		return x(20)
	})
	.attr("y1", function(d) {
		return y(10)
	})
	.attr("y2", function(d) {
		return y(10)
	})
	.remove();

	vis.selectAll(".circleVis").transition()
	.duration(450)
	.delay( function(d, i) {
		return i * 50;
	})
	.attr("cy", function(d) {
		return y(-20)
	})
	.remove();
	vis.selectAll(".description").remove();
	vis.selectAll(".label").remove();
}

function chairText(type) {
		if(type=="avgVoteLooks"){
			return "Gemiddeld gegeven voor uiterlijk";
		} else if(type=="avgVoteInfo"){
			return "Gemiddeld gegeven voor informatie";
		}else if(type=="highVoteLooks"){
			return "Hoogst gegeven stem voor uiterlijk";
		} else if(type=="lowVoteLooks"){
			return "Laagst gegeven stem voor uiterlijk";
		}else if(type=="highVoteInfo"){
			return "Hoogst gegeven stem voor informatief";
		} else if(type=="lowVoteInfo"){
			return "Laagst gegeven stem voor informatief";
		}
		}
		
function redraw(){
	
	var x = d3.scale.linear().domain([0,20]).range([screen.width / 2 -400, screen.width/2 + 400]),
	y = d3.scale.linear().domain([0,20]).range([screen.height/2 + 400, screen.height / 2 -400]);
	console.log("redraw");
	
	vis.selectAll(".label").transition()
	.duration(450)
	.delay( function(d, i) {
		return (i * 1250);
	})
	.attr("fill", "#000000")
	.attr("fill-opacity", 1);
	
	vis.selectAll(".label").transition()
	.duration(450)
	.delay( function(d, i) {
		return (i * 1250)+1050;
	})
	.attr("fill-opacity", 0);
	
	vis.selectAll(".circleVis").transition()
	.duration(450)
	.delay( function(d, i) {
		return i * 1250;
	})
	.attr("stroke", "#e29cc1")
	.attr("stroke-width", 8);
	
	
	vis.selectAll(".circleVis").transition()
	.duration(450)
	.delay( function(d, i) {
		return (i * 1250)+1050;
	})
	.attr("stroke", "#db0074")
	.attr("stroke-width", 0);
	
	
	
}
 