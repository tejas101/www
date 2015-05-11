/* /////// global vars \\\\\\\ */

var popData = []; // data for pop barcharts and map, json load
var popDataSliced = [];
var showSomeData = d3.select('body').append('div').attr('class', 'show_data'); // tooltip div
var currentMapData = 0; // keep track of selected dataset on map
var transitioning; // for treemap zooming

// viz dimensions
var margin = {top: 20, right: 0, bottom: 20, left: 40},
    width = 960, // html container max width full screen
    majorWidth = 670, // viz blocks floated right of minorWidth blocks
    minorWidth = 290, // viz blocks floated left of majorWidth blocks
    shortHeight = 200, // thin viz blocks, e.g. population
    height = 500 - margin.top; // tallest viz blocks, e.g. econ indicators

// formats
var perAxis = d3.format("%"),
    bigNumb=  d3.format(",d"),
    perTree = d3.format(".1%");

// layouts
var treemap = d3.layout.treemap()
    .children(function(d, depth) { return depth ? null : d._children; })
    .sort(function(a, b) { return a.value - b.value; })
    .ratio(height / width * 0.5 * (1 + Math.sqrt(5)))
    .round(false);

// scales and color palettes
var choropleth = d3.scale.quantile(5) // map choropleth colors; set the domain below
    .range(['#DEB4B4', '#D98C8B', '#D56563', '#D13E3B', '#CD1713']);
var yScaleShort = d3.scale.linear().range([shortHeight - margin.bottom, 0]); // for short charts

var x = d3.scale.linear() // for scaling treemaps
    .domain([0, width])
    .range([0, width]);

var y = d3.scale.linear() // for scaling treemaps
    .domain([0, height])
    .range([0, height]);

// axis; glob for easy transitions
var yShortAxis = d3.svg.axis().scale(yScaleShort).orient('left').ticks(2).tickFormat(perAxis).tickSize(2,4);

// main viz handlers
var popSvg = d3.select("#pop_chart").append("svg") // bar chart container
    .attr("width", width)
    .attr("height", shortHeight)
  .append("g")
    .attr("transform", "translate(" + margin.left + ", " + "0)")
    .attr("class", "pop_container");
var popBars; // handler for bar chart updates

var indiaMap = d3.selectAll("svg#india_map g"); // SVG groups of paths
var maplegend = d3.select('#map_legend'); // pre-existing div
var mapkeys; // d3 generated li's

var rtreeSvg = d3.select("#rev_tree").append("svg") // for revenue tree
    .attr("width", width)
    .attr("height", height + margin.top)
  .append("g")
    .attr("transform", "translate(0," + margin.top + ")")
    .style("shape-rendering", "crispEdges");
var rtGrandparent = rtreeSvg.append("g")
    .attr("class", "grandparent");
rtGrandparent.append("rect")
    .attr("y", - margin.top)
    .attr("width", width)
    .attr("height", margin.top);
rtGrandparent.append("text")
    .attr("x", 2)
    .attr("y", 4 - margin.top)
    .attr("dy", ".75em");

var etreeSvg = d3.select("#exp_tree").append("svg") // for exp tree
    .attr("width", width)
    .attr("height", height + margin.top)
  .append("g")
    .attr("transform", "translate(0," + margin.top + ")")
    .style("shape-rendering", "crispEdges");
var etGrandparent = etreeSvg.append("g")
    .attr("class", "grandparent");
etGrandparent.append("rect")
    .attr("y", - margin.top)
    .attr("width", width)
    .attr("height", margin.top);
etGrandparent.append("text")
    .attr("x", 2)
    .attr("y", 4 - margin.top)
    .attr("dy", ".75em");

// create data loading messages for treemaps
var revLoad = rtreeSvg.append("g")
      .attr("transform", "translate(" + ((width/2) - 40) + "," + ((height/2) - 40) + ")");
revLoad.append("text")
      .text("Data loading...")
      .style("font-size", "15px");
revLoad.append("image")
      .attr("width", "100")
      .attr("height", "100")
      .attr("xlink:href", "img/data-loading.gif");

var expLoad = etreeSvg.append("g")
      .attr("transform", "translate(" + ((width/2) - 40) + "," + ((height/2) - 40) + ")");
expLoad.append("text")
      .text("Data loading...")
      .style("font-size", "15px");
expLoad.append("image")
      .attr("width", "100")
      .attr("height", "100")
      .attr("xlink:href", "img/data-loading.gif");


/* /////// bar chart and map visualizations \\\\\\\ */

// load dataset wrapper function
d3.json('popdata.json', function(data){

  // populate the data variable w/ object array
  popData = data.states;

  // turn off the data loading imagery
  d3.selectAll(".pop_loading").remove();

  // additional variables
  barWidth = (width - margin.left) / popData.length;

  /* /////// bar chart setup \\\\\\\ */

  // set domain on y scale for bar chart
  yScaleShort.domain([0, d3.max(popData, function(d){
    return d.decGrw;
  })]);

  // create, sort groups for the bar chart rect and x labels
  popBars = popSvg.selectAll(".pop_container g")
    .data(popData).enter()
      .append("g")
      .sort(function(a, b){
        return d3.descending(a.decGrw, b.decGrw);
      })
      .attr("transform", function(d, i){
        return "translate(" + i * barWidth + ",0)";
      });
  
  // make the bars
  popBars.append("rect")
    .style("fill", "#CD1713")
    .attr({
      'height' : function(d){
        return shortHeight - margin.bottom - yScaleShort(d.decGrw);
      },
      'y' : function(d){
        return yScaleShort(d.decGrw);
      },
      'width' : barWidth - 1
    })
    .on('mouseover', function(d){
      // tooltip gets turned on
      d3.select(this).style('fill', '#DDDDDD');
      showSomeData.style({
        'opacity' : 1,
        'left' : (function(){
          if (d3.event.clientX < (window.innerWidth/2)) { 
            return (d3.event.clientX + 30) + 'px'; 
          } else { 
            return (d3.event.clientX - 320) + 'px'; 
          }
        }),
        'top' : (function(){
          return (d3.event.clientY - 150) + 'px';
        })
      });
      showSomeData.append("h5").text( d.state );
      showSomeData.append("p").html( "Population <span>" + bigNumb(d.pop2011) + "</span> | Rank <span>" + d.rnkPop2011 + "</span>" );
      showSomeData.append("p").html( "Decadal Growth <span>" + perAxis(d.decGrw) + "</span> | Rank <span>" + d.rnkGrw + "</span>" );
      showSomeData.append("p").html( "Female <span>" + perAxis(d.pctFem) + "</span> | Rank <span>" + d.rnkFem + "</span>" );
      showSomeData.append("p").html( "Poverty <span>" + perAxis(d.pctBPL) + "</span> | Rank <span>" + d.rnkBPL + "</span>" );
      showSomeData.append("p").html( "Rural <span>" + perAxis(d.pctRur) + "</span> | Rank <span>" + d.rnkRur + "</span>" );      
    })
    .on('mouseout', function(d){
      showSomeData
        .style("opacity", 0)
        .selectAll("h5, p, span").remove();
      d3.select(this)
        .transition()
        .duration(50)
        .style('fill', '#CD1713');
    });
  
  // make the bar labels
  popBars.append("text")
    .attr("class", "axis x")
    .text(function(d){
      return d.st;
    })
    .attr({
      'y' : shortHeight - margin.bottom + 12,
      'x' : barWidth/2
    });

  // make bar chart's y axis
  popSvg.append("g")
    .attr("class", "axis y")
    .attr("transform", "translate(-2,0)")
    .call(yShortAxis);

  
  /* /////// the map setup \\\\\\\ */

  // bind the data to state svg shapes
  indiaMap.datum(function(d) { return {st: d3.select(this).attr("id")}; }) // dummy data to setup keying
    // overwrite dummy data with key function
    // http://stackoverflow.com/questions/26652777/d3-data-binding-array-of-objects-by-id-value-to-loaded-svg
    .data(popData, function(d){ return d.st; });

  // choropleth the map
  choropleth.domain( popData.map(function(d) { return d.capHdp; }) ); // set the choropleth domain
  indiaMap.style("fill", function(d){ if (d.capHdp === null) { return "#999"; } else { return choropleth(d.capHdp); } }); // now color the map

  // tooltips for map
  indiaMap.on('mouseover', function(d){
    swapMapColors(this);
    showSomeData // turn on tooltip
      .style({
        'opacity' : 1,
        'left' : (function(){
          return (d3.event.clientX + 5) + 'px'; 
        }),
        'top' : (function(){
          return (d3.event.clientY + 5) + 'px';
        })
      })
      .append("h5").attr("class", "map_data").html(function(){
        // what data shows depends on user data selection
        if (currentMapData == 0) {
          var holder;
          if (d.capHdp === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + bigNumb(Math.round(d.capHdp)) + "</span>"; }
        } else if (currentMapData == 1) {
          var holder;
          if (d.capDoc === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + bigNumb(Math.round(d.capDoc)) + "</span>"; }
        } else if (currentMapData == 2) {
          var holder;
          if (d.capTub === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + bigNumb(Math.round(d.capTub)) + "</span>"; }
        } else if (currentMapData == 3) {
          var holder;
          if (d.capChd === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + bigNumb(Math.round(d.capChd)) + "</span>"; }
        } else if (currentMapData == 4) {
          var holder;
          if (d.ratStd === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + bigNumb(Math.round(d.ratStd)) + "</span>"; }
        } else if (currentMapData == 5) {
          var holder;
          if (d.ratSpm === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + bigNumb(Math.round(d.ratSpm)) + "</span>"; }
        } else if (currentMapData == 6) {
          var holder;
          if (d.ratSup === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + bigNumb(Math.round(d.ratSup)) + "</span>"; }
        } else if (currentMapData == 7) {
          var holder;
          if (d.perLit === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perLit) + "</span>"; }
        } else if (currentMapData == 8) {
          var holder;
          if (d.perLru === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perLru) + "</span>"; }
        } else if (currentMapData == 9) {
          var holder;
          if (d.perLur === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perLur) + "</span>"; }
        } else if (currentMapData == 10) {
          var holder;
          if (d.perLcg === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perLcg) + "</span>"; }
        } else if (currentMapData == 11) {
          var holder;
          if (d.perRof === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perRof) + "</span>"; }
        } else if (currentMapData == 12) {
          var holder;
          if (d.perEle === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perEle) + "</span>"; }
        } else if (currentMapData == 13) {
          var holder;
          if (d.perWat === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perWat) + "</span>"; }
        } else if (currentMapData == 14) {
          var holder;
          if (d.perCar === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perCar) + "</span>"; }
        } else if (currentMapData == 15) {
          var holder;
          if (d.perBik === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perBik) + "</span>"; }
        } else if (currentMapData == 16) {
          var holder;
          if (d.perPho === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perPho) + "</span>"; }
        } else if (currentMapData == 17) {
          var holder;
          if (d.perCom === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perCom) + "</span>"; }
        } else if (currentMapData == 18) {
          var holder;
          if (d.perTel === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perTel) + "</span>"; }
        } else if (currentMapData == 19) {
          var holder;
          if (d.perBnk === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.perBnk) + "</span>"; }
        } else {
          var holder;
          if (d.capHdp === null) { return d.state + " <span>NA</span>"; } else { return d.state + " <span>" + perAxis(d.capHdp) + "</span>"; }
        }
      });
  })
  .on('mouseout', function(d){
    swapMapColors(this);
    showSomeData
      .style("opacity", 0)
      .selectAll("h5, span").remove();
  });

  // make the legend
  // txs: http://eyeseast.github.io/visible-data/2013/08/27/responsive-legends-with-d3/
  maplegend.append('h4').text('Dispensaries per 1000 people');
  maplegend.append('ul');

  mapkeys = maplegend.selectAll('li.qbox')
      .data(choropleth.range())
      .enter().append('li')
      .attr('class', 'qbox')
      .style('border-top-color', String)
      .text(function(d) {
          var r = choropleth.invertExtent(d);
          return bigNumb(Math.round(r[0]));
      }); 
}); // end data loading 

// update the barchart
function updatePopData(selectDataSet){  
  
  // rescale the data
  yScaleShort.domain([0, d3.max(popData, function(d){ return d[selectDataSet]; })]);

  // Select the section we want to apply our changes to
  popSvg.transition()
    .select(".y.axis")
      .duration(2000)
      .call(yShortAxis);
  popBars
    .sort(function(a, b){ return d3.descending(a[selectDataSet], b[selectDataSet]); })
    .transition()
    .delay(function(d, i){ return i * 5; })
    .duration(2000)
    .attr("transform", function(d, i){ return "translate(" + i * barWidth + ",0)"; });
  popBars.selectAll('rect')
    .transition()
    .delay(function(d, i){ return i * 5; })
    .duration(2000)
    .attr({
      'height' : function(d){ return shortHeight - margin.bottom - yScaleShort(d[selectDataSet]); },
      'y' : function(d){ return yScaleShort(d[selectDataSet]); }
    });

} // end updatepopData function

// update map based on user input
function updateMap(selectDataSet, label, tracker){

  currentMapData = tracker;

  // reset the chorpleth's domain
  choropleth.domain( popData.map(function(d) { return d[selectDataSet]; }) );

  // repaint the map
  indiaMap.transition()
    .duration(400)
    .style("fill", function(d){ 
      if (d[selectDataSet] === null) { return "#999"; } else { return choropleth(d[selectDataSet]); }
    });

  // change legend labels
  mapkeys.text(function(d) {
      var r = choropleth.invertExtent(d);
      if (tracker < 7) { return bigNumb(Math.round(r[0])); } else { return perAxis(r[0]); }      
  });

  // reset the map legend label
  d3.select('#map_legend h4').text(label);

} // end updateMap function decGrw

// map colorswapping function
var currentColor; // need globally accessible var
function swapMapColors(theShape){  
  if (theShape.style.fill != "rgb(221, 221, 221)") {
    currentColor = theShape.style.fill;
    theShape.style.fill = "rgb(221, 221, 221)";
  } else {
    theShape.style.fill = currentColor;
  }
} // end swapMapColors function


/* /////// treemap visualizations \\\\\\\ */

// revenue treemap
d3.json("india_states_income.json", function(root) {
  initialize(root);
  accumulate(root);
  layout(root);
  display(root);  

  revLoad.remove(); // turn off data loading imagery

  function display(d) {
    rtGrandparent
        .datum(d.parent)
        .on("click", transition)
      .select("text")
        .text("[ ZOOM OUT ] " + name(d));

    var g1 = rtreeSvg.insert("g", ".grandparent")
        .datum(d)
        .attr("class", "depth");

    var g = g1.selectAll("g")
        .data(d._children)
      .enter().append("g");

    g.filter(function(d) { return d._children; })
        .classed("children", true)
        .on("click", transition);

    g.selectAll(".child")
        .data(function(d) { return d._children || [d]; })
      .enter().append("rect")
        .attr("class", "child")
        .call(rect);

    g.append("rect")
        .attr("class", "parent")
        .call(rect)
      .on("mouseover", function(d){
        if (d._children) {
          showSomeData // turn on tooltip
            .style({
              'opacity' : 1,
              'left' : (function(event){
                if (d3.event.clientX < (window.innerWidth/2)) { 
                  return (d3.event.clientX + 30) + 'px'; 
                } else { 
                  return (d3.event.clientX - 320) + 'px'; 
                }
              }),
              'top' : (function(){
                return (d3.event.clientY + 20) + 'px';
              })
            })
            .append("p").attr("class", "map_data").html(function(){
              var showThis = "",
              theName;

              if (d.name == "AP" || d.name == "AR" || d.name == "AS" || d.name == "BR" || d.name == "CG" || d.name == "GA" || d.name == "GJ" || d.name == "HR" || d.name == "HP" || d.name == "JK" || d.name == "JH" || d.name == "KA" || d.name == "KL" || d.name == "MP" || d.name == "MH" || d.name == "MN" || d.name == "ML" || d.name == "MZ" || d.name == "NL" || d.name == "OR" || d.name == "PB" || d.name == "RJ" || d.name == "SK" || d.name == "TN" || d.name == "TR" || d.name == "UK" || d.name == "UP" || d.name == "WB" || d.name == "TN" || d.name == "TR" || d.name == "DL" || d.name == "PY") { 
                theName = d.state; 
              } else { 
                theName = d.name; 
              }
              
              for (i=0; i<d._children.length; i++) {
                // showThis += "<strong>" + d._children[i].name + ":</strong> " + bigNumb(Math.round(d._children[i].value)) + "<br />" ;
                showThis += "<p>" + d._children[i].name + " <span>" + perTree(d._children[i].value/d.value) + "</span></p>" ;
              }

              return "<strong>" + theName + "</strong><br /><span>Rs‚¹" + bigNumb(Math.round(d.value)) + "</span> (in millions) | <span>" + perTree(d.value/d.parent.value) + "</span> of total<hr /><p><em>Includes ...</em></p>" + showThis;

            });
          } else { return; }
      })
      .on('mouseout', function(d){
        showSomeData
          .style("opacity", 0)
          .selectAll("p, span").remove();
      });

    g.append("text")
        .attr("dy", ".75em")
        .text(function(d) { if (d._children) { return d.name; } else { return d.name + " Rs‚¹" + bigNumb(Math.round(d.value)) + " (in millions)"; } })
        .call(text);

    function transition(d) {
      if (transitioning || !d) return;
      transitioning = true;

      var g2 = display(d),
          t1 = g1.transition().duration(750),
          t2 = g2.transition().duration(750);

      // Update the domain only after entering new elements.
      x.domain([d.x, d.x + d.dx]);
      y.domain([d.y, d.y + d.dy]);

      // Enable anti-aliasing during the transition.
      rtreeSvg.style("shape-rendering", null);

      // Draw child nodes on top of parent nodes.
      rtreeSvg.selectAll(".depth").sort(function(a, b) { return a.depth - b.depth; });

      // Fade-in entering text.
      g2.selectAll("text").style("fill-opacity", 0);

      // Transition to the new view.
      t1.selectAll("text").call(text).style("fill-opacity", 0);
      t2.selectAll("text").call(text).style("fill-opacity", 1);
      t1.selectAll("rect").call(rect);
      t2.selectAll("rect").call(rect);

      // Remove the old node when the transition is finished.
      t1.remove().each("end", function() {
        rtreeSvg.style("shape-rendering", "crispEdges");
        transitioning = false;
      });
    }

    return g;
  }
}); // end loading of revenue data callback

// expenditures treemap
d3.json("india_states_expend.json", function(root) {
  initialize(root);
  accumulate(root);
  layout(root);
  display(root);  

  expLoad.remove(); // turn off data loading imagery

  function display(d) {
    etGrandparent
        .datum(d.parent)
        .on("click", transition)
      .select("text")
        .text("[ ZOOM OUT ] " + name(d));

    var g1 = etreeSvg.insert("g", ".grandparent")
        .datum(d)
        .attr("class", "depth");

    var g = g1.selectAll("g")
        .data(d._children)
      .enter().append("g");

    g.filter(function(d) { return d._children; })
        .classed("children", true)
        .on("click", transition);

    g.selectAll(".child")
        .data(function(d) { return d._children || [d]; })
      .enter().append("rect")
        .attr("class", "child")
        .call(rect);

    g.append("rect")
        .attr("class", "parent")
        .call(rect)
      .on("mouseover", function(d){
        if (d._children) {
          showSomeData // turn on tooltip
            .style({
              'opacity' : 1,
              'left' : (function(event){
                if (d3.event.clientX < (window.innerWidth/2)) { 
                  return (d3.event.clientX + 30) + 'px'; 
                } else { 
                  return (d3.event.clientX - 320) + 'px'; 
                }
              }),
              'top' : (function(){
                return (d3.event.clientY + 20) + 'px';
              })
            })
            .append("p").attr("class", "map_data").html(function(){
              var showThis = "",
              theName;

              if (d.name == "AP" || d.name == "AR" || d.name == "AS" || d.name == "BR" || d.name == "CG" || d.name == "GA" || d.name == "GJ" || d.name == "HR" || d.name == "HP" || d.name == "JK" || d.name == "JH" || d.name == "KA" || d.name == "KL" || d.name == "MP" || d.name == "MH" || d.name == "MN" || d.name == "ML" || d.name == "MZ" || d.name == "NL" || d.name == "OR" || d.name == "PB" || d.name == "RJ" || d.name == "SK" || d.name == "TN" || d.name == "TR" || d.name == "UK" || d.name == "UP" || d.name == "WB" || d.name == "TN" || d.name == "TR" || d.name == "DL" || d.name == "PY") { 
                theName = d.state; 
              } else { 
                theName = d.name; 
              }
              
              for (i=0; i<d._children.length; i++) {
                // showThis += "<strong>" + d._children[i].name + ":</strong> " + bigNumb(Math.round(d._children[i].value)) + "<br />" ;
                showThis += "<p>" + d._children[i].name + " <span>" + perTree(d._children[i].value/d.value) + "</span></p>" ;
              }

              return "<strong>" + theName + "</strong><br /><span>Rs‚¹" + bigNumb(Math.round(d.value)) + "</span> (in millions) | <span>" + perTree(d.value/d.parent.value) + "</span> of total<hr /><p><em>Includes ...</em></p>" + showThis;

            });
          } else { return; }
      })
      .on('mouseout', function(d){
        showSomeData
          .style("opacity", 0)
          .selectAll("p, span").remove();
      });

    g.append("text")
        .attr("dy", ".75em")
        .text(function(d) { if (d._children) { return d.name; } else { return d.name + " Rs‚¹" + bigNumb(Math.round(d.value)) + " (in millions)"; } })
        .call(text);

    function transition(d) {
      if (transitioning || !d) return;
      transitioning = true;

      var g2 = display(d),
          t1 = g1.transition().duration(750),
          t2 = g2.transition().duration(750);

      // Update the domain only after entering new elements.
      x.domain([d.x, d.x + d.dx]);
      y.domain([d.y, d.y + d.dy]);

      // Enable anti-aliasing during the transition.
      etreeSvg.style("shape-rendering", null);

      // Draw child nodes on top of parent nodes.
      etreeSvg.selectAll(".depth").sort(function(a, b) { return a.depth - b.depth; });

      // Fade-in entering text.
      g2.selectAll("text").style("fill-opacity", 0);

      // Transition to the new view.
      t1.selectAll("text").call(text).style("fill-opacity", 0);
      t2.selectAll("text").call(text).style("fill-opacity", 1);
      t1.selectAll("rect").call(rect);
      t2.selectAll("rect").call(rect);

      // Remove the old node when the transition is finished.
      t1.remove().each("end", function() {
        etreeSvg.style("shape-rendering", "crispEdges");
        transitioning = false;
      });
    }

    return g;
  }
}); // end loading of expenditure data callback

// treemap builder functions
function initialize(root) {
  root.x = root.y = 0;
  root.dx = width;
  root.dy = height;
  root.depth = 0;
}

// Aggregate the values for internal nodes. This is normally done by the
// treemap layout, but not here because of our custom implementation.
// We also take a snapshot of the original children (_children) to avoid
// the children being overwritten when when layout is computed.
function accumulate(d) {
  return (d._children = d.children)
      ? d.value = d.children.reduce(function(p, v) { return p + accumulate(v); }, 0)
      : d.value;
}

// Compute the treemap layout recursively such that each group of siblings
// uses the same size (1Ã—1) rather than the dimensions of the parent cell.
// This optimizes the layout for the current zoom state. Note that a wrapper
// object is created for the parent node for each group of siblings so that
// the parentRs€™s dimensions are not discarded as we recurse. Since each group
// of sibling was laid out in 1Ã—1, we must rescale to fit using absolute
// coordinates. This lets us use a viewport to zoom.
function layout(d) {
  if (d._children) {
    treemap.nodes({_children: d._children});
    d._children.forEach(function(c) {
      c.x = d.x + c.x * d.dx;
      c.y = d.y + c.y * d.dy;
      c.dx *= d.dx;
      c.dy *= d.dy;
      c.parent = d;
      layout(c);
    });
  }
}

function text(text) {
  text.attr("x", function(d) { return x(d.x) + 2; })
      .attr("y", function(d) { return y(d.y) + 4; });
}

function rect(rect) {
  rect.attr("x", function(d) { return x(d.x); })
      .attr("y", function(d) { return y(d.y); })
      .attr("width", function(d) { return x(d.x + d.dx) - x(d.x); })
      .attr("height", function(d) { return y(d.y + d.dy) - y(d.y); });
}

function name(d) {
  var theName;

  if (d.name == "AP" || d.name == "AR" || d.name == "AS" || d.name == "BR" || d.name == "CG" || d.name == "GA" || d.name == "GJ" || d.name == "HR" || d.name == "HP" || d.name == "JK" || d.name == "JH" || d.name == "KA" || d.name == "KL" || d.name == "MP" || d.name == "MH" || d.name == "MN" || d.name == "ML" || d.name == "MZ" || d.name == "NL" || d.name == "OR" || d.name == "PB" || d.name == "RJ" || d.name == "SK" || d.name == "TN" || d.name == "TR" || d.name == "UK" || d.name == "UP" || d.name == "WB" || d.name == "TN" || d.name == "TR" || d.name == "DL" || d.name == "PY") { 
    theName = d.state; 
  } else { 
    theName = d.name; 
  }

  return d.parent
     ? name(d.parent) + " :: " + theName
     : d.name;
}


/* /////// additional user interface functions \\\\\\\ */
$(document).ready(function(){

  // control population data notes and download box
  var poptext = 0;
  $( "#controls_pop_chart > h3 > a > span" ).click(function(event) {
    $this = $(this);
    if ( poptext === 0 ) {
      $this.text( "Close Notes/Downloads" );
      poptext++;
    } else { 
      $this.text( "Data Notes/Downloads" ); 
      poptext = 0;
    }
    $( "#pop_data_notes" ).slideToggle( 600 );
    event.preventDefault(); // stop hash tag link behavior
  });

  // control revenue data notes and download box
  var revdatatext = 0;
  $( "#label_rev_tree > h3 > a > span" ).click(function(event) {
    $this = $(this);
    if ( revdatatext === 0 ) {
      $this.text( "Close Notes/Downloads" );
      revdatatext++;
    } else { 
      $this.text( "Data Notes/Downloads" ); 
      revdatatext = 0;
    }
    $( "#rev_data_notes" ).slideToggle( 600 );
    event.preventDefault(); // stop hash tag link behavior
  });

  // control revenue data notes and download box
  var expdatatext = 0;
  $( "#label_exp_tree > h3 > a > span" ).click(function(event) {
    $this = $(this);
    if ( expdatatext === 0 ) {
      $this.text( "Close Notes/Downloads" );
      expdatatext++;
    } else { 
      $this.text( "Data Notes/Downloads" ); 
      expdatatext = 0;
    }
    $( "#exp_data_notes" ).slideToggle( 600 );
    event.preventDefault(); // stop hash tag link behavior
  });

  // control map households data notes and download box
  var mapdatatext = 0;
  $( "#controls_house_chart > p > a > span" ).click(function(event) {
    $this = $(this);
    if ( mapdatatext === 0 ) {
      $this.text( "Close Notes/Downloads" );
      mapdatatext++;
    } else { 
      $this.text( "Data Notes/Downloads" ); 
      mapdatatext = 0;
    }
    $( "#map_data_notes" ).slideToggle( 600 );
    event.preventDefault(); // stop hash tag link behavior
  });

  // hijack the data download link for smooth scrolling
  $( ".dload_jump" ).click(function() {
      $('html, body').animate({
          scrollTop: $("#download_jump").offset().top
      }, 400);
  });

}); // end jQuery doc load function