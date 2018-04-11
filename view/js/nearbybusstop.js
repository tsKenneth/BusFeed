// Declare global variable
var map;
var curLocationMarker;
var busstopMarkers=[];
var curUserLocation;

function initPage() {
    initMap();
    getNearbyBusstops();
}

// Busstop Arrival Time Section ===============================================
function getBusroutesAtBusstop(busstopCode) {
    var xmlhttp = new XMLHttpRequest();
    $.ajax({
        url: '../controller/nearbybusstop_controller.php',
        data: {function:"retrieveRoutesAtBusstop",
                busstopcode: busstopCode},
        contentType: "application/json; charset=utf-8",
        success: showBusArrivalModal
    });
}

function showBusArrivalModal(results, status, xhr) {
    results = JSON.parse(results);
    var resultsDisplay =`
    <div class=\"table-responsive\">
        <table class=\"table\" id=\"busserviceTable\">
        <thead>
            <tr>
                <th scope=\"col\">Bus Service No.</th>
                <th scope=\"col\">Estimated Arrival Timing [next 2nd 3rd] in minutes</th>
            </tr>
        </thead>
        <tbody>
    `;

    results.forEach(function(element){
        resultsDisplay += `<tr>
            <th scope=\"row\">${element.serviceNo}</th>
            <td id="${element.serviceNo}"
            data-serviceno="${element.serviceNo}"
            data-busstopcode="${element.busStopCode}"
            data-direction="${element.direction}"
            onclick="refreshBusArrivalTiming(this)"
            ">Click to get time</td>
        </tr>`;
        getBusArrivalTiming(element.busStopCode,
            element.serviceNo,element.direction);
    });
    resultsDisplay += `</tbody></table></div>`

    document.getElementById("modalText").innerHTML = resultsDisplay;
}

function refreshBusArrivalTiming(e) {
    getBusArrivalTiming($(e).data('busstopcode'), $(e).data('serviceno'), $(e).data('direction'));
}
function getBusArrivalTiming(busStopCode, serviceNo, direction) {
    var xmlhttp = new XMLHttpRequest();
    $.ajax({
        url: '../controller/nearbybusstop_controller.php',
        data: {function:"retrieveBusArrivalTiming",
                busstopcode: busStopCode,
                serviceno: serviceNo,
                direction: direction},
        contentType: "application/json; charset=utf-8",
        success: updateTimingModal
    });
}

function updateTimingModal(results, status, xhr) {
    var results = JSON.parse(results);

    if (!results.hasOwnProperty("arrivals")) {
        document.getElementById(results.info.serviceNo).innerHTML = "Not in Operation";
        return;
    }

    var finalString = "";
    var arrivals = results.arrivals;
    for (var i = 0; i < arrivals.length; i++) {
        if (arrivals[i].arrivalMinute==0) {
            finalString += "Arriving";
        } else if(arrivals[i].arrivalMinute<0) {
            finalString += "";
        } else {
            finalString += arrivals[i].arrivalMinute;
        }
        finalString += " "
    }
    document.getElementById(results.info.serviceNo).innerHTML = finalString;
}

// Busstop Markers Section  ===================================================
function getNearbyBusstops() {
    var xmlhttp = new XMLHttpRequest();
    $.ajax({
        url: '../controller/nearbybusstop_controller.php',
        data: {function:"retrieveNearbyBusstop",
                pos: JSON.stringify(curUserLocation)},
        contentType: "application/json; charset=utf-8",
        success: markNearbyBusstops
    });
}

function markNearbyBusstops(results, status, xhr) {
    var tempMarker;
    clearMarkers();
    busstopCoords = JSON.parse(results);
    busstopCoords.forEach(function(item,index){
        tempMarker = new google.maps.Marker({
            position: {lat:item.latitude,lng:item.longitude},
            map: map,
            busstopcode: item.busStopCode,
            icon: {url:'js/mapsicon/busstopIcon.png',
            scaledSize: new google.maps.Size(30, 30)}
        });
        tempMarker.addListener('click', function() {
            getBusroutesAtBusstop(this.busstopcode);
            $('#nearbyModal').modal('show');
        });
        busstopMarkers.push(tempMarker);
    });
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
    curUserLocation = initLocation;
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: initLocation,
        styles:[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative.province","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#ff8200"}]},{"featureType":"administrative.province","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ff3800"}]},{"featureType":"administrative.province","elementType":"labels.text.fill","stylers":[{"color":"#ff6600"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d9d9d9"}]},{"featureType":"administrative.locality","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"on"},{"color":"#ff5d00"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.stroke","stylers":[{"color":"#000000"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#161616"},{"lightness":20}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#393939"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"lightness":21},{"color":"#303030"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#ffad77"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ff5300"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ff5e1c"},{"lightness":18},{"visibility":"on"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]
    });
    curLocationMarker = new google.maps.Marker({
        position: initLocation,
        map: map,
        icon: {url:'https://maps.google.com/mapfiles/kml/shapes/man.png',
        scaledSize: new google.maps.Size(40, 40)},
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
    curUserLocation = pos;
    map.setCenter(pos);
    curLocationMarker.setPosition(pos);
    getNearbyBusstops();
}

// Log any error to the console
function handleError(error) {
    console.log(error);
}
