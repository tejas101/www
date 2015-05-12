//Data Preparation
function prepareData(data, input) {
	if(input == 0) {
		var tmp = _.filter(data, function (d) {
		return d['Category']=='TOTAL CAPITAL RECEIPTS (I to XIII)'
				|| d['Category']=='TOTAL REVENUE (I+II)'});

		var size = _.where(tmp, { "Sub-Category1":""});
		var color = _.where(data, {Category:"TOTAL INCOME (Total Capital Receipts + Tax Revenue)"});

		return [size, color];

	} else if(input == 1) {
		var tmp = _.filter(data, function (d) {
			return d['Sub-Category1'] == "I. TAX REVENUE (A+B)"
				|| d['Sub-Category1'] == "II. NON-TAX REVENUE (C+D)"
		});
		var size = _.where(tmp, { "Sub-Category2":""} );

		var tmp1 = _.where(data, {Category:"TOTAL REVENUE (I+II)"});
		var color = _.where(tmp1, {"Sub-Category1":""});

		return [size, color]

	} else if(input == 2) {
		var tmp = _.filter(data, function (d) {
			return d['Category'] == "TOTAL CAPITAL DISBURSEMENTS (I to XII) category"
				|| d['Category'] == "TOTAL REVENUE EXPENDITURE (I+II+III)"
		});
		var size = _.where(tmp, {"Sub-Category1":""});
		var color = _.where(data, {"Display Names":"Tot. Exp."});

		return [size, color];
	} else if(input == 3) {
		var firstLevel = _.where(data, {"Sub-category":"Total No of Census Houses (in million)"});
		var secondLevel= _.filter(data, function (d) {
			return d['Sub-category'] == "Houses Using Kerosene (in %)"
				|| d['Sub-category'] == "Houses Using Oil (in %)"
				|| d['Sub-category'] == "Houses Using Solar Power (in %)"
				|| d['Sub-category'] == "Houses With Electricity (in %)"
				});
		var firstColor = _.where(data, {"Sub-category":"Population (in million)"});
		return [firstLevel, secondLevel, firstColor];
	} else if(input == 4) {
		var firstLevel = _.where(data, {"Sub-category":"Total No of Census Houses (in million)"});
		var secondLevel = _.filter(data, function (d) {
						return d['Sub-category'] == "Availability of Drinking Water Source Away (in %)"
							|| d['Sub-category'] == "Availability of Drinking Water Source Near The Premises (in %)"
							|| d['Sub-category'] == "Availability of Drinking Water Source Within Premises (in %)"
						});
		var firstColor = _.where(data, {"Sub-category":"Population (in million)"});
		return	[firstLevel, secondLevel, firstColor];
	} else if(input == 5) {
		var firstLevel = _.where(data, {"Sub-category":"Road Coverage (Total in km)"});
		var secondLevel = _.filter(data, function (d) {
							return d['Sub-category'] == "Coverage Of National Highways (Total in km)"
								|| d['Sub-category'] == "Coverage Of Rural Roads (Total in km)"
								|| d['Sub-category'] == "Coverage Of State Highways (Total in km)"
								|| d['Sub-category'] == "Coverage Of Urban Roads (Total in km)"
								|| d['Sub-category'] == "Other PWD Roads (Total in km)"
						 });
		var tmp1 = _.pluck(_.where(data, {"Sub-category":"Motor Vehicles (in million)"}), "Total");
		var tmp2 = _.pluck(_.where(data, {"Sub-category":"Population (in million)"}), "Total");
		var tmp = [];	
		for(i = 0; i < tmp1.length; i++) {
			tmp.push(tmp1[i]/tmp2[i]);
		}
		return [firstLevel, secondLevel, tmp];

	} else if(input == 6) {
		var firstLevel = _.where(data, {"Sub-category":"GSDP At Constant 2004-05 Prices (in Rs cr)"});
		var secondLevel = _.filter(data, function (d) {
							return d['Sub-category'] == "Agri GSDP (%)"
								|| d['Sub-category'] == "Industry GSDP (%)"
								|| d['Sub-category'] == "Service GSDP (%)"
						  });
		var firstColor = _.where(data, {"Sub-category":"Growth Rate (At Constant 2004-05 Prices) (%)"});
		return [firstLevel, secondLevel, firstColor];
	}
} //prepare function end

//Data preparation second
function prepareDataTwo(data, input) {
	if(input == 0) {
		var size = _.filter(data, function (d) {
				return d['Sub-category'] == "Rural Population Below Poverty Line (in million)"
					|| d['Sub-category'] == "Urban Population Below Povery Line (in million)";
		});

		var color = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Unemployment Rate  (2011-12)"
				|| d['Sub-category'] == "Urban Unemployment Rate  (2011-12)";
		});

		var firstColor = _.where(data, {"Sub-category":"Unemployment Rate   (2011-12)"});

		return [size ,color, firstColor];

	} else if(input == 1) {

		var size = _.filter(data, function (d) {
			return d['Sub-category'] == "No of Urban Beds (Govt)"
				|| d['Sub-category'] == "No of Rural Beds (Govt)";
		});

		var color = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Population (in million)"
				|| d['Sub-category'] == "Urban Population (in million)"
		});

		var firstColor = [];
		var tmp1 = _.pluck(_.where(data, {"Sub-category":"Population (in million)"}), "Total");
		var tmp2 = _.pluck(_.where(data, {"Sub-category":"Total No of Beds (Govt)"}), "Total");
		for(i = 0; i < tmp1.length; i++) {
			firstColor.push(tmp2[i] / (tmp1[i] * 10000));
		}
		
		return [size, color, firstColor];


	} else if(input == 2) {
		var size = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Population (in million)"
				|| d['Sub-category'] == "Urban Population (in million)"
		});

		var color = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Child Population (0-6 Years) (in million)"
				|| d['Sub-category'] == "Urban Child Population (0-6 Years) (in million)"
		});

		var firstColor = [];
		var tmp1 = _.pluck(_.where(data, {"Sub-category":"Population (in million)"}), "Total");
		var tmp2 = _.pluck(_.where(data, {"Sub-category":"Child Population (0-6 Years) (in Million)"}), "Total");
		for(i = 0; i < tmp1.length; i++) {
			firstColor.push(tmp2[i] / tmp1[i] * 100);
		}

		return [size, color, firstColor];

	} else if(input == 3) {
		var size = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Population (in million)"
				|| d['Sub-category'] == "Urban Population (in million)"
		});

		var color = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Population (in million)"
				|| d['Sub-category'] == "Urban Population (in million)"
		});
		var firstColor = _.where(data, {"Sub-category":"Maternal Mortality Rate"});

		return [size, color, firstColor];

	} else if(input == 4) {
		var size = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Population (in million)"
				|| d['Sub-category'] == "Urban Population (in million)"
		});

		var color = _.filter(data, function (d) {
			return d['Sub-category'] == "Sex Ratio-Rural"
				|| d['Sub-category'] == "Sex Ratio-Urban"
		});

		var firstColor = _.where(data, {"Sub-category":"Sex Ratio (Females Per 1000 Males)"})
		return [size, color, firstColor];
		
	} else if(input == 5) {
		var size = _.filter(data, function (d) {
				return d['Sub-category'] == "Rural Population Below Poverty Line (in million)"
					|| d['Sub-category'] == "Urban Population Below Povery Line (in million)";
		});

		var color = _.filter(data, function (d) {
			return d['Sub-category'] == "Rural Unemployment Rate  (2011-12)"
				|| d['Sub-category'] == "Urban Unemployment Rate  (2011-12)";
		});

		var firstColor = _.where(data, {"Sub-category":"Infant Mortality Rate (IMR)"});

		return [size ,color, firstColor];
	}
}//secondData ends

