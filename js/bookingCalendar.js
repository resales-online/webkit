// Fix for IE
if (!('forEach' in Array.prototype)) {
    Array.prototype.forEach= function(action, that /*opt*/) {
        for (var i= 0, n= this.length; i<n; i++)
            if (i in this)
                action.call(that, this[i], i, this);
    };
}
if (!('map' in Array.prototype)) {
    Array.prototype.map= function(mapper, that /*opt*/) {
        var other= new Array(this.length);
        for (var i= 0, n= this.length; i<n; i++)
            if (i in this)
                other[i]= mapper.call(that, this[i], i, this);
        return other;
    };
}

$(document).ready(function() {
	var startDate = $('#startRental').val();
	var endDate = $('#endRental').val();
	var last1Month = new Date(startDate);
	var after2Month = new Date(endDate);
	var curentDate = new Date();
	
	if(startDate == '' || typeof(startDate) == "undefined") {
		var date = new Date();
		
		if(date.getMonth() == 0) {
		   last1Month = new Date(date.getFullYear() - 1, 11, 1);
		} else {
		   last1Month = new Date(date.getFullYear(), date.getMonth() - 1, 1);
		}
		startDate = last1Month.getFullYear()+'-'+(last1Month.getMonth() + 1)+'-'+last1Month.getDate();
	}
	if(endDate == '' || typeof(endDate) == "undefined") {
		var date = new Date();
		
		if(date.getMonth() == 11) {
		    after2Month = new Date(date.getFullYear() + 1, 2, 0);
		} else {
			after2Month = new Date(date.getFullYear(), date.getMonth() + 3, 0);
		}
		endDate = after2Month.getFullYear()+'-'+(after2Month.getMonth() + 1)+'-'+after2Month.getDate();
	}
	
	var lStart = new Date(2015, 1, 1);
	var lEnd = new Date(2015, 1, 20);
	var dateRangs = new Array();
	
	var rangJSON = [];
	if (typeof($('#ranges').val()) != 'undefined' && $('#ranges').val() != null) {
		rangJSON = $.parseJSON($('#ranges').val());
	}
	var index = 0;
	
	rangJSON.forEach(function(data) {
		//dateRangs[index] = [new Date(data[0]), new Date(data[1]), 'event2', $('#bookingTextHover').val()];
		dateRangs[index] = [new Date(data[0].replace(/-/g, "/")), new Date(data[1].replace(/-/g, "/")), 'event2', $('#bookingTextHover').val()];

		index++;
	});
	
	$.datepicker.setDefaults($.datepicker.regional[$('#languageCode').val()]);
	
	var datePicker = $('#bookingCalendar').datepicker({
		    changeMonth:true,
            changeYear:true,
			rangeSelect: true,
            numberOfMonths:[2,2],
			beforeShow: function (event, ui) {
				var $link = $("#showCalendar");
				ui.dpDiv.offset({
					top: $link.offset().top + 10,
					left: $link.offset().left + 10
				});
        	},
			beforeShowDay: function (date) {
				for (i=0;i<dateRangs.length;i++) {
					var date1 = dateRangs[i][0];
                    var date2 = dateRangs[i][1];
					var style = dateRangs[i][2];
					var description = dateRangs[i][3];
					
					if (date >= date1 && date <= date2) {
						return [true, style, description];	
					}
					continue;
				}

			    return [true, '', ''];
        	},
			onSelect: function(text, event) {
				return;
			},
			onChangeMonthYear: function(year, month, inst) {
				var self = this;
			},
    }
	);
	
	$('#showCalendar').click(function(){
		$('#bookingCalendar').datepicker('show');
    });
	
	$(document).on('click','.ui-datepicker-next',function(){
        $.datepicker._adjustDate(datePicker, +2, 'M');
		
		var next2Month;
		if(curentDate.getMonth() == 10) {
		   next2Month = new Date(curentDate.getFullYear() + 1, 0, 1);
		} 
		else if(curentDate.getMonth() == 11) {
		   next2Month = new Date(curentDate.getFullYear() + 1, 1, 1);
		} else {
		   next2Month = new Date(curentDate.getFullYear(), curentDate.getMonth() + 2, 1);
		}
		$("#bookingCalendar").datepicker("setDate",next2Month);
		
		var next4Month;
		if(curentDate.getMonth() == 8) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 0, 0);
		} 
		else if(curentDate.getMonth() == 9) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 1, 0);
		} else if(curentDate.getMonth() == 10) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 2, 0);
		} else if(curentDate.getMonth() == 11) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 3, 0);
		} else {
		   next4Month = new Date(curentDate.getFullYear(), curentDate.getMonth() + 4, 0);
		}
		
		curentDate = next2Month;
		sDate = next2Month.getFullYear()+'-'+(next2Month.getMonth() + 1)+'-'+next2Month.getDate();
		eDate = next4Month.getFullYear()+'-'+(next4Month.getMonth() + 3)+'-'+next4Month.getDate();
		
		$.ajax({
			type: "GET",
			url: $("#bookingUrl").val()+"&P_Start="+sDate+"&P_End="+eDate,
			dataType: "text",
			success: function(dataJson) {
				
				rangJSON = $.parseJSON(dataJson);
				dateRangs = new Array();
				var index = 0;
				rangJSON.forEach(function(data) {
					dateRangs[index] = [new Date(data[0].replace(/-/g, "/")), new Date(data[1].replace(/-/g, "/")), 'event2', $('#bookingTextHover').val()];
					index++;
				});
				$("#bookingCalendar").datepicker("refresh");
			}
		});
    });
	
	$(document).on('click','.ui-datepicker-prev',function(){
        $.datepicker._adjustDate(datePicker, -2, 'M');
		var last2Month;
		if(curentDate.getMonth() == 1) {
		   last2Month = new Date(curentDate.getFullYear() - 1, 11, 1);
		} 
		else if(curentDate.getMonth() == 0) {
		   last2Month = new Date(curentDate.getFullYear() - 1, 10, 1);
		} else {
		   last2Month = new Date(curentDate.getFullYear(), curentDate.getMonth() - 2, 1);
		}
		$("#bookingCalendar").datepicker("setDate",last2Month);
		curentDate = last2Month;
		
		var next4Month;
		if(curentDate.getMonth() == 8) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 0, 0);
		} 
		else if(curentDate.getMonth() == 9) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 1, 0);
		} else if(curentDate.getMonth() == 10) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 2, 0);
		} else if(curentDate.getMonth() == 11) {
		   next4Month = new Date(curentDate.getFullYear() + 1, 3, 0);
		} else {
		   next4Month = new Date(curentDate.getFullYear(), curentDate.getMonth() + 4, 0);
		}
		
		sDate = last2Month.getFullYear()+'-'+(last2Month.getMonth() + 1)+'-'+last2Month.getDate();
		eDate = next4Month.getFullYear()+'-'+(next4Month.getMonth() + 1)+'-'+next4Month.getDate();
		
		$.ajax({
			type: "GET",
			url: $("#bookingUrl").val()+"&P_Start="+sDate+"&P_End="+eDate,
			dataType: "text",
			success: function(dataJson) {
				rangJSON = $.parseJSON(dataJson);
				dateRangs = new Array();
				var index = 0;
				rangJSON.forEach(function(data) {
					dateRangs[index] = [new Date(data[0].replace(/-/g, "/")), new Date(data[1].replace(/-/g, "/")), 'event2', $('#bookingTextHover').val()];
					index++;
				});
				$("#bookingCalendar").datepicker("refresh");
			}
		});
    });
	
});