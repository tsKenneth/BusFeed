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
    var input = document.getElementById('filterbox');
    var options = {
        componentRestrictions: {country: 'sg'}
    };
    autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        calculateRoute({lat:place.geometry.location.lat(),
            lng:place.geometry.location.lng()});
    });
}

function calculateRoute(end) {
    var start = new google.maps.LatLng(userCurPos.lat, userCurPos.lng);
    var request = {
        origin: start,
        destination: end,
        travelMode: 'TRANSIT',
        transitOptions: {
            modes: ['BUS']
        }
    };
    directionsService.route(request, function(result, status) {
        if (status == 'OK') {
            directionsDisplay.setDirections(result);
        } else {
            console.log(status);
        }
  });
}

// Maps Section ===============================================================
// Initialise the map
function initMap() {
    userCurPos = {lat: 1.3483099, lng: 103.680946};
    map = new google.maps.Map(document.getElementById('map2'), {
        zoom: 15,
        center: userCurPos,
        styles:[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative.province","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#ff8200"}]},{"featureType":"administrative.province","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ff3800"}]},{"featureType":"administrative.province","elementType":"labels.text.fill","stylers":[{"color":"#ff6600"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d9d9d9"}]},{"featureType":"administrative.locality","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"on"},{"color":"#ff5d00"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.stroke","stylers":[{"color":"#000000"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#161616"},{"lightness":20}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#393939"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"lightness":21},{"color":"#303030"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#ffad77"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ff5300"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ff5e1c"},{"lightness":18},{"visibility":"on"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]
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
            handleError,options = {
                enableHighAccuracy: true
            }
        );
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
