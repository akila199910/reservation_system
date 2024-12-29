@extends('layouts.business')

@section('title')
Manage  Intake Form
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.IntakeForm.index') }}">Manage Intake Form</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Intake Form Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.IntakeForm.index') }}" class="btn btn-primary" style="width: 100px">Back</a>
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
                                            <h3 class="text-uppercase">Intake Form Details</h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>First Name</h2>
                                                    <h3>{{ ucwords($intakes->f_name) }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Last Name</h2>
                                                    <h3>{{ ucwords($intakes->l_name) }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Email Address</h2>
                                                    <h3>{{ $intakes->email }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Address</h2>
                                                    <h3>{{ $intakes->address }}</h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Contact</h2>
                                                    <h3>{{ $intakes->contact }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Date of Birth</h2>
                                                    <h3>{{ $intakes->dob }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Gender</h2>
                                                    <h3>
                                                        @if($intakes->gender == 'M')
                                                            Male
                                                        @elseif($intakes->gender == 'F')
                                                            Female
                                                        @elseif($intakes->gender == 'O')
                                                            Other
                                                        @else
                                                            Not Provided
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Appointment date</h2>
                                                    <h3>{{ $intakes->appointment_date }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Appointment Time</h2>
                                                    <h3>{{ $intakes->appointment_time }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Communication Mode</h2>
                                                    <h3>
                                                        @if($intakes->communication_mode == 1)
                                                            Email
                                                        @elseif($intakes->communication_mode == 2)
                                                            Phone
                                                        @elseif($intakes->communication_mode == 3)
                                                            SMS
                                                        @elseif($intakes->communication_mode == 4)
                                                            Physical
                                                        @else
                                                            Not Provided
                                                        @endif
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
@endsection
