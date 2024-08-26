@extends('layouts.adminlayout')
@section('content')
    



    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if (session('fail'))
            setTimeout(function() {
                $('#showfail').modal('show');
            }, 500);
        @endif
    </script>



    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-4">จัดการการส่งซัก</h1>
                <p class="lead">ดูและอัพเดตสถานะการซักของชุด</p>
            </div>
        </div>

        <!-- Status Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">รอดำเนินการ</h5>
                        <p class="card-text display-4">{{ $countwait }} รายการ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">กำลังส่งซัก</h5>
                        <p class="card-text display-4">{{ $countdoing }} รายการ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">ซักเสร็จแล้ว</h5>
                        <p class="card-text display-4">{{ $countsuccess }} รายการ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laundry List -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-tshirt me-1"></i>
                รายการชุดที่รอดำเนินการ/กำลังซัก
            </div>

            <div class="card-body">
                <form action="{{ route('employee.cleanupdatestatus') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>เลือก</th>
                                    <th>รายการ</th>
                                    <th>สถานะการซัก</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clean as $clean)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="select_item_[]" value="{{ $clean->id }}"
                                                class="select-item">
                                        </td>
                                        <td>
                                            @php
                                                $dress = App\Models\Dress::find($clean->dress_id);
                                                $typedress = App\Models\Typedress::find($dress->type_dress_id);
                                            @endphp
                                            {{ $typedress->type_dress_name }}&nbsp;{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            @if ($clean->shirtitems_id)
                                                (เสื้อ)
                                            @elseif($clean->skirtitems_id)
                                                (กระโปรง)
                                            @else
                                                (ทั้งชุด)
                                            @endif
                                        </td>
                                        <td>
                                            @if ($clean->clean_status == 'รอดำเนินการ')
                                                <span class="badge bg-warning">{{ $clean->clean_status }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $clean->clean_status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" id="buttonupdate" class="btn btn-primary"
                                style="background: #A7567F; border: #A7567F">อัพเดตสถานะ</button>
                        </div>
                    </div>


                    {{-- <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var buttonupdate = document.getElementById('buttonupdate');
                    
                            function updateButtonState() {
                                var checkedCount = document.querySelectorAll('.select-item:checked').length;
                                buttonupdate.disabled = checkedCount === 0;
                            }
                    
                            // เรียกใช้ฟังก์ชันเพื่อตรวจสอบสถานะปุ่มเมื่อมีการเปลี่ยนแปลง checkbox
                            document.addEventListener('change', function(event) {
                                // ตรวจสอบว่า event target เป็น checkbox หรือไม่
                                if (event.target.classList.contains('select-item')) {
                                    updateButtonState();
                                }
                            });
                    
                            // ตรวจสอบสถานะปุ่มเริ่มต้น
                            updateButtonState();
                        });
                    </script> --}}






                    {{-- <div class="modal fade" role="dialog" id="updateclean{{$clean->id}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            </div>
                            <div class="modal-body">
                                {{$clean->id}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-success">บันทึก</button>
                            </div>
                        </div>
                    </div>
                </div> --}}




                </form>
            </div>
        </div>
    </div>
@endsection
