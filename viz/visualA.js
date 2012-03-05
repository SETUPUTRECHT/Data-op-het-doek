function visualA() {
	var data = [];
	for(i=0;i<22;i++) {
		data.push({"x":Math.random(), "y":Math.random()});
	}

	var h =1000;
	// vis = d3.select("body").append("svg:svg").attr("width", screen.width).attr("height",screen.innerHeight);

	var x = d3.scale.linear().domain([0,1]).range([screen.width / 2 -400, screen.width/2 + 400]),
	y = d3.scale.linear().domain([0,1]).range([0,h]),
	r = d3.scale.linear().domain([0,1]).range([5,10]),
	c = d3.scale.linear().domain([0,1]).range(["hsl(250, 50%, 50%)", "hsl(350, 100%, 50%)"]).interpolate(d3.interpolateHsl);
	vis.selectAll("circle")
	.data(data)
	.enter().append("svg:circle")
	.attr("cx", function(d) {
		return x(d.x)
	})
	.attr("cy", function(d) {
		return y(d.y)
	})
	.attr("fill", "#db0074")
	.attr("stroke", "#db0074")
	.attr("stroke-width", "0")
	.attr("r", function() {
		return r(Math.random())
	})
	.attr("class","vierkant");

	vis.selectAll("circle").transition()
	.duration(750)
	.delay( function(d, i) {
		return i * 100;
	})
	.attr("stroke", "#e29cc1")
	.attr("stroke-width", "8");

	vis.selectAll("circle").transition()
	.duration(750)
	.delay( function(d, i) {
		return i * 400;
	})
	.attr("stroke", "#db0074")
	.attr("stroke-width", "0");

}