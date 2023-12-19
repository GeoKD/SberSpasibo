$(document).ready(function () {
    $('form[name="reg"]').submit(function (e) {
        e.preventDefault();

        $('.error').text('');

        var formData = $(this).serialize();
        var form = $(this);

        var sexValue = $('select[name="sex"]').val();
        if (sexValue === "ns") {
            $('select[name="sex"]').siblings('.error').text("Поле 'Пол' не должно быть пустым");
            return;
        }

        $.ajax({
            url: '../../backend/registration.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'error' && response.errors) {
                    $.each(response.errors, function (key, value) {
                        $('input[name="' + key + '"]').siblings('.error').text(value);
                    });
                } else if (response.status === 'success') {
                    form[0].reset();
                    $('.error').text('');

                    localStorage.setItem('username', response.user.name);
                    localStorage.setItem('surname', response.user.surname);
                    localStorage.setItem('login', response.user.login);

                    $('input[type="text"], input[type="password"], select').val('');
                    $('.success-message').text('Поздравляем, вы успешно зарегистрировались!');
                    var redirectDelay = 2000;
                    setTimeout(function () {
                        location.href = '../account/account.html';
                    }, redirectDelay);
                }
            }
        });
    });
});