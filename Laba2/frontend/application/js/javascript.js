$(document).ready(function () {
    $('form[name="application"]').submit(function (e) {
        e.preventDefault();

        $('.error').text('');
        $('#error-message').text('');

        var formData = $(this).serialize();

        $.ajax({
            url: '../../backend/application.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    location.href = '../account/account.html';
                } else if (response.status === 'error') {
                    if (response.message === 'Такая заявка уже существует') {
                        var applicationNumber = response.applicationNumber;
                        var errorMessage =
                                'Ошибка: ' +
                                response.message +
                                '\nНомер существующей заявки: ' +
                                applicationNumber;
                        $('#error-message').text(errorMessage);
                    } else {
                        var errors = response.errors;
                        $.each(errors, function (field, errorMessage) {
                            $('#' + field + 'Error').text(errorMessage);
                        });
                        $('#error-message').text('');
                    }
                }
            }
        });
    });
});