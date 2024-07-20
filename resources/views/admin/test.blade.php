@extends('layouts.adminlayout')

@section('content')
    {{-- <form action="" method="GET" class="flex items-center">
    <input type="text" name="search" placeholder="ค้นหา" class="border border-gray-300 p-2 rounded-lg">
    <button type="submit" class="ml-2 p-2 bg-blue-500 text-white rounded-lg">ค้นหา</button>
</form> --}}


<form action="{{route('admin.search')}}" method="GET" class="mb-4">
    @csrf
    <div class="form-group">
        <label for="name">ชื่อ</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="ค้นหาชื่อ" >
    </div>
    <div class="form-group">
        <label for="name">นามสกุล</label>
        <input type="text" class="form-control" id="lname" name="lname" placeholder="ค้นหานามสกุล" >
    </div>
    <button type="submit" class="btn btn-primary">ค้นหา</button>
</form>


    <div class="container my-5">
        <h2 class="mb-4">รายการผู้ใช้</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>อีเมล</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->lname }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
