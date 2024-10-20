@extends('layouts.adminlayout')
@section('content')
<style>
    /* การตกแต่งสำหรับการ์ด */
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .card-header {
        background-color: #f1f1f1;
        font-weight: bold;
        font-size: 18px;
        border-bottom: 1px solid #ddd;
    }

    .card-body {
        padding: 20px;
    }

    /* การตกแต่งข้อความ */
    .card-body p {
        font-size: 16px;
        color: #555;
        margin-bottom: 10px;
    }

    /* การตกแต่งสำหรับปุ่ม */
    .btn {
        padding: 8px 15px;
        font-size: 14px;
        border-radius: 5px;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    .btn-danger {
        background-color: #E74C3C;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #C0392B;
    }

    .btn-secondary {
        background-color: #95A5A6;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #7F8C8D;
    }

    .btn-success {
        background-color: #28A745;
        color: #fff;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    /* การตกแต่งสำหรับ breadcrumb */
    .breadcrumb {
        background-color: transparent;
        padding: 8px 0;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .breadcrumb-item a {
        color: #3498DB;
    }

    .breadcrumb-item.active {
        color: #7F8C8D;
    }

    /* การตกแต่งสำหรับ modal */
    .modal-header {
        background-color: #f8d7da;
        border-bottom: none;
    }

    .modal-content {
        border-radius: 8px;
    }

    .modal-body {
        font-size: 16px;
        text-align: center;
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
    }


    .col-4,
    .col-mb-4 {
        padding: 15px;
    }
</style>


</style>


<!-- <ol class="breadcrumb" style="background-color: transparent; ">
        <li class="breadcrumb-item">
            <a href="{{ route('employeetotal') }}" style="color: black ; ">จัดการพนักงาน</a>
        </li>
        <li class="breadcrumb-item active">
            รายละเอียดของพนักงาน
        </li>
    </ol> -->




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
    @if(session('success'))
    setTimeout(function() {
        $('#showsuccessss').modal('show');
        setTimeout(function() {
            $('#showsuccessss').modal('hide');
        }, 6000);
    }, 500);
    @endif
</script>


<div class="container mt-5">
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
                    <p><strong>วันที่เริ่มทำงาน: </strong> 
                        {{ \Carbon\Carbon::parse($data->start_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($data->start_date)->year +543 }}
                    
                    </p>
                    <p><strong>วันเกิด: </strong> 
                        {{ \Carbon\Carbon::parse($data->birthday)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($data->birthday)->year +543 }}
                    
                    </p>
                    <p><strong>ที่อยู่: </strong> {{ $data->address }}</p>
                    <button id="changeStatusBtn" class="btn btn-danger" data-toggle="modal"
                        data-target="#confirmModal">เปลี่ยนสถานะ</button>

                </div>


            </div>
        </div>

    </div>
</div>

{{-- modal --}}
<div class="modal fade" id="confirmModal" role="dialog" aria-hidden="true" data-backdrop="static">
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