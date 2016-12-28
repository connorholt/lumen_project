<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Коллективная проза</title>

    <!-- CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 10px;
        }

        form {
            padding-bottom: 20px;
        }

        .part {
            padding-bottom: 20px;
        }

        a.likes {
            margin-right: 10px;
        }

        a.likes, a.dislike {
            text-decoration: none;
        }

        .jumbotron {
            background-image: url(http://lh3.ggpht.com/-nsGfO7iCWlc/VPMYcZlHxsI/AAAAAAAAIPs/Lqd_xGLb6n8/book-and-pen.jpg);
            background-size: cover;
            color: white;
        }
    </style>

    <!-- JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.1.7/vue.js"></script>
    <script src="//cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vuejs-paginator/2.0.0/vuejs-paginator.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
</head>

<body class="container">
<div id="app">

    <div class="col-md-8 col-md-offset-2">

        <ul class="nav nav-pills">
            <li role="presentation"><a href="/">Читать</a></li>
            <li role="presentation"><a href="/vote">Голосовать</a></li>
            <li role="presentation"><a href="/select">Выбрать главу</a></li>
            <li role="presentation"><a href="/about">О проекте</a></li>
        </ul>

        <div class="jumbotron">
            <h2>Выбор продолжения</h2>
            <h4>Только для админов</h4>
        </div>

        <h4>Выбор</h4>
        <form v-on:submit="create">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="id" v-model="id"
                       placeholder="#ID">
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-md">Выбрать</button>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">

    var app = new Vue({
        el: '#app',
        data: {
            id: '',
            errors: []
        },
        methods: {

            create: function (e) {
                e.preventDefault();
                $.ajax({
                    context: this,
                    type: "POST",
                    data: {
                        id: this.id
                    },
                    url: "/api/select",
                    success: function (result) {
                        $.notify("Часть добавлена в основной текст", {
                            position: "top center",
                            className: "success"
                        });
                        this.id = '';
                    },
                    error: function (data) {
                        this.errors = data;

                        $.notify("Часть не добавлена в основной текст", {
                            position: "top center",
                            className: "error"
                        });
                    }
                })
            }
        }
    })

</script>
</body>
</html>