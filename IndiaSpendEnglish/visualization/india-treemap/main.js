//==========================================================================>
                      //Custom Round Function
//==========================================================================>
function roundNumber(number,decimal_points) {
  if(!decimal_points) return Math.round(number);
  if(number == 0) {
    var decimals = "";
    for(var i=0;i<decimal_points;i++) decimals += "0";
    return "0."+decimals;
  }

  var exponent = Math.pow(10,decimal_points);
  var num = Math.round((number * exponent)).toString();
  return num.slice(0,-1*decimal_points) + "." + num.slice(-1*decimal_points)
}
 
//===========================================================================>
                      //  FIRST SECTION TREEMAP     
//===========================================================================>
function plotTreemap (entry, n, color) {
  var dpval = $('#dp').val();
  var dyntitle = $('#tm1').text();
  if(color == 3) {
       //=================================>Making the calculation based on the total
      var tempStack = [];
      for (i = 0; i < entry[0].length; i++) {
        for(j = 0; j < 4; j++) {
          var flag = i*4+j;
          tempStack.push(entry[0][i]['Total'] * entry[1][flag]['Total'] / 100);
         }
      }

      //=================================>Pushing into the  sumArray stack
       var sumArray = [];
       for(i = 0; i < entry[0].length; i++) {
         var temp = tempStack.slice(i*4,i*4+4);
         var entry_diff = entry[0][i]['Total'] - _.reduce(temp, function(memo, num){ return memo + num; }, 0);
         sumArray.push(entry_diff);
       } 


       //==>clubing sumArray and tempStack
       var sumTotal = [];
       for(i = 0; i < sumArray.length; i++) {
          var temp = tempStack.slice(i*4,i*4+4);
          for(j = 0; j < 4; j++){
          sumTotal.push({"State": entry[0][i]['State'],
                       "Sub-category":entry[1][j]['Sub-category'],
                       "Display Names":entry[1][j]['Display Names'],
                        "Total": temp[j]+'0'});
          }
         sumTotal.push({"State":entry[0][i]['State'], "Sub-category":"Others",
                        "Display Names":"Others", "Total": sumArray[i]+'0'});
         }


        var entries = sumTotal;

  } else if(color == 4) {
       //====================================>Making the calculation based on the total
      var tempStack = [];
      for (i = 0; i < entry[0].length; i++) {
        for(j = 0; j < 3; j++) {
          var flag = i*3+j;
          tempStack.push(entry[0][i]['Total'] * entry[1][flag]['Total'] / 100);
         }
      }

      //====================================>Pushing into the  sumArray stack
       var sumArray = [];
       for(i = 0; i < entry[0].length; i++) {
         var temp = tempStack.slice(i*3,i*3+3);
         var entry_diff = entry[0][i]['Total'] - _.reduce(temp, function(memo, num){ return memo + num; }, 0);
         sumArray.push(entry_diff);
       } 

       //==>clubing sumArray and tempStack
       var sumTotal = [];
       for(i = 0; i < sumArray.length; i++) {
          var temp = tempStack.slice(i*3,i*3+3);
          for(j = 0; j < 3; j++){
          sumTotal.push({ "State": entry[0][i]['State'],
                       "Sub-category":entry[1][j]['Sub-category'],
                       "Display Names":entry[1][j]['Display Names'],
                        "Total": String(temp[j])});
          }
         sumTotal.push({"State":entry[0][i]['State'], "Sub-category":"Others",
                        "Display Names":"Others", "Total": String(sumArray[i])});
         }


        var entries = sumTotal;

  } else if(color == 5) {
       //=======================================>Making the calculation based on the total
      var tempStack = [];
      for (i = 0; i < entry[0].length; i++) {
        for(j = 0; j < 5; j++) {
          var flag = i*5+j;
          tempStack.push(parseFloat(entry[1][flag]['Total']));
         }
      }
      //==>Pushing into the  sumArray stack
       var sumArray = [];
       for(i = 0; i < entry[0].length; i++) {
         var temp = tempStack.slice(i*5,i*5+5);
         var entry_diff = entry[0][i]['Total'] - _.reduce(temp, function(memo, num){ return memo + num; }, 0);
         sumArray.push(entry_diff);
       } 
       //==>clubing sumArray and tempStack
       var sumTotal = [];
       for(i = 0; i < sumArray.length; i++) {
          var temp = tempStack.slice(i*5,i*5+5);
          for(j = 0; j < 5; j++){
          sumTotal.push({ "State": entry[0][i]['State'],
                       "Sub-category":entry[1][j]['Sub-category'],
                       "Display Names":entry[1][j]['Display Names'],
                        "Total": String(temp[j])});
          }
         sumTotal.push({"State":entry[0][i]['State'], "Sub-category":"Others",
                        "Display Names":"Others", "Total": String(sumArray[i])});
         }

        var entries = sumTotal;

  } else if(color == 6) {
       //==>Making the calculation based on the total
      var tempStack = [];
      for (i = 0; i < entry[0].length; i++) {
        for(j = 0; j < 3; j++) {
          var flag = i*3+j;
          tempStack.push(entry[0][i]['Total'] * entry[1][flag]['Total'] / 100);
         }
      }

      //==>Pushing into the  sumArray stack
       var sumArray = [];
       for(i = 0; i < entry[0].length; i++) {
         var temp = tempStack.slice(i*3,i*3+3);
         var entry_diff = entry[0][i]['Total'] - _.reduce(temp, function(memo, num){ return memo + num; }, 0);
         sumArray.push(entry_diff);
       } 

       //==>clubing sumArray and tempStack
       var sumTotal = [];
       for(i = 0; i < sumArray.length; i++) {
          var temp = tempStack.slice(i*3,i*3+3);
          for(j = 0; j < 3; j++){
          sumTotal.push({ "State": entry[0][i]['State'],
                       "Sub-category":entry[1][j]['Sub-category'],
                       "Display Names":entry[1][j]['Display Names'],
                        "Total": String(temp[j])});
          }
         sumTotal.push({"State":entry[0][i]['State'], "Sub-category":"Others",
                        "Display Names":"Others", "Total": String(sumArray[i])});
         }

        var entries = sumTotal;
  } else {
    var entries = entry[0];
  }  
 
  //==> set the parameter for value
  if(n[0] == "Total") { num = 3; } else {
      var num = n[0];    
  }

  var story = ['2010-11','2011-12','2012-13','Total'];
    //Data declaration
   var w = 940,
       h = 450,
       root, cnt = ct = cl = count = ms = 0;  
  //color section
  if(color == 0) {
    colorValue = [];
    var tmp = _.pluck(entry[1], "2010-11");
    for(i = 0; i < tmp.length; i++){
      colorValue.push(undefined);
    };

  } else if(color == 1) {
    var ent1 = ent2 = 0, colorValue = [];
    ent1 = _.pluck(entry[1], "2010-11");
    ent2 = _.pluck(entry[1], "2011-12");
     if(ent1.length == ent2.length) {
      for(i = 0; i < ent1.length; i++) {
        colorValue.push((parseFloat((ent2[i]).replace(/,/g,"")) / parseFloat((ent1[i]).replace(/,/g,"")) - 1)*100);
      }
    }
  } else if(color == 2) {
    var ent1 = ent2 = 0, colorValue = [];
    ent1 = _.pluck(entry[1], "2011-12");
    ent2 = _.pluck(entry[1], "2012-13");
     if(ent1.length == ent2.length) {
      for(i = 0; i < ent1.length; i++) {
        colorValue.push((parseFloat((ent2[i]).replace(/,/g,"")) / parseFloat((ent1[i]).replace(/,/g,"")) - 1)*100);
      }
    }
  } else if(color == 3) {
    //Modern house hold
      var colorValue = [];
      var ent1 = _.pluck(entry[0], n[0]);
      var ent2 = _.pluck(entry[2], n[0]);
      for(i = 0; i < ent1.length; i++) {
        colorValue.push(parseFloat(ent2[i]) / ent1[i]);
      }
  } else if(color == 4) {
    //water availability
      var colorValue = [];
      var ent1 = _.pluck(entry[0], n[0]);
      var ent2 = _.pluck(entry[2], n[0]);
      for(i = 0; i < ent1.length; i++) {
        colorValue.push(parseFloat(ent2[i]) / ent1[i]);
      }
  } else if(color == 5) {
      //Transportation
      var colorValue = [];
      for(i = 0; i < entry[2].length; i++) {
          colorValue.push(parseFloat(entry[2][i]));
    }
  } else if(color == 6) {
      //economy distribution
      var colorValue = [];
      var ent1 = _.pluck(entry[2], n[0]);
      for(i = 0; i < ent1.length; i++) {
        colorValue.push(ent1[i]);
      }
  }

  //==> Treemap root definition
  root = {  values: d3.nest()
           .key(function (d) {  return d.State; })
           .rollup(function (d) { return d.map(function(d) { return { key: parseFloat((d[story[num]]).replace(/,/g,""))}; }); })
           .entries(entries) 
         };

    //Color scale define
  var colorScale = d3.scale.linear()
        .range(['red','yellow','green']) // or use hex values
        .domain([d3.min(colorValue), d3.quantile(colorValue,0.5), d3.max(colorValue)]);

    //Color scale define
  var colorScaleRev = d3.scale.linear()
        .range(['green','yellow','red']) // or use hex values
        .domain([d3.min(colorValue), d3.quantile(colorValue,0.5), d3.max(colorValue)]);

  //Plot the data as treemap
  var treemap = d3.layout.treemap()
        .size([w, h])
        .sticky(true)
        .padding([1,1,1,1])
        .sort(function(a,b) { return a.value - b.value; })
        .children(function(d) { return d.values; }) 
        .value(function(d) { return d.key; }); 

  var svg = d3.select("#chartone")
    .attr("class", "first")
    .append("svg")
    .attr('wrap', false)
    .attr('class','fit')
    .style("width", w + "px")
    .style("height", h + "px")
    .attr("transform", "translate(.5,.5)")
    .on("click", secondLevel)

  var cell = svg.selectAll(".cell")
          .data(treemap.nodes(root).filter(function(d) { 
              return d.depth && d.values;
          }));
        
  // enter new elements 
  var cellEnter = cell.enter()
     .append("g") 
     .attr("class", "cell");    
  cellEnter.append("rect");   
  cellEnter.append("text"); 
        
  // update remaining elements 
  cell.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

  //Append rectangle
  cell.select("rect") 
        .attr("width", function (d) { return d.dx; })
        .attr("height", function (d) { return d.dy; })
        .style("fill", function(d) { if(color == 3 || color == 4) {
          if(colorValue[cl++] == undefined) { return "grey" } else {
               return colorScaleRev(colorValue[cnt++]); 
            }
        } else {
            if(colorValue[cl++] == undefined) { return "grey" } else {
               return colorScale(colorValue[cnt++]); 
            }
          }
        })
       cell.append('g:title').text(function (d) { return d.key+ " "+ $('#dp').html().split(':')[1] +" :"+ roundNumber(d.value, 2) + " "+$('#color1').html().split(':')[1] +" : " + roundNumber(colorValue[count++], 2); })


  //Append text
  cell.select("text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr('id', 'textwrap')
      .attr("text-anchor", "middle")
      .style('font-family','Georgia')
      .style('font-size', function(d) { 
        if((d.dx / 10) < 4.5) {
          return 0 + "px";
        } else {
          return d.dx / 10 + "px"; 
        }
      })
     .text(function(d) { return d.key; });  

  //==>Dynamic Color Legend
  var svg = d3.selectAll('#legend').append('svg')
      .attr('width', 250)
      .attr('height', '30');

  var gradient = svg.append("svg:defs")
  .append("svg:linearGradient")
    .attr("id", "gradient");

  gradient.append("svg:stop")
      .attr("offset", "0%")
      .attr("stop-color", function(){
        if(color == 3 || color == 4) {
          return colorScaleRev(d3.min(colorValue));
        } else {
          return colorScale(d3.min(colorValue));
        }
      });

  gradient.append("svg:stop")
      .attr("offset", "50%")
      .attr("stop-color", function(){
        if(color == 3 || color == 4) {
          return colorScaleRev(d3.quantile(colorValue, 0.5));
        } else {
          return colorScale(d3.quantile(colorValue, 0.5));
        }
      });

  gradient.append("svg:stop")
      .attr("offset", "100%")
      .attr("stop-color", function(){
        if(color == 3 || color == 4) {
          return colorScaleRev(d3.max(colorValue));
        } else {
          return colorScale(d3.max(colorValue));
        }
      });

  svg.append("svg:rect")
      .attr("width", '250')
      .attr("height", '28')
      .style("fill", "url(#gradient)");

  svg.append("svg:text")
      .attr("x", '10')
      .attr("y", '16')
      .attr('dominant-baseline','middle')
      .style('fill','#fff')
      .text(roundNumber(d3.min(colorValue), 2));

  svg.append("svg:text")
      .attr('x', 200)
      .attr("y", '16')
      .attr('dominant-baseline','middle')
      .style('fill','#fff')
      .text(roundNumber(d3.max(colorValue), 2));

  //=========================>Inner Second function
  function secondLevel(){
    d3.select('#legend svg').remove();
    d3.select('#chartone svg').remove();
    $('#search1').val('');
      var story = ['2010-11','2011-12','2012-13', 'Total'];
      //Data declaration
     var w = 940,
         h = 450,
         root, cnt = count = cl = ct = ct1 = ms = 0;  

   
       //Data nesting
      root = {  values: d3.nest()
               .key(function (d) {  return d.State; })
               .key(function (d) {  return d[n[1]]; })
               .rollup(function (d) { return d.map(function(d) { return { key: parseFloat((d[story[num]]).replace(/,/g,""))}; }); })
               .entries(entries) 
             };

      //category title update based on split
      if(n[2] == 0) {
        $('#tm1').text('Income Split Based On Categories');
      } else if(n[2] == 1) {
        $('#tm1').text('Revenue Split Based On Categories');
      } else if(n[2] == 2) {
        $('#tm1').text('Expenditure Split Based On Categories');
        $('#dp').val('Size: Total Capital Disbursments & Total Revenue Expenditure');
        
      }


    //color section
    if(color == 0) {
      colorValue = [];
      var tmp = _.pluck(entries, "2010-11");
      for(i = 0; i < tmp.length; i++){
        colorValue.push(undefined);
      };

    } else if(color == 1) {
      var ent1 = ent2 = 0, colorValue = [];
      ent1 = _.pluck(entry[0], "2010-11");
      ent2 = _.pluck(entry[0], "2011-12");
      if(ent1.length == ent2.length) {
        for(i = 0; i < ent1.length; i++) {
          colorValue.push((parseFloat((ent2[i]).replace(/,/g,"")) / parseFloat((ent1[i]).replace(/,/g,"")) - 1)*100);
        }
      }

    } else if(color == 2) {
      var ent1 = ent2 = 0, colorValue = [];
      ent1 = _.pluck(entry[0], "2011-12");
      ent2 = _.pluck(entry[0], "2012-13");
      if(ent1.length == ent2.length) { 
        for(i = 0; i < ent1.length; i++) {
          colorValue.push((parseFloat((ent2[i]).replace(/,/g,"")) / parseFloat((ent1[i]).replace(/,/g,"")) - 1)*100);
        }
      }  

    } else if(color == 3) {
      $('#dp').val('Size: Houses Using Kerosene, Oil , Electricity, Solar');
      var colorValue = [];
      var ent1 = _.pluck(entries, n[0]);
      for(i = 0; i < ent1.length; i++) {  
      }
      $('#color1').text("Color: undefined:")
      $('#tm1').text('Fuel Sources Distribution');

    } else if(color == 4) {
      $('#dp').val('Size: Drinking Water Within, Near, Away From Premises');
      var colorValue = [];
      var ent1 = _.pluck(entries, n[0]);
      for(i = 0; i < ent1.length; i++) {
        colorValue.push(undefined);
      }
      $('#color1').text("Color: undefined:")
      $('#tm1').text('Drinking Water Availability Distribution');

    } else if(color == 5) {
      $('#dp').val('Size:National, State, Rural, Urban, PWD Road Covg');
      var colorValue = [];
      for(i = 0; i < entry[2].length; i++) {
        colorValue.push(undefined);
      }
      $('#color1').text('Color: undefined:');
      $('#tm1').text('Road Covg Split Based On Categories');

    } else if(color == 6) {
      $('#dp').val('Size:Agri,Service,Industry GSDP');
      var colorValue = [];
      var ent1 = _.pluck(entry[2], n[0]);
      for(i = 0; i < ent1.length; i++) {
        colorValue.push(undefined);
     }
      $('#color1').text('Color: undefined:');
      $('#tm1').text('GSDP Split Based On Categories');
    }

      //Color scale define
    var colorScale = d3.scale.linear()
          .range(['red','yellow','green']) // or use hex values
          .domain([d3.min(colorValue), d3.quantile(colorValue, 0.5), d3.max(colorValue)]);

    //Plot the data as treemap
    var treemap = d3.layout.treemap()
          .size([w, h])
          .sticky(true)
          .padding([2,2,2,2])
          .sort(function(a,b) { return a.value - b.value; })
          .children(function(d) { return d.values; }) 
          .value(function(d) { return d.key; }); 

    var svg = d3.select("#chartone")
      .attr("class", "first")
      .append("svg")
      .style("width", w + "px")
      .style("height", h + "px")
      .attr("transform", "translate(.5,.5)")
      .on("click", plotFirst)

    var cell = svg.selectAll(".cell")
            .data(treemap.nodes(root).filter(function(d) { 
              if(d.depth > 1) {
                return d.depth && d.values;
              }
            }));
          
    // enter new elements 
    var cellEnter = cell.enter()
       .append("g") 
         .attr("class", "cell");    
         cellEnter.append("rect");   
         cellEnter.append("text"); 
          
    // update remaining elements 
    cell.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    
    //Append rectangle
    cell.select("rect") 
          .attr("width", function (d) { return d.dx; })
          .attr("height", function (d) { return d.dy; })
          .style("fill", function(d) {
            if(colorValue[cl++] == undefined) { return "grey" } else {
               return colorScale(colorValue[cnt++]); 
            }
          })
          cell.append('g:title').text(function (d) { return d.parent.key + ": " +d.key.replace(/ *\([^)]*\) */g, "")+ " = " +roundNumber(d.value, 2) + " "+$('#color1').html().split(':')[1] +" : " + roundNumber(colorValue[count++], 2); })

    //Append text
    cell.append("rect:text")
        .attr("x", function(d) { return d.dx / 2 ; })
        .attr("y", function(d) { return d.dy / 4 ; })
        .attr("dy", ".35em")
        .attr("text-anchor", "middle")
        .style('font-family','Georgia')
        .style('font-size', function(d) { 
          if((d.dx / 14) < 4.5) {
            return 0 +"px";
          } else {
            return d.dx / 14 + "px"; 
          }
        })
        .text(function(d) { return d.parent.key; });


    //Append text
    cell.select("text")
        .attr("x", function(d) { return d.dx / 2; })
        .attr("y", function(d) { return d.dy / 2; })
        .attr("dy", ".35em")
        .style('font-family','Georgia')
        .attr("text-anchor", "middle")
        .style('font-size', function(d) { 
          if((d.dx / 18) < 4.5) {
            return 0 + "px";
          } else {
            return d.dx / 18 + "px"; 
          }
        })
        .text(function(d) { return entries[ct++]['Display Names'] ? entries[ct1++]['Display Names'].replace(/ *\([^)]*\) */g, "") : d.key } 
        );  

  

    //==>Dynamic Color Legend
      var svg = d3.selectAll('#legend').append('svg')
          .attr('width', 250)
          .attr('height', '30');

      var gradient = svg.append("svg:defs")
      .append("svg:linearGradient")
        .attr("id", "gradient");

      gradient.append("svg:stop")
          .attr("offset", "0%")
          .attr("stop-color", function(){
            if(color == 3 || color == 4) {
              return colorScaleRev(d3.min(colorValue));
            } else {
              return colorScale(d3.min(colorValue));
            }
          });

      gradient.append("svg:stop")
          .attr("offset", "50%")
          .attr("stop-color", function(){
            if(color == 3 || color == 4) {
              return colorScaleRev(d3.quantile(colorValue, 0.5));
            } else {
              return colorScale(d3.quantile(colorValue, 0.5));
            }
          });

      gradient.append("svg:stop")
          .attr("offset", "100%")
          .attr("stop-color", function(){
            if(color == 3 || color == 4) {
              return colorScaleRev(d3.max(colorValue));
            } else {
              return colorScale(d3.max(colorValue));
            }
          });

      svg.append("svg:rect")
          .attr("width", '250')
          .attr("height", '28')
          .style("fill", "url(#gradient)");

      svg.append("svg:text")
          .attr("x", '10')
          .attr("y", '16')
          .attr('dominant-baseline','middle')
          .style('fill','#fff')
          .text(roundNumber(d3.min(colorValue), 2));

      svg.append("svg:text")
          .attr('x', 200)
          .attr("y", '16')
          .attr('dominant-baseline','middle')
          .style('fill','#fff')
          .text(roundNumber(d3.max(colorValue), 2));

  }// end of plotTreemap function


  //====================>Plot First Map On Click
  function plotFirst(){
      $('#dp').val(dpval);
      $('#search1').val('');
      d3.select('#legend svg').remove();
      d3.select('#chartone svg').remove();
      var story = ['2010-11','2011-12','2012-13','Total'];
      if(color <= 2) {
        $('#color1').text('Color: YOY Growth Rate');
      } else if(color == 3) {
        $('#color1').text('Color: Population / House');
      } else if(color == 4) {
        $('#color1').text('Color: Population / House');
      } else if(color == 5) {
        $('#color1').text("Color: Vehicles / Person");
      } else if(color == 6) {
        $('#color1').text("Color: Growth Rate");
      }
      //Data declaration
     var w = 940,
         h = 450,
         root, cnt = cl= count = ms = 0;  

          //Data nesting
        root = {  values: d3.nest()
                 .key(function (d) {  return d.State; })
                 .rollup(function (d) { return d.map(function(d) { return { key: parseFloat((d[story[num]]).replace(/,/g,""))}; }); })
                 .entries(entries) 
               }; 

      //Color scale define
      var colorScale = d3.scale.linear()
            .range(['red','yellow','green']) // or use hex values
            .domain([d3.min(colorValue), d3.quantile(colorValue, 0.5), d3.max(colorValue)]);

      //Plot the data as treemap
      var treemap = d3.layout.treemap()
            .size([w, h])
            .sticky(true)
            .padding([1,1,1,1])
            .sort(function(a,b) { return a.value - b.value; })
            .children(function(d) { return d.values; }) 
            .value(function(d) { return d.key; }); 

      var svg = d3.select("#chartone")
        .attr("class", "first")
        .append("svg")
        .style("width", w + "px")
        .style("height", h + "px")
       .attr("transform", "translate(.5,.5)")
       .on("click", secondLevel)

      var cell = svg.selectAll(".cell")
              .data(treemap.nodes(root).filter(function(d) { 
                  return d.depth && d.values;
              }));
            
      // enter new elements 
      var cellEnter = cell.enter()
         .append("g") 
           .attr("class", "cell");    
           cellEnter.append("rect");   
           cellEnter.append("text"); 
            
      // update remaining elements 
      cell.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
      
      //Append rectangle
      cell.select("rect") 
            .attr("width", function (d) { return d.dx; })
            .attr("height", function (d) { return d.dy; })
            .style("fill", function(d) { if(color == 3 || color == 4) {
              if(colorValue[cl++] == undefined) { return "grey" } else {
                   return colorScaleRev(colorValue[cnt++]); 
                }
              } else {
                if(colorValue[cl++] == undefined) { return "grey" } else {
                   return colorScale(colorValue[cnt++]); 
                }
              }
            })
            cell.append('g:title').text(function (d) { return d.key+" "+ $('#dp').html().split(':')[1] +" :" +roundNumber(d.value, 2)+ " "+$('#color1').html().split(':')[1] +" : " + roundNumber(colorValue[count++], 2); })

      //Append text
      cell.select("text")
          .attr("x", function(d) { return d.dx / 2; })
          .attr("y", function(d) { return d.dy / 2; })
          .attr("dy", ".35em")
          .attr("text-anchor", "middle")
          .style('font-family','Georgia')
          .style('font-size', function(d) { 
            if((d.dx / 10) < 4.5) {
              return 0 + "px";
            } else {
              return d.dx / 10 + "px"; 
            }
           })
          .text(function(d) { return d.key; }) 

          //display dynamic title
         if(n[2] == 0) {
            if(n[0] == 0) {
              $('#tm1').text('Income Distribution For Year 2011');
            } else if(n[0] == 1) {
              $('#tm1').text('Income Distribution For Year 2012');
            } else if(n[0] == 2) {
              $('#tm1').text('Income Distribution For Year 2013');
            }
         } else if(n[2] == 1) {
           if(n[0] == 0) {
             $('#tm1').text('Revenue Distribution For Year 2011');
           } else if(n[0] == 1) {
             $('#tm1').text('Revenue Distribution For Year 2012');
           } else if(n[0] == 2) {
             $('#tm1').text('Revenue Distribution For Year 2013');
           }
         } else if(n[2] == 2) {
           if(n[0] == 0) {
             $('#tm1').text('Expenditure Distribution For Year 2011');
           } else if(n[0] == 1) {
             $('#tm1').text('Expenditure Distribution For Year 2012');
           } else if(n[0] == 2) {
             $('#tm1').text('Expenditure Distribution For Year 2013');
           }
         } else if(n[2] == 3) {
           $('#tm1').text('Houses Distribution For Year 2011');
           $('#color1').text('Color: Population / House:');
         } else if(n[2] == 4) {
           $('#tm1').text('Houses Distribution For Year 2011');
           $('#color1').text('Color: Population / House:');
         } else if(n[2] == 5) {
           $('#tm1').text('Road Covg Distribution For Year 2011');
           $('#color1').text('Color: Vehicles / Person:');
         } else if(n[2] == 6) {
           $('#tm1').text('GSDP Distribution For Year 2011');
           $('#color1').text('Color: Growth Rate:');
         }

      //==>Dynamic Color Legend
        var svg = d3.selectAll('#legend').append('svg')
            .attr('width', 250)
            .attr('height', '30');

        var gradient = svg.append("svg:defs")
        .append("svg:linearGradient")
          .attr("id", "gradient");

        gradient.append("svg:stop")
            .attr("offset", "0%")
            .attr("stop-color", function(){
              if(color == 3 || color == 4) {
                return colorScaleRev(d3.min(colorValue));
              } else {
                return colorScale(d3.min(colorValue));
              }
            });

        gradient.append("svg:stop")
            .attr("offset", "50%")
            .attr("stop-color", function(){
              if(color == 3 || color == 4) {
                return colorScaleRev(d3.quantile(colorValue, 0.5));
              } else {
                return colorScale(d3.quantile(colorValue, 0.5));
              }
            });

        gradient.append("svg:stop")
            .attr("offset", "100%")
            .attr("stop-color", function(){
              if(color == 3 || color == 4) {
                return colorScaleRev(d3.max(colorValue));
              } else {
                return colorScale(d3.max(colorValue));
              }
            });

        svg.append("svg:rect")
            .attr("width", '250')
            .attr("height", '28')
            .style("fill", "url(#gradient)");

        svg.append("svg:text")
            .attr("x", '10')
            .attr("y", '16')
            .attr('dominant-baseline','middle')
            .style('fill','#fff')
            .text(roundNumber(d3.min(colorValue), 2));

        svg.append("svg:text")
            .attr('x', 200)
            .attr("y", '16')
            .attr('dominant-baseline','middle')
            .style('fill','#fff')
            .text(roundNumber(d3.max(colorValue), 2));
        }
}

//===========================================================================>
                      //  SECOND SECTION TREEMAP     
//===========================================================================>
//Second Treemap Layout plotting

function plotMapTwo (entries, n, optnum) {
  var num = n;
  d3.select('#charttwo svg').remove();
  var dsdp1 = $('#dp1').val();
  var story = ['Total','Females'];
    //Data declaration
   var w = 940,
       h = 450,
       it = 0, it1= 0,
       root, count = cnt = cl = ct = u = k = ms = mp = 0; 
       var colorval = filtercolor = []; 

     //Data nesting
   root = {  values: d3.nest()
            .key(function (d) {  return d.State; })
            .rollup(function (d) { return d.map(function(d) { return { key: parseFloat((d[story[num]]).replace(/,/g,""))}; }); })
            .entries(entries[0]) 
          };

  if(optnum == 0) {
    //========>Color Filteration
    colorValue = [];
    pluck = _.pluck(entries[2], story[num]);
    for(i = 0; i < pluck.length; i++) {
      colorValue.push(parseFloat(pluck[i]));
    }

  } else if(optnum == 1) {
    colorValue = [];
    for(i = 0; i < entries[2].length; i++) {
      colorValue.push(entries[2][i]);
     }
  } else if(optnum == 2) {
    colorValue = [];
    for(i = 0; i < entries[2].length; i++) {
      colorValue.push(entries[2][i]);
    }

  } else if(optnum == 3) {
    colorValue = [];
    var ent1 = _.pluck(entries[2], "Total");
    for(i = 0; i < ent1.length; i++) {
       colorValue.push(parseFloat(ent1[i]));
       console.log(parseFloat(ent1[i]));
     }
  } else if(optnum == 4) {
        colorValue = [];
        var ent1 = _.pluck(entries[2], "Total");
        for(i = 0; i < ent1.length; i++) {
          colorValue.push(ent1[i]);
        }

  } else if(optnum == 5) {
    colorValue = [];
    var ent1 = _.pluck(entries[2], "Total");
    for(i = 0; i < ent1.length; i++) {
      colorValue.push(ent1[i]);
    }
  }

    //Color scale define
  var colorScale = d3.scale.linear()
        .clamp(true)
        .range(['green','yellow','red']) // or use hex values
        .domain([d3.min(colorValue), d3.quantile(colorValue, 0.5), d3.max(colorValue)]);

   //Color scale define
  var colorScaleRev = d3.scale.linear()
        .range(['red','yellow','green']) // or use hex values
        .clamp(true)
        .domain([d3.min(colorValue), d3.quantile(colorValue, 0.5), d3.max(colorValue)]);

    //Color scale define
  var colorScale3 = d3.scale.linear()
        .clamp(true)
        .range(['green','yellow','red']) // or use hex values
        .domain([d3.min(_.filter(colorValue, function(d) { return d!=NaN; } )),
          d3.mean(_.filter(colorValue, function(d) { return d; })),
          d3.max(_.filter(colorValue, function(d) { return d!=NaN; }))]);

  
  //Plot the data as treemap
  var treemap = d3.layout.treemap()
        .size([w, h])
        .sticky(true)
        .padding([1,1,1,1])
        .sort(function(a,b) { return a.value - b.value; })
        .children(function(d) { return d.values; }) 
        .value(function(d) { return d.key; }); 

  var svg = d3.select("#charttwo")
    .attr("class", "first")
    .append("svg")
    .style("width", w + "px")
    .style("height", h + "px")
    .attr("transform", "translate(.5,.5)")
    .on("click", secondLevel)

  var cell = svg.selectAll(".cell")
          .data(treemap.nodes(root).filter(function(d) { 
              return d.depth && d.values;
          }));
        
  // enter new elements 
  var cellEnter = cell.enter()
     .append("g") 
       .attr("class", "cell");    
       cellEnter.append("rect");   
       cellEnter.append("text"); 
        
  // update remaining elements 
  cell.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
  
  //Append rectangle
  cell.select("rect") 
        .attr("width", function (d) { return d.dx; })
        .attr("height", function (d) { return d.dy; })
        .style("fill", function (d) {
          if(optnum == 0 || optnum == 5 ) {
            if(_.isNaN(colorValue[cnt++])) { return "grey"}
            else { return colorScale(colorValue[ct++]);}
          } else if(optnum == 1 || optnum == 2 || optnum == 4) {
            if(_.isNaN(colorValue[cnt++])) { return "grey"}
            else { return colorScaleRev(colorValue[ct++]);}
          } else if(optnum == 3) {
              return colorScale3(colorValue[ct++]);
          }
        })
        cell.append('g:title').text(function (d) { 
          if(colorValue[it1++] == NaN) {
              return d.key+" "+$('#dp1').html().split(':')[1]+': ' +roundNumber(d.value, 2)+' '+ $('#color2').html().split(':')[1] +'  ' +"=  undefined"; 
          } else {
            return d.key+" "+$('#dp1').html().split(':')[1]+': ' +roundNumber(d.value, 2)+' '+ $('#color2').html().split(':')[1] +' = '+roundNumber(colorValue[count++], 2); 
          }
        });


  //Append text
  cell.select("text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .style('font-family','Georgia')
      .style('font-size', function(d) {
        if(optnum == 3) {
          if((d.dx / 10) < 4.5) {
            return 2 + "px";
          } else {
            return d.dx / 10 + "px"; 
          }
        } else {
          if((d.dx / 10) < 4.5) {
            return 0 + "px";
          } else {
            return d.dx / 10 + "px"; 
          }
        }
        
      })
     .text(function(d) {  return d.key; }) 

  //==>Dynamic Color Legend
     var svg = d3.selectAll('#legend1').append('svg')
         .attr('width','300')
         .attr('height', '30');

     var gradient = svg.append("svg:defs")
     .append("svg:linearGradient")
       .attr("id", "gradient");

     gradient.append("svg:stop")
         .attr("offset", "0%")
         .attr("stop-color", function(){
           if(optnum == 1 || optnum == 2 || optnum == 4){
             return colorScaleRev(d3.min(colorValue));
           } else if(optnum == 3) {
             return colorScale3(d3.min(_.filter(colorValue, function(d) { return d!=404 })));
           } else if(optnum == 0 || optnum == 5) {
             return colorScale(d3.min(colorValue));
           }
         });

     gradient.append("svg:stop")
         .attr("offset", "50%")
         .attr("stop-color", function(){
                 if(optnum == 1 || optnum == 2 || optnum == 4){
                   return colorScaleRev(d3.quantile(colorValue, 0.5));
                 } else if(optnum == 3) {
                   return colorScale3(d3.quantile(_.filter(colorValue, function(d) { return d!=404 }), 0.5));
                 } else if(optnum == 0 || optnum == 5)  {
                   return colorScale(d3.quantile(colorValue, 0.5));
                 }
               });

     gradient.append("svg:stop")
         .attr("offset", "100%")
         .attr("stop-color", function(){
           if(optnum == 1 || optnum == 2 || optnum == 4){
             return colorScaleRev(d3.max(colorValue));
           } else if(optnum == 3) {
             return colorScale3(d3.max(_.filter(colorValue, function(d) { return d!=404 })));
           } else if(optnum == 0 || optnum == 5)  {
             return colorScale(d3.max(colorValue));
           }
         });

     svg.append("svg:rect")
         .attr("width", '290')
         .attr("height", '28')
         .style("fill", "url(#gradient)");

     svg.append("svg:text")
         .attr("x", '10')
         .attr("y", '16')
         .attr('dominant-baseline','middle')
         .style('fill','#fff')
         .text(roundNumber(d3.min(colorValue), 2));

     svg.append("svg:text")
         .attr("x", '220')
         .attr("y", '16')
         .attr('dominant-baseline','middle')
         .style('fill','#fff')
         .text(function(){
          if(optnum == 3) {
            return roundNumber(d3.max(_.filter(colorValue, function(d) { return d!=404 })), 2);
         } else {
           return roundNumber(d3.max(colorValue), 2);
         }
       }); 

  //=========================>Inner Second function
    function secondLevel(){
      d3.select('#legend1 svg').remove();
      d3.select('#charttwo svg').remove();
      $('#search2').val('');
        var story = ['Total','Females'];
        //Data declaration
       var w = 940,
           h = 450, count = 0, cnt = cl = ct = ct1 = ms = 0,
           it = 0, it1=0;
           root;  

       //Data nesting
       root = {  values: d3.nest()
                .key(function (d) {  return d.State; })
                .key(function (d) {  return d['Sub-category']; })
                .rollup(function (d) { return d.map(function(d) { return { key: parseFloat((d[story[num]]).replace(/,/g,""))}; }); })
                .entries(entries[0]) 
              };

       //==> Color parameters
       if(optnum == 0) {
         //========>Color Filteration
         $('#dp1').val("Size: Rural and Urban Population BPL & Color: Rural and Urban Unemployment Rate;")
         colorValue = [];
         pluck = _.pluck(entries[1], story[num]);
         for(i = 0; i < pluck.length; i++) {
           colorValue.push(parseFloat(pluck[i]));
         }   
          $('#tm2').text('Rural And Urban Split Of BPL For Year 2011'); 

       } else if(optnum == 1) {
          $('#dp1').val("Size: Rural and Urban Beds & Color: % Beds Per Person")
          colorValue = [];
            var size1 = _.pluck(_.where(entries[0], {"Sub-category":"No of Rural Beds (Govt)"}), "Total");
            var size2 = _.pluck(_.where(entries[0], {"Sub-category":"No of Urban Beds (Govt)"}),"Total");

            var color1 = _.pluck(_.where(entries[1], {"Sub-category":"Rural Population (in million)"}), "Total");
            var color2 = _.pluck(_.where(entries[1], {"Sub-category":"Urban Population (in million)"}), "Total");

            var tmp1 = [];
            var tmp2 = [];
            for(i = 0; i < size1.length; i++) {
              tmp1.push(size1[i] / (color1[i] * 10000));
            }

            for(i = 0; i < size2.length; i++) {
              tmp2.push(size2[i] / (color2[i] * 10000));
            }
           
            for(i = 0; i < tmp1.length; i++) {
              colorValue.push(tmp2[i]);
              colorValue.push(tmp1[i]);
            }
          $('#tm2').text('Rural And Urban Beds Distribution For Year 2011'); 

       } else if(optnum == 2) {
        $('#dp1').val("Size: Rural and Urban Child Population & Color: % Of Child Population");
          colorValue = [];
          var ent1 = _.pluck(entries[0], "Total");
          var ent2 = _.pluck(entries[1], "Total");
          for(i = 0; i < entries[0].length; i++) {
            colorValue.push(ent2[i] / ent1[i] * 100);
          }
          $('#tm2').text('Rural And Urban Child Population Dist For Year 2011'); 
       } else if(optnum == 3) {
        $('#dp1').val("Size: Rural and Urban Females & Color: undefined");
        colorValue = [];
        for(i = 0; i < entries[0].length; i++) {
          colorValue.push(undefined);
        }
          $('#tm2').text('Rural And Urban Females Dist For Year 2011'); 

       } else if(optnum == 4) {
        $('#dp1').val("Size: Rural and Urban Females & Color: Rural and Urban Sex Ratio")
        colorValue = [];
        var ent1 = _.pluck(entries[1], "Total");
        for(i = 0; i < ent1.length; i++) {
          colorValue.push(ent1[i]);
        }
          $('#tm2').text('Rural And Urban Females Dist For Year 2011'); 

       } else if(optnum == 5) {
        $('#dp1').val("Size: Rural and Urban Population BPL & Color: undefined")
        colorValue = [];
        for(i = 0; i < entries[0].length; i++) {
          colorValue.push(undefined);
        }
          $('#tm2').text('Rural And Urban Split Of BPL For Year 2011'); 
       }


        //Color scale define
      var colorScale = d3.scale.linear()
            .range(['green','yellow','red']) // or use hex values
        .domain([d3.min(colorValue), d3.quantile(colorValue, 0.5), d3.max(colorValue)]);

      //Plot the data as treemap
      var treemap = d3.layout.treemap()
            .size([w, h])
            .sticky(true)
            .padding([2,2,2,2])
            .sort(function(a,b) { return a.value - b.value; })
            .children(function(d) { return d.values; }) 
            .value(function(d) { return d.key; }); 

      var svg = d3.select("#charttwo")
        .attr("class", "first")
        .append("svg")
        .style("width", w + "px")
        .style("height", h + "px")
        .attr("transform", "translate(.5,.5)")
        .on("click", plotFirst)

      var cell = svg.selectAll(".cell")
          .data(treemap.nodes(root).filter(function(d) { 
            if(d.depth > 1) {
              return d.depth && d.values;
            }
          }))
            
      // enter new elements 
      var cellEnter = cell.enter()
         .append("g") 
           .attr("class", "cell");    
           cellEnter.append("rect");   
           cellEnter.append("text"); 
            
      // update remaining elements 
      cell.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
      
      //Append rectangle
      cell.select("rect") 
            .attr("width", function (d) { return d.dx; })
            .attr("height", function (d) { return d.dy; })
            .style("fill", function (d) {
                  if(optnum == 0 ) {
                      return colorScale(colorValue[cnt++]);  

                  } else if(optnum == 1) {
                     return colorScaleRev(colorValue[ct++]);

                  } else if( optnum == 2 || optnum == 4) {
              
                    if(_.isNaN(colorValue[cnt++])) { return "grey"}
                    else {return colorScaleRev(colorValue[ct++]);}

                  } else if(optnum == 3) {
                    if(_.isUndefined(colorValue[cnt++])) { return "grey"}
                      else { return colorScale3(colorValue[ct++]);}

                  } else if(optnum == 5) {

                    if(_.isNaN(colorValue[cnt++])  || colorValue[u++] == undefined) { return "grey"}
                    else { return colorScale(colorValue[ct++]);}

                  } 
                }) 


            cell.append('g:title').text(function (d) { return d.parent.key + ":" +d.key+ ": " +roundNumber(d.value, 2)+" "+$('#color2').html().split(':')[1]+" = " +roundNumber(colorValue[count++], 2); })

      //Append text
      cell.append("rect:text")
          .attr("x", function(d) { return d.dx / 2 ; })
          .attr("y", function(d) { return d.dy / 4 ; })
          .attr("dy", ".35em")
          .attr("text-anchor", "middle")
          .style('font-family','Georgia')
          .style('font-size', function(d) {
            if((d.dx / 14) < 4.5) {
              return 0 +"px";
            } else {
              return d.dx / 14 + "px"; 
            }            
          })
          .text(function(d) { return d.parent.key; });


      //Append text
      cell.select("text")
          .attr("x", function(d) { return d.dx / 2; })
          .attr("y", function(d) { return d.dy / 2; })
          .attr("dy", ".35em")
          .style('font-family','Georgia')
          .attr("text-anchor", "middle")
          .style('font-size', function(d) {
            if((d.dx / 18) < 4.5) {
              return 0 +"px";
            } else {
              return d.dx / 18 + "px"; 
            }
          }) 
         .text(function(d) { if(optnum == 0 || optnum == 5) {
            return entries[0][ct++]['Display Names']
         } else if(optnum == 2)  {
            return entries[1][it1++]['Display Names']
         } else if(optnum == 4) {
            return entries[0][it1++]['Display Names']
         } else {
            return entries[1][ct1++]['Sub-category']
         } }); 
          


      //==>Dynamic Color Legend
       var svg = d3.selectAll('#legend1').append('svg')
           .attr('width','300')
           .attr('height', '30');

       var gradient = svg.append("svg:defs")
       .append("svg:linearGradient")
         .attr("id", "gradient");

       gradient.append("svg:stop")
           .attr("offset", "0%")
           .attr("stop-color", function(){
             if(optnum == 1 || optnum == 2 || optnum == 4){
               return colorScaleRev(d3.min(colorValue));
             } else if(optnum == 3) {
               return colorScale3(d3.min(colorValue));
             } else if(optnum == 0 || optnum == 5) {
               return colorScale(d3.min(colorValue));
             }
           });

       gradient.append("svg:stop")
           .attr("offset", "50%")
           .attr("stop-color", function(){
                   if(optnum == 1 || optnum == 2 || optnum == 4){
                     return colorScaleRev(d3.quantile(colorValue, 0.5));
                   } else if(optnum == 3) {
                     return colorScale3(d3.quantile(colorValue, 0.5));
                   } else if(optnum == 0 || optnum == 5)  {
                     return colorScale(d3.quantile(colorValue, 0.5));
                   }
                 });

       gradient.append("svg:stop")
           .attr("offset", "100%")
           .attr("stop-color", function(){
             if(optnum == 1 || optnum == 2 || optnum == 4){
               return colorScaleRev(d3.max(colorValue));
             } else if(optnum == 3) {
               return colorScale3(d3.max(colorValue));
             } else if(optnum == 0 || optnum == 5)  {
               return colorScale(d3.max(colorValue));
             }
           });

       svg.append("svg:rect")
           .attr("width", '290')
           .attr("height", '28')
           .style("fill", "url(#gradient)");

       svg.append("svg:text")
           .attr("x", '10')
           .attr("y", '16')
           .attr('dominant-baseline','middle')
           .style('fill','#fff')
           .text(roundNumber(d3.min(colorValue), 2));

       svg.append("svg:text")
           .attr("x", '220')
           .attr("y", '16')
           .attr('dominant-baseline','middle')
           .style('fill','#fff')
           .text(roundNumber(d3.max(colorValue), 2)); 


    }// end of plotTreemap function


  //====================>Plot First Map On Click
  function plotFirst(){
      $('#dp1').val(dsdp1);
      $('#search2').val('');

      d3.select('#legend1 svg').remove();
      d3.select('#charttwo svg').remove();
      var story = ['Total', 'Females'];
      //Data declaration
     var w = 940,
         h = 450,
         root, cnt = count = ct = ms = it = it1 = 0;  

     //==> Color definition
     if(optnum == 0) {
       //========>Color Filteration
       colorValue = [];
       pluck = _.pluck(entries[2], story[num]);
       for(i = 0; i < pluck.length; i++) {
         colorValue.push(parseFloat(pluck[i]));
       }
      $('#tm2').text('BPL Distribution For Year 2011');

     } else if(optnum == 1) {
        colorValue = [];
        for(i = 0; i < entries[2].length; i++) {
          colorValue.push(entries[2][i]);
        }
       $('#tm2').text('Govt Beds Availability For Year 2011');

     } else if(optnum == 2) {
        colorValue = [];
        for(i = 0; i < entries[2].length; i++) {
          colorValue.push(entries[2][i]);
        }
       $('#tm2').text('Child Population Distribution For Year 2011');
      } else if(optnum == 3) {
        colorValue = [];
        var ent1 = _.pluck(entries[2], "Total");
        for(i = 0; i < ent1.length; i++) {
         // if(ent1[i] == "NA") {
             colorValue.push(parseFloat(ent1[i]));
           //}  else {
           // colorValue.push(ent1[i]);
           //}
         }
        $('#tm2').text('Females Distribution For Year 2011');
      } else if(optnum == 4) {
        colorValue = [];
        var ent1 = _.pluck(entries[2], "Total");
        for(i = 0; i < ent1.length; i++) {
          colorValue.push(ent1[i]);
        }
        $('#tm2').text('Females Distribution For Year 2011');
      } else if(optnum == 5) {
        colorValue = [];
        var ent1 = _.pluck(entries[2], "Total");
        for(i = 0; i < ent1.length; i++) {
          colorValue.push(ent1[i]);
        }
        $('#tm2').text('BPL Distribution For Year 2011');

      }

       //Data nesting
     root = {  values: d3.nest()
              .key(function (d) {  return d.State; })
              .rollup(function (d) { return d.map(function(d) { return { key: parseFloat((d[story[num]]).replace(/,/g,""))}; }); })
              .entries(entries[0]) 
            };

      //Color scale define
      var colorScale = d3.scale.linear()
            .range(['green','yellow','red']) // or use hex values
            .domain([d3.min(colorValue), d3.quantile(colorValue, 0.5), d3.max(colorValue)]);

        
      //Plot the data as treemap
      var treemap = d3.layout.treemap()
            .size([w, h])
            .sticky(true)
            .padding([1,1,1,1])
            .sort(function(a,b) { return a.value - b.value; })
            .children(function(d) { return d.values; }) 
            .value(function(d) { return d.key; }); 

      var svg = d3.select("#charttwo")
        .attr("class", "first")
        .append("svg")
        .style("width", w + "px")
        .style("height", h + "px")
        .attr("transform", "translate(.5,.5)")
        .on("click", secondLevel)

      var cell = svg.selectAll(".cell")
              .data(treemap.nodes(root).filter(function(d) { 
                  return d.depth && d.values;
              }));
            
      // enter new elements 
      var cellEnter = cell.enter()
         .append("g") 
           .attr("class", "cell");    
           cellEnter.append("rect");   
           cellEnter.append("text"); 
            
      // update remaining elements 
      cell.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
      
      //Append rectangle
      cell.select("rect") 
            .attr("width", function (d) { return d.dx; })
            .attr("height", function (d) { return d.dy; })
            .style("fill", function (d) {
            if(optnum == 0 || optnum == 5 ) {
              if(_.isNaN(colorValue[cnt++])) { return "grey"}
              else { return colorScale(colorValue[ct++]);}
            } else if(optnum == 1 || optnum == 2 || optnum == 4) {
              if(_.isNaN(colorValue[cnt++])) { return "grey"}
              else { return colorScaleRev(colorValue[ct++]);}
            } else if(optnum == 3) {
              return colorScale3(colorValue[ct++]);
            }
          })
        
          //cell.append('g:title').text(function (d) { return d.key+ ": size =" +roundNumber(d.value, 2) + " color = " +roundNumber(colorValue[count++], 2); })
          cell.append('g:title').text(function (d) { 
            if(colorValue[it1++] == 404) {
                return d.key+" "+$('#dp1').html().split(':')[1]+': ' +roundNumber(d.value, 2)+' '+ $('#color2').html().split(':')[1] +' = '+ " =  undefined"; 
            } else {
              return d.key+" "+$('#dp1').html().split(':')[1]+': ' +roundNumber(d.value, 2)+' '+ $('#color2').html().split(':')[1] +' = '+roundNumber(colorValue[count++], 2); 
            }
          });

      //Append text
      cell.select("text")
          .attr("x", function(d) { return d.dx / 2; })
          .attr("y", function(d) { return d.dy / 2; })
          .attr("dy", ".35em")
          .style('font-family','Georgia')
          .attr("text-anchor", "middle")
          .style('font-size', function(d) {
            if(optnum == 3) {
              if((d.dx / 10) < 4.5) {
                return 2 + "px";
              } else {
                return d.dx / 10 + "px"; 
              }
            } else {
              if((d.dx / 10) < 4.5) {
                return 0 + "px";
              } else {
                return d.dx / 10 + "px"; 
              }
            }
          })
         .text(function(d) { return d.key; }) 
     
     //==>Dynamic Color Legend
      var svg = d3.selectAll('#legend1').append('svg')
          .attr('width','300')
          .attr('height', '30');

      var gradient = svg.append("svg:defs")
      .append("svg:linearGradient")
        .attr("id", "gradient");

      gradient.append("svg:stop")
          .attr("offset", "0%")
          .attr("stop-color", function(){
            if(optnum == 1 || optnum == 2 || optnum == 4){
              return colorScaleRev(d3.min(colorValue));
            } else if(optnum == 3) {
              return colorScale3(d3.min(_.filter(colorValue, function(d) { return d!=404 })));
            } else if(optnum == 0 || optnum == 5) {
              return colorScale(d3.min(colorValue));
            }
          });

      gradient.append("svg:stop")
          .attr("offset", "50%")
          .attr("stop-color", function(){
                  if(optnum == 1 || optnum == 2 || optnum == 4){
                    return colorScaleRev(d3.quantile(colorValue, 0.5));
                  } else if(optnum == 3) {
                    return colorScale3(d3.quantile(_.filter(colorValue, function(d) { return d!=404 }), 0.5));
                  } else if(optnum == 0 || optnum == 5)  {
                    return colorScale(d3.quantile(colorValue, 0.5));
                  }
                });

      gradient.append("svg:stop")
          .attr("offset", "100%")
          .attr("stop-color", function(){
            if(optnum == 1 || optnum == 2 || optnum == 4){
              return colorScaleRev(d3.max(colorValue));
            } else if(optnum == 3) {
              return colorScale3(d3.max(_.filter(colorValue, function(d) { return d!=404 })));
            } else if(optnum == 0 || optnum == 5)  {
              return colorScale(d3.max(colorValue));
            }
          });

      svg.append("svg:rect")
          .attr("width", '290')
          .attr("height", '28')
          .style("fill", "url(#gradient)");

      svg.append("svg:text")
          .attr("x", '10')
          .attr("y", '16')
          .attr('dominant-baseline','middle')
          .style('fill','#fff')
          .text(roundNumber(d3.min(colorValue), 2));

      svg.append("svg:text")
          .attr("x", '220')
          .attr("y", '16')
          .attr('dominant-baseline','middle')
          .style('fill','#fff')
          .text(function(){
           if(optnum == 3) {
             return roundNumber(d3.max(_.filter(colorValue, function(d) { return d!=404 })), 2);
          } else {
            return roundNumber(d3.max(colorValue), 2);
          }
        }); 
 
    }

}//End of treemap second 