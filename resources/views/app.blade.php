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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function() {
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
                        console.log(data);
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
                console.log($("#CityId").val())
                console.log(name);
                var name = $(this).val();
                if (name != "") {
                    var map;

                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        address: name
                    }, function(results, status) {
                        if (status == "OK") {
                            // Get the latitude and longitude of the city
                            var lat = results[0].geometry.location.lat();
                            var lng = results[0].geometry.location.lng();
                            console.log(lat,lng);
                            // Create a new LatLng object
                            var cityLatLng = new google.maps.LatLng(lat, lng);
                            // console.log(cityLatLng.lat());
                            map = new google.maps.Map(document.getElementById('map'), {
                                center: {
                                    lat: cityLatLng.lat(),
                                    lng: cityLatLng.lng()
                                },
                                zoom: 8
                            });

                            // Create a new PlacesService object
                            var service = new google.maps.places.PlacesService(map);
                            console.log(service);
                            // Use the textSearch method to search for nearby gyms
                            service.textSearch({
                                query: "stores in Jeddah",
                                location: cityLatLng,
                                radius: 100000 // Search within 5km
                            }, function(results, status) {
                                console.log(results);
                                var nextPageToken = null;
                                if (status === google.maps.places.PlacesServiceStatus.OK) {
                                    // Display the results
                                     $("#storesBody").empty();
                                    var countA = 1;
                                    for (var i = 0; i < results.length; i++) {
                                        console.log(results[i]);
                                        var place = results[i];
                                        var marker = new google.maps.Marker({
                                            position: place.geometry.location,
                                            map: map,
                                            title: place.name
                                        });
                                        var storeName = place.name;
            var storeAddress = place.formatted_address;
            var storeLocation = place.geometry.location.toString();
            var storeLocation = place.geometry.location;
var storeLat = storeLocation.lat();
var storeLng = storeLocation.lng();
                                        var div = `
                <tr>
                    <td>${countA}</td>
                    <td>${storeName}</td>
                    <td>${storeAddress}</td>
                    <td>${storeLat}</td>
        <td> ${storeLng}</td>
        <td>${name}</td>
                </tr>
            `;
                                        $("#storesBody").append(div);

                                        countA++;
                                    }

                                }
                            });
                        } else {
                            console.log("Geocode was not successful for the following reason: " +
                                status);
                        }
                    });
                }
            });
        })
    </script>
</body>

</html>
