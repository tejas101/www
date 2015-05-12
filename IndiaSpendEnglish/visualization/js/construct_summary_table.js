//var temp = createMenu();
var sel_metric_filtData;
var stat_filt_data=[], incm_filt_data=[], expd_filt_data=[];
var final_table_summary = {};
//var low_good = ['Student: Teacher Ratio - All Schools','Student: Teacher Ratio - Primary','Student: Teacher Ratio - Upper Primary','Decadal Population Growth (2001-2011) (in %)']
var low_good = ['Drop Out Rate Primary','Industry GSDP (%)','Agri GSDP (%)','Service GSDP (%)','Revenue Receipts (BE) (As % Of GSDP)','Fiscal Deficit (BE) (As % Of GSDP)','Unemployment Rate   (2011-12)','Rural Unemployment Rate(2011-12)','Urban Unemployment Rate (2011-12)','Infant Mortality Rate (IMR)','HIV-Estimated Adults (15-49) With HIV (%)','Poverty Rate']
// ************************************** Hash url change action *****************************************//
function hashchange(e) {
  // Decoding the url
  var hash = decodeURIComponent(window.location.hash.replace(/^#/, ''));
  var temp = new Array();
  temp = hash.split("/");
  if (onLoad_change_metric != "yes") {
    set_year_dropdown(temp[0],'urlChange');
  }

  temp.shift();
  dyn_sel_metrics_1 = [];
  dyn_sel_metrics_1 = hash.split("/");
  dyn_sel_metrics_1.shift();
  if (temp[0] != " " && temp[0] != undefined) {
    //console.log('url change ===============> Construction table')
    if (temp.length > 10) {
      d3.select("#summary_table div#summary_container").remove()
      d3.select("#display_error strong").remove()
      d3.select("#display_error").append("strong").attr("class","alert alert-error").text(temp.length+" metrics were selected. Metric selection cannot exceed more than 10.");
      d3.select(".ajax_loading").style("visibility", "hidden");
      exit();
      //return;
    }
    else {
      d3.select("#display_error strong").remove();
      loadjscssfile("js/sticky/jquery.hcsticky-min.js", "js")
      loadjscssfile("js/visual_button_float.js", "js")
      check_checkbox_metrics(temp); //---> Check checkboxes based on the metrics coming from url
      construct_summary_dict(dyn_sel_metrics_1);
      construct_color_dropdown(temp);
    }
  }
  else{
    clear_tags_onerror();
  }
  loadjscssfile("js/sticky/jquery.hcsticky-min.js", "js")
  loadjscssfile("js/visual_button_float.js", "js")
}

function clear_tags_onerror() {
  d3.select("#summary_table div#summary_container").remove()
  $('.map_color_metrics').empty();
  d3.select(".state_summary table").remove()
  d3.select("div#map_header h3").remove();
  d3.select("div#state_click_legend").remove();
  d3.select("div#color_legend svg").remove();
  d3.selectAll("div#india_map_container svg g").attr("data-original-title","");
  d3.selectAll("div#india_map_container svg g").attr("fill","#fff");

  d3.select("#display_error strong").remove();
  d3.select("#display_error").append("strong").attr("class","alert alert-error").text("Select Metrics from the Master Metric list");
  d3.select(".ajax_loading").style("visibility", "hidden");
}

function error_dialog(args) {
  var t = "Error";
  $("#div-dialog-warning").dialog({
    title: t,
    resizable: false,
    height: 160,
    modal: true,
    buttons: {
        "Ok" : function () {
            $(this).dialog("close");
        }
    }
  }).parent().addClass("ui-state-error");
}
// ************************************** Table Summary Construction Area *****************************************//

function construct_summary_dict(sel_metrics) {
  var stat_metr_sel = [];
  var incm_metr_sel = [];
  var expd_metr_sel = [];

  // Selected metrics are seperated
  sel_metrics.forEach(function(m){
    if (_.contains(stat_data_metrics, m)) {
      stat_metr_sel.push(m);
    }
    if (_.contains(incm_data_metrics, m)) {
      incm_metr_sel.push(m);
    }
    if (_.contains(expd_data_metrics, m)) {
      expd_metr_sel.push(m);
    }
  })

  // construct dictionary to pass for table summary construction
  final_table_summary = {};
  if (stat_metr_sel.length != 0) {
    stat_filt_data = data_filter(filter_year_data,stat_metr_sel,"Sub-category");
    stat_nested_data = d3.nest()
                        .key(function(d){ return d["State"];})
                        .key(function(d){ return d["Sub-category"];})
                        .map(stat_filt_data);
    //-- Iterates through each districts
    d3.keys(stat_nested_data).forEach(function(d){
      //-- Iterates through the static metrics selected
      var met_val = {};
      stat_metr_sel.forEach(function(m){
        if (typeof stat_nested_data[d][m] == "undefined") {
          //met_val[e] = parseFloat("NA");//-->Inserts null
          met_val[m] = "NA";
        }
        else{
          //met_val[e] = parseFloat(stat_nested_data[d][e][0]["Total"]);//--> Inserts null
          met_val[m] = stat_nested_data[d][m][0]["Total"];
        }
      })
      final_table_summary[d] = met_val;
    })
  }
//var yr = ['2010','2011','2012']
  if (incm_metr_sel.length != 0) {
    //alert('hello');
    incm_filt_data = data_filter(income_data,incm_metr_sel,"Sub-Category 3");
    incm_nested_data = d3.nest()
                        .key(function(d){ return d["State"];})
                        .key(function(d){ return d["Sub-Category 3"];})
                        .map(incm_filt_data);






    var incm_india_total_sum = d3.nest()
                              .key(function(d) { return d["Sub-Category 3"]; })
                              .rollup(function(d) {
                                var temp = {}, s = {};
                                var result = d.reduce(function(prev, cur, index, arr) {
                                  if (index == 1) {
                                    _.each(incm_avail_yrs,function(f){
                                        temp[f] = [prev[f]];
                                    })
                                  }
                                  _.each(incm_avail_yrs,function(f){
                                    if(temp.hasOwnProperty(f)){
                                      _.extend(temp[f], temp[f].push(cur[f]))
                                    } else {
                                      temp[f] = [cur[f]];
                                    }
                                  })
                                  return temp;
                                  //return prev.values.map(function(d,i) { return d + cur.values[i];});
                                });
                                _.each(_.keys(result),function(d){
                                  tot_sum = d3.sum(result[d], function(d) { return d });
                                  s[d] = tot_sum;
                                })
                                return s;
                              })
                              .entries(incm_filt_data);
//  console.log('Dynamic values ==========> ',JSON.stringify(incm_india_total_sum));


    // ==================================================== solution - 2 for handling year dynamically.
    //var temp = {};
    //var s = {}, t={};
    //_.each(_.keys(incm_india_total_sum),function(key){
    //  _.each(incm_india_total_sum[key], function(d){
    //    _.each(yr,function(f){
    //      if(temp.hasOwnProperty(f)){
    //        _.extend(temp[f], temp[f].push(d[f]))
    //      } else {
    //        temp[f] = [d[f]];
    //      }
    //    })
    //  });
    //
    //  _.each(_.keys(temp),function(d){
    //    tot_sum = d3.sum(temp[d], function(d) { return d });
    //    s[d] = tot_sum;
    //  })
    //  t[key] = s
    //  s = {}
    //  temp = {}
    //})
    // ====================================================

    incm_india_total_sum.forEach(function(d){
      incm_sprk_india_total[(d.key)] = d.values;
    })


    var combined_districts = _.union(d3.keys(final_table_summary),d3.keys(incm_nested_data));
    // Iterates through each districts
    combined_districts.forEach(function(d){
      incm_metr_sel.forEach(function(m){
        var met_val = {};
        if (typeof incm_nested_data[d] == "undefined") {
          final_table_summary[d][m] = "NA";
        }
        else{
          if (typeof incm_nested_data[d][m] == "undefined") {
            //met_val[e] = parseFloat("NA");//-->Inserts null
            met_val[m] = "NA";
          }
          else{
            //met_val[e] = parseFloat(stat_nested_data[d][e][0]["Total"]);//--> Inserts null
            met_val[m] = incm_nested_data[d][m][0][selected_year];
          }

          if(final_table_summary.hasOwnProperty(d)){
            final_table_summary[d] = _.extend(final_table_summary[d], met_val)
            //final_table_summary[d][d3.keys(met_val)] = d3.values(met_val)[0];
          }
          else{
            final_table_summary[d] = met_val;
            stat_metr_sel.forEach(function(stat_metrics){
              var temp = {}
              temp[stat_metrics] = "NA"
              final_table_summary[d] = _.extend(final_table_summary[d], temp);
            })
          }
        }
      })//-- End of income metrics iteration
    })//-- End of Districs iteration
  }

  if (expd_metr_sel.length != 0) {
    expd_filt_data = data_filter(expenditure_data,expd_metr_sel,"Sub-Category 4");
    expd_nested_data = d3.nest()
                        .key(function(d){ return d["State"];})
                        .key(function(d){ return d["Sub-Category 4"];})
                        .map(expd_filt_data);

    var expd_india_total_sum = d3.nest()
                              .key(function(d) { return d["Sub-Category 4"]; })
                              //.rollup(function(year) {
                              //  return {
                              //    "2010": d3.sum(year, function(d) {return d[2010];}),
                              //    "2011": d3.sum(year, function(d) {return d[2011];}),
                              //    "2012": d3.sum(year, function(d) {return d[2012];})
                              //  }
                              //})
                              .rollup(function(d) {
                                var temp = {}, s = {};
                                var result = d.reduce(function(prev, cur, index, arr) {
                                  if (index == 1) {
                                    _.each(expd_avail_yrs,function(f){
                                        temp[f] = [prev[f]];
                                    })
                                  }
                                  _.each(expd_avail_yrs,function(f){
                                    if(temp.hasOwnProperty(f)){
                                      _.extend(temp[f], temp[f].push(cur[f]))
                                    } else {
                                      temp[f] = [cur[f]];
                                    }
                                  })
                                  return temp;
                                  //return prev.values.map(function(d,i) { return d + cur.values[i];});
                                });
                                _.each(_.keys(result),function(d){
                                  tot_sum = d3.sum(result[d], function(d) { return d });
                                  s[d] = tot_sum;
                                })
                                return s;
                              })
                              .entries(expd_filt_data);

    expd_india_total_sum.forEach(function(d){
      expd_sprk_india_total[(d.key)] = d.values;
    })

    var combined_districts = _.union(d3.keys(final_table_summary),d3.keys(expd_nested_data));
    // Iterates through each districts
    combined_districts.forEach(function(d){
      expd_metr_sel.forEach(function(m){
        var met_val = {};
        if (typeof expd_nested_data[d] == "undefined") {
          final_table_summary[d][m] = "NA";
        }
        else{
          if (typeof expd_nested_data[d][m] == "undefined") {
            //met_val[e] = parseFloat("NA");//-->Inserts null
            met_val[m] = "NA";
          }
          else{
            met_val[m] = expd_nested_data[d][m][0][selected_year];
          }

          if(final_table_summary.hasOwnProperty(d)){
            final_table_summary[d] = _.extend(final_table_summary[d], met_val)
            //final_table_summary[d][d3.keys(met_val)] = d3.values(met_val)[0];
          }
          else{
            final_table_summary[d] = met_val;
            stat_metr_sel.forEach(function(stat_metrics){
              var temp = {};
              temp[stat_metrics] = "NA";
              final_table_summary[d] =  _.extend(final_table_summary[d], temp);
            })
            incm_metr_sel.forEach(function(incm_metrics){
              var temp = {}
              temp[incm_metrics] = "NA"
              final_table_summary[d] = _.extend(final_table_summary[d], temp);
            })
          }
        }
      })//-- End of expenditure metrics iteration
    })//-- End of Districs iteration
  }
//console.log(JSON.stringify(_.keys(final_table_summary)));
  // This will sort the dictionary based upon the State name.
  var sorted_table_summary = d3.entries(final_table_summary).sort(function(a, b){
    return d3.ascending(a.key,b.key);
  })

  construct_summaryTable(sel_metrics,sorted_table_summary)
  loadjscssfile("js/fix_table_header.js", "js")
}

//=====================> Filters data based on multiple value references from a column
function data_filter(data,selectedMetrics,column_name) {
  var ids = {};
  // ===================================> Solution 1
  //var out = data.filter(function(d) { return _.contains(selectedMetrics,d[column_name]) })
  // ===================================> Solution 2
  _.each(selectedMetrics, function (bb) { ids[bb] = true; });
  var out = _.filter(data, function(val){
                return ids[val[column_name]];
              });
  return out;
}

//****************************************************************************************************************************************
//  Summary table construction.Draws cell bar function. Fill Cell gradient function
//****************************************************************************************************************************************

function construct_summaryTable(table_headers, input_data) {
  d3.select("#summary_table div#summary_container").remove()
  div_container = d3.select("#summary_table").append('div').attr("id","summary_container").append('div').attr("class","relativeContainer");

  // State Header
  div_container.append('div').attr('class','fixedTB')
      .append("table").attr("width","100%").attr("height","100%").attr("border","0")
      .append("tr")
      .append("td").attr("id","State").attr("class","sort_neutral")
      .append('svg')
      .attr("width", "250")
      .attr("height", "70")
      .append("text").attr("x","65.0").attr("y","35.0").attr("dominant-baseline","middle").attr("fill","#000").style("font-size","20px")
      .text("State");

  // State Names List
  div_container.append('div').attr('class','leftContainer').append('div').attr('class','leftSBWrapper')
              .append("table").attr("width","100%").attr("cellpadding","6").attr("cellspacing","20").attr("border","0").selectAll("tr").data(input_data).enter().append("tr")//.attr("height","45px")
              .append("td").text(function(d){ return String(d.key) });

  right_container = div_container.append('div').attr('class','rightContainer')

  // Top Header metrics
  test = right_container.append('div').attr('class','topSBWrapper')
                  .append("table").attr("width","100%").attr("height","100%").attr("cellpadding","5").attr("border","0").append("tr").attr("class","table_header")
                  .selectAll("td").data(table_headers).enter()
                  .append("td").attr("class","sort_neutral").attr("id",function(d){return display_dict[d]})
                  .attr("width","100px").attr("height","62px").attr("title",function(d){ return d })
                  .style("border-right","1px solid black")
                  .style("word-wrap", "break-word")

                  test.append("label").attr("class","summary_tbl_headers")
                      .text(function(d){ return display_dict[d] });
                  test.append('svg')
                      .attr("width", "101")
                      .attr("height", "0")
                      .text(function(d){ return '' });


  // Visual representation
  tr = right_container.append('div').attr('class','SBWrapper')
                  .append("table").attr("width","100%").attr("cellpadding","6").attr("cellspacing","20").attr("border","0").selectAll("tr").data(_.keys(input_data)).enter().append("tr")//.attr("height","45px");//.attr('bgcolor','#808080');

  table_headers.forEach(function(metric){
    var column_data = []
    //_.values(input_data).forEach(function(obj){
    //  column_data.push(obj[metric])
    //})
    input_data.forEach(function(obj){
      column_data.push(obj.value[metric])
    })

    if (_.contains(metric,"%")) {
      fill_cellGradient(metric,column_data)
    }
    else if (metric.indexOf('Rate') > -1) {
      fill_cellGradient(metric,column_data)
    }
    else{
      draw_cellbar(column_data);
    }
  })

  loadjscssfile("css/summary_tableStyle.css", "css")
  jq203('.topSBWrapper table tr td').tooltip({
    'trigger': 'hover',
    'placement': 'top',
    'container': 'body',
  });
  d3.select(".ajax_loading").style("visibility", "hidden");
  //sort_table();
}

//function sort_table(args) {
//  d3.selectAll(".leftSBWrapper table tr")
//        .sort(function(a, b) {
//                return d3.descending(a,b);
//        });
//}


//********************************************************** Function that draws cell bar.
function draw_cellbar(input_data) {
  var width = 100,
      height = 20;

  format = d3.format(",");
  var max = d3.max(input_data, function(val) {
                return +val;
            });

  var t = tr.append("td").attr("width", "100px")
            .data(input_data)
          //.text(function(d){ return d.value })
            .append('svg')
            .attr("width", width)
            .attr("height", height)
            .append("g")

            t.append("rect")
              .attr("x",0)
              .attr("y",0)
              .attr("width",function(d){ return ((d/max)*100) })
              //.attr("width",function(d){ return (d.value) })
              .attr("height", 20)
              //.attr("fill",'steelblue');
              .attr("fill",'#a0b0d4');

            t.append("text")
              .attr("x",0)
              .attr("y",15)
              .text(function(d){ return d })
}

// ***************************** Function that fills <td> with cell gradient.
function fill_cellGradient(metric,input_data) {
  //== Finds the min and max value of an aggregated data
  var min = d3.min(input_data, function(val) {
    return +val;
  });
  var max = d3.max(input_data, function(val) {
    return +val;
  });
  var median = (min + max)/2;
  var color_calculator
  //== color scales
  if (_.contains(low_good,metric)) {
    color_calculator = d3.scale.linear().domain([min, median, max]).range(['green', 'yellow', 'red']);
  }
  else{
    color_calculator = d3.scale.linear().domain([min, median, max]).range(['red', 'yellow', 'green']);
  }
  tr.append("td").attr("width","100px")
    .append('svg')
    .attr("width", "100")
    .attr("height", "20")
    .data(input_data)
    .style("background-color",function(d){ return color_calculator(d);})
        .append("text")
              .attr("x",100)
              .attr("y",10)
              .attr("text-anchor","end")
              .attr("dominant-baseline","middle")
    .text(function(d){ return d });
}
