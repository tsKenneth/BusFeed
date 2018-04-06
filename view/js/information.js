var busstopArray;
var busserviceArray;

function initialiseTable() {
    retrieveBusstopList();
    retrieveBusserviceList()
    document.querySelector('#filterBox').addEventListener('keyup',
        filterBusstopTable, false);
    document.querySelector('#filterBox').addEventListener('keyup',
        filterBusserviceTable, false);
}

$('#informationModal').on('show.bs.modal', function(modal) {
    var modalType = modal.relatedTarget.dataset.type;

    if (modalType == "busstop") {
        var busstopCode = modal.relatedTarget.dataset.busstopcode;
        for (var i=0; i < busstopArray.length; i++) {
            if (busstopArray[i].busStopCode == busstopCode) {
                document.getElementById("modalText").innerHTML = `
                <p>
                    <b>Busstop Code:</b> ${busstopArray[i].busStopCode} <br />
                    <b>Road Name:</b> ${busstopArray[i].roadName} <br />
                    <b>Description:</b> ${busstopArray[i].description} <br />
                    <b>Latitude:</b> ${busstopArray[i].latitude} <br />
                    <b>Longitude:</b> ${busstopArray[i].longitude} <br />
                </p>
                <hr>
                <b>Busses</b>
                <button type="button" class="btn btn-success">Add to bookmarks</button>
                `;
            }
        }
    } else if(modalType == "busservice") {
        var serviceno = modal.relatedTarget.dataset.serviceno;
        var direction = modal.relatedTarget.dataset.direction;
        for (var i=0; i < busserviceArray.length; i++) {
            if (busserviceArray[i].serviceNo == serviceno) {
                document.getElementById("modalText").innerHTML = `
                <p>
                    <b>Service Number:</b> ${busserviceArray[i].serviceNo} <br />
                    <b>Operator:</b> ${busserviceArray[i].operator} <br />
                    <b>Direction:</b> ${busserviceArray[i].direction} <br />
                    <b>Category:</b> ${busserviceArray[i].category} <br />
                    <b>Origin Code:</b> ${busserviceArray[i].originCode} <br />
                    <b>Destination Code:</b> ${busserviceArray[i].destinationCode} <br />
                    <b>AM Peak Frequency:</b> ${busserviceArray[i].AM_Peak_Freq} <br />
                    <b>AM Offpeak Frequency:</b> ${busserviceArray[i].AM_Offpeak_Freq} <br />
                    <b>PM Peak Frequency:</b> ${busserviceArray[i].PM_Peak_Freq} <br />
                    <b>PM Offpeak Frequency:</b> ${busserviceArray[i].PM_Offpeak_Freq} <br />
                    <b>Loop at:</b> ${busserviceArray[i].loopDesc} <br />
                </p>
                <button type="button" class="btn btn-success">Add to bookmarks</button>

                `;
            }
        }
    }
});

function updateBusstopBusArrivalTiming() {
    $.ajax({
        url: '../model/requestHandler.php',
        data: "function=getAllBusstopJSON",
        contentType: "application/json; charset=utf-8",
        success: updateBusstopResultsTable
    });
}

function retrieveBusstopList() {
    $.ajax({
        url: '../model/requestHandler.php',
        data: "function=getAllBusstopJSON",
        contentType: "application/json; charset=utf-8",
        success: updateBusstopResultsTable
    });
}

function retrieveBusserviceList() {
    $.ajax({
        url: '../model/requestHandler.php',
        data: "function=getAllBusserviceJSON",
        contentType: "application/json; charset=utf-8",
        success: updateBusserviceResultsTable
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
    var rows = document.querySelector("#busserviceTable tbody").rows;

    for (var i = 0; i < rows.length; i++) {
        var busservicenoCol = rows[i].cells[0].textContent.toUpperCase();
        var originCol = rows[i].cells[1].textContent.toUpperCase();
        var destinationCol = rows[i].cells[2].textContent.toUpperCase();
        var loopCol = rows[i].cells[3].textContent.toUpperCase();
        if (busservicenoCol.indexOf(filter) > -1
            || originCol.indexOf(filter) > -1 || destinationCol.indexOf(filter) > -1
            || loopCol.indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function updateBusserviceResultsTable(results, status, xhr) {
    var resultsArray = JSON.parse(results);
    var tableString = `
    <div class=\"table-responsive\">
        <table class=\"table\" id=\"busserviceTable\">
            <thead>
                <tr>
                    <th scope=\"col\">Bus Service No.</th>
                    <th scope=\"col\">Origin</th>
                    <th scope=\"col\">Destination</th>
                    <th scope=\"col\">Loop at</th>
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
                    <td>${arrayItem.originCode}</td>
                    <td>${arrayItem.destinationCode}</td>
                    <td>${arrayItem.loopDesc}</td>
                </tr>
            `
    });

    tableString += `
            </tbody>
        </table>
    </div>
    `
    busserviceArray = resultsArray;
    document.getElementById("busservice").innerHTML = tableString;
}


function updateBusstopResultsTable(results, status, xhr) {
    var resultsArray = JSON.parse(results);
    var tableString = `
    <div class=\"table-responsive\">
        <table class=\"table\" id=\"busstopTable\">
            <thead>
                <tr>
                    <th scope=\"col\">Busstop Code</th>
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
