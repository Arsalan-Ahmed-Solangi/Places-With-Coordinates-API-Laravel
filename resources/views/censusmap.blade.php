@extends('layouts.app')
@section('content')
    <div class="container-fluid" style="width:100%;margin:0px;padding:0px">
        {{-- <div class="card shadow-lg bg-white" style="width:100%;margin:auto">
            <div class="card-header bg-dark text-light">POPULATION CENSUS MAP</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
                        <form action="" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                                    <div class="form-group  mt-2 mb-2">
                                        <label>Country</label>
                                        <select name="CountryId" id="CountryId" required class="form-select">
                                            <option value="">Select Country</option>
                                            @forelse ($countries ?? array() as $country => $value)
                                                <option value="{{ $value->CountryId }}">{{ $value->CountryName }}</option>

                                            @empty

                                                <option value="">--No Countires Found---</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                                    <div class="form-group  mt-2 mb-2">
                                        <label>City</label>
                                        <select name="CityId" id="CityId" required class="form-select">
                                            <option value="">Select City</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>






            </div>
        </div> --}}
        <div id="map" style="width:100%;height:1000px"></div>
        <div class="card p-5 zoomed-div shadow-sm"
            style="position:fixed;
        z-index:10;
        zoom: 80%;
        width:40%;
        overflow-y: auto;
        overflow-x:hidden;
        font-size:20px;
        left:20px;
        top:80px;
        padding-bottom:50px;">





            <div class="row">
                <div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">


                    <div class="form-group">
                        <label>Country</label>
                        <select name="CountryId" id="CountryId" required class="form-control form-control-sm">
                            <option value="">Select Country</option>
                            @forelse ($countries ?? array() as $country => $value)
                                <option value="{{ $value->CountryId }}">{{ $value->CountryName }}</option>

                            @empty

                                <option value="">--No Countires Found---</option>
                            @endforelse
                        </select>
                    </div>

                </div>
                <div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">

                    <div class="form-group">
                        <label>Select City</label>
                        <select id="CityId" name="CityId" class="form-control form-control-sm">
                            @foreach ($cities ?? [] as $key => $value)
                                <option value="{{ $value['CityId'] }}">{{ $value['CityName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>



            </div>



            <div class="card-body p-0 mt-4" id="polygonData" style="display:none;font-size:11px">

                <div class="row ">
                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">


                        <div class="row mb-2">
                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">

                                <table style="width: 100%" class="table table-bordered table-hover">
                                    <thead class="bg-danger text-light">

                                        <tr class="text-center">
                                            <th>Population</th>
                                            <th>Area</th>
                                            <th>Density</th>
                                            <th>Stores</th>
                                            <th>Total Sale</th>
                                            <th>Captia Sale</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td id="population"></td>
                                            <td id="area"></td>
                                            <td id="density"></td>
                                            <td id="storesCount"></td>
                                            <td id="salesCount"></td>
                                            <td id="capitaSale"></td>

                                        </tr>
                                    </tbody>
                                </table>

                            </div>


                            <div class="row mb-3">

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <table style="height:40%;width:100%" class="table table-bordered table-hover">
                                        <thead class="bg-danger text-light">
                                            <tr class="text-center">
                                                <th colspan="2">Gender</th>
                                            </tr>
                                        </thead>
                                        <tbody id="genderBody">
                                        </tbody>
                                    </table>

                                </div>

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <div id="gender-chart" style="height:110px"></div>

                                </div>

                            </div>

                            <div class="row mb-3">

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <table style="height:100%;width:100%" class="table table-bordered table-hover">
                                        <thead class="bg-danger text-light">
                                            <tr class="text-center">
                                                <th colspan="2">UrbanRural</th>
                                            </tr>
                                        </thead>
                                        <tbody id="UrbanRural"></tbody>
                                    </table>

                                </div>

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <div id="urban-chart" style="height:110px"></div>

                                </div>

                            </div>

                            <div class="row mb-3">

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <table style="height:100%;width:100%" class="table table-bordered table-hover">
                                        <thead class="bg-danger text-light">
                                            <tr class="text-center">
                                                <th colspan="2">Literacy</th>
                                            </tr>
                                        </thead>
                                        <tbody id="Literacy"></tbody>
                                    </table>

                                </div>

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <div id="literacy-chart" style="height:110px"></div>

                                </div>
                            </div>


                            <div class="row mb-2">
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <table style="height:100%;width:100%" class="table table-bordered table-hover">
                                        <thead class="bg-danger text-light">
                                            <tr class="text-center">
                                                <th colspan="2">Age Distribution</th>
                                            </tr>
                                        </thead>
                                        <tbody id="AgeDistribution"></tbody>
                                    </table>

                                </div>

                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">

                                    <div id="age-chart" style="height:200px"></div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                var aArrayMarker = [];
                var hexagons = [];

                let objDrawingManager;
                let selectedShape;
                let MarkerManager;

                var aSeletedArrayMarker = [];
                let ordersListMap = [];
                let globalPolygonObject = "";
                let aArrayDistributor = [];
                let iStoreCount = 0;
                let ipolygon = 0;
                let sDMOptions = "";
                let aOrderList = [];
                let drawings = [];
                let sHTMLLoadFormRestriction = "";
                let aArrayLatLng = [];
                let iUserType = 0;
                let aStoreDetails = [];
                let aSelectedStores = [];
                let ShowStoresOnMapFor = "";
                let aStores = [];
                let aTempMarker = [];
                let objTempPolygon = 0;
                let sGeoFance = [];
                let sAction = "";
                let aArrayPolygon = [];
                let aArrayInfoWindow = [];
                let aArrayAssignment = [];
                let currentLat = "";
                let currentLng = "";
                let UserId = 0;
                let aGeoFance = 0;
                let openInfoWindows = [];
                let selectedGeoFence = [];
                let mergingStoresList = [];


                var map;
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: -33.866,
                        lng: 151.196
                    },
                    zoom: 15
                });
                $("#CountryId").change(function() {
                    var Id = $(this).val();
                    if (Id != "") {
                        $.ajax({
                            url: '{{ route('getCities') }}',
                            type: 'POST',
                            data: {
                                id: Id
                            },
                            success: function(data) {
                                if (data.status == 200) {
                                    $("#CityId").html("<option value=''>--Select City--</option>");
                                    $.each(data.cities, function(i, value) {
                                        $("#CityId").append("<option value=" + value
                                            .CityName + ">" + value.CityName +
                                            "</option>");
                                    })

                                } else {
                                    alert("No Cities Found");
                                }
                            }
                        });
                    }
                });




                $("#CityId").change(function() {
                    var name = $("#CityId").val();

                    var cityLatLng = new google.maps.LatLng(21.4925, 39.17757);

                    map = new google.maps.Map(document.getElementById('map'), {
                        center: {
                            lat: 21.4925,
                            lng: 39.17757
                        },
                        zoom: 10
                    });

                    var bounds = new google.maps.LatLngBounds(
                        new google.maps.LatLng(21.4925, 39.17757),
                        new google.maps.LatLng(21.4925,
                            39.17757)
                    );
                    console.log("City bounds:", bounds);





                    $.ajax({
                        url: "{{ route('getCityStores') }}", // Specify your API endpoint to fetch lat/lng
                        type: "POST",
                        data: {
                            city: name
                        },
                        success: function(response) {
                            if (response.success == true) {
                                aArrayMarker = [];
                                var aResponce = JSON.parse(response.data);


                                $.each(aResponce, function(iKey, aArrayValue) {



                                    var marker = new google.maps.Marker({
                                        position: {
                                            lat: Number(aArrayValue[
                                                2]),
                                            lng: Number(aArrayValue[
                                                3])
                                        },
                                        map: map,
                                        icon: {
                                            url: 'shop.png',
                                            scaledSize: new google
                                                .maps.Size(16,
                                                    16
                                                ),
                                        },
                                        title: aArrayValue[1]
                                    });
                                    aArrayMarker.push(marker)

                                })



                                //*****AddShapes********//
                                addHexagons(cityLatLng);


                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'No Stores Found!',

                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX request failed: " + error);
                        }
                    });



                    // var geocoder = new google.maps.Geocoder();
                    // geocoder.geocode({
                    //     address: name
                    // }, function(results, status) {
                    //     if (status == "OK") {
                    //         var lat = results[0].geometry.location.lat();
                    //         var lng = results[0].geometry.location.lng();




                    //     } else {
                    //         console.log(
                    //             "Geocode was not successful for the following reason: " +
                    //             status);
                    //     }
                    // });
                })

                function extractLatLng(objPolygon, typeExtract = 0) {
                    aArrayLatLng = [];
                    if (typeExtract == 1) {
                        for (let i = 0; i < objPolygon.getPath().getLength(); i++)
                            aArrayLatLng.push([objPolygon.getPath().getAt(i).lat(), objPolygon.getPath().getAt(i)
                                .lng()
                            ]);
                    } else {
                        for (let i = 0; i < objPolygon.getPath().getLength(); i++)
                            aArrayLatLng.push(objPolygon.getPath().getAt(i).lat(), objPolygon.getPath().getAt(i).lng());
                    }

                    return (aArrayLatLng);
                }

                function checkLatLongInPolygon(aArrayPoint, aArrayPolygon) {
                    let x = aArrayPoint[0],
                        y = aArrayPoint[1];

                    let inside = false;
                    for (let i = 0, j = aArrayPolygon.length - 1; i < aArrayPolygon.length; j = i++) {
                        let xi = aArrayPolygon[i][0],
                            yi = aArrayPolygon[i][1];
                        let xj = aArrayPolygon[j][0],
                            yj = aArrayPolygon[j][1];
                        let intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                        if (intersect) inside = !inside;
                    }
                    return inside;
                }



                function addHexagons(cityLatLng) {
                    let currentLat = cityLatLng.lat();
                    let currentLng = cityLatLng.lng();

                    let h3Index = h3.geoToH3(currentLat, currentLng, 8);
                    let hexBoundary = h3.h3ToGeoBoundary(h3Index);
                    let h3Neighbour = h3.kRing(h3Index, 40);
                    let sGeoFance;
                    if (sGeoFance)
                        aGeoFance = JSON.parse([]);

                    for (let j = 0; j < h3Neighbour.length; j++) {
                        let h3IndexJ = h3Neighbour[j];

                        let hexNeighbourBoundary = h3.h3ToGeoBoundary(h3IndexJ);
                        let polygonCoords = changeCoordsFormat(hexNeighbourBoundary);

                        if (checkSelected(h3IndexJ)) {
                            addPolygon(polygonCoords, h3IndexJ, h3IndexJ, "#EC7063", true);

                        } else
                            addPolygon(polygonCoords, h3IndexJ, h3IndexJ, "#EC7063", false);
                    }

                }

                function changeCoordsFormat(hexBoundary) {
                    let aArrayCoords = [];
                    for (let i = 0; i < hexBoundary.length; i++) {
                        aArrayCoords.push({
                            lat: hexBoundary[i][0],
                            lng: hexBoundary[i][1]
                        });
                    }


                    return (aArrayCoords);
                }

                function addPolygon(polygonCoords = [], id = 0, sTitle = "", sCOLOR = "#EC7063", sSelected = false,
                    addEvent = 1) {
                    let objPolygon = new google.maps.Polygon({
                        paths: polygonCoords,
                        strokeColor: sCOLOR,
                        strokeOpacity: 0.3,
                        strokeWeight: 0.5,
                        fillColor: sCOLOR,
                        fillOpacity: 0.1,
                        id: id,
                        title: sTitle,
                        selected: sSelected
                    });

                    objPolygon.setMap(map);

                    if (addEvent === 1)
                        google.maps.event.addListener(objPolygon, "click", function() {
                            console.log("addListener", id);
                            clickPolygon(objPolygon, "#F4D03F");
                            loadData();
                        });
                    else {

                        //google.maps.event.addListener(objPolygon, "click",function(){console.log("addListener",id);});
                        var contentString = "&nbsp;&nbsp;<strong>H3 Assigned to : </strong> " + sTitle + "&nbsp;&nbsp;";
                        objInfoWindow = new google.maps.InfoWindow();

                        google.maps.event.addListener(objPolygon, "mouseover", function(event) {
                            objInfoWindow.setContent(contentString);
                            objInfoWindow.setPosition(event.latLng);
                            objInfoWindow.open(objMap);
                        });

                        aArrayInfoWindow.push(objInfoWindow);
                    }

                    aArrayPolygon.push(objPolygon);
                }

                function getRandomNumber(min, max) {
                    const randomNumber = Math.random() * (max - min) + min;
                    return parseFloat(randomNumber.toFixed(2));
                }

                function getRandomInt(min, max) {
                    min = Math.ceil(min);
                    max = Math.floor(max);
                    return Math.floor(Math.random() * (max - min + 1)) + min;
                }


                function loadData() {

                    var name = $("#CityId").val();
                    $.ajax({
                        url: " {{ route('getCityDetails') }}",
                        type: "POST",
                        data: {

                            City: name
                        },
                        success: function(response) {
                            if (response.success === true) {

                                var genderBody = '';
                                var UrbanRural = '';
                                var Literacy = '';
                                var AgeDistribution = '';
                                var saleCount = getRandomNumber(10000, 500000);
                                var storesCount = getRandomInt(5, 200);
                                var captiaSale = saleCount / response.population;

                                $("#area").html(response.area !== null ? response.area
                                    .toLocaleString() +
                                    ' km²' : '0 km²');
                                $("#totalStores").html(0);

                                $("#storesCount").html(storesCount);
                                $("#salesCount").html(getRandomNumber(10000, 5000000));
                                $("#capitaSale").html(captiaSale.toFixed(2));
                                $("#StoresLiptonCount").html(0);
                                $("#StoresMergingCount").html(0);

                                $("#MilkSales").html('0');
                                $("#MilkSalesTotal").html('0');

                                $("#salesfloTotalSale").html('0');
                                $("#liptonSales").html('0');
                                $("#liptonSalesTotal").html('0');

                                $("#mergingSales").html('0');
                                $("#TotalSales").html('0');
                                $("#population").html(response.population !== null ? response.population
                                    .toLocaleString() + '/km²' : '0/km²');
                                $("#density").html(response.density !== null ? response.density
                                    .toLocaleString() + '/km²' : '0/km²');
                                $.each(response.gender, function(iKey, value) {
                                    genderBody += '<tr><td>' + iKey + ' </td><td> ' + value
                                        .toLocaleString() + ' </td></tr>'

                                })

                                $("#genderBody").html(genderBody)


                                $.each(response.UrbanRural, function(iKey, value) {

                                    UrbanRural += '<tr><td>' + iKey + ' </td><td> ' + value
                                        .toLocaleString() + ' </td></tr>'

                                })


                                $("#UrbanRural").html(UrbanRural)


                                $.each(response.Literacy, function(iKey, value) {

                                    Literacy += '<tr><td>' + iKey + ' </td><td> ' + value
                                        .toLocaleString() + ' </td></tr>'

                                })


                                $("#Literacy").html(Literacy)



                                $.each(response.AgeDistribution, function(iKey, value) {

                                    AgeDistribution += '<tr><td>' + iKey + ' </td><td> ' + value
                                        .toLocaleString() + ' </td></tr>'

                                })

                                chartFunction(response.genderChart, response.urbanChart, response
                                    .literacyChart, response.ageChart);


                                $("#AgeDistribution").html(AgeDistribution)
                                $('#polygonData').css("height", "400px");
                                $("#polygonData").show()
                                Swal.close();
                            } else {
                                $("#polygonData").hide()
                                Swal.close();
                                Swal.fire(
                                    "Opps!",
                                    response.message ?? "Something went wrong!",
                                    "error"
                                );

                            }
                        },
                        error: function(error) {
                            $("#polygonData").hide()
                            Swal.close();
                            Swal.fire("Opps!", "Something Went Wrong!" + error, "error");
                        },
                    });

                }


                function chartFunction(gender, urban, literacy, age) {

                    let urbanSource, genderDataSource, literacySource, ageSource;
                    genderDataSource = {
                        chart: {

                            showpercentvalues: "1",
                            bgAlpha: "4",
                            theme: "candy"
                        },
                        data: gender
                    };

                    FusionCharts.ready(function() {
                        var myChart = new FusionCharts({
                            type: "pie2d",
                            renderAt: "gender-chart",
                            width: "110%",
                            height: "150%",
                            dataFormat: "json",
                            dataSource: genderDataSource
                        }).render();


                    });


                    urbanSource = {
                        chart: {

                            showpercentvalues: "1",
                            bgAlpha: "4",
                            theme: "candy"
                        },
                        data: urban
                    };


                    FusionCharts.ready(function() {
                        var myChart = new FusionCharts({
                            type: "pie2d",
                            renderAt: "urban-chart",
                            width: "110%",
                            height: "150%",
                            dataFormat: "json",
                            dataSource: urbanSource
                        }).render();
                    });


                    literacySource = {
                        chart: {

                            showpercentvalues: "1",
                            bgAlpha: "4",
                            theme: "candy"
                        },
                        data: literacy
                    };


                    FusionCharts.ready(function() {
                        var myChart = new FusionCharts({
                            type: "pie2d",
                            renderAt: "literacy-chart",
                            width: "110%",
                            height: "150%",
                            dataFormat: "json",
                            dataSource: literacySource
                        }).render();
                    });


                    ageSource = {
                        chart: {

                            showpercentvalues: "1",
                            bgAlpha: "4",
                            theme: "candy"
                        },
                        data: age
                    };


                    FusionCharts.ready(function() {
                        var myChart = new FusionCharts({
                            type: "pie2d",
                            renderAt: "age-chart",
                            width: "110%",
                            height: "150%",
                            dataFormat: "json",
                            dataSource: ageSource
                        }).render();
                    });





                }

                function clickPolygon(objPolygon, sCOLOR = "#78281F", setStore = true) {
                    let h3Index = objPolygon.id;
                    let selected = objPolygon.selected;
                    aArrayLatLng = extractLatLng(objPolygon, 1);


                    selected = !selected;
                    if (selected)
                        sCOLOR = "#78281F";
                    else
                        sCOLOR = "#78281F";

                    objPolygon.setOptions({
                        strokeColor: sCOLOR,
                        fillColor: sCOLOR,
                        selected: selected
                    });

                    if (selected)
                        aArrayAssignment.push(h3Index);
                    else
                        removeAssignment(h3Index);

                    markerLat2 = 0;
                    markerLng2 = 0;
                    ordersListMap = [];
                    aOrderListTemp = [];

                    $.each(aArrayMarker, function(keyValue, objMarkerValue) {

                        let markerLat = objMarkerValue.getPosition().lat();
                        let markerLng = objMarkerValue.getPosition().lng();
                        let markerId = objMarkerValue.markerId;

                        if (checkLatLongInPolygon([markerLat, markerLng], aArrayLatLng)) {

                            if (markerLat != markerLat2 && markerLng != markerLng2) {
                                markerLat2 = markerLat;
                                markerLng2 = markerLng;
                                iStoreCount++;
                            }

                            if (selected) {
                                aTempMarker.push(objMarkerValue);
                                aOrderList.push(markerId);
                            } else {
                                let index = aOrderList.indexOf(markerId);
                                if (index > -1)
                                    aOrderList.splice(index, 1);
                            }

                        }

                    });

                }

                function checkSelected(h3IndexJ) {

                    // for (let i = 0; i < aGeoFance.length; i++) {
                    //     if (aGeoFance[i] == h3IndexJ)
                    //         return (true);
                    // }

                    // return (false);
                }

                function removeAssignment(h3Index) {
                    let index = aArrayAssignment.indexOf(h3Index);
                    if (index > -1)
                        aArrayAssignment.splice(index, 1);
                }

            })
        </script>
    @endpush
@endsection
