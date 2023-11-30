<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'Laravel' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />

</head>

<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9Ntbzd-arHSe4s-H1Og78KwucQtppVu0&libraries=places">
    </script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            let csvData = []
            var allResults = [];


            // Function to export and download the table data as CSV
            function exportTableToCSV() {
                var data = [];

                // Loop through the table rows and extract data
                $('#storesTable tbody tr').each(function() {
                    var row = [];
                    $(this).find('td').each(function() {
                        row.push($(this).text().replace(/,/g, ''));
                    });
                    data.push(row);
                });

                // Convert data to CSV format using PapaParse with encoding options
                var csv = Papa.unparse({
                    fields: data,
                    encoding: 'UTF-8', // Set the encoding to UTF-8
                });

                // Create a blob object and create a download link
                var blob = new Blob([csv], {
                    type: 'text/csv;charset=utf-8;'
                });
                var url = window.URL.createObjectURL(blob);

                // Create a download link and trigger the click event
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'table_data.csv';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }

            $('#downloadButton').on('click', function() {
                exportTableToCSV();
            });

            var map;
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: -33.866,
                    lng: 151.196
                },
                zoom: 15
            });

            function performTextSearch(query, location, radius, pageToken) {
                var service = new google.maps.places.PlacesService(map);

                function handleSearchResults(results, status, pagination) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        for (let i = 0; i < results.length; i++) {
                            const placeId = results[i].place_id;

                            service.getDetails({
                                placeId: placeId,
                                fields: ["name", "formatted_address"]
                            }, function(place, status) {
                                if (status !== 'OK') return
                                if (status == google.maps.places.PlacesServiceStatus.OK) {

                                    allResults.push({
                                        ...results[i],
                                        ...place
                                    })
                                }
                            });
                        }
                        // if (pagination.hasNextPage) {
                        //     // Perform the next page request if available
                        //     pagination.nextPage();
                        // } else {
                        //     // When no more pages are available, display all results
                        //     // displayResults(allResults);
                        //     Swal.close();
                        // }
                    } else {
                        // console.error('Text Search request failed with status:', status);
                        alert("Stores not found")
                    }
                }

                service.nearbySearch({
                    type: query,
                    location: location,
                    radius: radius,
                }, handleSearchResults);
            }

            function displayResults(results) {
                $("#storesBody").empty();
                var countA = 1;

                csvData = []

                let removeDuplicate = {
                    lat: [],
                    lng: [],
                }

                const {
                    lat,
                    lng
                } = removeDuplicate



                results.forEach(function(place) {

                    var storeName = place.name.replace(/,/g, '');
                    var storeAddress = place.formatted_address.replace(/,/g, '');
                    var storeLocation = place.geometry.location;
                    var storeLat = storeLocation.lat();
                    var storeLng = storeLocation.lng();

                    if (!lat.includes(storeLat.toString()) && !lng.includes(storeLng.toString())) {

                        csvData.push({
                            storeName,
                            storeAddress,
                            storeLat,
                            storeLng,
                            storeCity: $("#CityId").val()
                        })

                        lat.push(storeLat.toString())

                        lng.push(storeLng.toString())

                        var div = `
                        <tr>
                            <td>${countA}</td>
                            <td>${storeName}</td>
                            <td>${storeAddress}</td>
                            <td>${storeLat}</td>
                            <td>${storeLng}</td>
                            <td>${ $("#CityId").val() }</td>
                        </tr>
                    `;
                        div = div.replace(/[;,،'"]/g, '');
                        $("#storesBody").append(div);

                        countA++;

                    }

                });
            }

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
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    address: name
                }, function(results, status) {
                    if (status == "OK") {
                        var lat = results[0].geometry.location.lat();
                        var lng = results[0].geometry.location.lng();

                        var cityLatLng = new google.maps.LatLng(lat, lng);

                        map = new google.maps.Map(document.getElementById('map'), {
                            center: {
                                lat: cityLatLng.lat(),
                                lng: cityLatLng.lng()
                            },
                            zoom: 10
                        });

                        google.maps.event.addListener(map, "click", function(event) {
                            var search = $("#SearchName").val();
                            var query = search
                            // + " in " + name;
                            var radius = $("#StoreRadius").val()
                            radius = Number(radius)

                            var center = new google.maps.LatLng(event.latLng.lat(), event
                                .latLng.lng());


                            var marker = new google.maps.Marker({
                                position: center,
                                map: map,
                            });

                            var circle = new google.maps.Circle({
                                map: map,
                                center: center,
                                radius: radius,
                                strokeColor: '#FF0000',
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: '#FF0000',
                                fillOpacity: 0.35,
                            });

                            // console.log({
                            //     lat: event.latLng.lat(),
                            //     lng: event.latLng.lng()
                            // });
                            // console.log(radius);
                            console.log(query);

                            performTextSearch(query, {
                                lat: event.latLng.lat(),
                                lng: event.latLng.lng()
                            }, radius, null);

                        });


                    } else {
                        console.log(
                            "Geocode was not successful for the following reason: " +
                            status);
                    }
                });
            })


            $("#showAllStores").click(() => {

                console.log(allResults);


                displayResults(allResults);
            })















            // Trigger the search when the city is selected
            // $("#SearchName").change(function() {
            //     $("#storesBody").empty();
            //     Swal.fire({
            //         title: 'Please Wait!',
            //         allowOutsideClick: false,
            //         onBeforeOpen: () => {
            //             Swal.showLoading();
            //         }
            //     });

            //     var name = $("#CityId").val();
            //     var search = $(this).val();

            //     if (search != "") {
            //         console.log(search);
            //         if (name != "") {
            //             var geocoder = new google.maps.Geocoder();
            //             geocoder.geocode({
            //                 address: name
            //             }, function(results, status) {
            //                 if (status == "OK") {
            //                     var lat = results[0].geometry.location.lat();
            //                     var lng = results[0].geometry.location.lng();

            //                     var cityLatLng = new google.maps.LatLng(lat, lng);
            //                     console.log(lat, lng);
            //                     map = new google.maps.Map(document.getElementById('map'), {
            //                         center: {
            //                             lat: cityLatLng.lat(),
            //                             lng: cityLatLng.lng()
            //                         },
            //                         zoom: 8
            //                     });


            //                     // Perform the initial text search with an empty page token
            //                     // performTextSearch('Wholesale Stores in Jeddah', cityLatLng, 100000, null);

            //                     var query = search + " in " + name;
            //                     performTextSearch(query, cityLatLng, 2000, null);

            //                 } else {
            //                     console.log(
            //                         "Geocode was not successful for the following reason: " +
            //                         status);
            //                 }
            //             });
            //         }

            //     } else {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Oops...',
            //             text: 'Please select type',

            //         })
            //     }
            // });


            $("#downloadCSV").click(function() {
                $.ajax({
                    url: '{{ route('downloadCSV') }}',
                    type: 'POST',
                    data: {
                        csvData
                    },
                    success: function(data) {
                        if (!data.status) {
                            alert("No Cities Found")
                            return
                        }


                        let apth = window.location.origin + "/files/stores.csv"
                        window.open(
                            apth,
                            '_blank'
                        );
                    }
                });
            })
        });
    </script>
</body>

</html>
