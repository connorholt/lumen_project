<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Коллективная проза</title>

    <!-- CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <style>
        body        { padding-top:10px; }
        form        { padding-bottom:20px; }
        .part    { padding-bottom:20px; }
        a.likes { margin-right: 10px; }
        a.likes, a.dislike { text-decoration: none; }
    </style>

    <!-- JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/0.12.1/vue.min.js"></script>

</head>

<body class="container" id="story">

<div class="col-md-8 col-md-offset-2">

    <ul class="nav nav-pills">
        <li role="presentation"><a href="/">Читать</a></li>
        <li role="presentation"><a href="/vote">Голосовать</a></li>
        <li role="presentation"><a href="/about">О проекте</a></li>
    </ul>

    <h3>На голосовании</h3>

    <div class="part" v-repeat="part: parts">
        <p>{{ part.text }}</p>
        <a href="javascript:" v-on="click: onLike(part)" class="likes"><i class="glyphicon glyphicon-thumbs-up"></i> <span class="badge">{{ part.like_count }}</span></a>
        <a href="javascript:" v-on="click: onDislike(part)" class="dislike"><i class="glyphicon glyphicon-thumbs-down"></i> <span class="badge">{{ part.dislike_count }}</span></a>
        <span class="pull-right label label-success">by {{ part.author }}</span>
        <div class="clearfix"></div>
        <hr>
    </div>

</div>
<script type="text/javascript">
    new Vue({
        el: '#story',

        data: {
            parts: [],
            text: '',
            author: '',
            like_count: 0,
            dislike_count: 0
        },

        ready: function() {
            this.getMessages();
        },

        methods: {
            getMessages: function() {
                $.ajax({
                    context: this,
                    url: "/api/vote/part",
                    success: function (result) {
                        this.$set("parts", result)
                    }
                })
            },

            onCreate: function(e) {
                e.preventDefault()
                $.ajax({
                    context: this,
                    type: "POST",
                    data: {
                        author: this.author,
                        text: this.text
                    },
                    url: "/api/part",
                    success: function(result) {
                        this.parts.push(result);
                        this.author = '';
                        this.text = '';
                        this.like_count = 0;
                        this.dislike_count = 0;
                    }
                })
            },

            onLike: function (part) {
                $.ajax({
                    context: part,
                    type: "PUT",
                    url: "/api/part/like/" + part.id,
                    success: function(result) {
                        this.like_count = result.like_count;
                    }
                })
            },

            onDislike: function (part) {
                $.ajax({
                    context: part,
                    type: "PUT",
                    url: "/api/part/dislike/" + part.id,
                    success: function(result) {
                        this.dislike_count = result.dislike_count;
                    }
                })
            }
        }
    })
</script>
</body>
</html>