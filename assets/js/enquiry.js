;(function($){

    
    $('#tu-academy-enquiry-form').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: tuAcademy.ajaxurl,
            data: {
                action: 'tu_academy_enquiry',
                _wpnonce: tuAcademy.nonce,
            },
            success: function (response) {
                if(response.success){
                    alert(response.data.message)
                }else{
                    alert(response.data.message)
                }
            },
            error: function(){
                alert(tuAcademy.error);
            }
        });

    })
    

})(jQuery);
