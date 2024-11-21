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
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>








    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header"><i class="bi bi-info-circle"></i> ข้อมูลการเช่าเครื่องประดับ
            </div>
            <div class="card-body">

                <form action="{{ route('employee.savemanageitemrentjewelry', ['id' => $orderdetail->id]) }}" method="POST">
                    @csrf


                    <div class="row">
                        <div class="col-md-12">
                            <div class="media">
                                @if ($reservation->jewelry_id)
                                    <img src="{{ asset('storage/' . $imagejewelry->jewelry_image) }}" class="mr-5"
                                        alt="..." style="max-height: 350px; width: auto;">
                                @elseif($reservation->jewelry_set_id)
                                    {{-- <img src="{{ asset('images/setjewelry.jpg') }}" class="mr-5" alt="..."
                                        style="width: 96px; height: 145px; border-radius: 8px;"> --}}
                                @endif

                                <div class="media-left">

                                    <p><strong>รายการ :</strong>
                                        @if ($reservation->jewelry_id)
                                            เช่า{{ $typejewelry->type_jewelry_name }}
                                            {{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}
                                        @elseif($reservation->jewelry_set_id)
                                            เช่าเซต{{ $jewelryset->set_name }}
                                        @endif
                                    </p>

                                    @if ($reservation->jewelry_id)
                                        <p><strong>ประเภทเครื่องประดับ :</strong> {{ $typejewelry->type_jewelry_name }}</p>
                                        <p><strong>หมายเลขเครื่องประดับ :</strong>
                                            {{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}</p>
                                        <p><strong>ราคาเช่า :</strong> {{ number_format($orderdetail->price) }} บาท</p>
                                        <p><strong>เงินมัดจำ :</strong> {{ number_format($orderdetail->deposit) }} บาท</p>
                                        <p><strong>ประกันค่าเสียหาย
                                                :</strong>{{ number_format($orderdetail->damage_insurance) }} บาท</p>
                                    @elseif($reservation->jewelry_set_id)
                                        <p><strong>ประเภทเครื่องประดับ :</strong> เซต</p>
                                        <p><strong>หมายเลขเครื่องประดับ :</strong> SET00{{ $jewelryset->id }}</p>
                                        <p><strong>ราคาเช่า :</strong> {{ number_format($orderdetail->price) }} บาท</p>
                                        <p><strong>เงินมัดจำ :</strong> {{ number_format($orderdetail->price * 0.3) }} บาท
                                        </p>
                                        <p><strong>ประกันค่าเสียหาย :</strong>{{ number_format($orderdetail->price) }} บาท
                                        </p>
                                    @endif





                                    <p><strong>วันนัดรับ :</strong>
                                        {{ \Carbon\Carbon::parse($reservation->start_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($reservation->start_date)->year + 543 }}
                                    </p>
                                    <p><strong>วันนัดคืน :</strong>
                                        {{ \Carbon\Carbon::parse($reservation->end_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($reservation->end_date)->year + 543 }}
                                    </p>

                                    @if ($reservation->jewelry_set_id)
                                        <p>ประกอบด้วย :</p>
                                        <div class="row">
                                            @foreach ($setjewelryitem as $item)
                                                <div class="col-md-4 mb-3">
                                                    <img src="{{ asset('storage/' . $item->jewitem_m_to_o_jew->jewelryimages->first()->jewelry_image) }}"
                                                        alt="เครื่องประดับในเซต" class="img-fluid rounded mb-2"
                                                        style="height: 150px; width: 150px; object-fit: cover;">
                                                    <small class="d-block">
                                                        {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                                        {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif





                                </div>

                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="card shadow mt-3">








            <div class="card-body">
                <div class="col-md-8">
                    <strong>รายละเอียดอื่นๆ</strong>
                    <textarea name="note" id="" cols="1" rows="4" class="form-control">{{ $orderdetail->note }}</textarea>

                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn " style="background-color:#ACE6B7;">ยืนยัน</button>
                    </div>
                </div>
            </div>

        </div>



        </form>

    </div>
@endsection
