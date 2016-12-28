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
</head>

<body class="container">
<div id="app">

    <div class="col-md-8 col-md-offset-2">

        <ul class="nav nav-pills">
            <li role="presentation"><router-link to="/">Читать</router-link></li>
            <li role="presentation"><router-link to="/vote">Голосовать</router-link></li>
            <li role="presentation"><router-link to="/select">Выбрать главу</router-link></li>
            <li role="presentation"><router-link to="/about">О проекте</router-link></li>
        </ul>

        <router-view></router-view>

    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.js"></script>
<script src="/assets/build.js"></script>
</body>
</html>