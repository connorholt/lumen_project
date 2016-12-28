<template>
    <div>
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
</template>

<script>
    export default {
        data () {
            return {
                id: '',
                errors: []
            }
        },
        methods: {
            create (e) {
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
                });
            }
        }
    }
</script>