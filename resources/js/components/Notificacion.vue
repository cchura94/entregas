<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>FECHA:</th>
                            <th>USUARIO:</th>
                            <th>LATITUD:</th>
                            <th>LONGITUD:</th>
                            <th>MENSAJE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="message in messages" v-bind:key="message.id">
                            <td><strong>{{ message.created_at }}</strong></td>
                            <td>{{ message.user.name }}</td>
                            <td>{{ message.latitud }}</td>
                            <td>{{ message.longitud }}</td>
                            <td>{{ message.mensaje }}</td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['messages', 'id'],
        mounted() {
            console.log(this.messages)
            console.log(this.id)
        }
    }
</script>
<style scoped>
    .panel{
        margin-bottom: 0;
    }
    
</style>