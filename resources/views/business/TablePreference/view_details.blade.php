@extends('layouts.business')

@section('title')
    Manage Sections
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.preference') }}">Manage Sections</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Section Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.preference') }}" class="btn btn-primary" style="width: 100px">Back</a>
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
                                            <h3 class="text-uppercase">Section Details</h3>
                                        </div>
                                        <div class="row  align-items-center">
                                            <div class="col-xl-4 col-md-4 text-center">
                                                <div class="detail-personal">
                                                    <h3>
                                                        @if ($preference->image || $preference->image != 0)
                                                            <img src="{{ config('aws_url.url') . $preference->image }}"
                                                                alt="Cafe Image" height="100px" border="0"
                                                                width="100" height="100"
                                                                style="border-radius:50%;object-fit: cover;"
                                                                class="stylist-image" align="center">
                                                        @else
                                                            <img src="{{ asset('layout_style/img/preference.png') }}"
                                                                alt="Default Image" height="50px">
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-8 col-md-8">
                                                <div class="row mb-3">
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h2>Section</h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h3>{{ ucwords($preference->preference) }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h2>Location</h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h3>{{ ucwords($preference->location->location_name) }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h2>Status</h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h3>
                                                                @php
                                                                    $status =
                                                                        $preference->status == 1
                                                                            ? 'Active'
                                                                            : 'Inactive';
                                                                    $badgeClass =
                                                                        $preference->status == 1
                                                                            ? 'custom-badge status-green'
                                                                            : 'custom-badge status-red';
                                                                @endphp
                                                                <span class="{{ $badgeClass }}">{{ $status }}</span>
                                                            </h3>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h2>Default?</h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="detail-personal">
                                                            <h3>
                                                                @php
                                                                    $is_default = '';
                                                                    if ($preference->is_default == 0) {
                                                                        $is_default = 'No';
                                                                    }
                                                                    if ($preference->is_default == 1) {
                                                                        $is_default = 'Yes';
                                                                    }
                                                                @endphp
                                                                {{ $is_default }}
                                                            </h3>
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
        </div>
    </div>

@endsection
