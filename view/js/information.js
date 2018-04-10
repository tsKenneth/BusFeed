var busstopArray;
var busServiceArray;
var busstopCookies;
var busServiceCookies;

function initialiseTable() {
    if (Cookies.get('busstop')){
        busstopCookies = Cookies.get('busstop');
    } else {
        busstopCookies = [];
        Cookies.set('busstop',[]);
    }

    if (Cookies.get('busservice')){
        busServiceCookies =  Cookies.get('busservice');
    } else {
        busServiceCookies = [];
        Cookies.set('busservice',[]);
    }

    retrieveBusstopList();
    retrieveBusserviceList()
    document.querySelector('#filterBox').addEventListener('keyup',
        filterBusstopTable, false);
    document.querySelector('#filterBox').addEventListener('keyup',
        filterBusserviceTable, false);
}

function toggleBusServiceBookmark() {
    $('#bookmarkButton').button('toggle');
    if ($('#bookmarkButton').attr('data-bookmarked') == "true") {
        var tempArr = JSON.parse(busServiceCookies);
        var index = tempArr.findIndex(function(element) {
            return element.serviceNo == $('#bookmarkButton').data('serviceno').toString() &&
            element.direction == $('#bookmarkButton').data('direction').toString();
        });
        if (index > -1 ) {
            tempArr.splice(index, 1);
        }

        Cookies.set('busservice',JSON.stringify(tempArr));
        busServiceCookies = Cookies.get('busservice');
        $('#bookmarkButton').attr('data-bookmarked','false');
        $('#bookmarkButton').text("Bookmark");
    } else {
        var tempArr = JSON.parse(busServiceCookies);
        tempArr.push({serviceNo:$('#bookmarkButton').data('serviceno').toString(),direction:$('#bookmarkButton').data('direction').toString()});
        Cookies.set('busservice',JSON.stringify(tempArr));
        busServiceCookies = Cookies.get('busservice');
        $('#bookmarkButton').attr('data-bookmarked','true');
        $('#bookmarkButton').text("Remove Bookmark");
    }

}

function toggleBusstopBookmark() {
    $('#bookmarkButton').button('toggle');
    if ($('#bookmarkButton').attr('data-bookmarked') == "true") {
        var tempArr = JSON.parse(busstopCookies);
        var index = tempArr.indexOf($('#bookmarkButton').data('busstopcode').toString());
        if (index > -1) {
            tempArr.splice(index, 1);
        }

        Cookies.set('busstop',JSON.stringify(tempArr));
        busstopCookies = Cookies.get('busstop');
        $('#bookmarkButton').attr('data-bookmarked','false');
        $('#bookmarkButton').text("Bookmark");
    } else {
        var tempArr = JSON.parse(busstopCookies);
        tempArr.push($('#bookmarkButton').data('busstopcode').toString());
        Cookies.set('busstop',JSON.stringify(tempArr));
        busstopCookies = Cookies.get('busstop');
        $('#bookmarkButton').attr('data-bookmarked','true');
        $('#bookmarkButton').text("Remove Bookmark");
    }
}

// Information Modal and Bus Arrival Information
$('#informationModal').on('show.bs.modal', function(modal) {
    var modalType = modal.relatedTarget.dataset.type;

    if (modalType == "busstop") {
        var busstopCode = modal.relatedTarget.dataset.busstopcode;
        for (var i=0; i < busstopArray.length; i++) {
            if (busstopArray[i].busStopCode == busstopCode) {
                var displayString = `
                <p>
                    <b>Busstop Code:</b> ${busstopArray[i].busStopCode} <br />
                    <b>Road Name:</b> ${busstopArray[i].roadName} <br />
                    <b>Description:</b> ${busstopArray[i].description} <br />
                    <b>Latitude:</b> ${busstopArray[i].latitude} <br />
                    <b>Longitude:</b> ${busstopArray[i].longitude} <br />
                </p>
                <div id="busStopArrivalTable" class=\"table-responsive\">
                </div>
                <br/>
                <button id="bookmarkButton" onclick="toggleBusstopBookmark()" data-busstopcode="${busstopArray[i].busStopCode}" data-toggle="button" data-bookmarked="false" class="btn btn-success" aria-pressed="true" autocomplete="off">Bookmark</button>
                `;

                getFullBusStopRoute(busstopArray[i].busStopCode)
                document.getElementById("modalText").innerHTML = displayString;

                var cookiesArr = JSON.parse(busstopCookies);
                cookiesArr.forEach(function(element) {
                    if(element == busstopArray[i].busStopCode) {
                        $('#bookmarkButton').button('toggle');
                        $('#bookmarkButton').text("Remove Bookmark");
                        $('#bookmarkButton').attr('data-bookmarked','true');
                    }
                });

                break;
            }
        }
    } else if(modalType == "busservice") {
        var serviceno = modal.relatedTarget.dataset.serviceno;
        var direction = modal.relatedTarget.dataset.direction;
        var resultsDisplay = ""
        for (var i=0; i < busServiceArray.length; i++) {
            if (busServiceArray[i].serviceNo == serviceno) {
                var displayString = `
                <p>
                    <b>Service Number:</b> ${busServiceArray[i].serviceNo} <br />
                    <b>Operator:</b> ${busServiceArray[i].operator} <br />
                    <b>Direction:</b> ${busServiceArray[i].direction} <br />
                    <b>Category:</b> ${busServiceArray[i].category} <br />
                    <b>AM Peak Frequency:</b> ${busServiceArray[i].AM_Peak_Freq} <br />
                    <b>AM Offpeak Frequency:</b> ${busServiceArray[i].AM_Offpeak_Freq} <br />
                    <b>PM Peak Frequency:</b> ${busServiceArray[i].PM_Peak_Freq} <br />
                    <b>PM Offpeak Frequency:</b> ${busServiceArray[i].PM_Offpeak_Freq} <br />
                    <b>Loop at:</b> ${busServiceArray[i].loopDesc} <br />
                </p>
                <div id="busServiceArrivalTable" class=\"table-responsive\">
                </div>
                <br/>
                <button id="bookmarkButton" onclick="toggleBusServiceBookmark()"
                data-direction="${busServiceArray[i].direction}"
                data-serviceno="${busServiceArray[i].serviceNo}"
                data-toggle="button" data-bookmarked="false"
                class="btn btn-success" aria-pressed="true"
                autocomplete="off">Bookmark</button>
                `;

                getFullBusserviceRoute(busServiceArray[i].serviceNo,busServiceArray[i].direction);
                document.getElementById("modalText").innerHTML = displayString;

                var cookiesArr = JSON.parse(busServiceCookies);
                cookiesArr.forEach(function(element) {
                    if(element.serviceNo == busServiceArray[i].serviceNo
                        && element.direction == busServiceArray[i].direction) {
                        $('#bookmarkButton').button('toggle');
                        $('#bookmarkButton').text("Remove Bookmark");
                        $('#bookmarkButton').attr('data-bookmarked','true');
                    }
                });
                break;
            }
        }
    }
});

function getFullBusserviceRoute(serviceNo,direction) {
    var xmlhttp = new XMLHttpRequest();
    $.ajax({
        url: '../controller/information_busservice_controller.php',
        data: {function:"retrieveBusRouteFromService",
                serviceNo: serviceNo,
                direction: direction},
        contentType: "application/json; charset=utf-8",
        success: updateBusServiceArrivalTable
    });
}

function getFullBusStopRoute(busStopCode) {
    var xmlhttp = new XMLHttpRequest();
    $.ajax({
        url: '../controller/information_busstop_controller.php',
        data: {function:"retrieveBusRouteFromBusStop",
                busstopcode: busStopCode},
        contentType: "application/json; charset=utf-8",
        success: updateBusStopArrivalTable
    });
}

function updateBusStopArrivalTable(results, status, xhr) {
    var results = JSON.parse(results);
    var resultsDisplay = `<table class=\"table\" id=\"busStopTable\">
    <thead>
        <tr>
            <th scope=\"col\">Bus Service No.</th>
            <th scope=\"col\">Estimated Arrival Timing [next 2nd 3rd] in minutes</th>
        </tr>
    </thead>
    <tbody>`

    results.forEach(function(element){
        resultsDisplay += `<tr>
            <th scope=\"row\">${element.serviceNo}</th>
            <td id="${element.serviceNo}"
            data-serviceno="${element.serviceNo}"
            data-busstopcode="${element.busStopCode}"
            data-direction="${element.direction}"
            data-type="busstop"
            onclick="refreshBusArrivalTiming(this)"
            ">Click to get time</td>
        </tr>`;
    });
    resultsDisplay += `</tbody></table>`
    document.getElementById('busStopArrivalTable').innerHTML = resultsDisplay;
}

function updateBusServiceArrivalTable(results, status, xhr) {
    var results = JSON.parse(results);
    var iterator = 1;
    var resultsDisplay = `<table class=\"table\" id=\"busserviceTable\">
    <thead>
        <tr>
            <th scope=\"col\">Order</th>
            <th scope=\"col\">Bus Stop Code</th>
            <th scope=\"col\">Estimated Arrival Timing [next 2nd 3rd] in minutes</th>
        </tr>
    </thead>
    <tbody>`

    results.forEach(function(element){
        resultsDisplay += `<tr>
            <th scope=\"row\">${iterator}</th>
            <td>${element.busStopCode}</td>
            <td id="${element.busStopCode}"
            data-serviceno="${element.serviceNo}"
            data-busstopcode="${element.busStopCode}"
            data-direction="${element.direction}"
            data-type="busservice"
            onclick="refreshBusArrivalTiming(this)"
            ">Click to get time</td>
        </tr>`;
        iterator++;
    });
    resultsDisplay += `</tbody></table>`
    document.getElementById('busServiceArrivalTable').innerHTML = resultsDisplay;
}

function refreshBusArrivalTiming(e) {
    getBusArrivalTiming($(e).data('busstopcode'), $(e).data('serviceno'), $(e).data('direction'),$(e).data('type'));
}

function getBusArrivalTiming(busStopCode, serviceNo, direction, type) {
    var xmlhttp = new XMLHttpRequest();
    if (type == "busservice") {
        $.ajax({
            url: '../controller/information_busservice_controller.php',
            data: {function:"retrieveBusArrivalTiming",
                    busstopcode: busStopCode,
                    serviceno: serviceNo,
                    direction: direction},
            contentType: "application/json; charset=utf-8",
            success: updateTimingModalBusservice
        });
    } else {
        $.ajax({
            url: '../controller/information_busstop_controller.php',
            data: {function:"retrieveBusArrivalTiming",
                    busstopcode: busStopCode,
                    serviceno: serviceNo,
                    direction: direction},
            contentType: "application/json; charset=utf-8",
            success: updateTimingModalBusstop
        });
    }
}

function updateTimingModalBusservice(results, status, xhr) {
    var results = JSON.parse(results);
    if (!results.hasOwnProperty("arrivals")) {
        document.getElementById(results.info.busStopCode).innerHTML = "Not in Operation";
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
    document.getElementById(results.info.busStopCode).innerHTML = finalString;
}

function updateTimingModalBusstop(results, status, xhr) {
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

// Results table section ======================================================
function retrieveBusstopList() {
    $.ajax({
        url: '../controller/information_busstop_controller.php',
        data: "function=retrieveAllInfo",
        contentType: "application/json; charset=utf-8",
        success: updateBusstopResultsTable
    });
}

function retrieveBusserviceList() {
    $.ajax({
        url: '../controller/information_busservice_controller.php',
        data: "function=retrieveAllInfo",
        contentType: "application/json; charset=utf-8",
        success: updateBusServiceResultsTable
    });
}

function filterBusstopTable(event) {
    var filter = event.target.value.toUpperCase();
    var rows = document.querySelector("#busstopTable tbody").rows;

    for (var i = 0; i < rows.length; i++) {
        var busstopCodeCol = rows[i].cells[0].textContent.toUpperCase();
        var roadnameCol = rows[i].cells[1].textContent.toUpperCase();
        var descriptionCol = rows[i].cells[2].textContent.toUpperCase();
        if (busstopCodeCol.indexOf(filter) > -1 || roadnameCol.indexOf(filter) > -1
            || descriptionCol.indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function filterBusserviceTable(event) {
    var filter = event.target.value.toUpperCase();
    var rows = document.querySelector("#busServiceTable tbody").rows;

    for (var i = 0; i < rows.length; i++) {
        var busServiceNoCol = rows[i].cells[0].textContent.toUpperCase();
        if (busServiceNoCol.indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function updateBusServiceResultsTable(results, status, xhr) {
    var resultsArray = JSON.parse(results);
    var tableString = `
    <div class=\"table-responsive\">
        <table class=\"table\" id=\"busServiceTable\">
            <thead>
                <tr>
                    <th scope=\"col\">Bus Service No.</th>
                    <th scope=\"col\">Origin</th>
                    <th scope=\"col\">Destination</th>
                </tr>
            </thead>
            <tbody>
    `;
    resultsArray.forEach(
        function (arrayItem) {
            tableString += `
                <tr data-toggle=\"modal\" data-target=\"#informationModal\"
                    data-type=\"busservice\" data-serviceno=\"${arrayItem.serviceNo}\"
                    data-direction=\"${arrayItem.direction}\">
                    <th scope=\"row\">${arrayItem.serviceNo}</th>
                    <td>${arrayItem.originName}</td>
                    <td>${arrayItem.destinationName}</td>
                </tr>
            `
    });

    tableString += `
            </tbody>
        </table>
    </div>
    `
    busServiceArray = resultsArray;
    document.getElementById("busservice").innerHTML = tableString;
}

function updateBusstopResultsTable(results, status, xhr) {
    var resultsArray = JSON.parse(results);
    var tableString = `
    <div class=\"table-responsive\">
        <table class=\"table\" id=\"busstopTable\">
            <thead>
                <tr>
                    <th scope=\"col\">Bus Stop Code</th>
                    <th scope=\"col\">Road name</th>
                    <th scope=\"col\">Description</th>
                </tr>
            </thead>
            <tbody>
    `;
    resultsArray.forEach(
        function (arrayItem) {
            tableString += `
                <tr data-toggle=\"modal\" data-target=\"#informationModal\"
                    data-type=\"busstop\" data-busstopcode=\"${arrayItem.busStopCode}\">
                    <th scope=\"row\">${arrayItem.busStopCode}</th>
                    <td>${arrayItem.roadName}</td>
                    <td>${arrayItem.description}</td>
                </tr>
            `
    });

    tableString += `
            </tbody>
        </table>
    </div>
    `

    busstopArray = resultsArray;
    document.getElementById("busstop").innerHTML = tableString;
}
