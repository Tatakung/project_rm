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

        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>


    </style>


    <ol class="breadcrumb" style="background: white ; ">
        <li class="breadcrumb-item">
            <a href="{{ route('employeetotal') }}" style="color: black ; ">จัดการพนักงาน</a>
        </li>
        <li class="breadcrumb-item active">
            รายละเอียดของพนักงาน
        </li>
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

    <div class="container">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle"></i> รายละเอียดของพนักงาน</div>


            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <img src="{{ asset('storage/' . $data->image) }}" alt="รูปภาพ"
                            style="width: 250px; height: 350px;">
                    </div>
                    <div class="col-mb-4">
                        <p><strong>รหัสพนักงาน: </strong> {{ $data->id }}</p>
                        <p><strong>ชื่อ-สกุล: </strong> {{ $data->name . ' ' . $data->lname }}</p>
                        <p><strong>อีเมล: </strong> {{ $data->email }}</p>
                        <p><strong>สถานะ: </strong>
                            @if ($data->status == 1)
                                เป็นพนักงาน
                            @else
                                ไม่ได้เป็นพนักงาน
                            @endif
                        </p>
                        <p><strong>เบอร์ติดต่อ: </strong> {{ $data->phone }}</p>
                        <p><strong>วันที่เริ่มทำงาน: </strong> {{ $data->start_date }}</p>
                        <p><strong>วันเกิด: </strong> {{ $data->birthday }}</p>
                        <p><strong>ที่อยู่: </strong> {{ $data->address }}</p>
                        <button id="changeStatusBtn" class="btn btn-danger" data-toggle="modal"
                            data-target="#confirmModal">เปลี่ยนสถานะ</button>
                        <!-- <span style="font-weight: bold;">รหัสพนักงาน: </span> {{ $data->id }}
                        </p>
                        <p class="card-text">
                            <span style="font-weight: bold;">ชื่อ-สกุล: </span> {{ $data->name . ' ' . $data->lname }}
                        </p>
                        <p class="card-text">
                            <span style="font-weight: bold;">อีเมล: </span> {{ $data->email }}
                        </p>
                        <p class="card-text">
                            <span style="font-weight: bold;">สถานะ: </span>
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
                        </p>  -->

                    </div>


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
                        <button type="submit" class="btn btn-success">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
