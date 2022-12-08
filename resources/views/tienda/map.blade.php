@extends('layouts.admin')

@section('contenido_con_mapa')
<div class="card card-warning">
    <div class="card-header">
            <h3 class="card-title">MAPA DE CLIENTES</h3>
            <a href="{{route('sucursal.index')}}" class="btn btn-warning float-right">volver</a>
    </div>
    <div class="card-body">
    <label for="">Seleccionar: </label>
    <select name="tienda" id="tie" onchange="seleccionar_tienda()" class="form-control">
        <option>Seleccionar...</option>
    </select>
    <div class="row">
        <div class="col-md-12">
            <!--div id="mapCanvas" style="width:100%;height:600px;background:#787;"></div-->
            <div id='map' style="width:100%;height:600px;background:#787;"></div>        
        
        </div>
    </div>       
        

    </div>
</div>
@endsection

@section('mapbox')
<script>
    var ubicaciones = [];
    iniciarMapa();
function iniciarMapa(){
	mapboxgl.accessToken = 'pk.eyJ1IjoiY2h1cmEiLCJhIjoiY2s4aXcyN3d5MDFhYjNscHFrNXRwZjJncSJ9.0pxS7oAdvDckGKhoUjTiMg';
    var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-68.173132, -16.567758],
    zoom: 11
    });

    map.addControl(new mapboxgl.NavigationControl());


    map.on('load', function() {

        map.addLayer({
                "id" : "places",
                "type" : "symbol",
                "source" : {
                    "type" : "geojson",
                    "data" : {
                        "type" : "FeatureCollection",
                        "features" : ubicaciones
                    }
                },
                "layout" : {
                    "icon-image" : "{icon}-15",
                    'icon-size': 2,
                    "icon-allow-overlap" : true
                }
            });
            
        console.log("Ubicaciones: ",ubicaciones)

        map.on('click', 'places', function(e) {
                var coordinates = e.features[0].geometry.coordinates.slice();
                var description = e.features[0].properties.description;

                // Ensure that if the map is zoomed out such that multiple
                // copies of the feature are visible, the popup appears
                // over the copy being pointed to.
                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }

                new mapboxgl.Popup().setLngLat(coordinates).setHTML(description)
                        .addTo(map);
            });

             // Change the cursor to a pointer when the mouse is over the places layer.
             map.on('mouseenter', 'places', function() {
                map.getCanvas().style.cursor = 'pointer';
            });

            // Change it back to a pointer when it leaves.
            map.on('mouseleave', 'places', function() {
                map.getCanvas().style.cursor = '';
            });


    });
}
</script>

@endsection
@section('js')

<script>


 cargar_tiendas()

// funcion para Cargar tienda al campo <select>
function cargar_tiendas() {
 var array = @json($tiendas);

 addOptions("tienda", array);
}

// Rutina para agregar opciones a un <select>
function addOptions(domElement, array) {
 var select = document.getElementsByName(domElement)[0];

 for (value in array) {
  var option = document.createElement("option");
  option.text = array[value].nombre;
  select.add(option);
 }
}

function seleccionar_tienda(){
    var select = document.getElementById("tie").selectedIndex
    var sucursales= @json($sucursales);
    var arraytiendas = @json($tiendas);
    ubicaciones = [];
    detalles_ubi = [];
    
    for (let i = 0; i < sucursales.length; i++) {
        if(sucursales[i] && sucursales[i].tienda){
            if(sucursales[i].tienda_id === arraytiendas[select-1].id){
                ubi = [sucursales[i].nombre, sucursales[i].latitud, sucursales[i].longitud];
                //ubicaciones.push(ubi);
                var id_tie = sucursales[i].tienda.id;
                ubicaciones.push(
                    {
                        'type': 'Feature',
                        'geometry': {
                            'type': 'Point',
                            'coordinates': [parseFloat(parseFloat(sucursales[i].longitud).toFixed(5)), parseFloat(parseFloat(sucursales[i].latitud).toFixed(5))]
                        },properties: {
                                "description" : `<strong>${sucursales[i].nombre}</strong> <br/><br/>
                                        ${sucursales[i].tienda.nombre}<br/>
                                        <strong>TELEFONO : </strong>${sucursales[i].telefono_c}<br/>
                                        <strong>DIRECCION : </strong>${sucursales[i].direccion }<br/>

                                        <a href="/admin/sucursal/${sucursales[i].id}" target=\"_blank\">VER SUCURSAL</a>`,
                        "icon": "rocket"
                            }
                    })
                
               /* det_ubi = ['<div class="info_content">' +
                    '<h4>'+ sucursales[i].nombre +'('+sucursales[i].tienda.nombre +')</h4>' +
                    '<p>'+ sucursales[i].descripcion +'</p>' +
                    '<p>TELEFONO: '+ sucursales[i].telefono_c +'</p>' +
                    '<p>DIRECCION: '+ sucursales[i].direccion +'</p>' +
                    '<a href=/admin/tienda/'+id_tie+'>ver tienda</a>' + '</div>'];   
                detalles_ubi.push(det_ubi) */

                console.log(ubicaciones)
            }
        }       
    }
    //initMap();
    iniciarMapa();

}



var ubicaciones = [];
var detalles_ubi = [];
var tiendas= @json($tiendas);
var sucursales= @json($sucursales);

for (let i = 0; i < sucursales.length; i++) {
    if(sucursales[i] && sucursales[i].tienda){
        ubi = [sucursales[i].nombre, sucursales[i].latitud, sucursales[i].longitud];
        //ubicaciones.push(ubi);
        ubicaciones.push(
                    {
                        'type': 'Feature',
                        'geometry': {
                            'type': 'Point',
                            'coordinates': [sucursales[i].latitud, sucursales[i].longitud]
                        }
                    })
        var id_tie = sucursales[i].tienda.id;
        det_ubi = ['<div class="info_content">' +
            '<h4>'+ sucursales[i].nombre +'('+sucursales[i].tienda.nombre +')</h4>' +
            '<p>'+ sucursales[i].descripcion +'</p>' +
            '<p>TELEFONO: '+ sucursales[i].telefono_c +'</p>' +
            '<p>DIRECCION: '+ sucursales[i].direccion +'</p>' +
            '<a href=/admin/tienda/'+id_tie+'>ver tienda</a>' + '</div>'];   
        detalles_ubi.push(det_ubi)              

    }
    
}
//console.log(ubicaciones);

        
/*initMap = function() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
    map.setTilt(50);
        
    // Multiple markers location, latitude, and longitude
    var markers = ubicaciones;
                        
    // Info window content
    var infoWindowContent = detalles_ubi;
        
    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;

    //var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
    var iconBase = 'http://maps.google.com/mapfiles/kml/paddle/'
    
    // Place each marker on the map  
    for( i = 0; i < markers.length; i++ ) {
        
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            //icon: iconBase + 'map-icon-store.png',
            icon: iconBase + 'purple-blank.png',            
            map: map,
            title: markers[i][0]
        });
        
        // Add info window to marker    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Center the map to fit all markers on the screen
        map.fitBounds(bounds);
    }

    // Set zoom level
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });
    
}*/
// Load initialize function
//google.maps.event.addDomListener(window, 'load', initMap);
</script>
    <!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCssDur9_mhqQThoCuEYDcjq2EmVCCQYXw&callback=initMap"
    async defer></script-->

@endsection