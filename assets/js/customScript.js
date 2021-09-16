$(document).on('input', '.number', function(e){
        var num=$(this).val().replace(/[^\d\.]/g, '');
        $(this).val(num);
});

