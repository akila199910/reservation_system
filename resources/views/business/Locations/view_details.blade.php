@extends('layouts.business')

@section('title')
Manage Locations
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.Locations') }}">Manage Locations</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active"> Location Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.Locations') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="doctor-table-blk mb-4 pt-2">
                                            <h3 class="text-uppercase">Location Details</h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Location Name</h2>
                                                    <h3>{{ ucwords($business_locations->location_name) }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Contact No</h2>
                                                    <h3>{{ $business_locations->contact_no }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Email</h2>
                                                    <h3>{{ $business_locations->email }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Address</h2>
                                                    <h3>{{ ucwords($business_locations->address) }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Status</h2>
                                                    <h3>
                                                        @php
                                                            $status =
                                                                $business_locations->status == 1
                                                                    ? 'Active'
                                                                    : 'Inactive';
                                                            $badgeClass =
                                                                $business_locations->status == 1
                                                                    ? 'custom-badge status-green'
                                                                    : 'custom-badge status-red';
                                                        @endphp
                                                        <span class="{{ $badgeClass }}">{{ $status }}</span>
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Is_default</h2>
                                                    <h3>
                                                        @php
                                                            $is_default = '';

                                                            if ($business_locations->is_default == 0) {
                                                                $is_default = 'No';
                                                            }
                                                            if ($business_locations->is_default == 1) {
                                                                $is_default = 'Yes';
                                                            }

                                                        @endphp
                                                        {{ $is_default }}

                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Google Location</h2>
                                                    <h3>{{ ucwords($business_locations->google_location) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
