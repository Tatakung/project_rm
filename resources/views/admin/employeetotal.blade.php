@extends('layouts.adminlayout')

@section('content')
    <div class="container d-flex justify-content-start">
        <div class="table-responsive text-start" style="width: 100%;">
            <h2 class="text text-center py-4" style="color: #AA9367;">พนักงานทั้งหมด</h2>
            <div class="grid-container">
                <div style="display: flex; justify-content: end;">
                    <a href="{{ route('register') }}" class="btn" style="color: #ffffff; background-color: #64615c;">เพิ่มพนักงาน</a>
                </div>
                <div class="col py-1"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-start">
                        <thead class="table-dark" style="background-color: #868686; color: #F4E8E8;">
                            <tr class="text text-center">
                                <th scope="col" class="col-1">ลำดับ</th>
                                <th scope="col" class="col-3">ชื่อ</th>
                                <th scope="col" class="col-2">อีเมล</th>
                                <th scope="col" class="col-2">สถานะ</th>
                                <th scope="col" class="col-2">รายละเอียด</th>
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
                                        <a href="{{route('admin.employeedetail',['id'=>$user->id])}}">ดูรายละเอียด</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        
                    </table>

                    <ul class="pagination justify-content-end">
                        <li class="page-item {{ ($data->currentPage() == 1) ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $data->url(1) }}" tabindex="-1" aria-disabled="true">ย้อนกลับ</a>
                        </li>
                        @for ($i = 1; $i <= $data->lastPage(); $i++)
                            <li class="page-item {{ ($data->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ ($data->currentPage() == $data->lastPage()) ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $data->url($data->currentPage() + 1) }}">ถัดไป</a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
@endsection
