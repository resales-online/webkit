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

(function ($) {
	$.support.placeholder = ('placeholder' in document.createElement('input'));
})(jQuery);

//fix for IE7 and IE8
$(function () {

 if (!$.support.placeholder) {
	 $("[placeholder]").focus(function () {
		 if ($(this).val() == $(this).attr("placeholder")) $(this).val("");
	 }).blur(function () {
		 if ($(this).val() == "") $(this).val($(this).attr("placeholder"));
	 }).blur();

	 $("[placeholder]").parents("form").submit(function () {
		 $(this).find('[placeholder]').each(function() {
			 if ($(this).val() == $(this).attr("placeholder")) {
				 $(this).val("");
			 }
		 });
	 });
 }
});

$(document).ready(function() {

	$('[name="area"]').on('change',function(){

		var newArea = $('[name="area"]').val();
		var locationJSON = $.parseJSON($('#locationsJSON').val());
		
		locationJSON.Areas.Area.forEach( function(locationArray) {

			if ( locationArray.AreaName == newArea ) {
				$("#searchLocation").find('option:not(:first)').remove().end();
				locationArray.Locations.LocationName.forEach( function(location) {
					$('#searchLocation').append('<option class="chosen-item" value="' + location + '">' + location + '</option>');
				});
				if ( $('#activeChosen').val() ) {
					$("#searchLocation").trigger("chosen:updated");
				}
			};
		});		
	})

	if ( $('#activeChosen').val() ) {
		$('#property-type').chosen({search_contains: true});
		$('#features').chosen({search_contains: true});
		$('#searchLocation').chosen({search_contains: true});
	} else {
		$('.more').hide();
	}
	
	$(window).resize(function() {
		$("#moreFeatureContainer").dialog("option", "position", {my: "center", at: "center", of: window});
		$('div.chosen-container').width($('.listing-search-main').width());
	});

	$(document).on('click', 'li.category', function() {
		// Get unselected items in this group
		var unselected = $(this).nextUntil('.category').not('.result-selected');
		if(unselected.length) {
			$(this).nextUntil('.category').each(function() {
				// Deselect all items in this group
				$('#property_type_chosen a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
			});
		}
	});
	
	$(document).on('click', 'li.item', function() {
		$(this).prevUntil().each(function() {
		    if($(this).hasClass('category')) {
			    $('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
			}
	    });
	});
	
	$(document).on('click', 'li.chosen-item', function() {
		if($(this).data('option-array-index') != 0) {
			$('a.search-choice-close[data-option-array-index="0"]').trigger('click');
		} else {
			$(this).nextUntil().not('.active-result').each(function() {
				$('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
			});
		}
	});
	
	$('[name="searchType"]').on('change',function(){
		var curType = $('[name="searchType"]').val();
		createMaxMinPrice(curType);
		if(curType == 'RentalLT' || curType == 'RentalST') {
			$('.listing-search-field-rental-date-from').show();
			$('.listing-search-field-rental-date-to').show();
		} else {
			$('.listing-search-field-rental-date-from').hide();
			$('.listing-search-field-rental-date-to').hide();
		}
	});
	
	$.datepicker.setDefaults($.datepicker.regional[$('#languageCode').val()]);
	
	$("#rental_date_from").datepicker({
		dateFormat: 'dd-mm-yy',
		onSelect: function(dateText, inst) { 
        	var date = $(this).datepicker('getDate');
            var day  = date.getDate(); 
            var month = date.getMonth();              
            var year =  date.getFullYear();
			var last1Month;
			if(month == 0) {
			    last1Month = new Date(year - 1, 11, 1);
			} else {
				last1Month = new Date(year, month - 1, 1);
			}
			$('#startRentalDate').val(last1Month.getFullYear()+'-'+(last1Month.getMonth() + 1)+'-'+last1Month.getDate());
       }
	});

	$("#rental_date_to").datepicker({
		dateFormat: 'dd-mm-yy',
		onSelect: function(dateText, inst) { 
        	var date = $(this).datepicker('getDate');
            var day  = date.getDate(); 
            var month = date.getMonth();              
            var year =  date.getFullYear();
			var after2Month;
			if(month == 11) {
			    after2Month = new Date(year + 1, 2, 0);
			} else {
				after2Month = new Date(year, month + 3, 0);
			}
			$('#endRentalDate').val(after2Month.getFullYear()+'-'+(after2Month.getMonth() + 1)+'-'+after2Month.getDate());
       }
    });
	
	function createSubTypeData() {
		var curType = $('[name="property-type"]').val();
		var typesJSON = $.parseJSON($('#typesJSON').val());
		
		typesJSON.PropertyTypes.PropertyType.forEach(function(typesArray) {
			if (typesArray.OptionValue == curType[0]){
				$('[name="subtype"]').find("option:gt(0)").remove();
				typesArray.SubType.forEach(function(subtype) {
					$('[name="subtype"]').append('<option value="' + subtype.OptionValue + '">' + subtype.Type + '</option>');
				});
			};
		});
	}
	
	function createMaxMinPrice(searchType) {
		var priceArrayJSON;
		switch(searchType) {
			case 'Resale':
				priceArrayJSON = $.parseJSON($('#priceRangeResale').val());
				$("#searchForm").attr("action", $('#searchResultsPage').val() +"/ForSale/");
				break;
			case 'RentalLT':
				priceArrayJSON = $.parseJSON($('#priceRangeRentalLT').val());
				$("#searchForm").attr("action", $('#searchResultsPage').val() +"/LongTerm/");
				break;
			case 'RentalST':
				priceArrayJSON = $.parseJSON($('#priceRangeRentalST').val());
				$("#searchForm").attr("action", $('#searchResultsPage').val() +"/ShortTerm/");
		}
		priceArrayJSON = priceArrayJSON.map(String);
		$( "#minPrice" ).autocomplete({
            source: priceArrayJSON
        });
		$( "#maxPrice" ).autocomplete({
            source: priceArrayJSON
        });
	}
	
	createMaxMinPrice($('[name="searchType"]').val());
	
	if($('.listing-search-currency').is(":visible")) {
		$('.listing-search-currency').val($('#oldCurrency').val());
	}
	
	$('.btn-primary').bind('click', function() {
		$('#searchTypeValue').val($('[name="searchType"] option:selected').text());
		$('#areaValue').val($('[name="area"]').val());
		$('#locationValue').val($('[name="location"] option:selected').text());
		$('#propertyTypeValue').val($('[name="property-type"] option:selected').text());
		$('#subTypeValue').val($('[name="subtype"] option:selected').text());
		$('#bedValue').val($('[name="beds"] option:selected').text());
		$('#bathValue').val($('[name="baths"] option:selected').text());
	});
	
	$('.chosen-choices').each(function() {
	    $(this).addClass('form-control');
	});
	
	$("#moreFeatureContainer").dialog({
        title: "More Features",
        modal: true,
		autoOpen: false,
		width: '60%',
        height:'auto',
        resizable:false,
		open: function( event, ui ) {
			setSelectFeature();
			$('.ui-widget-overlay').bind('click', function() {
                $('#moreFeatureContainer').dialog('close');
            })
		}
    });
	
	$(".feature-more").change(function() {
		$("#features").val(getFeatureValue());
		$('#features').trigger("chosen:updated");
	});
	
	function getFeatureValue() {
		var res = [];
		$('input:checkbox.feature-more').each(function () {
       		var sThisVal = (this.checked ? $(this).val() : "");
			if(sThisVal != "") {
			    res.push(sThisVal);
			}
  		});
		return res;
	}
	
	function setSelectFeature() {
		var features = $("#features").val();
		$('input:checkbox.feature-more').each(function () {
			if($.inArray($(this).val(), features) >= 0) {
				$(this).prop("checked", true);
			} else {
				$(this).prop("checked", false);
			}
  		});
	}
	
	$(document).on('div', '.fullFeature', function() {
	}).on('mousemove', '.more', function(e) {
        $(this).css("cursor", "pointer");
	}).on('mouseout', '.more', function(e) {
        $(this).css("cursor", "");
	}).on('click', '.more', function(e){
    	$('#moreFeatureContainer').dialog('open').dialog('option', 'position',[e.clientX,e.clientY]);
	});
	
	$("#accordion").accordion({
		active: 0,
		autoHeight: false
	});
	$('.ui-accordion-content').css('height', 'auto');
	
	//Fix mobile multi select
	if(/iP(od|hone)/i.test(window.navigator.userAgent) || /Android/i.test(window.navigator.userAgent)) {
        $('select[multiple]').each(function(){
            var select = $(this).on({
                "focusout": function(){
                    var values = select.val() || [];
                    setTimeout(function(){
						if(values.length == 0) {
							select.prepend('<option class="defaultPlaceHolder" value="" selected="selected" disabled="disabled">'+select.attr('data-placeholder')+'</option>');
							select.change();
						} else {
							select.find('option[value=""]').remove();
							select.change();
						}
                    }, 500);
                }
            });
            var firstOption = '<option class="defaultPlaceHolder" value="" disabled="disabled"';
            firstOption += (select.val() || []).length > 0 ? '' : ' selected="selected"';
            firstOption += '>' + (select.attr('data-placeholder') || 'Options');
            firstOption += '</option>';
            select.prepend(firstOption);
        });
    }

	
	function number_format(number, decimals, dec_point, thousands_sep) {
	  number = (number + '')
		.replace(/[^0-9+\-Ee.]/g, '');
	  var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
		  var k = Math.pow(10, prec);
		  return '' + (Math.round(n * k) / k)
			.toFixed(prec);
		};
	  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
	  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
		.split('.');
	  if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  }
	  if ((s[1] || '')
		.length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1)
		  .join('0');
	  }
	  return s.join(dec);
	}
});