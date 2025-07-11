@extends('layouts.adminlayout')

@section('content')
    <style>
        .card-header,
        .card-body label,
        .card-body input,
        .card-body textarea,
        .card-body .invalid-feedback,
        .card-body button {
            color: #000000;
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif









    <div class="container" style="margin-top : 10px ;">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header text-center" style="font-size: 24px;">
                        {{ __('เพิ่มบัญชีพนักงาน') }}
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end ">{{ __('ชื่อ') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="lname"
                                    class="col-md-4 col-form-label text-md-end">{{ __(' นามสกุล') }}</label>

                                <div class="col-md-6">
                                    <input id="lname" type="text"
                                        class="form-control @error('lname') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="lname" value="{{ old('lname') }}" required autocomplete="lname" autofocus>

                                    @error('lname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('รหัสผ่าน') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('ยืนยันรหัสผ่าน') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password"
                                        class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="phone"
                                    class="col-md-4 col-form-label text-md-end">{{ __('เบอร์โทรศัพท์') }}</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="phone" value="{{ old('phone') }}" required autocomplete="phone">

                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @php
                                $todays = \Carbon\Carbon::today()->toDateString();
                            @endphp

                            <div class="row mb-3">
                                <label for="start_date"
                                    class="col-md-4 col-form-label text-md-end">{{ __('วันที่ทำงานวันแรก') }}</label>

                                <div class="col-md-6">
                                    <input id="start_date" type="date"
                                        class="form-control @error('start_date') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="start_date" value="{{ old('start_date') }}" min="{{ $todays }}"
                                        required>
                                    @error('start_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="birthday"
                                    class="col-md-4 col-form-label text-md-end">{{ __('วันเกิด') }}</label>

                                <div class="col-md-6">
                                    <input id="birthday" type="date"
                                        class="form-control @error('birthday') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="birthday" value="{{ old('birthday') }}" required>

                                    @error('birthday')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="row mb-3">
                                <label for="address"
                                    class="col-md-4 col-form-label text-md-end">{{ __('ที่อยู่') }}</label>

                                <div class="col-md-6">
                                    <textarea id="address"
                                        class="form-control @error('address') is-invalid @enderror shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                        name="address" required>{{ old('address') }}</textarea>

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="image"
                                    class="col-md-4 col-form-label text-md-end">{{ __('รูปประจำตัว') }}</label>

                                <div class="col-md-6">
                                    <input id="image" type="file" name="image" accept="image/*" required>

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row justify-content-end">
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success" style="color: #ffffff;">
                                        {{ __('ยืนยัน') }}
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
