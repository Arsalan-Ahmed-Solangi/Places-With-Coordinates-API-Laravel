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
                }); // Set the charset to UTF-8
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


            // Attach the export function to the downloadButton click event
            $('#downloadButton').on('click', function() {
                exportTableToCSV();
            });


            // Trigger the download button click to export the table data






            var map;
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: -33.866,
                    lng: 151.196
                },
                zoom: 15
            });

            // Function to perform a text search and retrieve all results
            function performTextSearch(query, location, radius, pageToken) {
                var service = new google.maps.places.PlacesService(map);
                var allResults = [];

                function handleSearchResults(results, status, pagination) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        allResults = allResults.concat(results);

                        if (pagination.hasNextPage) {
                            // Perform the next page request if available
                            pagination.nextPage();
                        } else {
                            // When no more pages are available, display all results
                            displayResults(allResults);
                            Swal.close();
                        }
                    } else {
                        console.error('Text Search request failed with status:', status);
                    }
                }

                // Initial text search request
                service.textSearch({
                    query: query,
                    location: location,
                    radius: radius,
                    pageToken: pageToken
                }, handleSearchResults);
            }

            // Function to display results in a table
            function displayResults(results) {
                $("#storesBody").empty();
                var countA = 1;

                results.forEach(function(place) {

                    var marker = new google.maps.Marker({
                        position: place.geometry.location,
                        map: map,
                        title: place.name
                    });

                    var storeName = place.name.replace(/,/g, '');
                    var storeAddress = place.formatted_address.replace(/,/g, '');
                    var storeLocation = place.geometry.location;
                    var storeLat = storeLocation.lat();
                    var storeLng = storeLocation.lng();

                    var div = `
                        <tr>

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


            // Trigger the search when the city is selected
            $("#SearchName").change(function() {


                Swal.fire({
                    title: 'Please Wait!',
                    allowOutsideClick: false, // Prevent users from clicking outside the modal
                    onBeforeOpen: () => {
                        Swal.showLoading(); // Show the loading spinner
                    }
                });

                var name = $("#CityId").val();
                var search = $(this).val();

                if (search != "") {
                    console.log(search);
                    if (name != "") {
                        var geocoder = new google.maps.Geocoder();
                        geocoder.geocode({
                            address: name
                        }, function(results, status) {
                            if (status == "OK") {
                                var lat = results[0].geometry.location.lat();
                                var lng = results[0].geometry.location.lng();

                                var cityLatLng = new google.maps.LatLng(lat, lng);
                                console.log(lat, lng);
                                map = new google.maps.Map(document.getElementById('map'), {
                                    center: {
                                        lat: cityLatLng.lat(),
                                        lng: cityLatLng.lng()
                                    },
                                    zoom: 8
                                });


                                // Perform the initial text search with an empty page token
                                // performTextSearch('Wholesale Stores in Jeddah', cityLatLng, 100000, null);

                                console.log(search);
                                var query = search + " in " + name;
                                console.log(search);
                                performTextSearch(query, cityLatLng, 2000, null);

                            } else {
                                console.log(
                                    "Geocode was not successful for the following reason: " +
                                    status);
                            }
                        });
                    }

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select type',

                    })
                }
            });
        });
    </script>
</body>

</html>
