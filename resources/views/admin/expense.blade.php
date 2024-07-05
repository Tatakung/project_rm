@extends('layouts.adminlayout')

@section('content')
<main class="container mt-4">
    <h2 class="text-center mb-4">บัญชีค่าใช้จ่ายที่เกี่ยวข้องกับธุรกิจ</h2>

    <form action="{{ route('admin.saveexpense') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="expense_date">วันที่</label>
                <input type="date" name="expense_date" id="expense_date" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="expense_type">ประเภทค่าใช้จ่าย</label>
                <input type="text" name="expense_type" id="expense_type" class="form-control" placeholder="ประเภทค่าใช้จ่าย">
            </div>
            <div class="form-group col-md-4">
                <label for="expense_value">ราคา</label>
                <input type="number" name="expense_value" id="expense_value" class="form-control" placeholder="ราคา" step="0.01">
            </div>
        </div>
        <button class="btn btn-danger" type="submit">บันทึกค่าใช้จ่าย</button>
    </form>

    <div class="table-responsive mt-4" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">วันที่</th>
                    <th scope="col">ประเภทค่าใช้จ่าย</th>
                    <th scope="col">ราคา</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataexpense as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->date)->locale('th')->isoFormat('D MMMM ') }}{{ \Carbon\Carbon::parse($item->date)->addYears(543)->format('Y') }}</td>
                        <td>{{ $item->expense_type }}</td>
                        <td>{{ $item->expense_value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>



    
@endsection
