$(function() {
    Template.init();
    Task.init();
    User.init();
});


var Template = {
    init: function() {
        // Удаляем слои бесплатного хостинга
        $('body div:eq(0)').remove();
        $('.cbalink').remove();

        // Подпись для бутсраповского input file
        $('.custom-file input[type=file]').change(function() {
            $(this).closest('.custom-file').find('.custom-file-label').text($(this).val());
        });

        // Сортировка
        $('.sort a.active').click(function(e) {
            e.preventDefault();
        });
    }
};


var Task = {
    init: function() {
        this.showForm();
        this.create();
        this.preview();
        this.done();
        this.updateTask();
    },

    // Отображение формы создания задачи
    showForm: function() {
        $('.btn-new-task').click(function() {
            $('.new-task-form').fadeToggle();
        });
    },

    // Создание задачи
    create: function() {
        var $this = this;

        $('#create-task').submit(function(e) {
            e.preventDefault();

            var form = $(this).closest('form');
            $this.send(form);
        });
    },

    // Предпросмотр задачи
    preview: function() {
        var $this = this;

        $('.btn-preview-task').click(function() {
            var form = $(this).closest('form');
            $this.send(form, true);
        });
    },

    // Отправка данных о создании/предпросмотра задачи на бэк
    send: function(form, preview = false) {
        var $this = this;

        // Данные формы
        var data = new FormData;
        data.append('username', form.find('[name=username]').val());
        data.append('email', form.find('[name=email]').val());
        data.append('description', form.find('[name=description]').val());
        data.append('image', form.find('[name=image]').prop('files')[0]);

        if (preview) data.append('preview', true);

        // Очищаем предыдущие сообщения и ошибки
        form.find('.success').html('');
        form.find('.errors').html('');

        $.ajax({
            url: '/main/create',
            method: 'POST',
            dataType: 'JSON',
            data: data,
            processData: false,
            contentType: false,
            success: function(json) {
                if (json.success) {
                    var tp = $('.task-preview');

                    // Предпросмотр или обновление списка
                    if (preview) {
                        tp.find('.username').text(json.username)
                        tp.find('.email').text(json.email)
                        tp.find('.description').html(json.description)
                        tp.find('img').attr('src', json.image)
                        tp.fadeIn();
                    } else {
                        $('.tasks-container').load('/ .tasks-wrap');

                        form.find('.success').append(json.message + '<br>');
                        $this.clearform(form);
                    }
                } else {    // Ошибки
                    for (i in json.errors) {
                        form.find('.errors').append(json.errors[i] + '<br>');
                    }
                }
            }
        });
    },

    // Очистка формы создания
    clearform: function(form) {
        form.find('input[type=text], textarea').val('');
        form.find('input[type=file]').val('')
            .closest('.custom-file').find('.custom-file-label').text('выберите картинку');
    },

    // Выполнить задачу
    done: function() {
        var $this = this;

        $('body').on('click', '.btn-done', function() {
            var id = $(this).closest('tr').data('id');
            var data = {id: id, done: true};

            $this.update(data);
        });
    },

    // Отображение формы обновления задачи
    updateTask: function() {
        var $this = this;

        $('body').on('click', '.btn-update-task', function() {
            var btn = $(this);
            var tr = $(this).closest('tr');
            var td = $(this).closest('td');
            var dt = td.find('.task-description');
            var dtu = td.find('.task-description-update');

            var id = tr.data('id');

            if (dtu.is(':hidden')) {
                btn.text('Сохранить');
                dt.hide();
                dtu.fadeIn();
            } else {
                btn.text('Изменить');
                dtu.hide();
                dt.fadeIn();

                var data = {id: id, description: dtu.find('textarea').val()};

                $this.update(data);
            }
        });
    },

    // Отправка данных об обновлении задачи на бэк
    update: function(data) {
        $.ajax({
            url: '/main/update',
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function(json) {
                if (json.success) {
                    var id = json.task.id;
                    var tr = $('tr[data-id=' + id + ']');

                    // Если задача выполнена
                    if (json.task.done != 0) {
                        tr.addClass('done');
                        tr.find('.btn-done').hide();
                        tr.find('li.done').show();
                    }

                    tr.find('.task-description').html(json.task.description);
                }
            }
        });
    },
};


var User = {
    init: function() {
        this.login();
    },

    // Авторизация юзера
    login: function() {
        $('.login-form').submit(function(e) {
            e.preventDefault();

            var form = $(this);
            var data = $(this).serialize();

            form.find('.errors').html('');

            $.ajax({
                url: '/user/auth',
                method: 'POST',
                dataType: 'JSON',
                data: data,
                success: function(json) {
                    if (json.success) {
                        location.href = '/';
                    } else {
                        for (i in json.errors) {
                            form.find('.errors').append(json.errors[i] + '<br>');
                        }
                    }
                }
            });
        });
    },
}