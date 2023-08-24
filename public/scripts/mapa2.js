
var mymap = L.map('map').setView([40.4168, -3.7038], 13); // Madrid

console.log('Mapa cargado:', mymap);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    maxZoom: 19
}).addTo(mymap);


var startUbication = document.getElementById('co_start').innerText;
var startUbicationArray = startUbication.split(',');
console.log(startUbicationArray[0]);
console.log(startUbicationArray[1]);

var endUbication = document.getElementById('co_end').innerText;
var endUbicationArray = endUbication.split(',');
console.log(endUbicationArray[0]);
console.log(endUbicationArray[1]);



var startCoord = [startUbicationArray[0],startUbicationArray[1]];
var endCoord = [endUbicationArray[0], endUbicationArray[1]];


L.marker(startCoord).addTo(mymap);
L.marker(endCoord).addTo(mymap);


mymap.fitBounds([startCoord, endCoord]);