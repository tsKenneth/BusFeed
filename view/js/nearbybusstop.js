// Declare global variable
var map;
var curLocationMarker;
var busstopMarkers = [];

// Busstop Markers ============================================================
function getNearestBusstop(position) {
    var stringedPosition = JSON.stringify(position)
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var busstopCoords = JSON.parse(this.responsetext);
            clearMarkers;
            busstopCoords.forEach(function(item,index){
                var tempMarker = new google.maps.Marker({
                    position: item,
                    map: map
                });
                busstopMarkers.push(tempMarker);
            });
        }
    };
    xmlhttp.open("GET", "../model/Busstop_Model.php?pos="
        + stringedPosition, true);
    xmlhttp.send();
}

// Clear all markers prior to update
function clearMarkers() {
    for (var i = 0; i < busstopMarkers.length; i++) {
        busstopMarkers[i].setMap(null);
    }
    busstopMarkers = [];
}

// Maps Section ===============================================================
// Initialise the map
function initMap() {
    var initLocation = {lat: 1.3483099, lng: 103.680946};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: initLocation
    });
    curLocationMarker = new google.maps.Marker({
        position: initLocation,
        map: map,
        icon: {url:'https://maps.google.com/mapfiles/kml/shapes/man.png',
        scaledSize: new google.maps.Size(40, 40)}
    });
    getLocation()
}

// Watch the user's current location
function getLocation() {
    if (navigator.geolocation) {

       var geoObject = navigator.geolocation.watchPosition(showPosition,
           handleError);
    }
}

// Updates and mark the user's position on the map upon change
function showPosition(position) {
    var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
    };
    map.setCenter(pos);
    curLocationMarker.setPosition(pos);
}

// Log any error to the console
function handleError(error) {
    console.log(error);
}
