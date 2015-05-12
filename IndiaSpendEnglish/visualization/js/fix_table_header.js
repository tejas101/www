//jq203('#summary_table').fxdHdrCol({
//        fixedCols    : 1,        /* 1 fixed columns */
//        width        : "100%",   /* set the width of the container (fixed or percentage)*/
//        height       : "500",      /* set the height of the container */
//        colModal: [              /* Optional: set the colmodal property for each tr/th or td/td*/
//
//        ]
//});

// **************************************************************************
//jq203('#myTable').tinytbl({
//    direction: 'ltr',      // text-direction (default: 'ltr')
//    thead:     true,       // fixed table thead
//    tfoot:     true,       // fixed table tfoot
//    cols:      1,          // fixed number of columns
//    width:     700,     // table width (default: 'auto')
//    height:    600      // table height (default: 'auto')
//});
// **************************************************************************

$('.SBWrapper').scroll(function () {
    var rc = $(this).closest('.relativeContainer');
    var lfW = rc.find('.leftSBWrapper');
    var tpW = rc.find('.topSBWrapper');

    lfW.css('top', ($(this).scrollTop()*-1));
    tpW.css('left', ($(this).scrollLeft()*-1));
});