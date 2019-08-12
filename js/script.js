$('.text_origin').on('keyup', function () {    
    $('.text_target').val($(this).val().toLowerCase().trim()); 
});