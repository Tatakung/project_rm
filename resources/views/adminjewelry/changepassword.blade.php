<style>
    body {
        margin-top: 20px;
        background: #f8f8f8;
        width: 100%;
    }
</style>
@extends('layouts.adminlayout')
@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{session('success')}}
</div>
 @endif
    <div class="container d-flex justify-content-start">
        <div class="table-responsive text-start" style="width: 100%;">
            <h2 class="text text-start py-3">จัดการโปรไฟล์</h2>

            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

            <div class="col">
                <div class="row">
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="e-profile">
                                    <div class="row">
                                        <div class="col-12 col-sm-auto mb-3">
                                            <div class="mx-auto" style="width: 140px;">
                                                <div class="d-flex justify-content-center align-items-center rounded"
                                                    style="height: 140px; background-color: rgb(233, 236, 239);">
                                                    <span> <img src="{{ asset('storage/' . $adm->image) }}" alt="รูปภาพ"
                                                            style="width: 140px; height: 140px;">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                            <div class="text-center text-sm-left mb-2 mb-sm-0">
                                                <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">
                                                    {{ $adm->name . ' ' . $adm->lname }}</h4>
                                                <p class="mb-0">{{ $adm->email }}</p>

                                            </div>
                                            <div class="text-center text-sm-right">
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="{{route('admin.adminprofile')}}" class="active nav-link">ตั้งค่าข้อมูล</a>
                                        </li>
                                        <li class="nav-item"><a href="{{route('admin.changepassword')}}" class="active nav-link">เปลี่ยนรหัสผ่าน</a>
                                        </li>

                                    </ul>
                                    <div class="tab-content pt-3">
                                        <div class="tab-pane active">


                                            <form class="form" method="POST" action="{{ route('admin.updatepassword') }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label for="current_password">รหัสผ่านเดิม:</label>
                                                                    <input type="password" name="old_password"
                                                                        class="form-control"required>
                                                                </div>
                                                            </div>
                                                            @error('current_password')
                                                                <p class="text-red-500">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">

                                                                    <label for="password">รหัสผ่านใหม่:</label>
                                                                    <input class="form-control" type="password"
                                                                        name="new_password" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @error('password')
                                                            <p class="text-red-500">{{ $message }}</p>
                                                        @enderror
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="password_confirmation">ยืนยันรหัสผ่านอีกครั้ง:</label>
                                                                    <input class="form-control" type="password"
                                                                        name="new_password_confirmation" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @error('password_confirmation')
                                                            <p class="text-red-500">{{ $message }}</p>
                                                        @enderror

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col d-flex justify-content-end">
                                                        <button class="btn btn-success" type="submit">บันทึก</button>
                                                    </div>
                                                </div>
                                            </form>
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
