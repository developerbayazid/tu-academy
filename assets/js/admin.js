;(function($){

    
    $('table.wp-list-table.table-view-list.contacts').find('a.submitdelete').on('click', function (e) {
        e.preventDefault();

        if(!confirm(tuAcademy.confirm)){
            return;
        }

        var self = $(this);
        id = self.data('id');

        wp.ajax.post('tu-academy-delete-contact', {
            id: id,
            _wpnonce: tuAcademy.nonce
        })
        .done(function(response){
            
            self.closest('tr')
            .css('background-color', 'red')
            .hide(400, function(){
                $(this).remove();
            })

        })
        .fail(function(){
            alert(tuAcademy.error);
        })

    });


})(jQuery);