$(window).load(function() {
    $('#mapTab').addClass('disabled');

    setTimeout(function() {
        $('#mapTab').removeClass('disabled');
    }, 60000);

});

function isIE() {
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");
	if (msie > 0) {
		return true;
	}
	return false;
}

$(document).ready(function() {
    var index = 0;
    var imgs = $('.imageCarrousel img').map(function() {
        return this.src;
    }).get();
	
    $('.imageCarrousel img').each(function() {
        $(this).css("width", "50px");
        $(this).css("height", "50px");
    });
    if (index == 0) {
        //$('.preBtn').attr('disabled', 'disabled');
        $('.preBtn').bind('click', function(e) {
            e.preventDefault();
        });
    } else {
        $('.preBtn').unbind('click');
        $('.preBtn').removeAttr('disabled');
		//$('.preBtn').attr('disabled', '');
    }

    if (index == imgs.length - 1) {
        $('.nextBtn').bind('click', function(e) {
            e.preventDefault();
        });
    } else {
        $('.nextBtn').unbind('click');
        $('.nextBtn').removeAttr('disabled');
    }

    $('.preBtn').on("click", function(e) {
        e.preventDefault();
        index--;
        if (index >= 0) {
            $('.resizecrop').attr("src", imgs[index]);
			relocationImage();
            resetBorder();
            borderImageWithIndex(index);
        } else {
            index = 0;
        }
    });

    $('.nextBtn').on("click", function(e) {
        e.preventDefault();
        index++;
        if (index <= imgs.length - 1) {
            $('.resizecrop').attr("src", imgs[index]);
			relocationImage();
            resetBorder();
            borderImageWithIndex(index);
        } else {
            index = imgs.length - 1;
        }
    });
    var isManual = false;
    $('.imageCarrousel img').hover(function(e) {
        var oldImage = $('.resizecrop').attr("src");
        if ($(this).attr("src") != oldImage) {
            e.preventDefault();
            index = findIndex(imgs, $(this).attr("src"));
            $('.resizecrop').attr("src", $(this).attr("src"));
			relocationImage();
            resetBorder();
            isManual = true;
            $(this).css("border", "2px solid blue");
        }
    });
	
	function relocationImage() {
		var screenImage = $(".resizecrop");
		var theImage = new Image();
		theImage.src = screenImage.attr("src");
		
		var cHeight = $("#photos").height(),
                cWidth = $("#photos").width(),
                iHeight = theImage.height,
                iWidth = theImage.width;
		console.log('cHeight: '+cHeight+', iHeight: '+ iHeight);				
		console.log('cWidth: '+cWidth+', iWidth: '+iWidth);
						
		if(iWidth < cWidth) {
			if(iHeight < cHeight) {
				if(cHeight - iHeight < 100) {
					$('.resizecrop').css({
						'position': '',
						'top': '45%',
						'left': '47%',
						'-webkit-transform': '',
						'transform': ''
					});
				} else {
					$('.resizecrop').css({
						'position': '',
						'top': '45%',
						'left': '47%',
						'-webkit-transform': '',
						'transform': ''
					});
					
				}
				$('.resizecrop').css({'height': '550px'});
			} else {
				if(isIE()) {
					$('.resizecrop').css({
					  'position': 'absolute',
					  'top': '0%',
					  'left': '22%',
					  'width': 0.7*iWidth+'px'
     				});
				} else {				
					$('.resizecrop').css({
						'position': 'absolute',
						'top': '50%',
						'left': '47%',
						'-webkit-transform': 'translate(-50%, -50%)',
						'transform': 'translate(-50%, -50%)'
					});
				}
			}
			
			
		} else {
			if(iHeight < cHeight) {
				if(cHeight - iHeight < 100) {
					$('.resizecrop').css({
						'position': '',
						'top': '28%',
						'left': '47%',
						'-webkit-transform': '',
						'transform': ''
					});
				} else {
					if(isIE()) {
						$('.resizecrop').css({
						  'position': 'absolute',
						  'top': '0%',
						  'left': '0%'
     					});
					} else {
						$('.resizecrop').css({
							'position': 'absolute',
							'top': '45%',
							'left': '47%',
							'-webkit-transform': 'translate(-50%, -50%)',
							'transform': 'translate(-50%, -50%)'
						});
					}
				}
				$('.resizecrop').css({'height': '550px'});
			} else {
				$('.resizecrop').css({
						'position': 'absolute',
						'top': '0px',
						'left': '0px',
						'-webkit-transform': '',
						'transform': ''
					});
			}
		}
	}

    function findIndex(imgs, img) {
        for (var i = 0; i < imgs.length; i++) {
            if (img == imgs[i]) {
                return i;
            }
        }
        return 0;
    }
	
    var count = 1;
    $('#detailImage, .navigator').hover(function() {
        $('.preCtrl').show();
        $('.nextCtrl').show();
		if(!isIE()) {
			$('.preArea').css('background', 'rgba(77,77,79,0.7)');
			$('.nextArea').css('background', 'rgba(77,77,79,0.7)');
		}
        count++;
    });

    $('#detailImage, .navigator').on('mouseleave', function(e) {
        e.preventDefault();
        $('.preCtrl').hide();
        $('.nextCtrl').hide();
		
		$('.preArea').css('background', 'none');
		$('.nextArea').css('background', 'none');
    });

    function resetBorder() {
        $('.imageCarrousel img').each(function() {
            $(this).css("border", "none");
        });
    }

    function borderImageWithIndex(index) {
        var i = 0;
        $('.imageCarrousel img').each(function() {
            if (i == index) {
                $(this).css("border", "2px solid blue");
            }
            i++;
        });
    }

    borderImageWithIndex(0);

    setInterval(function() {
        if (!isManual) {
            $('.nextBtn').click();
            if (index == imgs.length - 1) {
                index = 0;
            }
        }
    }, 5000);

    $('.destImg').resizecrop({
        width: 700,
        height: 500,
        vertical: "center",
    });

    $('#photoTab').click(function() {
        $('.imageCarrousel').show();
    });

    $('#mapTab').click(function() {
        $('.imageCarrousel').hide();
        fire();
    });
	
	relocationImage();

    //$('.resizecrop').css('width', '120%');
});