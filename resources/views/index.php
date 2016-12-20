<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Коллективная проза</title>

    <!-- CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <style>
        body        { padding-top:30px; }
        form        { padding-bottom:20px; }
        .part    { padding-bottom:20px; }
    </style>

    <!-- JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/0.12.1/vue.min.js"></script>

</head>

<body class="container" id="story">
<div class="col-md-8 col-md-offset-2">
    <div class="jumbotron">
        <h2>Коллективная проза</h2>
        <h4>Пишем произведение вместе</h4>
    </div>

    <form v-on="submit: onCreate">
        <div class="form-group">
            <input type="text" class="form-control input-sm" name="author" v-model="author" placeholder="Представьтесь">
        </div>

        <div class="form-group">
            <textarea class="form-control input-sm" name="text" v-model="text" placeholder="Введите текст"></textarea>
        </div>

        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary btn-md">Отправить на голосование</button>
        </div>
    </form>

    <div class="part" v-repeat="part: parts">
        <h3>Часть <small>by {{ part.author }}</h3>
        <p>{{ part.text }}</p>
        <p><span class="btn btn-primary text-muted" v-on="click: onDelete(part)">Удалить</span></p>
    </div>
</div>
<script type="text/javascript">
    new Vue({
        el: '#story',

        data: {
            parts: [],
            text: '',
            author: ''
        },

        ready: function() {
            this.getMessages();
        },

        methods: {
            getMessages: function() {
                $.ajax({
                    context: this,
                    url: "/api/part",
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
                        this.author = ''
                        this.text = ''
                    }
                })
            },

            onDelete: function (part) {
                $.ajax({
                    context: part,
                    type: "DELETE",
                    url: "/api/part/" + part.id,
                })
                this.parts.$remove(part);
            }
        }
    })
</script>
</body>
</html>