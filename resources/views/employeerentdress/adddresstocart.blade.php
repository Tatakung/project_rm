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
    </style>
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


    {{-- ส่วนฟิลเตอร์ --}}
    <div class="container  mt-5">
        <h1 class=" font-bold mb-4 ">การเช่าชุด</h1>
        <p id="show_day"></p>

        <form action="{{ route('employee.addrentdresstocardfilter') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for="" class="form-label" style="color: #848383 ; ">ประเภทชุด</label>
                    <select class="form-control" name="dress_type" id="dress_type" required>
                        <option value="" disabled @if ($dress_type == '') selected @endif>เลือกประเภทชุด
                        </option>
                        @foreach ($typedress as $item)
                            <option value="{{ $item->type_dress_name }}" @if ($dress_type == $item->type_dress_name) selected @endif>
                                {{ $item->type_dress_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="" class="form-label" style="color: #848383 ; ">ลักษณะชุด</label>
                    <select class="form-control" name="character" id="character">
                        <option id="ten" value="10">ทั้งชุด</option>
                        <option id="twenty" value="20">เสื้อ</option>
                        <option id="thirty" value="30">ผ้าถุง</option>
                    </select>
                </div>

                <script>
                    var value_character = '{{ $character }}';
                    var ten = document.getElementById('ten');
                    var twenty = document.getElementById('twenty');
                    var thirty = document.getElementById('thirty');

                    if (value_character === '' || value_character === '10') {
                        ten.selected = true;
                    } else if (value_character === '20') {
                        twenty.selected = true;
                    } else if (value_character === '30') {
                        thirty.selected = true;
                    }
                </script>






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

                                        show_day.innerHTML = 'ระยะเวลาเช่า ' + convert_enddate_startdate +' วัน';


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
                    <button type="submit" class="btn btn-success">ค้นหา</button>
                </div>

        </form>


    </div>
    </div>
    @if ($dress_pass == null)
        <div class="container mt-5">
            <div class="card" style="margin-left: 150px; margin-right: 150px;">
                <div class="card-body text-center">
                    <p><strong>ยินดีต้อนรับสู่ระบบเช่าชุด</strong></p>
                    <p>กรุณาเลือกประเภทชุด ลักษณะชุด และวันที่ต้องการเช่า</p>
                    <p>แล้วกดปุ่ม "ค้นหา" เพื่อดูรายการชุดที่สามารถเช่าได้</p>
                </div>
            </div>
        </div>
    @elseif ($dress_pass->count() > 0)
        <div class="container mt-5">
            <div class="row">
                @foreach ($dress_pass as $dress)
                    <div class="col-md-3 mb-4">
                        <div class="card text-left custom-card">
                            @php
                                $image = App\Models\Dressimage::where('dress_id', $dress->id)->first();
                            @endphp

                            <!-- ปุ่มที่ครอบทั้งการ์ดและเป็นตัวเปิด modal -->
                            <button type="button" class="btn p-0" data-toggle="modal"
                                data-target="#showModal{{ $dress->id }}" style="border: none; background: none;">
                                <img src="{{ asset('storage/' . $image->dress_image) }}" class="card-img-top custom-img"
                                    alt="{{ $image->dress_image }}">
                                <div class="card-body">
                                    @php
                                        $typename = App\Models\Typedress::find($dress->type_dress_id);
                                    @endphp
                                    <h5 class="card-title">{{ $typename->type_dress_name }}</h5>
                                    <p class="card-title">รหัส: {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Modal สำหรับแต่ละรายการ -->
                    <div class="modal fade" id="showModal{{ $dress->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="showModalLabel{{ $dress->id }}" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="showModalLabel{{ $dress->id }}">รายละเอียดชุด:
                                        {{ $typename->type_dress_name }}
                                        @if ($textcharacter == 'ทั้งชุด')
                                            (ทั้งชุด)
                                        @elseif($textcharacter == 'เสื้อ')
                                            (เสื้อ)
                                        @elseif($textcharacter == 'กระโปรง/ผ้าถุง')
                                            (ผ้าถุง)
                                        @endif

                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- รูปภาพทางด้านซ้าย -->
                                            <img src="{{ asset('storage/' . $image->dress_image) }}" class="img-fluid"
                                                alt="{{ $image->dress_image }}" style="max-width: 100%; height: auto;">
                                        </div>
                                        <div class="col-md-8">



                                            @if ($textcharacter == 'ทั้งชุด')
                                                <p><strong>ประเภทชุด:</strong> {{ $typename->type_dress_name }}</p>
                                                <p><strong>รหัสชุด:</strong>
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                </p>
                                                <p><strong>ราคาเช่า:</strong> {{ $dress->dress_price }} บาท</p>
                                                <p><strong>ราคามัดจำ:</strong> {{ $dress->dress_deposit }} บาท</p>
                                                <p><strong>ประกันชุด:</strong> {{ $dress->damage_insurance }} บาท</p>
                                            @elseif($textcharacter == 'เสื้อ')
                                                @php
                                                    $shirt = App\Models\Shirtitem::where(
                                                        'dress_id',
                                                        $dress->id,
                                                    )->first();
                                                @endphp
                                                <p><strong>ประเภทชุด:</strong> {{ $typename->type_dress_name }}</p>
                                                <p><strong>รหัสชุด:</strong>
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                </p>
                                                <p><strong>ราคาเช่า:</strong> {{ $shirt->shirtitem_price }} บาท</p>
                                                <p><strong>ราคามัดจำ:</strong> {{ $shirt->shirtitem_deposit }} บาท</p>
                                                <p><strong>ประกันชุด:</strong> {{ $shirt->shirt_damage_insurance }} บาท</p>
                                            @elseif($textcharacter == 'กระโปรง/ผ้าถุง')
                                                @php
                                                    $skirt = App\Models\Skirtitem::where(
                                                        'dress_id',
                                                        $dress->id,
                                                    )->first();
                                                @endphp
                                                <p><strong>ประเภทชุด:</strong> {{ $typename->type_dress_name }}</p>
                                                <p><strong>รหัสชุด:</strong>
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                </p>
                                                <p><strong>ราคาเช่า:</strong> {{ $skirt->skirtitem_price }} บาท</p>
                                                <p><strong>ราคามัดจำ:</strong> {{ $skirt->skirtitem_deposit }} บาท</p>
                                                <p><strong>ประกันชุด:</strong> {{ $skirt->skirt_damage_insurance }} บาท</p>
                                            @endif



                                            <div class="modal-footer">
                                                <form action="{{ route('addtocart') }}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="textcharacter"
                                                        value="{{ $textcharacter }}">
                                                    {{-- ทั้งชุด --}}
                                                    <input type="hidden" name="dress_id" value="{{ $dress->id }}">
                                                    <input type="hidden" name="pickupdate" value="{{ $start_date }}">
                                                    <input type="hidden" name="returndate" value="{{ $end_date }}">

                                                    {{-- เสื้อ --}}
                                                    @php
                                                        $shirt_form = App\Models\Shirtitem::where(
                                                            'dress_id',
                                                            $dress->id,
                                                        )->first();
                                                    @endphp
                                                    @if ($shirt_form)
                                                        <input type="hidden" name="shirt_id"
                                                            value="{{ $shirt_form->id }}">
                                                    @endif

                                                    {{-- กระโปรง/ผ้าถุง --}}
                                                    @php
                                                        $skirt_form = App\Models\Shirtitem::where(
                                                            'dress_id',
                                                            $dress->id,
                                                        )->first();
                                                    @endphp
                                                    @if ($skirt_form)
                                                        <input type="hidden" name="skirt_id"
                                                            value="{{ $skirt_form->id }}">
                                                    @endif




                                                    <button type="submit" class="btn btn-primary">เพิ่มลงตะกร้า</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">ปิด</button>
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
    @elseif($dress_pass->count() == 0)
        <div class="container mt-5">
            <div class="card" style="margin-left: 150px; margin-right: 150px;">
                <div class="card-body text-center">
                    <p><strong>ไม่พบรายการ</strong></p>

                </div>
            </div>
        </div>
    @endif
@endsection
