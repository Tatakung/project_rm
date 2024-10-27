@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12" style="text-align: center ; ">
                <h5>จัดเซตเครื่องประดับ</h5>
            </div>
        </div>


        <form action="{{ route('admin.managesetjewelryfilter') }}" method="GET">
            @csrf
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="typeJewelry" class="form-label">เลือกประเภทเครื่องประดับ</label>
                    <select name="typeJewelry_id" id="typeJewelry_id" class="form-control" onchange="this.form.submit()">
                        <option value="01" {{ $typeJewelry_id === '01' ? 'selected' : '' }}>ทั้งหมด</option>
                        @foreach ($typejewelry as $item)
                            <option value="{{ $item->id }}" @if ($typeJewelry_id == $item->id) selected @endif>
                                {{ $item->type_jewelry_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if ($jewtotal)
                <div class="row mt-2">
                    <div class="col-12">
                        <div style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>jew_id</th>
                                        <th>เลือก</th>
                                        <th>หมายเลขเครื่องประดับ</th>
                                        <th>ประเภท</th>
                                        <th>ราคาเช่า</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jewtotal as $type)
                                        @foreach ($type->jewelrys as $jew)
                                            <tr>
                                                <td>
                                                    {{ $jew->id }}
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="jew_id" id="{{ $jew->id }}" data-typename="-{{$type->type_jewelry_name}} {{$type->specific_letter}}{{$jew->jewelry_code}} ราคา {{$jew->jewelry_price}} บาท"
                                                        class="class_name" onclick="list_id_to_click()" data-price="{{$jew->jewelry_price}}">
                                                </td>
                                                <td>
                                                    {{ $type->specific_letter }}{{ $jew->jewelry_code }}
                                                </td>
                                                <td>
                                                    {{ $type->type_jewelry_name }}
                                                </td>
                                                <td>{{ number_format($jew->jewelry_price, 2) }} บาท</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </form>

        <script>
            function list_id_to_click() {
                var alldata = document.getElementsByClassName('class_name');
                var show_selete = document.getElementById('show_selete') ; 
                var show_list = document.getElementById('show_list') ; 
                var show_total_price = document.getElementById('show_total_price') ; 
                var list_selecte = [];
                var list_show_select = [] ; 
                var sum_price = 0 ; 
                
                for (var i = 0; i < alldata.length; i++) {
                    if (alldata[i].checked) {
                        var string = alldata[i].getAttribute('data-typename') ;
                        var data_price = parseFloat(alldata[i].getAttribute('data-price')) ; 
                        console.log(typeof(data_price))
                        list_selecte.push(alldata[i].id);
                        list_show_select.push( string  ) ; 
                        sum_price = sum_price + data_price ; 
                    }
                }
                show_selete.innerHTML = '' ; 
                list_show_select.forEach(element => {
                    show_selete.innerHTML += 
                        '<div class="row">' + 
                            '<div class="col-md-6">' + 
                                '<p>'+element+'</p>' + 
                            '</div>' + 
                        '</div>' ;
                });

                
       
            
                show_list.innerHTML = '<input type="hidden" name="list_select_jew_id" value="'+list_selecte+'">' ; 
                show_total_price.innerHTML = 'ราคารวม: ' +   sum_price + ' บาท' ; 
            }
        </script>



        <form action="{{route('admin.managesetjewelrysubmit')}}" method="POST">
            @csrf

        <div class="row mt-5">
            <div class="col-md-6">
                <label for="title" class="form-label">ชื่อเซต</label>
                <input type="text" name="set_name" id="set_name" class="form-control" placeholder="ระบุชื่อเซต">
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-6">
                <label for="" class="form-label">ราคาเช่าต่อเซต</label>
                <input type="number" name="set_price" id="set_price" class="form-control" min="1"
                    placeholder="ระบุราคาเช่า">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <h5>รายการที่เลือก</h5>
                <p class="mt-2">ประกอบด้วย</p>
            </div>
        </div>

        <div id="show_selete"> 
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                {{-- <p>ราคารวม : <span>1200 บาท</span></p> --}}
                <p><strong><span id="show_total_price"></span></strong></p>
            </div>
        </div>

        <div id="show_list">

        </div>

        

        <div class="row mt-5 ">
            <button type="submit" class="btn btn-success">บันทึกเซต</button>
        </div>
        

        </form>

      





    </div>
    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
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
