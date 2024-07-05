@extends('layouts.adminlayout')

@section('content')
    <div class="container">
        <h2>เพิ่มชุดใหม่</h2>

        @if (session('dressCodes'))
            <div class="alert alert-info">
                <strong>กรุณานำรหัสชุดเหล่านี้ไปติดไว้ กำกับกับชุดที่ท่านได้เพิ่ม:</strong>
                <ul>
                    @foreach (session('dressCodes') as $code)
                        <li>{{ $code }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        




        <form action="{{ route('dresses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="type_dress_id">ประเภทชุด</label>
                <select name="type_dress_id" id="type_dress_id" class="form-control">
                    @foreach($typeDresses as $typeDress)
                        <option value="{{ $typeDress->id }}">{{ $typeDress->type_dress_name }}</option>
                    @endforeach
                    <option value="select_other">อื่นๆ</option>
                </select>
            </div>

            
            
            <div class="col-12">
                <div style="display: none" id="showinputother">
                    <label class="form-label" for="inputother">อื่นๆโปรดระบุ</label>
                    <input type="text" name="inputother" id="inputother">
                </div>
            </div> {{-- script สำหรับ แสดงช่อง input --}}
            <script>
                var selectdresstype = document.getElementById('type_dress_id'); //เลือกประเภทชุด
                var showshowinputother = document.getElementById('showinputother'); //แสดงกล่องสำหรับinput เพิ่ทใหม่ 
                selectdresstype.addEventListener('click', function() {

                    if (selectdresstype.value == "select_other") {
                        showshowinputother.style.display = "block";
                    } else {
                        showshowinputother.style.display = "none";
                        value = '';
                    }
                });
            </script>

    
            <div class="form-group">
                <label for="dress_title_name">ชื่อชุด</label>
                <input type="text" name="dress_title_name" id="dress_title_name" class="form-control" required>
            </div>
    
            <div class="form-group">
                <label for="dress_price">ราคา</label>
                <input type="number" name="dress_price" id="dress_price" class="form-control" required>
            </div>
    
            <div class="form-group">
                <label for="dress_count">จำนวนชุด</label>
                <input type="number" name="dress_count" id="dress_count" class="form-control" required>
            </div>
           
    
            <div class="form-group">
                <label for="dress_description">รายละเอียด</label>
                <textarea name="dress_description" id="dress_description" class="form-control" required></textarea>
            </div>
    
            <button type="submit" class="btn btn-primary">เพิ่มชุดใหม่</button>
        </form>
        
        
    
    </div>
@endsection
