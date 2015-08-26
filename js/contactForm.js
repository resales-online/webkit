//Fix IE Placeholder
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

	$('form').on('submit', function(e) {

		e.preventDefault();

		$('#contactFormSubmit').attr('disabled', true);

		if ( validateContactForm() ) {
			$.ajax({
				type: 'POST',
				url: '../contact.php',
				data: {
					'p1': $('#p1').val(),
					'p2': $('#p2').val(),
					'M1': $('#M1').val(),
					'M2': $('#M2').val(),
					'M5': $('#M5').val(),
					'M7': $('#M7').val(),
					'M6': $('#M6').val(),
					'RsId': $('#RsId').val(),
					'W1': $('#W1').val(),
					'W2': $('#W2').val(),
					'W3': $('#W3').val(),
					'W4': $('#W4').val(),
					'W6': $('#W6').val(),
					'W7': $('#W7').val(),
					'W10': $('#W10').val(),
					'Lang': $('#Lang').val()
				 },
				 success: function(msg) {
					$("#formMsg").html(msg);
					$("#formMsg").show().delay(10000).fadeOut();
					$('#contactFormSubmit').attr('disabled', false);
				 }
			});

			/**
			$.ajax({
				type: 'POST',
				url: $('#contactForm').attr('action'),
				data: {
					'p1': $('#p1').val(),
					'p2': $('#p2').val(),
					'M1': $('#M1').val(),
					'M2': $('#M2').val(),
					'M5': $('#M5').val(),
					'M7': $('#M7').val(),
					'M6': $('#M6').val(),
					'RsId': $('#RsId').val(),
					'W1': $('#W1').val(),
					'W2': $('#W2').val(),
					'W3': $('#W3').val(),
					'W4': $('#W4').val(),
					'W6': $('#W6').val(),
					'W7': $('#W7').val(),
					'Lang': $('#Lang').val()
				 },
				 success: function(msg) {
					$("#formMsg").html(msg);
					$("#formMsg").show().delay(10000).fadeOut();
				 }
			});
			
			$.ajax({
				type: 'POST',
				url: '../contact.php',
				data: {
					'M1': $('#M1').val(),
					'M2': $('#M2').val(),
					'M5': $('#M5').val()
				 },
				 success: function(msg) {
				 }
			});
			**/
		} else {
			$('#contactFormSubmit').attr('disabled', false);
		}
	});
	
});