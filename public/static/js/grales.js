
//Boton del menu
$(document).ready(function(){

	$('#btn-menu').click(function(){

		if ($('.b-menu span').attr('class') == 'ico-burguer' ){	
			$('.b-menu span').removeClass('ico-burguer').addClass('ico-cerrar');
                        $('.hiddenshow').removeAttr('tabindex');                        
                        $('.hiddenshow').attr('tabindex', '3');
			$('.full-menu').css({'left':'0'})
		}else{
			$('.b-menu span').removeClass('ico-cerrar').addClass('ico-burguer');
                        $('.hiddenshow').removeAttr('tabindex');                        
                        $('.hiddenshow').attr('tabindex', '-1');
			$('.full-menu').css({'left':'-100%'})
		}
	});
        
        $('#btn-menu').keypress(function(e){
                if (e.keyCode == 13) {
		if ($('.b-menu span').attr('class') == 'ico-burguer' ){
			$('.b-menu span').removeClass('ico-burguer').addClass('ico-cerrar');
                        $('.hiddenshow').removeAttr('tabindex');
                        $('.hiddenshow').attr('tabindex', '3');
			$('.full-menu').css({'left':'0'})
		}else{
			$('.b-menu span').removeClass('ico-cerrar').addClass('ico-burguer');
                        $('.hiddenshow').removeAttr('tabindex');
                        $('.hiddenshow').attr('tabindex', '-1');
			$('.full-menu').css({'left':'-100%'})
		}
            }
	});
        
        $(".navbar-brand").focus(function () {
                       $('.b-menu span').removeClass('ico-cerrar').addClass('ico-burguer');
                        $('.hiddenshow').removeAttr('tabindex');                        
                        $('.hiddenshow').attr('tabindex', '-1');
			$('.full-menu').css({'left':'-100%'})
                    });
});

//Tooltip de bootstrap
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});