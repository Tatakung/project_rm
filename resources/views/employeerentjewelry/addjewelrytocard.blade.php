@extends('layouts.adminlayout')
@section('content')
    <style>
        .custom-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: linear-gradient(145deg, #ffffff, #ffffff);
        }

        .custom-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .custom-img {
            width: 100%;
            /* Make sure the image fills the width of the card */
            height: 320px;
            /* You can adjust the height as needed */
            object-fit: cover;
            /* This ensures the image keeps its aspect ratio */
            border-bottom: 2px solid #f8f8f8;
        }


        .card-body h5,
        .card-body p {
            margin: 0;
        }

        .card-body p {
            font-size: 14px;
        }

        .card-body .text-success {
            color: #28a745;
            font-weight: bold;
        }

        .card-body .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .modal-header {
            background: #EAD8C0;
        }

        .btn-s {
            border-radius: 20px;
            background-color: #007bff;
            border: none;
            padding: 5px 15px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .btn-s:hover {
            background-color: #0056b3;
        }

        .btn-s i {
            margin-right: 2px;
            font-size: 14px;
        }
    </style>
    {{-- ส่วนฟิลเตอร์ --}}
    <div class="container  mt-5">
        <h1 class=" font-bold mb-4 ">การเช่าเครื่องประดับ</h1>
        <p id="show_day"></p>

        <form action="{{ route('employee.addrentjewelrytocardfilter') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for="" class="form-label" style="color: #848383 ; ">ประเภทเครื่องประดับ</label>
                    <select class="form-control" name="jewelry_type" id="jewelry_type" required>
                        <option value="" disabled @if ($jewelry_type == '') selected @endif>
                            เลือกประเภทเครื่องประดับ
                        </option>
                        @foreach ($typejew as $item)
                            <option value="{{ $item->id }}" @if ($jewelry_type == $item->id) selected @endif>
                                {{ $item->type_jewelry_name }}</option>
                        @endforeach
                        <option value="set" @if ($jewelry_type == 'set') selected @endif>เซต</option>
                    </select>
                </div>




                <div class="col-md-4">
                    <label for="" class="form-label" style="color: #848383 ;">วันนัดรับ - วันนัดคืน</label>
                    <input type="text" id="date-range" class="w-full p-2 border rounded"
                        placeholder="เลือกวันที่รับและคืนชุด">
                    <input type="hidden" name="start_date" id="start_date">
                    <input type="hidden" name="end_date" id="end_date">
                </div>





                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
                <script>
                    var value_start = '{{ $start_date }}';
                    var value_end = '{{ $end_date }}';

                    var show_day = document.getElementById('show_day');

                    document.addEventListener('DOMContentLoaded', function() {
                        var dateRangeInput = document.getElementById('date-range');
                        if (dateRangeInput) {
                            var fp = flatpickr("#date-range", {
                                mode: "range",
                                dateFormat: "d/m/Y",
                                locale: "th",
                                // minDate: "today",
                                // maxDate: new Date().fp_incr(60),
                                altInput: true,
                                altFormat: "j F Y",
                                conjunction: " ถึง ",
                                onChange: function(selectedDates, dateStr, instance) {
                                    if (selectedDates.length === 2) {
                                        var strstartdate = instance.formatDate(selectedDates[0], 'Y-m-d');
                                        var strenddate = instance.formatDate(selectedDates[1], 'Y-m-d');
                                        document.getElementById('start_date').value = strstartdate;
                                        document.getElementById('end_date').value = strenddate;

                                        var startdate = new Date(strstartdate);
                                        var enddate = new Date(strenddate);

                                        var enddate_startdate = enddate - startdate;
                                        var convert_enddate_startdate = Math.ceil(enddate_startdate / (1000 * 60 *
                                            60 * 24));

                                        // show_day.innerHTML = 'ระยะเวลาเช่า ' + convert_enddate_startdate +' วัน';


                                    }
                                }
                            });

                            if (value_start && value_end) {
                                console.log('มีค่าที่ส่งมา');
                                // แปลงรูปแบบวันที่จาก Y-m-d เป็น Date object
                                var startDate = new Date(value_start);
                                var endDate = new Date(value_end);
                                // กำหนดค่าให้กับ Flatpickr
                                fp.setDate([startDate, endDate]);
                                // กำหนดค่าให้กับ hidden inputs
                                document.getElementById('start_date').value = value_start;
                                document.getElementById('end_date').value = value_end;
                            } else {
                                console.log('ไม่มีค่าที่ถูกส่งมาเลยจร้า');
                            }
                        }
                    });
                </script>

                <div class="col-md-2">
                    <label for="" class="form-label" style="color: #ffffff">ค้นหาค้นหาค้นหา</label>
                    <button type="submit" class="btn btn-s"style="background-color:#BACEE6 ;">ค้นหา</button>
                </div>
        </form>
    </div>



    {{-- ส่วนแสดงผล --}}



    @if ($jewelry_pass == null)
        <div class="container mt-5">
            <div class="card" style="margin-left: 150px; margin-right: 150px;">
                <div class="card-body text-center">
                    <p><strong>ยินดีต้อนรับสู่ระบบเช่าเครื่องประดับ</strong></p>
                    <p>กรุณาเลือกประเภทเครื่องประดับ และวันที่ต้องการเช่า</p>
                    <p>แล้วกดปุ่ม "ค้นหา" เพื่อดูรายการเครื่องประดับที่สามารถเช่าได้</p>
                </div>
            </div>
        </div>
    @elseif($jewelry_pass->count() > 0 && $jewelry_type != 'set')
        <div class="container mt-5">
            <div class="row">
                @foreach ($jewelry_pass as $jew)
                    <div class="col-md-3 mb-4">
                        <div class="card text-left custom-card">
                            @php
                                $image = App\Models\Jewelryimage::where('jewelry_id', $jew->id)->first();
                            @endphp

                            <!-- ปุ่มที่ครอบทั้งการ์ดและเป็นตัวเปิด modal -->
                            <button type="button" class="btn p-0" data-toggle="modal"
                                data-target="#showModal{{ $jew->id }}" style="border: none; background: none;">
                                {{-- <p>jewelry_id : {{$jew->id}}</p> --}}

                                <img src="{{ asset('storage/' . $image->jewelry_image) }}" class="card-img-top custom-img"
                                    alt="{{ $image->jewelry_image }}">
                                <div class="card-body">
                                    @php
                                        $typename = App\Models\Typejewelry::find($jew->type_jewelry_id);
                                    @endphp
                                    <h5 class="card-title">{{ $typename->type_jewelry_name }}</h5>
                                    <p class="card-title">รหัส: {{ $typename->specific_letter }}{{ $jew->jewelry_code }}
                                    </p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Modal สำหรับแต่ละรายการ -->
                    <div class="modal fade" id="showModal{{ $jew->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="showModalLabel{{ $jew->id }}" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="showModalLabel{{ $jew->id }}">รายละเอียดเครื่องประดับ
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- รูปภาพทางด้านซ้าย -->
                                            <img src="{{ asset('storage/' . $image->jewelry_image) }}" class="img-fluid"
                                                alt="{{ $image->jewelry_image }}" style="max-width: 100%; height: auto;">
                                        </div>
                                        <div class="col-md-8">
                                            <p><strong>ประเภทเครื่องประดับ:</strong> {{ $typename->type_jewelry_name }}</p>
                                            <p><strong>รหัสเครื่องประดับ:</strong>
                                                {{ $typename->specific_letter }}{{ $jew->jewelry_code }}
                                            </p>
                                            <p><strong>ราคาเช่า:</strong> {{ $jew->jewelry_price }} บาท</p>
                                            <p><strong>ราคามัดจำ:</strong> {{ $jew->jewelry_deposit }} บาท</p>
                                            <p><strong>เงินประกัน:</strong> {{ $jew->damage_insurance }} บาท</p>




                                            <div class="modal-footer">
                                                <form action="{{route('employee.addrentjewelrytocardaddtocard')}}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="pickupdate" value="{{ $start_date }}">
                                                    <input type="hidden" name="returndate" value="{{ $end_date }}">
                                                    <input type="hidden" name="jew_id" value="{{$jew->id}}">
                                                    <input type="hidden" name="jew_price" value="{{$jew->jewelry_price}}">
                                                    <input type="hidden" name="jew_deposit" value="{{$jew->jewelry_deposit}}">
                                                    <input type="hidden" name="jew_damage_insurance" value="{{$jew->damage_insurance}}">


                                                    <button type="submit" class="btn"
                                                        style="background-color:#ACE6B7;">เพิ่มลงตะกร้า</button>
                                                    <!-- <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">ปิด</button> -->
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($jewelry_pass->count() > 0 && $jewelry_type == 'set')
        <div class="container mt-5">
            <div class="row">
                @foreach ($jewelry_pass as $jewset)
                    <div class="col-md-3 mb-4">
                        <div class="card text-left custom-card">

                            <!-- ปุ่มที่ครอบทั้งการ์ดและเป็นตัวเปิด modal -->
                            <button type="button" class="btn p-0" data-toggle="modal"
                                data-target="#showModal{{ $jewset->id }}" style="border: none; background: none;">
                                {{-- <p>jewelry_id : {{$jew->id}}</p> --}}

                                <img src="{{ asset('images/setjewelry.jpg') }}" class="card-img-top custom-img"
                                    alt="">
                                <div class="card-body">
                                    <p><strong>jew_set_id:</strong> {{ $jewset->id }}</p>
                                    <h5 class="card-title">{{ $jewset->set_name }}</h5>
                                    <p class="card-title">รหัส: SET00{{ $jewset->id }}</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Modal สำหรับแต่ละรายการ -->
                    <div class="modal fade" id="showModal{{ $jewset->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="showModalLabel{{ $jewset->id }}" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="showModalLabel{{ $jewset->id }}">
                                        รายละเอียดเซตเครื่องประดับ</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- รูปภาพทางด้านซ้าย -->
                                            <img src="{{ asset('images/setjewelry.jpg') }}" class="img-fluid"
                                                alt="" style="max-width: 100%; height: auto;">
                                        </div>
                                        <div class="col-md-8">
                                            <p><strong>ชื่อเซต:</strong> {{ $jewset->set_name }}</p>
                                            <p><strong>ราคาเช่า:</strong> {{ $jewset->set_price }} บาท</p>
                                            <p><strong>ราคามัดจำ:</strong> {{ number_format($jewset->set_price * 0.3) }}
                                                บาท</p>
                                            <p><strong>เงินประกัน:</strong> {{ $jewset->set_price }} บาท</p>
                                            <p>ในเซตประกอบด้วย</p>
                                            @php
                                                $item_jew = App\Models\Jewelrysetitem::where(
                                                    'jewelry_set_id',
                                                    $jewset->id,
                                                )->get();
                                            @endphp
                                            @foreach ($item_jew as $item)
                                                <li>{{$item->jewelry_id}}
                                                    {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                                    {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                                    
                                                </li>
                                            @endforeach



                                            <div class="modal-footer">
                                                <form action="{{route('employee.addrentjewelrytocardaddtocardset')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="pickupdate" value="{{ $start_date }}">
                                                    <input type="hidden" name="returndate" value="{{ $end_date }}">
                                                    <input type="hidden" name="jewset_id" value="{{$jewset->id}}">
                                                    <input type="hidden" name="jewset_price" value="{{$jewset->set_price}}">
                                                    <button type="submit" class="btn"
                                                        style="background-color:#ACE6B7;">เพิ่มลงตะกร้า</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($jewelry_pass->count() == 0)
        <div class="container mt-5">
            <div class="card" style="margin-left: 150px; margin-right: 150px;">
                <div class="card-body text-center">
                    <p><strong>ไม่พบรายการ</strong></p>

                </div>
            </div>
        </div>
    @endif














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
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
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

    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>
@endsection
