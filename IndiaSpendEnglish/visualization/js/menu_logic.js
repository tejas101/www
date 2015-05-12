  $('#example2').accordion({
	canToggle: true,
    canOpenMultiple: true
  });
  //$('#example2').accordion({
//    canToggle: true
  //});
 $(".loading").removeClass("loading");
 $('#search_metric').keyup(function(){
  var searchText = new RegExp($(this).val(), 'i');
  console.log(searchText)
  //var searchText = $(this).val();
  if($(this).val() == ""){
    $('#example2').children('li').removeClass('active')
    $('#example2 li > div#metric_list').removeClass('panel open')
    $('#example2 li > div#metric_list').addClass('panel')
    $('#example2 li > div#metric_list').css('display','none')
  }else{
    $('#example2').children('li').addClass('active')
    $('#example2 li > div#metric_list').removeAttr( "Style" );
  }
  $('#example2 li > div#metric_list').css("height", "auto");
  $allListElements = $('#metric_list ul > li'),
  //$matchingListElements = $allListElements.filter(function(i, el){ return $(el).text().indexOf(searchText) !== -1; });
  $matchingListElements = $allListElements.filter(function(i, el){ return $(el).text().match(searchText) });
  $allListElements.hide();
  $matchingListElements.show();
});