$(document).ready(function() {
	function createPageSizeOption(num) {
		for(var i = 1; i< num; i++) {
			$('[name="pageSize"]').append('<option value="' + i*10 + '">' + i*10 + '</option>');
		}
	}
	
	$('[name="pageSize"]').on('change',function(){
		$('[name="pageSize"]').val($(this).val());
		$('#searchForm').submit();
	});
	
	$('[name="pSort"]').on('change',function(){
		$('[name="pSort"]').val($(this).val());
		$('#searchForm').submit();
	});
	
	createPageSizeOption(5);
	$('[name="pageSize"]').val($('#pageSizeOld').val());
	
	if($('#pSortOld').val() != '') {
		$('[name="pSort"]').children("option[value='']").remove();
		$('[name="pSort"]').val($('#pSortOld').val());
		
	} else {
		$('[name="pSort"]').val($('#pSortOld').val());
	}
	
	function resizeImageWidth() {
		var parentW = $('.wbResultContainer').width();
		$('.wbResultImage img').width(parentW);
	}
	if(/iP(od|hone)/i.test(window.navigator.userAgent) || /Android/i.test(window.navigator.userAgent)) {
		resizeImageWidth();
		
		window.addEventListener("orientationchange", function() {
  			resizeImageWidth();
		}, false);
	}
	
});
