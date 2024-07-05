@extends('layouts.adminlayout')
@section('content')
    <style>
        .card-text {
            margin-bottom: 22px;
            font-size: 20px;
            /* เพิ่มส่วนนี้เพื่อกำหนดขนาดตัวหนังสือ */
            margin-left: 20px;
            /* ระยะห่างทางด้านซ้ายของประเภท */
            margin-top: 15px;
        }

        #image-container {
            flex-shrink: 0;
            margin-right: 30px;

        }

        #b {
            background-color: #E36414;
            color: #FFFFFF;
            /* เพิ่มส่วนนี้เพื่อกำหนดสีตัวหนังสือ */
            margin-left: 20px;
            /* ระยะห่างทางด้านซ้ายของประเภท */
        }

        #b1 {
            background-color: #994D1C;
            color: #FFFFFF;
            /* เพิ่มส่วนนี้เพื่อกำหนดสีตัวหนังสือ */
            margin-left: 20px;
            /* ระยะห่างทางด้านซ้ายของประเภท */

        }

    </style>




    <ol class="breadcrumb" style="background-color: #00000000;">
        <li class="breadcrumb-item"><a style="color: #AA9367" href="{{ route('employeetotal') }}">จัดการพนักงาน</a></li>
        <li class="breadcrumb-item active" aria-current="page">รายละเอียด</li>
    </ol>


    <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #53b007;">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">อัพเดตสถานะสำเร็จ !</p>
                </div>
            </div>
        </div>
    </div>







    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccessss').modal('show');
                setTimeout(function() {
                    $('#showsuccessss').modal('hide');
                }, 6000);
            }, 500);
        @endif
    </script>




    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}
    <div class="container d-flex justify-content-start">
        <div class="table-responsive text-start" style="width: 100%;">
            <h2 class="text text-start py-4">รายละเอียดของพนักงาน</h2>

            <div class="d-flex align-items-start">

                <div id="image-container">
                    {{-- รูปภาพ --}}
                    {{-- <img src="{{asset('storage/' .$employeefind->image)}}" alt="รูปภาพ" style="width: 250px; height: 300px;"> --}}
                    <img src="{{ asset('storage/' . $data->image) }}" alt="รูปภาพ" style="width: 250px; height: 300px;">

                </div>
                <div class="card-body">
                    <p class="card-text">
                        <span style="font-weight: bold;">รหัสพนักงาน: </span> {{ $data->id }}
                    </p>
                    <p class="card-text">
                        <span style="font-weight: bold;">ชื่อ-สกุล: </span> {{ $data->name . ' ' . $data->lname }}
                    </p>
                    <p class="card-text">
                        <span style="font-weight: bold;">อีเมล: </span> {{ $data->email }}
                    </p>
                    <p class="card-text">
                        <span style="font-weight: bold; color: #f30808;">สถานะ: </span>
                        @if ($data->status == 1)
                            เป็นพนักงาน
                        @else
                            ไม่ได้เป็นพนักงาน
                        @endif
                    </p>
                    <p class="card-text">
                        <span style="font-weight: bold;">เบอร์ติดต่อ: </span> {{ $data->phone }}
                    </p>
                    <p class="card-text">
                        <span style="font-weight: bold;">วันที่เริ่มทำงาน: </span> {{ $data->start_date }}
                    </p>
                    <p class="card-text">
                        <span style="font-weight: bold;">วันเกิด: </span> {{ $data->birthday }}
                    </p>
                    <p class="card-text">
                        <span style="font-weight: bold;">ที่อยู่: </span> {{ $data->address }}
                    </p>
                    <button id="changeStatusBtn" class="btn btn-danger" data-toggle="modal"
                        data-target="#confirmModal">เปลี่ยนสถานะ</button>
                </div>
            </div>
        </div>
    </div>


    {{-- modal --}}
    <div class="modal fade" id="confirmModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header alert alert-danger">
                    <h5 class="modal-title" id="confirmModalLabel">ยืนยันการเปลี่ยนสถานะ</h5>
                </div>
                {{-- <form action="{{ route('admin.changestatus', ['id' => $employeefind->id]) }}" method="POST"> --}}
                <form action="{{ route('admin.changestatusemployee', ['id' => $data->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        คุณต้องการเปลี่ยนสถานะของคุณ {{ $data->name }} {{ $data->lname }}&nbsp;หรือไม่?


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-danger">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
