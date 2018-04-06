// Declare global variable
var userCurPos;
var autocomplete;
var curLocationMarker
var directionsService;
var directionsDisplay;
var map;

// Init Page ==================================================================
function initPage() {
    initMap();
    initRouteRenderer();
    initPlacesAutoComplete();
}


// Route Planning Section =====================================================
function initRouteRenderer() {
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map);
}

function initPlacesAutoComplete() {
    var input = document.getElementById('destinationBox');
    var options = {
        componentRestrictions: {country: 'sg'}
    };
    autocomplete = new google.maps.places.Autocomplete(input, options);
}

function calculateRoute() {
  var start = new google.maps.LatLng(userCurPos.lat, userCurPos.lng);
  var end = document.getElementById('destinationBox').value;
  var request = {
      origin: start,
      destination: end,
      travelMode: 'TRANSIT'
  };
  directionsService.route(request, function(result, status) {
      if (status == 'OK') {
          directionsDisplay.setDirections(result);
      }
  });
}

// Maps Section ===============================================================
// Initialise the map
function initMap() {
    userCurPos = {lat: 1.3483099, lng: 103.680946};
    map = new google.maps.Map(document.getElementById('map2'), {
        zoom: 15,
        center: userCurPos
    });
    curLocationMarker = new google.maps.Marker({
        position: userCurPos,
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
    userCurPos = pos;
    map.setCenter(pos);
    curLocationMarker.setPosition(pos);
}

// Log any error to the console
function handleError(error) {
    console.log(error);
}
