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

        .new {
            border-left: 5px solid #2aabd2;
            padding-left: 10px;
            padding-bottom: 0px;
            margin-bottom: 10px;
        }
    </style>

    <!-- JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.1.7/vue.js"></script>
    <script src="//cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vuejs-paginator/2.0.0/vuejs-paginator.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.js"></script>
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
            <h2>Голосование</h2>
            <h4>Также вы можете оставить свой вариант</h4>
        </div>

        <div class="part" v-bind:class="{ new: part.is_new }" v-for="part in parts">
            <p>{{ part.text }}</p>
            <a href="javascript:" v-on:click="like(part)" class="likes">
                <i class="glyphicon glyphicon-thumbs-up"></i>
                <span class="badge">{{ part.like_count }}</span>
            </a>
            <a href="javascript:" v-on:click="dislike(part)" class="dislike">
                <i class="glyphicon glyphicon-thumbs-down"></i>
                <span class="badge">{{ part.dislike_count }}</span>
            </a>
            <span class="pull-right label label-default">#{{ part.id }} by {{ part.author }}</span>
            <div class="clearfix"></div>
            <hr>
        </div>
        <v-paginator :options="options" :resource_url="resource_url" @update="updateResource"></v-paginator>

        <br/>
        <h4>Свой вариант</h4>
        <form v-on:submit="create">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="author" v-model="author"
                       placeholder="Представьтесь">
            </div>

            <div class="form-group">
            <textarea rows="5" class="form-control textarea-sm" name="text" v-model="text"
                      placeholder="Введите текст"></textarea>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-md">Отправить на голосование</button>
            </div>
        </form>
    </div>
</div>

<template id="paginator">
    <div class="v-paginator">
        <button class="btn btn-default" @click="fetchData(prev_page_url)" :disabled="!prev_page_url">
            {{config.previous_button_text}}
        </button>
        <span>Страница {{current_page}} из {{last_page}}</span>
        <button class="btn btn-default" @click="fetchData(next_page_url)" :disabled="!next_page_url">
            {{config.next_button_text}}
        </button>
    </div>
</template>


<script type="text/javascript">

    var socket = io.connect('http://localhost:8890');
    socket.on('message', function (data) {
        var part = JSON.parse(data);

        part.like_count = 0;
        part.dislike_count = 0;
        part.is_new = true;

        app.parts.unshift(part);

        console.log(part);
    });


    VuePaginator.template = '#paginator';

    var app = new Vue({
        el: '#app',
        data: {
            messages: [],
            input: "",

            parts: [],
            text: '',
            author: '',
            like_count: 0,
            dislike_count: 0,

            resource_url: '/api/vote/parts',
            options: {
                remote_data: 'list',
                remote_current_page: 'current',
                remote_last_page: 'count',
                remote_next_page_url: 'next',
                remote_prev_page_url: 'prev',
                next_button_text: 'Дальше',
                previous_button_text: 'Назад'
            }
        },
        components: {
            VPaginator: VuePaginator
        },
        methods: {
            updateResource(data){
                this.parts = data
            },

            create: function (e) {
                e.preventDefault();
                $.ajax({
                    context: this,
                    type: "POST",
                    data: {
                        author: this.author,
                        text: this.text
                    },
                    url: "/api/part",
                    success: function (result) {
                        // @todo если ошибка показать сообщенние
                        this.author = '';
                        this.text = '';
                    }
                })
            },

            like: function (part) {
                $.ajax({
                    context: part,
                    type: "PUT",
                    url: "/api/part/like/" + part.id,
                    success: function (result) {
                        this.like_count = result.like_count;
                    }
                })
            },

            dislike: function (part) {
                $.ajax({
                    context: part,
                    type: "PUT",
                    url: "/api/part/dislike/" + part.id,
                    success: function (result) {
                        this.dislike_count = result.dislike_count;
                    }
                })
            }
        }
    });

    socket.on('chat message', function (msg) {
        vm.messages.push(msg);
    });

</script>
</body>
</html>