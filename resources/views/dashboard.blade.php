@extends('text')
@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg bg-white p-2" style="width:100%;margin:auto">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                        <div id="map" style="width:100%;height:400px"></div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                        <form action="" method="POST">
                            @csrf


                            <div class="form-group  mt-2 mb-2">
                                <label>Country</label>
                                <select name="CountryId" id="CountryId" required class="form-select">
                                    <option value="">Select Country</option>
                                    @forelse ($countires ?? array() as $country => $value)
                                        <option value="{{ $value->CountryId }}">{{ $value->CountryName }}</option>

                                    @empty



                                        <option value="">--No Countires Found---</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="form-group  mt-2 mb-2">
                                <label>City</label>
                                <select name="CityId" id="CityId" required class="form-select">
                                    <option value="">Select City</option>

                                </select>
                            </div>
                            <div class="form-group  mt-2 mb-2">
                                <label>Type</label>
                                <select name="SearchName" id="SearchName" required class="form-select">
                                    <option value="">Select Type</option>
                                    <option value="Tabacoo , Hookah, Shisha and Vape stores">Tabacco Store</option>
                                    <option value="Shisha Store">Shisha Store</option>
                                    <option value="department_store">department_store</option>
                                    <option value="home_goods_store">home_goods_store</option>
                                    <option value="supermarket">supermarket</option>
                                    <option value="store">store</option>
                                    <option value="Restaurants">Restaurants</option>
                                    <option value="Grocery Stores">Grocery Stores</option>
                                    <option value="shoe_store">shoe_store</option>
                                    <option value="hardware_store">hardware_store</option>
                                    <option value="electronics_store">electronics_store</option>
                                    <option value="furniture_store">furniture_store</option>
                                    <option value="atm">Meezan Bank</option>
                                </select>
                            </div>
                            <div class="form-group mt-2 mb-2">
                                <label>Radius</label>
                                <select name="StoreRadius" id="StoreRadius" required class="form-select">
                                    @for ($i = 500; $i <= 10000; $i += 500)
                                        <option value={{ $i }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                        </form>

                        <button id="showAllStores" class="btn btn-primary">
                            Show All
                        </button>

                    </div>
                </div>
                <hr />
                <button id="downloadCSV" class="btn btn-warning">
                    Download CSV
                </button>
                <br />
                <br />
                <table class="table table-responsive table-bordered" id="storesTable">
                    <thead>
                        <tr>
                            <th>SR#</th>
                            <th>Store Name</th>
                            <th>Address</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>City</th>
                        </tr>
                    </thead>
                    <tbody id="storesBody">

                    </tbody>
                </table>


            </div>
        </div>
    </div>
@endsection
