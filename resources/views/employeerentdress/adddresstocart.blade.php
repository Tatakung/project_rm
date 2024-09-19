@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-5">
        <form action="{{ route('searchadddresstocart') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">เลือกประเภทชุด</label>
                    <select name="dress_type" id="dress_type" class="form-select" required>
                        <option value="" disabled selected>เลือกประเภทชุด</option>
                        @foreach ($typedress as $item)
                            <option value="{{ $item->type_dress_name }}" @if ($dress_type == $item->type_dress_name) selected @endif>
                                {{ $item->type_dress_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" id="character">ลักษณะชุด</label>
                    <select class="form-select" name="character" id="character">
                        <option value="">เลือกลักษณะชุด</option>
                        <option value="10" @if ($textcharacter == 'ทั้งชุด') selected @endif>ทั้งชุด</option>
                        <option value="20" @if ($textcharacter == 'เสื้อ') selected @endif>เสื้อ</option>
                        <option value="30" @if ($textcharacter == 'กระโปรง/ผ้าถุง') selected @endif>กระโปรง/ผ้าถุง</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" id="datepicker" placeholder="เลือกช่วงวัน"
                        value="{{ $text_startDate }} ถึง {{ $text_endDate }}">
                    <input type="hidden" id="startDate" name="startDate">
                    <input type="hidden" id="endDate" name="endDate">
                    <input type="hidden" id="totalDay" name="totalDay">
                    <p id="showday" style="font-size: 20px;"> {{ $text_totalDay }} วัน</p>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success">ค้นหา</button>
                </div>
            </div>
        </form>
        <hr>

        @if ($avalable_rent_pass)
            <div class="row">
                @foreach ($avalable_rent_pass as $dress)
                    <div class="col-md-3">
                        <button type="button" data-toggle="modal" data-target="#modaldetail{{ $dress->id }}">
                            <div class="card">
                                @php
                                    $image = App\Models\Dressimage::where('dress_id', $dress->id)->value('dress_image');
                                @endphp
                                <p style="color: rgb(241, 5, 147)">dress_id ที่ : {{ $dress->id }} </p>
                                <p>หมายเลขชุด : {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                                <img src="{{ asset('storage/' . $image) }}" alt=""
                                    style="max-height: 300px; object-fit: contain; margin-top: 10px;">
                            </div>
                        </button>
                        <br>
                    </div>

                    <div class="modal fade" role="dialog" id="modaldetail{{ $dress->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">

                                    @if ($textcharacter == 'ทั้งชุด')
                                        ราคา(ทั้งชุด)
                                    @elseif($textcharacter == 'เสื้อ')
                                        ราคา(เสื้อ)
                                    @elseif($textcharacter == 'กระโปรง/ผ้าถุง')
                                        ราคา(กระโปรง/ผ้าถุง)
                                    @endif
                                </div>
                                <div class="modal-body">

                                    @if ($textcharacter == 'ทั้งชุด')
                                        <p>รหัสชุด : {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                                        <p>ราคา : {{ $dress->dress_price }} บาท</p>
                                        <p>ราคามัดจำ : {{ $dress->dress_deposit }} บาท</p>
                                        <p>ราคาประกันค่าเสียหาย : {{ $dress->damage_insurance }} บาท</p>
                                        <p>คำอธิบายชุด : {{ $dress->dress_description }}</p>
                                    @elseif($textcharacter == 'เสื้อ')
                                        @php
                                            $shirt = App\Models\Shirtitem::where('dress_id', $dress->id)->first();
                                        @endphp
                                        <p>ราคา : {{ $shirt->shirtitem_price }} บาท</p>
                                        <p>ราคามัดจำ : {{ $shirt->shirtitem_deposit }} บาท</p>
                                        <p>ราคาประกันค่าเสียหาย : {{ $shirt->shirt_damage_insurance }} บาท</p>
                                    @elseif($textcharacter == 'กระโปรง/ผ้าถุง')
                                        @php
                                            $skirt = App\Models\Skirtitem::where('dress_id', $dress->id)->first();
                                        @endphp
                                        <p>ราคา : {{ $skirt->skirtitem_price }} บาท</p>
                                        <p>ราคามัดจำ : {{ $skirt->skirtitem_deposit }} บาท</p>
                                        <p>ราคาประกันค่าเสียหาย : {{ $skirt->skirt_damage_insurance }} บาท</p>
                                    @endif

                                </div>
                                <div class="modal-footer">


                                    <form action="{{route('addtocart')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="textcharacter" value="{{ $textcharacter }}">
                                        {{-- ทั้งชุด --}}
                                        <input type="hidden" name="dress_id" value="{{ $dress->id }}">
                                        <input type="hidden" name="pickupdate" value="{{ $text_startDate }}">
                                        <input type="hidden" name="returndate" value="{{ $text_endDate }}">
                                        <input type="hidden" name="totalday" value="{{ $text_totalDay }}">

                                        {{-- เสื้อ --}}
                                        @php
                                            $shirt_form = App\Models\Shirtitem::where('dress_id', $dress->id)->first();
                                        @endphp
                                        @if($shirt_form)
                                        <input type="hidden" name="shirt_id" value="{{ $shirt_form->id }}">
                                        @endif

                                        {{-- กระโปรง/ผ้าถุง --}}
                                        @php
                                            $skirt_form = App\Models\Shirtitem::where('dress_id', $dress->id)->first();
                                        @endphp
                                        @if($skirt_form)
                                        <input type="hidden" name="skirt_id" value="{{ $skirt_form->id }}">
                                        @endif

                                        <button class="btn btn-success" type="submit">เพิ่มลงตะกร้า</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var valuestartdate = document.getElementById('startDate');
            var valueenddate = document.getElementById('endDate');
            var totalDay = document.getElementById('totalDay');
            var showday = document.getElementById('showday');

            flatpickr('#datepicker', {
                dateFormat: 'Y-m-d',
                locale: 'th',

                mode: 'range',

                onChange: function(array, str, instance) {
                    if (array.length === 2) {
                        var stardate = array[0];
                        var enddate = array[1];

                        // แปลง object เป็น date
                        var strstartdate = instance.formatDate(stardate, 'Y-m-d');
                        var strenddate = instance.formatDate(enddate, 'Y-m-d');
                        // กำหนด value ให้ช่อง hidden 
                        valuestartdate.value = strstartdate;
                        valueenddate.value = strenddate;

                        // ตรวจสอบว่าเป็นกี่วัน 
                        var count_day = enddate - stardate;
                        var totalday = Math.ceil(count_day / (1000 * 60 * 60 * 24));
                        totalDay.value = totalday;
                        showday.innerHTML = totalday + ' วัน';
                    }
                }
            });
        });
    </script>
@endsection
