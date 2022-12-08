<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-footer">
                        <form @submit.prevent.keyup="send">
                            <div class="row">                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Latitud:</label>
                                        <input type="text" class="form-control" v-model="message.latitud" id="lat" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Longitud:</label>
                                        <input type="text" class="form-control" v-model="message.longitud" id="lng" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Mensaje:</label>
                                        <textarea v-model="message.mensaje" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>                         
                            
                            
                            <div class="form-group">
                                <button type="submit" class=" btn btn-primary">Enviar mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['user'],
        data() {
            return {
                message: {
                    mensaje: '',
                    user: this.user,
                    latitud: '',
                    longitud: ''        
                },
                location:null,
                gettingLocation: false,
                errorStr:null
            }
        },
        created() {
            //do we support geolocation
            if(!("geolocation" in navigator)) {
                this.errorStr = 'Geolocation is not available.';
                return;
            }

            this.gettingLocation = true;
            // get position
            navigator.geolocation.getCurrentPosition(pos => {
                this.gettingLocation = false;
                this.location = pos;
                this.message.latitud = this.location.coords.latitude;
                this.message.longitud = this.location.coords.longitude;
            }, err => {
                this.gettingLocation = false;
                this.errorStr = err.message;
            })
        },
        methods: {
            send () {
                this.$emit('messagesent', this.message)
                //this.message = {}
                //console.log(this.message);
            }
        }
    }
</script>