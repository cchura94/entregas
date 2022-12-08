@extends('layouts.app')


@section('script')

<script>
  var map;


var marker;          //variable del marcador
var coords = {};    //coordenadas obtenidas con la geolocalización

//Funcion principal
initMap = function () 
{
    //usamos la API para geolocalizar el usuario
    navigator.geolocation.getCurrentPosition(
      function (position){
        coords =  {
          lng: position.coords.longitude,
          lat: position.coords.latitude
        };
        //alert(coords.lng +" , "+coords.lat)
        setMapa(coords);  //pasamos las coordenadas al metodo para crear el mapa
            

      },
      function(error){
            console.log(error);
            //alert("error");
      });
    
  }



  function setMapa (coords)
  {   
      //Se crea una nueva instancia del objeto mapa
      var map = new google.maps.Map(document.getElementById('map'),
      {
        zoom: 18,
        center:new google.maps.LatLng(coords.lat,coords.lng),

      });

      //Creamos el marcador en el mapa con sus propiedades
      //para nuestro obetivo tenemos que poner el atributo draggable en true
      //position pondremos las mismas coordenas que obtuvimos en la geolocalización
      console.log(coords.lat);
      console.log(coords.lng);
      document.getElementById('lat').value = coords.lat;
      document.getElementById('lng').value = coords.lng;
      marker = new google.maps.Marker({
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(coords.lat,coords.lng),

      });
      //agregamos un evento al marcador junto con la funcion callback al igual que el evento dragend que indica 
      //cuando el usuario a soltado el marcador
      marker.addListener('click', toggleBounce);
      
      marker.addListener( 'dragend', function (event)
      {
        //escribimos las coordenadas de la posicion actual del marcador dentro del input #coords

        document.getElementById("lat").value = this.getPosition().lat();
        document.getElementById("lng").value = this.getPosition().lng();
      });
    }

//callback al hacer clic en el marcador lo que hace es quitar y poner la animacion BOUNCE
function toggleBounce() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

// Carga de la libreria de google maps 
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCssDur9_mhqQThoCuEYDcjq2EmVCCQYXw&callback=initMap"
async defer></script>

@endsection

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">ENTREGA CODIGO PEDIDO: <span class="badge badge-warning">{{ $entrega->pedido->cod_pedido }} </span> - ENCARGADO: <span class="badge badge-success">{{ $entrega->repartidor->paterno }} {{ $entrega->repartidor->materno }} {{ $entrega->repartidor->nombre }} </span></div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <label for="">CODIGO PEDIDO</label>
              <input type="text" value="{{ $entrega->pedido->cod_pedido }}" disabled="" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="">NOMBRE</label>
              <input type="text" value="{{ $entrega->pedido->sucursal->tienda->nombre }} - {{ $entrega->pedido->sucursal->nombre }}" disabled="" class="form-control">

            </div>
            <div class="col-md-6">
              <label for="">DIRECCION</label>
              <input type="text" value="{{ $entrega->pedido->sucursal->direccion }}" disabled="" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="">ESTADO</label>
              <br>
              @if($entrega->estado === 0)
              <span class="badge badge-danger">Pendiente</span>
              @elseif($entrega->estado === 1)
              <span class="badge badge-warning">En Proceso</span>
              @elseif($entrega->estado === 2)
              <span class="badge badge-success">Entregado</span>
              @elseif($entrega->estado === 3)
              <span class="badge badge-secondary">Demorado</span>
              @else
              <span class="badge badge-danger">Cancelado</span>
              @endif

            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">REGISTRAR ENTREGA </div>

        <div class="card-body">
          <form action="{{ route('registrar_entrega_ruta', $entrega->id) }}" method="post">
            @csrf
            @Method('PUT')

            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                    <!--label for="">LATITUD</label-->
                    <div class="input-group">
                      <input type="hidden" class="form-control" name="latitud_r" placeholder="Latitud" id="lat">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <!--label for="">LONGITUD</label-->
                    <div class="input-group">
                      <input type="hidden" class="form-control" name="longitud_r" placeholder="Longitud" id="lng">
                    </div>
                  </div>       

                  <div class="col-md-12">
                    <div id="map" style="width: 100%;height: 300px;"></div>
                  </div>
                </div>
              </div>


              <div class="col-md-6">
                <label for="">DESCRIPCION</label>
                <textarea name="descripcion" id="" cols="30" rows="10" class="form-control"></textarea>
              </div>

              <div class="col-md-6">
                <input type="hidden" value="2" name="estado">

              </div>
              <div class="col-md-6">
                <input type="hidden" value="2" name="estado">

              </div>
               @if($entrega->estado === 1)
              <input type="submit" value="Registrar Entrega" class="btn btn-primary">
              @endif
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>


@endsection

