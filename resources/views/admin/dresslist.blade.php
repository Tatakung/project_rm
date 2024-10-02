@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-6">
        <table class="table">
            <thead>
                <tr>
                    <th>รูป</th>
                    <th>ปรเภทชุด</th>
                    <th>หมายเลขชุด</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($typedresss as $type)
                    @foreach ($type->dresses as $dress)
                        <tr>
                            <td>
                                @php
                                    $images = App\Models\Dressimage::where('dress_id',$dress->id)->value('dress_image') ; 
                                @endphp
                                <img src="{{ asset('storage/'  .$images)   }}" alt="" width="60px;">
                            </td>
                            <td>{{$type->type_dress_name}}</td>
                            <td>{{$dress->dress_code_new}}{{$dress->dress_code}}</td>
                            <td>
                                    <a href="{{ route('admin.dressdetail', ['id' => $dress->id , 'separable' => $dress->separable]) }}" class="btn btn-danger">รายละเอียด</a>
                                    <a href="{{route('admin.historydressadjust',['id' => $dress->id])}}" class="btn btn-danger">ประวัติการปรับแก้ชุด</a>
                                    <a href="{{route('admin.historydressrepair', ['id' => $dress->id])}}" class="btn btn-danger">ประวัติการซ่อมชุด</a>
                                    <a href="{{route('admin.historydressrent', ['id' => $dress->id])}}" class="btn btn-danger">ประวัติการเช่า</a>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
