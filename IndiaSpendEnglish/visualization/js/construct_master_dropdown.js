var static_data, income_data, expenditure_data, filter_year_data, nested_data, selected_year, sel_color_metric;
var stat_nested_data, incm_nested_data, expd_nested_data;
var stat_data_metrics=[], incm_data_metrics = [], expd_data_metrics = [];

var dyn_sel_metrics_1 = [],dyn_sel_metrics_2 = ["State"];
var india_total = {},display_dict = {}, incm_sprk_india_total={}, expd_sprk_india_total={};

var pre_sel_metrics, default_metric_list, incm_avail_yrs, expd_avail_yrs;
var onLoad_change_metric;

//var default_metric_list =  ['TOTAL INCOME', 'TOTAL  EXPENDITURE ','TOTAL REVENUE EXPENDITURE','Population (in million)','Poverty/ Per Capita Income/ Rural','GSDP At Constant 2004-05 Prices (in Rs cr)','Growth Rate (At Constant 2004-05 Prices) (%) ',' Fiscal Services','IMR','MMR','Literacy Rate (in %)','Drop Out Rate Primary ','Road Coverage (Total in km)','Railway Coverage (Total in km) (2011-12)','Availability of Drinking Water Source Within Premises (in %)','Houses With Electricity (in %)']
d3.csv("config.csv",function(data){
   default_metric_list = _.pluck(data, 'Default Metrics');
   //incm_avail_yrs      = _.pluck(data, 'Income Year');
   //expd_avail_yrs      = _.pluck(data, 'Expenditure Year');
   incm_avail_yrs = _.pluck(data, 'Income Year').filter(function(e){return e});
   expd_avail_yrs = _.pluck(data, 'Expenditure Year').filter(function(e){return e});
});

//var default_metric_list =  ['TOTAL INCOME','Internal Debt']

$("#india_map_container").load("india-state.svg" + " svg");
//=========================================================================================================//
//----------------------- Read multiple data files for input  --------------------------------------//
//=========================================================================================================//
queue()
    .defer(d3.csv, "data/Static data transformed.csv")
    .defer(d3.csv, "data/TOTAL INCOME FOR ALL STATES NEW.csv")
    .defer(d3.csv, "data/TOTAL EXPENDITURE ALL STATES.csv")
    //.await( function(error, static_data, income_data) {console.log(static_data,income_data)})
    .await(restOfAction)

//=========================================================================================================//
//----------------------- Construct Year drop down list --------------------------------------//
//=========================================================================================================//
function restOfAction(err, stat, incm, expd) {
  stat.forEach(function(data){
    data["State"] = data["State"].toUpperCase();
  });
  incm.forEach(function(data){
    data["State"] = data["State"].toUpperCase();
  });
  expd.forEach(function(data){
    data["State"] = data["State"].toUpperCase();
  });

  static_data = stat;
  income_data = incm;
  expenditure_data = expd;

  var yr = d3.nest()
              .key(function(d){ return d["To Year"];})
              .map(static_data);

  d3.select("#year_dropdown").append("select").attr("id","disp_year").attr("class","year_list")
    .attr("onchange","filter_year_dat(this.value,'onChange')")
    .style("width","100px")
    .selectAll("option").data(_.keys(yr),function(d){ return d }).enter()
    .append("option").attr("value",function(d){ return d })
    .text(function(d){ return d });

  construct_button(); // line 210
  //construct_year_dropdown(yr) //------------------> 1
  if(window.location.hash) {
    onLoad_change_metric = "no";
    window.addEventListener('hashchange', hashchange);
    hashchange();
  }
  else {
    onLoad_change_metric = "yes";
    set_year_dropdown("2011",'onLoad');
    check_checkbox_metrics(default_metric_list);
    pre_sel_metrics = default_metric_list;
    selectedMetrics("onload");
  }
}

//=========================================================================================================//
//----------------------- Set default year in year drop down list.  --------------------------------------//
//=========================================================================================================//
// filter_year_dat(year) ==> Constructs Master metric list dropdown

function set_year_dropdown(year,call_type) {
  //console.log('==============>'+year)
  //console.log('set_year_dropdown==============>'+call_type)
  //======================> Default selection for dropdown
  function setSelectedIndex(s, v) {
    for ( var i = 0; i < s.options.length; i++ ) {
     if ( s.options[i].text == v ) {
     s.options[i].selected = true;
     return;
     }
    }
  }
  setSelectedIndex(document.getElementById('disp_year'),year);
  //======================>
  filter_year_dat(year,call_type);
}

//******************************************************************************************************//
//*********** Filters data based upon the year and constructs master drop down list *************//
//******************************************************************************************************//
// On select of drop-down year this method will be called.
function filter_year_dat(sel_year,call_type) {
  //console.log('filter_year_dat==============>'+call_type)
  selected_year = sel_year;
  stat_data_metrics=[], incm_data_metrics = [], expd_data_metrics = [];
  d3.select("div.master_dropdown ul").remove();
  filter_year_data = static_data.filter(function(d){return (d['To Year'] == sel_year) });
  filter_year_data.forEach(function(data){
    display_dict[data["Sub-category"]] = data["Display Names"]
  });
  income_data.forEach(function(data){
    display_dict[data["Sub-Category 3"]] = data["Display Name"]
  });
  expenditure_data.forEach(function(data){
    display_dict[data["Sub-Category 4"]] = data["Display Names"]
  });

  stat_nested_metrics = d3.nest()
                      .key(function(d){ return d["Category"];})
                      .key(function(d){ return d["Sub-category"]})
                      .map(filter_year_data);
  d3.keys(stat_nested_metrics).forEach(function(key){
    stat_data_metrics.push(d3.keys(stat_nested_metrics[key]));
  })
  stat_data_metrics = d3.merge(stat_data_metrics)

  inc_nested_data = d3.nest()
                      .key(function(d){ return d["Category"];})
                      .key(function(d){ return d["Sub-Category 3"]})
                      .map(income_data);
  d3.keys(inc_nested_data).forEach(function(key){
    incm_data_metrics.push(d3.keys(inc_nested_data[key]));
  })
  incm_data_metrics = d3.merge(incm_data_metrics)

  expd_nested_data = d3.nest()
                      .key(function(d){ return d["Category"];})
                      .key(function(d){ return d["Sub-Category 4"]})
                      .map(expenditure_data);
  d3.keys(expd_nested_data).forEach(function(key){
    expd_data_metrics.push(d3.keys(expd_nested_data[key]));
  })
  expd_data_metrics = d3.merge(expd_data_metrics)

  var master_met_container = d3.select(".master_dropdown").append("ul").attr("id","example2").attr("class","accordion")

  build_master_dropdown(stat_nested_metrics)
  if (_.contains(incm_avail_yrs,sel_year)) {
    build_master_dropdown(inc_nested_data)
  }
  if (_.contains(expd_avail_yrs,sel_year)) {
    build_master_dropdown(expd_nested_data)
  }
  //if (sel_year == 2010 || sel_year == 2011 || sel_year == 2012) {
  //  build_master_dropdown(inc_nested_data)
  //  build_master_dropdown(expd_nested_data)
  //}

  //=============================================>
  function build_master_dropdown(met_data) {
     var category_list = master_met_container.selectAll("li")
                                          .data(d3.keys(met_data),function(d){ return d})
                                          .enter().append("li")
                        category_list.append("h5")
                              .text(function(d){ return d.toUpperCase() })

    var level2        = category_list.append("div").attr("class","panel loading").attr("id","metric_list")

                        //level2.append("input").attr("type","text")

    var level3        = level2.append("ul").attr("id","list")
                                  .selectAll("li")
                                  .data(function(d){ return d3.keys(met_data[d]) })
                                  .enter().append("li");

                        level3.append("input")
                              .attr("type","Checkbox")
                              .attr("class","submenu_list")
                              .attr("value",function(d){ return d != "" ? d : null });

                        level3.append("span")
                              .style("Padding-left","6px")
                              .text(function(d){ return d != "" ? d : null })
  }
  //loadjscssfile("js/jquery-1.4.2.min.js", "js")
  //loadjscssfile("js/jquery.accordion.2.0.js", "js")
  loadjscssfile("css/menu/accordion.core.css", "css")
  loadjscssfile("css/menu/menu_style.css", "css")
  loadjscssfile("js/menu_logic.js", "js")

  if (call_type == 'onChange') {
    check_checkbox_metrics(pre_sel_metrics);
    selectedMetrics('onChange');
    onLoad_change_metric = "yes";
  }
}

//********************************************************************************************************//
function construct_button() {
  d3.select(".visual_button").append("input")
    .attr("class","btn btn-primary")
    .attr("type","button")
    .attr("value","Visualise")
    .attr("onclick","selectedMetrics('visualise')")//-----> onclick event

  d3.select(".visual_button").append("input")
    .attr("class","btn btn-primary")
    .attr("id","clear_chk_metrics")
    .attr("type","button")
    .attr("value","Show Default")
    .attr("onclick","check_defaultMetrics()");//-----> onclick event

  d3.select(".visual_button").append("input")
    .attr("class","btn btn-danger")
    .attr("id","deselect_all_metrics")
    .attr("type","button")
    .attr("value","Deselect All")
    .attr("onclick","clearChecked()");//-----> onclick event

  d3.select(".search_box").append("h6").text("Search Metric:")
  d3.select(".search_box").append("input").attr("type","text").attr("name","search_metric").attr("id","search_metric").attr("placeholder","Type text to filter metrics list");

  loadjscssfile("js/sticky/jquery.hcsticky-min.js", "js")
  loadjscssfile("js/visual_button_float.js", "js")
}
//*************************** End of Master metrics dropdown construction **********************************

//=========================================================================================================//
//--  Check the check boxes in the master drop down list ---//
//=========================================================================================================//
function check_checkbox_metrics(sel_metrics) {
  var menu_list_var = $(".submenu_list");
  $('.submenu_list').each(function(d,inp){ if (_.contains(sel_metrics, inp.value)){this.checked = true} });
}

//=========================================================================================================//
//--  Function called oncliking visualise button. Returns an Array List of selected metrics by the user ---//
//=========================================================================================================//
function selectedMetrics(args) {
  dyn_sel_metrics_1 = [];
  $('.submenu_list').each(function(){
    var $this = $(this);
    if ($(this).is(':checked')) {
      dyn_sel_metrics_1.push($this.val());
      dyn_sel_metrics_2.push($this.val());
    }
  });

  construct_url(dyn_sel_metrics_1);
  if (onLoad_change_metric == 'yes') {
    if (dyn_sel_metrics_1.length > 0) {
      if (dyn_sel_metrics_1.length > 10) {
        d3.select("#summary_table div#summary_container").remove()
        d3.select("#display_error strong").remove()
        d3.select("#display_error").append("strong").attr("class","alert alert-error").text(dyn_sel_metrics_1.length+" metrics were selected. Metric selection cannot exceed more than 10.");
      }
      else{
        d3.select("#display_error strong").remove();
        construct_summary_dict(dyn_sel_metrics_1);
        //dyn_sel_metrics_1.unshift('Select Color Metric');
        construct_color_dropdown(dyn_sel_metrics_1);
        //dyn_sel_metrics_1.shift();
      }
    }
    else{
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
    }
 }
}

//=========================================================================================================//
//--  URL hash list construction ---//
//=========================================================================================================//
function construct_url(sel_metrics) {
  var sel_year = $("#disp_year option:selected").text();
  //var current_url =  window.location.href;
  var edited_url= sel_year+'/';
  $.each(sel_metrics,function(index,val){
    edited_url = edited_url+val+'/';
  });
  window.location.hash = encodeURIComponent(edited_url.slice(0,-1));
}

// Called on clicking Deselect all button
function clearChecked(){
  //console.log('deselect')
  onLoad_change_metric = "yes";
  $('.submenu_list').each(function(d,inp){ this.checked = false });
  var loc = window.location.href,
  index = loc.indexOf('#');
  if (index > 0) {
    d3.select("#summary_table div#summary_container").remove();
    //d3.select("#color_legend_top").style("visibility",'hidden');
    $('.map_color_metrics').empty();
    d3.select(".state_summary table").remove();
    d3.select("div#state_click_legend").remove();
    d3.select("div#color_legend svg").remove();
    d3.select("div#map_header h3").remove();
    d3.selectAll("div#india_map_container svg g").attr("data-original-title","");
    d3.selectAll("div#india_map_container svg g").attr("fill","#fff");
    window.location = loc.substring(0, index+1);
  }
  d3.select("#display_error strong").remove();
  d3.select("#display_error").append("strong").attr("class","alert alert-error").text("Select Metrics from the Master Metric list");
}

function check_defaultMetrics(){
  $('.submenu_list').each(function(d,inp){ this.checked = false });
  var loc = window.location.href,
  index = loc.indexOf('#');
  if (index > 0) {
    window.location = loc.substring(0, index);
  }
}

//**************************************************************************************************************//
//************************** construct color metrics dropdown to the side of India map  ************************
//**************************************************************************************************************//
var temp_count = 0;
function construct_color_dropdown(metrics) {
  temp_count = 0;
  d3.select(".color_metrics h3").remove();
  d3.select(".color_metrics select").remove();
  d3.select(".color_metrics").append("h3").text("Color metrics List")
  d3.select(".color_metrics").append("select").attr("class","map_color_metrics").attr("onchange","fill_map_color(this)").style("width","350px")
    .selectAll("option")
    .data(metrics,function(d){ return d })
    .enter()
    .append("option")
    .attr("value",function(d){ return d })
    .text(function(d){ return d });
    //.text(function(d){ return d != "State" ? d : "Select Color Metric"; })

  d3.select(".state_summary h4").remove();
  d3.select(".state_summary table").remove();
  d3.select("div#map_header h3").remove();
  d3.select("div#state_click_legend").remove();
    //d3.select("div#color_legend svg defs").remove();

  var def = {};
  def["value"] = metrics[0];
  fill_map_color(def);
}

//********************************************** Fill the color on the Map ***************************************
function fill_map_color(args) {
  sel_color_metric = args.value;
  //console.log(sel_color_metric);
  temp_count = temp_count + 1;
  d3.select("div#map_header h3").remove();
  //d3.select("div#map_header").append("h3").text(args.value+' - '+selected_year);
  d3.select("div#map_header").append("h3").style("margin","3px").text(selected_year);
  d3.selectAll("div#india_map_container svg g").attr("fill","#fff");

  var value_list = _.pluck(final_table_summary, args.value);
  //console.log(final_table_summary);
  //console.log(value_list);
  // ----------------------- Finds the min and max value of an aggregated data set using nest()
  //NA_filter_data = value_list.filter(function(d){return (d != "NA") });
  var min = d3.min(value_list, function(val) {
    return +val;
  });

  var max = d3.max(value_list, function(val) {
    return +val;
  });
  var median = (min + max)/2;

  d3.select("div#color_legend svg").remove();
  legend_container = d3.select("div#color_legend").append("svg").attr("xmlns","http://www.w3.org/2000/svg").attr("width","200").attr("height","25")
  gradient_container = legend_container.append("defs").append("linearGradient").attr("id","gradient_legend_a916980f-36a2-437e-bb68-9f6e888c5454")
                        gradient_container.append("stop").attr("offset","0.0%").attr("stop-color","#ff0000");
                        gradient_container.append("stop").attr("offset","50.0%").attr("stop-color","#ffff00");
                        gradient_container.append("stop").attr("offset","100.0%").attr("stop-color","#00ff00");
  legend_container.append("rect").attr("fill","url(#gradient_legend_a916980f-36a2-437e-bb68-9f6e888c5454)").attr("x","0").attr("y","0").attr("width","200").attr("height","25");

  // ----------------------- color scales
  var color_calculator
  //== color scales
  if (_.contains(low_good,args.value)) {
    color_calculator = d3.scale.linear().domain([min, median, max]).range(['green', 'yellow', 'red']);
    legend_container.append("text").attr("x","10.0").attr("y","12.0").attr("dominant-baseline","middle").attr("fill","#fff").text(max);
    legend_container.append("text").attr("x","75.0").attr("y","12.0").attr("text-anchor","middle").attr("dominant-baseline","middle").attr("fill","#000")
    legend_container.append("text").attr("x","190").attr("y","12.0").attr("text-anchor","end").attr("dominant-baseline","middle").attr("fill","#000").text(min);
  }
  else{
    color_calculator = d3.scale.linear().domain([min, median, max]).range(['red', 'yellow', 'green']);
    legend_container.append("text").attr("x","10.0").attr("y","12.0").attr("dominant-baseline","middle").attr("fill","#fff").text(min);
    legend_container.append("text").attr("x","75.0").attr("y","12.0").attr("text-anchor","middle").attr("dominant-baseline","middle").attr("fill","#000")
    legend_container.append("text").attr("x","190").attr("y","12.0").attr("text-anchor","end").attr("dominant-baseline","middle").attr("fill","#000").text(max);
  }

  //-----------------------------------> fill the map color
  var parent = d3.selectAll("div#india_map_container svg g")
                  .attr("data-original-title","");

  _.keys(final_table_summary).forEach(function(state_name){
    parent.select("g[id="+"\""+state_name+"\""+"]")
          .attr("fill",color_calculator(final_table_summary[state_name][args.value]))
          .attr("title",String("State: "+state_name+"; "+"Value: "+final_table_summary[state_name][args.value]))
          .attr("data-original-title",String("State: "+state_name+"; "+"Value: "+final_table_summary[state_name][args.value]));
  });

  //Map Hover tooltip "jq191 is an alias of jquery1.9.1 version"
  jq203('g').tooltip({
    'trigger': 'hover',
    'placement': 'top',
    'container': 'body',
  });

  if(temp_count == 1){
    d3.select(".state_summary").append("div").attr("id","state_click_legend").text("Click on any state in the map to view the summmary of metrics selected");
    temp_count = 1;
  }
  map_click_event();
  //side_table_summary();
}

var side_table_data = []
//var contribution_dict = {};
function side_table_summary(state_clicked) {
  d3.select("div#state_click_legend").remove();
  side_table_data = []; //contribution_dict = {};
  var metric_percent = [];
  var state_sprk = [], india_sprk =[];
  var india_total='';
  dyn_sel_metrics_1.forEach(function(m){
    //var total_sum = d3.sum(_.pluck(final_table_summary, m));
    if (_.contains(stat_data_metrics, m)) {
      if (_.has(stat_nested_data, state_clicked)) {
        if (typeof stat_nested_data[state_clicked][m] == "undefined") {
          india_total = 'NA'
        }
        else{
          india_total = stat_nested_data[state_clicked][m][0]['All India Total'];
        }
      }
      else{
        india_total = 'NA';
      }
    }
    else{ //Expenditure and income data dont have All India Total.So summing it up.
      india_total = d3.sum(_.pluck(final_table_summary, m));
    }

    //============================================================================================
    // This will calculate min and max according to All India Total.
    //============================================================================================
    // -- Iterates all metrics of each state
    //_.values(final_table_summary).forEach(function(allMetrics){
    //  var temp = ((allMetrics[m]/india_total)*100)
    //  metric_percent.push(temp)
    //})
    //
    //var min = d3.min(metric_percent, function(val) {
    //  return +val;
    //});
    //var max = d3.max(metric_percent, function(val) {
    //  return +val;
    //});
    //var median = (min + max)/2;
    //contribution_dict[m] = [min,median,max]
    //============================================================================================

    if (_.contains(m, "%")) {
      var cont_perc = "";
    }
    else{
      // TO-DO -- Handle if exist
      //var cont_perc = ((final_table_summary[state_clicked][m]/total_sum)*100);
      var cont_perc = ((final_table_summary[state_clicked][m]/india_total)*100);
    }

    // -- Construct Sparkline data

    if (_.contains(incm_data_metrics, m)) {
      state_sprk = [], india_sprk =[];
      if (typeof incm_nested_data[state_clicked] == "undefined" || typeof incm_nested_data[state_clicked][m] == "undefined") {
        state_sprk.push(0,0,0)
      } else {
        for (i=2;i>0;i--) {
          var sel_yr_index = _.indexOf(incm_avail_yrs, selected_year);
          arr_index = sel_yr_index - i;
          if (arr_index >= 0) {
            state_sprk.push(incm_nested_data[state_clicked][m][0][incm_avail_yrs[arr_index]])
          }
        }
        state_sprk.push(incm_nested_data[state_clicked][m][0][selected_year])
      }

      if (typeof incm_sprk_india_total[m] == "undefined") {
        india_sprk.push(0,0,0)
      } else {
        for (i=2;i>0;i--) {
          var sel_yr_index = _.indexOf(incm_avail_yrs, selected_year);
          arr_index = sel_yr_index - i;
          if (arr_index >= 0) {
            india_sprk.push(incm_sprk_india_total[m][incm_avail_yrs[arr_index]])
          }
        }
        india_sprk.push(incm_sprk_india_total[m][selected_year])
      }
    }

    // -- Construct Sparkline data

    else if (_.contains(expd_data_metrics, m)) {
      state_sprk = [], india_sprk =[];
      if (typeof expd_nested_data[state_clicked] == "undefined" || typeof expd_nested_data[state_clicked][m] == "undefined") {
        state_sprk.push(0,0,0)
      } else {
        for (i=2;i>0;i--) {
          var sel_yr_index = _.indexOf(expd_avail_yrs, selected_year);
          arr_index = sel_yr_index - i;
          if (arr_index >= 0) {
            state_sprk.push(expd_nested_data[state_clicked][m][0][expd_avail_yrs[arr_index]])
          }
        }
        state_sprk.push(expd_nested_data[state_clicked][m][0][selected_year])
      }

      if (typeof expd_sprk_india_total[m] == "undefined") {
        india_sprk.push(0,0,0)
      } else {
        for (i=2;i>0;i--) {
          var sel_yr_index = _.indexOf(expd_avail_yrs, selected_year);
          arr_index = sel_yr_index - i;
          if (arr_index >= 0) {
            india_sprk.push(expd_sprk_india_total[m][expd_avail_yrs[arr_index]])
          }
        }
        india_sprk.push(expd_sprk_india_total[m][selected_year])
      }
    } else {
      //india_sprk = [];
      //state_sprk = [];
      india_sprk = [0,0,0];
      state_sprk = [0,0,0];
    }
    //india_sprk = [0,0,0];
    //state_sprk = [0,0,0];
    side_table_data.push([m,india_total,india_sprk,final_table_summary[state_clicked][m],state_sprk,cont_perc])
  })
  //console.log(side_table_data);
  //console.log(contribution_dict);
}

//********************************************** Construct map state summary side table***************************************
var contribution_count = -1
function map_click_event() {
  //--------------------- Onclick action for a map

  var parent = d3.selectAll("div#india_map_container svg g");

  contribution_min_max();
  parent.selectAll("g")
        .on("click", function(d) {
          d3.select("#menu_container").style("display","none");
          d3.select("#master_metrics_menu").attr("class","deactive_menu");
          d3.select(".state_summary").style("display","block");
          d3.select("#state_summary_menu").attr("class","active_menu");

          var state_clicked = d3.select(this).attr("id")
          side_table_summary(state_clicked); //--------> Calls the method to create side table summary of the map state

          var state_name = state_clicked+ "Value";
          table_headers = ["Metric","India Total","India Trend","State Value","State Trend","Contrib %"]

          d3.select(".state_summary table").remove();
          table = d3.select(".state_summary")
                    .append('table').attr("cell-padding","10").attr("cell-spacing","10").attr("class","mapside_table");
          thead = table.append("thead");
          tbody = table.append("tbody");

          thead.append("tr").append("th").attr("colspan","6").attr("class","state_header").append("h2").text(state_clicked +'-'+selected_year);
          thead.append("tr").attr("class","metrics_header")
                .selectAll("th")
                .data(table_headers)
                .enter()
                .append("th")
                .attr("id",function(d){ return d })
                .text(function(d){ return d });

          tr = tbody.selectAll("tr")
                    .data(side_table_data)
                    .enter()
                    .append("tr")

          format = d3.format("00,000");
          test = tr.selectAll('td')
            .data(function(d){ return d; })
            .enter()
            .append("td").attr("id",function(d){ return d})
            .style("background-color",function(d,i){ return (i==5) ? generate_contr_color(d) : ""; })
            .attr("bgcolor",function(d,i){ return (i==0) ? check_selection(d) : ""; })
            .text(function(d,i){ return (i==2 || i ==4) ? '' : _.isString(d) ? d : format(d.toFixed(2)) });

          test.filter(function(d,i) { return (i==2) }).call(spark);
          test.filter(function(d,i) { return (i==4) }).call(spark);

          function check_selection(color_metric){
            if (sel_color_metric == color_metric) {
              return "#DADADA";
            }else{
              return "";
            }
          }
          contribution_count = -1 //-- Reset count to -1

          function spark(tag) {
            var w = 80, h = 40;
            var vis = tag.append("svg")
                .attr("width", w)
                .attr("height", h)
            var g = vis.append("g").attr("transform", "translate(0,30)");
            g.append("path").attr("d", function(d){ return spark_min_max(d) }).attr('class', 'wind');
            return vis;
          }

          function spark_min_max(args) {
            var wind =args;
            var w = 75, h = 20;
            var yWind = d3.scale.linear().domain([d3.min(wind), d3.max(wind)]).range([0 , h])
            x = d3.scale.linear().domain([0, wind.length]).range([0, w])
            var lineWind = d3.svg.line()
                .x(function(d,i) { return x(i); })
                .y(function(d) { return -1 * yWind(d) });
            return lineWind(args);
          }
        });
}

var contribution_dict = {}
function contribution_min_max() {
  // Iterates each metric
  dyn_sel_metrics_1.forEach(function(metric){
    var metric_percent = []
    var total_sum = d3.sum(_.pluck(final_table_summary, metric));
    // -- Iterates all metrics of each state
    _.values(final_table_summary).forEach(function(allMetrics){
      var temp = ((allMetrics[metric]/total_sum)*100)
      metric_percent.push(temp)
    })

    var min = d3.min(metric_percent, function(val) {
      return +val;
    });
    var max = d3.max(metric_percent, function(val) {
      return +val;
    });
    var median = (min + max)/2;
    contribution_dict[metric] = [min,median,max]
  });
}

function generate_contr_color(data) {
    // ----------------------- color scales
  contribution_count = contribution_count + 1
  if (!_.contains(side_table_data[contribution_count][0], "%")) {
      var min_med_max_list = contribution_dict[side_table_data[contribution_count][0]]
      var color_calculator = d3.scale.linear().domain([min_med_max_list[0], min_med_max_list[1], min_med_max_list[2]]).range(['red', 'yellow', 'green']).clamp(true);
      return color_calculator(data);
  }
  else{
    return "#fff";
  }
}
