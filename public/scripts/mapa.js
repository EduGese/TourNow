var mymap = L.map('map').setView([40.4168, -3.7038], 13); // Madrid
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
  maxZoom: 19
}).addTo(mymap);

var startPoint = null;
var endPoint = null;
var startMarker = null;
var endMarker = null;
var routeControl = null;

function obtenerDireccion(coordenadas, callback) {
  var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + coordenadas[0] + '&lon=' + coordenadas[1];
  fetch(url)
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {
      var direccion = data.address;
      var direccionCompleta = direccion.road || direccion.pedestrian;
      if (direccion.house_number) {
        direccionCompleta += ' ' + direccion.house_number;
      }
      if (direccion.postcode) {
        direccionCompleta += ', ' + direccion.postcode;
      }
      direccionCompleta += ', ' + direccion.city + ', ' + direccion.state + ', ' + direccion.country;
      callback(direccionCompleta);
    });
}

// function crearRuta() {
//   if (startPoint === null || endPoint === null) {
//     alert('Debes seleccionar dos puntos en el mapa.');
//     return;
//   }

//   if (routeControl !== null) {
//     mymap.removeControl(routeControl);
//   }

//   // Crear control de enrutamiento de Leaflet Routing Machine
//   var start = L.latLng(startPoint[0], startPoint[1]);
//   var end = L.latLng(endPoint[0], endPoint[1]);

//   // Configurar el enrutamiento para peatones
//   var router = L.Routing.osrmv1({
//     serviceUrl: 'https://router.project-osrm.org/route/v1',
//     profile: 'foot'
//   });

//   routeControl = L.Routing.control({
//     waypoints: [start, end],
//     router: router,
//     lineOptions: {
//       styles: [{ color: 'blue', opacity: 0.6, weight: 6, dashArray: '10 10' }]
//     }
//   }).addTo(mymap);
// }

mymap.on('click', function(e) {
  if (startPoint === null) {
    startPoint = [e.latlng.lat, e.latlng.lng];
    startMarker = L.marker(startPoint).addTo(mymap);
    obtenerDireccion(startPoint, function(direccion) {
      document.getElementById('create_activity_form_start_ubication').value = direccion;
      document.getElementById('create_activity_form_start_coord').value = e.latlng.lat + ',' + e.latlng.lng;
    });
  } else if (endPoint === null) {
    endPoint = [e.latlng.lat, e.latlng.lng];
    endMarker = L.marker(endPoint).addTo(mymap);
    obtenerDireccion(endPoint, function(direccion) {
      document.getElementById('create_activity_form_end_ubication').value = direccion;
      document.getElementById('create_activity_form_end_coord').value = e.latlng.lat + ',' + e.latlng.lng;
    });
    document.getElementById('crearRutaBtn').disabled = false;
  }
});
var clearButton = document.getElementById('clear');
  clearButton.addEventListener('click', function() {
    if (startMarker !== null) {
      mymap.removeLayer(startMarker);
      startMarker = null;
      startPoint = null;
    }
    if (endMarker !== null) {
      mymap.removeLayer(endMarker);
      endMarker = null;
      endPoint = null;
    }

    document.getElementById('create_activity_form_start_ubication').value = '';
    document.getElementById('create_activity_form_end_ubication').value = '';
    document.getElementById('create_activity_form_start_coord').value = '';
    document.getElementById('create_activity_form_end_coord').value = '';

    // document.getElementById('crearRutaBtn').disabled = true;
  });
