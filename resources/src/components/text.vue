<template>
    <div>
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
</template>

<script>
    import VuePaginator from 'vuejs-paginator'

    export default {
        data() {
            return {
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
            }
        },
        methods: {
            updateResource(data) {
                this.parts = data
            }
        },
        components: {
            VPaginator: VuePaginator
        }
    }
</script>