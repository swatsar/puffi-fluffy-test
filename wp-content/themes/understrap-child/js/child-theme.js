(function ($) {
    $(document).ready(function() {
        $('#add_realty').submit(function (e){
            e.preventDefault();
            e.stopPropagation();
            let formData = new FormData(document.getElementById('add_realty')),
                btn = $('#add_realty button');
            formData.append('_wpnonce',child_js.nonce);
            jQuery.ajax({
                type: "POST",
                url: child_js.url,
                dataType: 'json',
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: function(e) {
                    $(btn).html('Добавляю');
                },
                success: function(responce) {
                    if(responce.status == 200){
                        $('.cities_content .flex-wrap').prepend(responce.content);
                        $(btn).html('Добавлено');
                        setTimeout(function () {
                            $(btn).html('Добавить');
                        }, 1000);
                    }
                    if(responce.status == 500){
                        alert(responce.errors);
                    }
                }
            });
        });
    });
})(jQuery);