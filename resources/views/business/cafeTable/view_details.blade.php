@extends('layouts.business')

@section('title')
    Manage Tables
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.cafe') }}">Manage Tables</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Table Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.cafe') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="doctor-table-blk mb-4 pt-2">
                        <h3 class="text-uppercase">Table Details</h3>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div class="detail-personal">
                                <h3>
                                    @if ($cafes->image != '' || $cafes->image != 0)
                                        <img src="{{ config('aws_url.url') . $cafes->image }}" alt="Cafe Image"
                                            height="100px" border="0" width="100" height="100"
                                            style="border-radius:50%;object-fit: cover;" class="stylist-image"
                                            align="center">
                                    @else
                                        <img src="{{ asset('layout_style/img/cafe_table.png') }}" alt="Default Image"
                                            height="50px">
                                    @endif
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-8 col-xl-8 ">
                            <div class="row mb-3">
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h2>Table Name</h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h3>{{ ucwords($cafes->name) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h2>Section</h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h3>{{ ucwords($cafes->preference->preference) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h2>Amount</h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h3>{{ $cafes->amount }}</h3>
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
                                        @php
                                            $status = $cafes->status == 1 ? 'Active' : 'Inactive';
                                            $badgeClass =
                                                $cafes->status == 1
                                                    ? 'custom-badge status-green'
                                                    : 'custom-badge status-red';
                                        @endphp
                                        <h3><span class="{{ $badgeClass }}">{{ $status }}</span></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h2>Reservation Status</h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h3>
                                            @php
                                                $reservation_status = '';
                                                if ($cafes->reservation_status == 0) {
                                                    $reservation_status = 'Not Reserved';
                                                }
                                                if ($cafes->reservation_status == 1) {
                                                    $reservation_status = 'Reserved';
                                                }
                                            @endphp
                                            {{ $reservation_status }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h2>Location Name</h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="detail-personal">
                                        <h3>{{ $cafes->location->location_name }}</h3>
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
