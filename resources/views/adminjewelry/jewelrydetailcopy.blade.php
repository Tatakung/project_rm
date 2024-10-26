@extends('layouts.adminlayout')
@section('content')
    <style>
        .table-container {
            height: 400px;
            /* กำหนดความสูงของตาราง */
            overflow-y: scroll;
            /* แสดงแถวเลื่อนแนวนอน */
        }

        .table::-webkit-scrollbar {
            width: 10px;
            /* กำหนดความกว้างของลูกกลิ้ง */
            height: 8px;
            /* กำหนดความสูงของลูกกลิ้ง */
        }

        .table::-webkit-scrollbar-thumb {
            background: #888;
            /* กำหนดสีพื้นหลังของลูกกลิ้ง */
            border-radius: 5px;
            /* กำหนดมุมโค้งมนของลูกกลิ้ง */
        }

        .table::-webkit-scrollbar-track {
            background: #ccc;
            /* กำหนดสีพื้นหลังของแถบเลื่อน */
        }
    </style>
    <div class="container d-flex justify-content-start">


        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('fail'))
            {{ session('fail') }}
        @endif --}}

        <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog" role="document">
                <div class="modal-content custom-modal-content"
                    style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #53b007;">
                    <div class="modal-body"
                        style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                        <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
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



        <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog" role="document">
                <div class="modal-content custom-modal-content"
                    style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #db430c;">
                    <div class="modal-body"
                        style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                        <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            @if (session('fail'))
                setTimeout(function() {
                    $('#showfail').modal('show');
                    setTimeout(function() {
                        $('#showfail').modal('hide');
                    }, 6000);
                }, 500);
            @endif
        </script>

        <div>

        </div>




        <div class="table-responsive text-start" style="width: 100%;">





            @foreach ($dataimage as $imagedata)
                <img src="{{ asset('storage/' . $imagedata->jewelry_image) }}" alt=""
                    style="max-height: 300px; width: auto;">
            @endforeach

            

            



            <h2 class="text text-start pt-5 ">รายละเอียดชุด</h2>
            <div class=”grid-container”>
                <div class="card mb-3 border-2" style="max-width: 1080px;">
                    <div class="row g-0">
                        <div class="col-md-4">


                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#showeditdress">แก้ไข</button>
                                
                                <p class="card-text">
                                    <span style="font-weight: bold;">ประเภทเครื่องประดับ : </span> {{ $data_type_name }}
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">รหัสเครื่องประดับ : </span>{{ $datajewelry->jewelry_code_new }}{{ $datajewelry->jewelry_code }}
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">ชื่อเครื่องประดับ : </span> {{ $datajewelry->jewelry_title_name }}
                                </p>
                                
                                <p class="card-text">
                                    <span style="font-weight: bold;">ราคา : </span> {{ $datajewelry->jewelry_price }}&nbsp;บาท
                                </p>
                                <p class="card-text">
                                    <span style="font-weight: bold;">ราคามัดจำ : </span>
                                    {{ $datajewelry->jewelry_deposit }}&nbsp;บาท
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">จำนวนเครื่องประดับ : </span>
                                    {{ $datajewelry->jewelry_count }}&nbsp;ชิ้น
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">สถานะเครื่องประดับ : </span> {{ $datajewelry->jewelry_status }}
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">จำนวนครั้งที่เครื่องประดับถูกเช่า : </span>
                                    {{ $datajewelry->jewelry_rental }} ครั้ง
                                </p>


                                <p class="card-text">
                                    <span style="font-weight: bold;">คำอธิบายเครื่องประดับ : </span>
                                    {{ $datajewelry->jewelry_description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>





                {{-- modalแก้ไขเครื่องประดับ --}}
                <div class="modal fade" id="showeditdress" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">แก้ไขเครื่องประดับ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form action="{{ route('admin.updatejewelry', ['id' => $datajewelry->id]) }}" method="POST">
                                @csrf
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label" for="update_jewelry_type">ประเภทชุด:
                                            {{ $data_type_name }}</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_code">หมายเลขชุด:
                                            {{ $datajewelry->jewelry_code_new }}{{ $datajewelry->jewelry_code }}</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_price">ราคา:
                                            {{ $datajewelry->jewelry_price }} บาท</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_deposit">ราคามัดจำ:
                                            {{ $datajewelry->jewelry_deposit }} บาท</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_count">จำนวนชุด:
                                            {{ $datajewelry->jewelry_count }} ชุด</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_status">สถานะชุด:
                                            {{ $datajewelry->jewelry_status }}</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_rental">จำนวนครั้งที่ถูกเช่า:
                                            {{ $datajewelry->jewelry_rental }} ครั้ง</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_jewelry_title_name">ชื่อชุด:</label>
                                        <input type="text" class="form-control" id="update_jewelry_title_name"
                                            name="update_jewelry_title_name" value="{{ $datajewelry->jewelry_title_name }}"
                                            required>
                                    </div>

                                    

                                    <div class="mb-3">
                                        <label class="form-label" for="update_jewelry_description">คำอธิบายชุด:</label>
                                        <textarea class="form-control" id="update_jewelry_description" name="update_jewelry_description" rows="3"
                                            required>{{ $datajewelry->jewelry_description }}</textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-secondary">ยืนยัน</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            

            


          








          



        @endsection
