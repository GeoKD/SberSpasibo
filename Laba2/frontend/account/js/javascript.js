$(document).ready(function () {
    var login = localStorage.getItem('login');
    var username = localStorage.getItem('username');
    var surname = localStorage.getItem('surname');

    $('#login').text(login);
    $('#firstname').text(username);
    $('#lastname').text(surname);

    $('#apply-btn').click(function () {
        localStorage.setItem('login', login);
        localStorage.setItem('username', username);
        localStorage.setItem('surname', surname);
        location.href = '../application/application.html';
    });

    $('#logout-btn').click(function () {
        localStorage.clear();
        location.href = '../authorization/index.html';
    });

    loadApplications();

    function loadApplications() {
        $.ajax({
            url: '../../backend/saveApplication.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    var applications = response.applications;
                    updateApplicationsTable(applications);
                }
            }
        });
    }

    function updateApplicationsTable(applications) {
        var tableBody = $('#applications-table tbody');
        tableBody.empty();

        for (var i = 0; i < applications.length; i++) {
            var application = applications[i];
            var row = $('<tr></tr>');

            var applicationNumber = $('<td></td>').text(i + 1);
            var age = $('<td></td>').text(application.age);
            var city = $('<td></td>').text(application.city);
            var variant = $('<td></td>').text(application.variant);
            var study = $('<td></td>').text(application.study);
            var description = $('<td></td>').text(application.me);

            row.append(applicationNumber, age, city, variant, study, description);
            tableBody.append(row);
        }
    }
});


