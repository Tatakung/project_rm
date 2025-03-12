@extends('layouts.adminlayout')
@section('content')





<div class="container d-flex justify-content-start">
    <div class="table-responsive text-start" style="width: 100%;">
        <div class="container mt-5">
            <h1 class="card-title">พนักงานทั้งหมด</h1>
        </div>
        <div class="grid-container">
            <!-- <div style="display: flex; justify-content: end;">
                    <a href="{{ route('register') }}" class="btn btn-success" >เพิ่มพนักงาน</a>
                </div> -->
            <div class="col py-1"></div>
            <div class="table-responsive">
                <table class="table table-striped text-start">
                    <thead class="table">
                        <tr class="text text-center">
                            <th class="col-1">ลำดับ</th>
                            <th class="col-3">ชื่อ</th>
                            <th class="col-2">อีเมล</th>
                            <th class="col-2">สถานะ</th>
                            <th class="col-2">รายละเอียด</th>
                        </tr>
                    </thead>
                    @php
                    $counter = 1 ;
                    @endphp

                    @foreach ($data as $user)
                    @if($user->is_admin != 1)
                    <tr>
                        <td style="text-align: center;">{{$counter++}}</td>
                        <td>คุณ{{$user->name . ' ' . $user->lname}}</td>
                        <td style="text-align: center;">{{$user->email}}</td>
                        <td style="text-align: center;">
                            @if($user->status == 1)
                            เป็นพนักงาน
                            @elseif($user->status == 0)
                            ไม่ได้เป็นพนักงาน
                            @endif
                        </td>
                        {{-- <td>
                                        <a href="{{route('admin.employeedetail',['id' => $user->id])}}">ดูรายละเอียด</a>
                        </td> --}}
                        <td style="text-align: center;">
                            <a href="{{route('admin.employeedetail',['id'=>$user->id])}}" class="btn btn-sm btn-secondary">ดูรายละเอียด</a>
                        </td>
                    </tr>
                    @endif
                    @endforeach

                </table>


            </div>
        </div>
    </div>
</div>
@endsection