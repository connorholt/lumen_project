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
</head>

<body class="container">
<div id="app">

    <div class="col-md-8 col-md-offset-2">

        <ul class="nav nav-pills">
            <li role="presentation"><a href="/">Читать</a></li>
            <li role="presentation"><a href="/vote">Голосовать</a></li>
            <li role="presentation"><a href="/selected">Выбрать главу</a></li>
            <li role="presentation"><a href="/about">О проекте</a></li>
        </ul>

        <div class="jumbotron">
            <h2>Коллективная проза</h2>
            <h4>Пишем произведение вместе</h4>
        </div>

        <div class="part" v-for="part in parts">
            <p>{{ part.text }}</p>
            <span class="pull-right label label-success">by {{ part.author }}</span>
            <div class="clearfix"></div>
            <hr>
        </div>
        <v-paginator :options="options" :resource_url="resource_url" @update="updateResource"></v-paginator>
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

    VuePaginator.template = '#paginator';

    var app = new Vue({
        el: '#app',
        data: {
            parts: [],
            resource_url: '/api/text/parts',
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
            }
        }
    })

</script>
</body>
</html>