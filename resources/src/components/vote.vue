<template>
    <div>
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
</template>


<script>
    import VuePaginator from 'vuejs-paginator'

    export default {
        data() {
            return {
                messages: [],
                input: "",
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
            }
        },
        computed: {
            parts () {
                return this.$store.getters.all;
                //return this.$store.state.parts
            }
        },
        components: {
            VPaginator: VuePaginator
        },
        methods: {
            updateResource(data){
                //this.parts = data
                this.$store.commit('setAll', data);
            },
            create(e) {
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
                        this.author = '';
                        this.text = '';
                    },
                    error: function () {
                        $.notify("Вариант не добавлен", {
                            position: "top center",
                            className: "error"
                        });
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
    }

</script>