function initialiseTable() {
    var resultsArray = retrieveBusstopList();
    var resultsArray2 = retrieveBusserviceList();
    updateBusstopResultsTable(resultsArray);
    updateBusserviceResultsTable(resultsArray2);
    document.querySelector('#filterBox').addEventListener('keyup',
        filterBusstopTable, false);
    document.querySelector('#filterBox').addEventListener('keyup',
        filterBusserviceTable, false);
}

function retrieveBusstopList() {
    var results = [
        {
            busstopcode:"481",
            roadname:"Woodlands Rd",
            latitude:"1.37835675026421",
            longitude:"103.743085058036",
            description:"BT PANJANG TEMP BUS PK"
        },
        {
            busstopcode:"1012",
            roadname:"VictoriaSt",
            latitude:"1.29684825487647",
            longitude:"103.85253591654",
            description:"Hotel Grand Pacific"
        },
        {
            busstopcode:"1029",
            roadname:"Nth Bridge Rd",
            latitude:"1.297504044",
            longitude:"103.854414224643",
            description:"Cosmic Insurance Bldg"
        },
        {
            busstopcode:"1119",
            roadname:"Victoria St",
            latitude:"1.2996041093804",
            longitude:"103.855129340796",
            description:"Bugis Junction"
        },
        {
            busstopcode:"1121",
            roadname:"Victoria St",
            latitude:"1.30393809691048",
            longitude:"103.858679992432",
            description:"Stamford Pr Sch"
        },
        {
            busstopcode:"1119",
            roadname:"Victoria St",
            latitude:"1.2996041093804",
            longitude:"103.855129340796",
            description:"Bugis Junction"
        },
        {
            busstopcode:"1119",
            roadname:"Victoria St",
            latitude:"1.2996041093804",
            longitude:"103.855129340796",
            description:"Bugis Junction"
        },
        {
            busstopcode:"1119",
            roadname:"Victoria St",
            latitude:"1.2996041093804",
            longitude:"103.855129340796",
            description:"Bugis Junction"
        },
        {
            busstopcode:"1119",
            roadname:"Victoria St",
            latitude:"1.2996041093804",
            longitude:"103.855129340796",
            description:"Bugis Junction"
        },
        {
            busstopcode:"1119",
            roadname:"Victoria St",
            latitude:"1.2996041093804",
            longitude:"103.855129340796",
            description:"Bugis Junction"
        }
    ];
    return results;
}

function retrieveBusserviceList() {
    var results = [
        {
            serviceno:"118",
            direction:"1",
            origincode:"65009",
            destinationcode:"97009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"118",
            direction:"2",
            origincode:"97009",
            destinationcode:"65009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"119",
            direction:"1",
            origincode:"65009",
            destinationcode:"65009",
            loopdescription:"Hougang St 21",
            operator:"GAS"
        },
        {
            serviceno:"118",
            direction:"1",
            origincode:"65009",
            destinationcode:"97009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"118",
            direction:"2",
            origincode:"97009",
            destinationcode:"65009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"119",
            direction:"1",
            origincode:"65009",
            destinationcode:"65009",
            loopdescription:"Hougang St 21",
            operator:"GAS"
        },
        {
            serviceno:"118",
            direction:"1",
            origincode:"65009",
            destinationcode:"97009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"118",
            direction:"2",
            origincode:"97009",
            destinationcode:"65009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"119",
            direction:"1",
            origincode:"65009",
            destinationcode:"65009",
            loopdescription:"Hougang St 21",
            operator:"GAS"
        },
        {
            serviceno:"118",
            direction:"1",
            origincode:"65009",
            destinationcode:"97009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"118",
            direction:"2",
            origincode:"97009",
            destinationcode:"65009",
            loopdescription:"",
            operator:"GAS"
        },
        {
            serviceno:"119",
            direction:"1",
            origincode:"65009",
            destinationcode:"65009",
            loopdescription:"Hougang St 21",
            operator:"GAS"
        }

    ];
    return results;
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
        var destinationCol = rows[i].cells[3].textContent.toUpperCase();
        var loopCol = rows[i].cells[4].textContent.toUpperCase();
        if (busservicenoCol.indexOf(filter) > -1
            || originCol.indexOf(filter) > -1 || destinationCol.indexOf(filter) > -1
            || loopCol.indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function updateBusserviceResultsTable(resultsArray) {
    var tableString = `
    <div class=\"table-responsive\">
        <table class=\"table\" id=\"busserviceTable\">
            <thead>
                <tr>
                    <th scope=\"col\">Bus Service No.</th>
                    <th scope=\"col\">Origin</th>
                    <th scope=\"col\"></th>
                    <th scope=\"col\">Destination</th>
                    <th scope=\"col\">Loop at</th>
                </tr>
            </thead>
            <tbody>
    `;
    resultsArray.forEach(
        function (arrayItem) {
            tableString += `
                <tr>
                    <th scope=\"row\">${arrayItem.serviceno}</th>
                    <td>${arrayItem.origincode}</td>
                    <td>${arrayItem.direction}</td>
                    <td>${arrayItem.destinationcode}</td>
                    <td>${arrayItem.loopdescription}</td>
                </tr>
            `
    });

    tableString += `
            </tbody>
        </table>
    </div>
    `

    console.log(tableString);
    document.getElementById("busservice").innerHTML = tableString;
}


function updateBusstopResultsTable(resultsArray) {
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
                <tr>
                    <th scope=\"row\">${arrayItem.busstopcode}</th>
                    <td>${arrayItem.roadname}</td>
                    <td>${arrayItem.description}</td>
                </tr>
            `
    });

    tableString += `
            </tbody>
        </table>
    </div>
    `

    console.log(tableString);
    document.getElementById("busstop").innerHTML = tableString;
}
