$(document).ready(function () {
    $('form[name="auth"]').submit(function (e) {
        e.preventDefault();

        $('.error').text('');

        var formData = $(this).serialize();

        $.ajax({
            url: '../../backend/authorization.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    localStorage.setItem('username', response.user.name);
                    localStorage.setItem('surname', response.user.surname);
                    localStorage.setItem('login', response.user.login);

                    location.href = '../account/account.html';
                } else if (response.status === 'error' && response.errors) {
                    $.each(response.errors, function (key, value) {
                        $('#' + key + '-error').text(value);
                    });
                }
            }
        });
        return false;
    });
});