@extends('layouts.adminlayout')
@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="dress-tab" data-toggle="tab" href="#dress" role="tab" aria-controls="dress"
                aria-selected="true">ข้อมูลชุด</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="shirt-tab" data-toggle="tab" href="#shirt" role="tab" aria-controls="shirt"
                aria-selected="false">ข้อมูลเสื้อ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pants-tab" data-toggle="tab" href="#pants" role="tab" aria-controls="pants"
                aria-selected="false">ข้อมูลกางเกง</a>
        </li>
    </ul>



    <div class="tab-content" id="myTabContent">
    </div>




    <button class="btn btn-danger" data-toggle="modal" data-target="#showmodal">กดmodalเพื่อแสดงผล</button>


    <div class="modal fade" role="dialog" aria-hidden="true" id="showmodal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    ส่วนหัวmodal
                </div>
                <div class="modal-body">
                    ส่วนกลางmodal
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>



    <ul>
        <li>ทำไร</li>
        <li>ทดสอบ</li>
    </ul>


    <div class="container mt-5">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#profile">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#contact">Contact</a>
            </li>
        </ul>
        <!-- Tab content -->
        <div class="tab-content">

            <div class="tab-pane active" id="home">
                <h3>Home Content</h3>
                <p>This is the content for the Home tab.</p>
            </div>
            <div class="tab-pane" id="profile">
                <h3>Profile Content</h3>
                <p>This is the content for the Profile tab.</p>
            </div>
            <div class="tab-pane" id="contact">
                <h3>Contact Content</h3>
                <p>This is the content for the Contact tab.</p>
            </div>
        </div>
    </div>


    <div class="container">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#one">หน้าแรก</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#two">หน้าสอง</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#three">หน้าสาม</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="one">
                หน้าแรก
            </div>

            <div class="tab-pane" id="two">
                หน้าที่สอง
            </div>

            <div class="tab-pane" id="three">
                หน้าที่สาม
            </div>
        </div>
    </div>
    <br>
    @php
        $status = 'เอฟ';
    @endphp

    <div class="container">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#o" class="nav-link @if ($status == 'จอย') active @endif "
                    data-toggle="tab">หน้าแรก</a>
            </li>
            <li class="nav-item">
                <a href="#t" class="nav-link @if ($status == 'เอฟ') active @endif"
                    data-toggle="tab">หน้าสอง</a>
            </li>
            <li class="nav-item">
                <a href="#th" class="nav-link" data-toggle="tab">หน้าที่สาม</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane" id="o">
                แรก
            </div>
            <div class="tab-pane @if ($status == 'เอฟ') active @endif" id="t">
                สอง
            </div>
            <div class="tab-pane" id="th">
                สาม
            </div>
        </div>

    </div>


    <div class="container">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#one" class="nav-link active" data-toggle="tab">หน้าแรก</a>
            </li>
            <li class="nav nav-item">
                <a href="#two" class="nav-link" data-toggle="tab">หน้าสอง</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="one">
                หน้าแรก
            </div>
            <div class="tab-pane" id="two">
                หนาที่สอง
            </div>
        </div>

    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {



        });
    </script>



    <table class="table shadow-sm" style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ลำดับคิว</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่นัดรับ</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">สถานะชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ดูรายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $index => $reservation)
            <tr style="border-bottom: 1px solid #e6e6e6;">
                @php
                                $list_one = [];
                                
                                $find_shirt = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'ถูกจอง')
                                    ->where('shirtitems_id',$reservation->shirtitems_id)
                                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                    ->get();
                                $find_skirt = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'ถูกจอง')
                                    ->where('skirtitems_id', $reservation->skirtitems_id)
                                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                    ->get();

                                $find_dress = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'ถูกจอง')
                                    ->where('dress_id', $reservation->dress_id)
                                    ->whereNull('shirtitems_id')
                                    ->whereNull('skirtitems_id')
                                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                    ->get();
                                
      
                                foreach ($find_shirt as $key => $value) {
                                    $list_one[] = $value->id;
                                }
                                foreach ($find_skirt as $key => $value) {
                                    $list_one[] = $value->id;
                                }
                                foreach ($find_dress as $key => $value) {
                                    $list_one[] = $value->id;
                                }
                                $list_one = array_unique($list_one);

                                $total = App\Models\Reservation::whereIn('id',$list_one)
                                ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                ->get() ; 

                                foreach ($total as $index_dress => $item) {
                                    if ($item->id == $reservation->id) {
                                        $number = $index_dress + 1;
                                        break;
                                    }
                                }
                            @endphp



                            @if ($number == 1)
                                คิวที่ {{ $number }} <span style="color: red; margin-left: 5px;">&#9733;</span>
                            @else
                                คิวที่ {{ $number }}
                            @endif

                        </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
