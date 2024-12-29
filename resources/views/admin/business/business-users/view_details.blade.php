@extends('layouts.home')

@section('title')
Manage Business Users
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.business-users') }}">Manage Business Users</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Business User Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('admin.business-users') }}" class="btn btn-primary" style="width: 100px">Back</a>
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
                                            <h3 class="text-uppercase"> Business User Details</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>First Name</h2>
                                                    <h3>{{ ucwords($user->first_name) }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Last Name</h2>
                                                    <h3>{{ ucwords($user->last_name) }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Full Name</h2>
                                                    <h3>{{ ucwords($user->name) }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Email Address</h2>
                                                    <h3>{{ $user->email }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Contact</h2>
                                                    <h3>{{ $user->contact }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-12 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Client Status </h2>
                                                    <h3>
                                                        @if ($user->status == 1)
                                                            <span class="custom-badge status-green ">Active</span>
                                                        @else
                                                            <span class="custom-badge status-red ">Inactive</span>
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>

                                            @if (count($user->business_users))
                                                <div class="col-xl-12 col-md-12 mb-3">
                                                    <div class="detail-personal">
                                                        <h2>Business List</h2>
                                                        @foreach ($user->business_users as $item)
                                                            <span class="custom-badge status-blue mb-2">{{$item->business->name}}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

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
