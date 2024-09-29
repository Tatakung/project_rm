@extends('layouts.adminlayout')
@section('content')
    

    <div class="container mt-5">
        <h2>ประวัติการซ่อมชุด</h2>
        <p>{{$typedressname->type_dress_name}} {{$dress->dress_code_new}}{{$dress->dress_code}}</p>


        @if($history->count() > 0 )
        <table class="table">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รายละเอียด</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $item)
                <tr>
                    <td>{{$item->created_at}}</td>
                    <td>{{$item->repair_description}}</td>
                    <td>{{$item->repair_status}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center ; ">ไม่มีรายการประวัติการซ่อม</p>
        @endif







    </div>





@endsection
