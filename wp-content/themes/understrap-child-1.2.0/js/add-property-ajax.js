jQuery(document).ready(function($) {
    $('#add-property-form').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: property_ajax.ajax_url,
            data: {
                action: 'add_property',
                property_data: formData
            },
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.data.message);
                // Можно добавить дополнительные действия при успешном добавлении недвижимости
            },
            error: function(xhr, status, error) {
                alert('Произошла ошибка при добавлении недвижимости: ' + error);
            }
        });
    });
});
