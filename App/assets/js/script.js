var pattern = /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i;
var mail = $('.email');

mail.blur(function () {
    if (mail.val() != '') {
        if (mail.val().search(pattern) == 0) {
            $('#submit').attr('disabled', false);
        } else {
            $('#valid').text('Email не верный');
            $('#submit').attr('disabled', true);
        }
    } else {
        $('#valid').text('Поле e-mail не должно быть пустым!');
        $('#submit').attr('disabled', true);
    }
});

function login() {
    var login = $('#login').val();
    var pass = $('#pass').val();
    $.ajax({
        url: 'home/login',
        type: 'post',
        data: {login: login, pass: pass},
        success: function (data) {
            if (data != false) {
                location.reload();
            } else {
                alert('Логин и/или пароль не верны');
                location.reload();
            }
        },

    });
}

function addTask() {
    $.ajax({
        url: 'home/addTask',
        type: 'post',
        data: $('#new-task').serialize(),
        success: function () {
            alert('Задача успешно добавлена');
            location.reload();
        }

    });
}

function checkTask(id) {
    $.ajax({
        url: 'home/checkTask',
        type: 'post',
        data: {id: id},
        success: function (data) {
            alert('Задача выполнена');
            $('.check' + id).text(data);
			location.reload();
        }
       
    });
}

function editTask(id) {
    $.ajax({
        url: 'home/editTask',
        type: 'post',
        data: {id: id},
        dataType: 'json',
        success: function (data) {
            console.log(data)
            $('.task' + id).html(
                "<textarea name='task' class='newtask' style='min-height: 150px; background-color: #d3d3d3'>" + data + "</textarea>"
            );
            $('.check' + id).html('<a href="javascript:void(0)" onclick="saveTask(' + id + ')">Сохранить</a>');

        },

    });
}

function saveTask(id) {
    var task = $('.newtask').val();
    $.ajax({
        url: 'home/saveTask',
        type: 'post',
        data: {id: id, task: task},
        success: function (data) {
            alert('Задача отредактирована');
            location.reload();
        }
    });
}
