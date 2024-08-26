@extends('layouts.adminlayout')

@section('content')
    <ol class="breadcrumb" style="background: white ; ">
        <li class="breadcrumb-item active">ค่าใช้จ่าย</li>
    </ol>

    





    <main class="container mt-4">
        <h2 class="text-center mb-4">บัญชีค่าใช้จ่ายที่เกี่ยวข้องกับธุรกิจ</h2>

        <form action="{{ route('admin.saveexpense') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="expense_date">วันที่</label>
                    @php
                        $now = date('Y-m-d');
                        $today = \Carbon\Carbon::today()->toDateString();
                    @endphp
                    <input type="date" name="expense_date" id="expense_date" class="form-control" required
                        value="{{ $now }}" min="{{ $today }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="expense_type">ประเภทค่าใช้จ่าย</label>
                    <select name="expense_type" id="expense_type" class="form-control" required>
                        <option value="" disabled selected>เลือกรายการ</option>
                        <option value="ค่าน้ำ">ค่าน้ำ</option>
                        <option value="ค่าไฟ">ค่าไฟ</option>
                        <option value="ค่าซัก">ค่าซัก</option>
                        <option value="other_expense">อื่นๆ</option>
                    </select>
                </div>
                <div class="form-group col-md-3" id="show_aira_other" style="display: none ; ">
                    <label for="expense_value">ประเภทค่าใช้จ่ายอื่นๆ</label>
                    <input type="text" name="other_expense_type" id="other_expense_type" class="form-control"
                        placeholder="ระบุประเภทค่าใช้จ่าย">
                </div>


                <script>
                    var expense_type = document.getElementById('expense_type');
                    var show_aira_other = document.getElementById('show_aira_other');
                    var other_expense_type = document.getElementById('other_expense_type');

                    expense_type.addEventListener('change', function() {
                        if (expense_type.value === "other_expense") {
                            show_aira_other.style.display = 'block';
                            other_expense_type.setAttribute('required', 'required');
                        } else {
                            show_aira_other.style.display = 'none';
                            other_expense_type.removeAttribute('required');
                            other_expense_type.value = '';
                        }

                    });
                </script>






                <div class="form-group col-md-3">
                    <label for="expense_value">ราคา</label>
                    <input type="number" name="expense_value" id="expense_value" class="form-control" placeholder="ราคา"
                        step="0.01" min="0" required>
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
                            <td>{{ \Carbon\Carbon::parse($item->date)->locale('th')->isoFormat('D MMM ') }}{{ \Carbon\Carbon::parse($item->date)->addYears(543)->format('Y') }}
                            </td>
                            <td>{{ $item->expense_type }}</td>
                            <td>{{ $item->expense_value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
