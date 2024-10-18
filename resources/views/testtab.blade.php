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


    @php
        $mea = 10 ; 
    @endphp

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-6">
                <input class="form-control" type="number" name="mea_number" id="mea_number" value="{{$mea}}">
            </div>
            <div class="col-md-6">
                <span id="show_message"></span>
            </div>

            <script>
                var mea_number = document.getElementById('mea_number') ; 
                var show_message = document.getElementById('show_message') ; 
                var local_number = '{{$mea}}' ; 
                var convert_local_number = parseFloat(local_number) ; 

                mea_number.addEventListener('input',function(){
                    var convert_mea_number = parseFloat(mea_number.value) ; 

                    if(convert_mea_number != convert_local_number ){
                        show_message.innerHTML = 'ปรับแก้จาก ' + convert_local_number + ' เป็น ' + convert_mea_number + ' นิ้ว' ; 
                    }
                    else{
                        show_message.innerHTML = 'ไม่ต้องปรับ' ; 
                    }



                }) ; 
            </script>












        </div>
    </div>




   
@endsection
