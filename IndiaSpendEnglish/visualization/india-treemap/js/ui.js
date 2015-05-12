//First Map Special Map implementation
d3.csv('Static data transformed.csv', function(d){ csv = d;});


//=================================================>User Interface section

//Onload Query
$(document).ready(function (){
  $('#year').hide();
  d3.select('svg').remove();
});

//get the value of category dropdown
$('#category1').change(function(){
  $('#year').show();
  if(this.value <= 1){
      drawData(prepareData(this.value), this.value, 1);
  } else if ( this.value == 2){
      var tm = $('#year').val();
      drawData(prepareData(this.value), this.value, 3);

      //==>Special treemap with data named "csv"
  } else if (this.value == 3){
      $('#year').hide();
      d3.select('#chart1 svg').remove();
      specialFirstLevel(firstLevelData(csv, 8),firstLevelColor(csv, 3), this.value);
  } else if (this.value == 4){
    $('#year').hide();
    d3.select('#chart1 svg').remove();
    specialFirstLevel(firstLevelData(csv, 8), firstLevelColor(csv, 3), this.value);
  } else if (this.value == 5){
    $('#year').hide();
    d3.select('#chart1 svg').remove();
    specialFirstLevel(firstLevelData(csv, 9), firstLevelColor(csv, 10), this.value);
  } else if (this.value == 6){
    $('#year').hide();
    d3.selectAll('#chart1 svg').remove();
    specialFirstLevel(firstLevelData(csv, 11), firstLevelColor(csv, 12), this.value);
  } 
});

$('#year').change(function(){
      d3.selectAll('svg').remove();
      //generate treemap based on year
      var tm = $('#category1').val();
      drawData(prepareData(tm), tm, this.value)

});

//get the value for select level by click
d3.select('.main1').on("click", function(){ 
  if(this.id == 3) {
    var click = this.id;
     d3.selectAll('svg').remove();
     specialSecondLevel(specialSecondData(csv, this.id), specialSecondColor(csv, this.id), this.id);
    //generate second level special treemap
  } else if (this.id == 4) {
    var click = this.id;
     d3.selectAll('svg').remove();
     specialSecondLevel(specialSecondData(csv, this.id), specialSecondColor(csv, this.id), this.id);
    //generate second level special treemap
  } else if (this.id == 5) {
    var click = this.id;
     d3.selectAll('svg').remove();
     specialSecondLevel(specialSecondData(csv, this.id), specialSecondColor(csv, this.id), this.id);
    //generate second level special treemap
  } else if (this.id == 6) {
    var click = this.id;
     d3.selectAll('svg').remove();
     specialSecondLevel(specialSecondData(csv, this.id), specialSecondColor(csv, this.id), this.id);
    //generate second level special treemap
  } else {
    var click = this.id;
     var b = prepareSecond(this.id);
      d3.selectAll('svg').remove();
      drillData(b, this.id);
  }
});
