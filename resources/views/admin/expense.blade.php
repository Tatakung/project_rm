@extends('layouts.adminlayout')

@section('content')
    <div class="container-fluid py-4">
        <!-- บันทึกรายจ่าย -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2"></i>บันทึกรายจ่าย
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.saveexpense') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- แถวที่ 1: วันที่และจำนวนเงิน -->
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">วันที่ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" value="{{ $today }}" id="date"
                                name="expense_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">จำนวนเงิน <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="amount" name="expense_value"
                                    min="1" step="0.01" required>
                                <span class="input-group-text">บาท</span>
                            </div>
                        </div>

                        <!-- แถวที่ 2: ประเภทรายจ่ายและช่องสำหรับอื่นๆ -->
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">ประเภทรายจ่าย <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="category" name="expense_type" required>
                                <option value="">เลือกประเภทรายจ่าย</option>
                                <option value="ค่าผ้า">ค่าผ้า</option>
                                <option value="ค่าน้ำ">ค่าน้ำ</option>
                                <option value="ค่าไฟ">ค่าไฟ</option>
                                <option value="ค่าซ่อมบำรุง">ค่าซ่อมบำรุง</option>
                                <option value="ค่าซักรีด">ค่าซักรีด</option>
                                {{-- <option value="ค่าวัสดุสิ้นเปลือง">ค่าวัสดุสิ้นเปลือง</option> --}}
                                <option value="other_expense">อื่นๆ</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div id="otherCategoryGroup" style="display: none;">
                                <label for="other_category" class="form-label">ระบุประเภทรายจ่ายอื่นๆ <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="other_category" name="other_expense_type">
                            </div>
                        </div>

                        <!-- แถวที่ 3: รายละเอียด -->
                        {{-- <div class="col-12 mb-3">
                        <label for="description" class="form-label">รายละเอียดเพิ่มเติม</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div> --}}

                        <!-- แถวที่ 4: ปุ่มบันทึก -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>บันทึกรายจ่าย
                            </button>
                            {{-- <button type="reset" class="btn btn-secondary ms-2">
                            <i class="fas fa-redo me-2"></i>ล้างข้อมูล
                        </button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // แสดง/ซ่อนช่องกรอกประเภทอื่นๆ
            document.getElementById('category').addEventListener('change', function() {
                var otherCategoryGroup = document.getElementById('otherCategoryGroup');
                var otherCategoryInput = document.getElementById('other_category');

                if (this.value === 'other_expense') {
                    otherCategoryGroup.style.display = 'block';
                    otherCategoryInput.required = true;
                } else {
                    otherCategoryGroup.style.display = 'none';
                    otherCategoryInput.required = false;
                    otherCategoryInput.value = '';
                }
            });
        </script>

        @php
            $value_month = 0;
            $value_year = 2025;
        @endphp

        <!-- ตารางแสดงรายการ -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>ประวัติรายจ่าย
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">


                    {{-- Filter Section --}}

                    <div class="container">

                        <form action="{{ route('expensefilter') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <select class="form-control rounded-lg border" id="month" name="month" required>
                                        <option value="0" {{ $month == 0 ? 'selected' : '' }}>ทุกเดือน</option>
                                        <option value="1" {{ $month == 1 ? 'selected' : '' }}>มกราคม</option>
                                        <option value="2" {{ $month == 2 ? 'selected' : '' }}>กุมภาพันธ์</option>
                                        <option value="3" {{ $month == 3 ? 'selected' : '' }}>มีนาคม</option>
                                        <option value="4" {{ $month == 4 ? 'selected' : '' }}>เมษายน</option>
                                        <option value="5" {{ $month == 5 ? 'selected' : '' }}>พฤษภาคม</option>
                                        <option value="6" {{ $month == 6 ? 'selected' : '' }}>มิถุนายน</option>
                                        <option value="7" {{ $month == 7 ? 'selected' : '' }}>กรกฎาคม</option>
                                        <option value="8" {{ $month == 8 ? 'selected' : '' }}>สิงหาคม</option>
                                        <option value="9" {{ $month == 9 ? 'selected' : '' }}>กันยายน</option>
                                        <option value="10" {{ $month == 10 ? 'selected' : '' }}>ตุลาคม</option>
                                        <option value="11" {{ $month == 11 ? 'selected' : '' }}>พฤศจิกายน</option>
                                        <option value="12" {{ $month == 12 ? 'selected' : '' }}>ธันวาคม</option>
                                    </select>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <select class="form-control rounded-lg border" id="year" name="year" required>
                                        <option value="0" {{ $year == 0 ? 'selected' : '' }}>ทุกปี</option>
                                        @for ($i = 2023; $i <= now()->year; $i++)
                                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                                {{ $i + 543 }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <button type="submit" class="btn w-100 rounded-lg"
                                        style="background-color: #EBF5FF; color: #1E40AF;">
                                        <i class="bi bi-search"></i> ค้นหา
                                    </button>
                                </div>
                            </div>
                        </form>



                        {{-- Summary Section --}}
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <div class="rounded-lg p-4" style="background-color: #EBF5FF;">
                                    <div class="text-muted mb-1" style="font-size: 0.875rem;">รายจ่ายรวมทั้งหมด</div>
                                    <div class="fw-bold" style="font-size: 1.5rem; color: #1E40AF;">
                                        {{ number_format($expense->sum('expense_value'), 2) }} บาท
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <div class="rounded-lg p-4" style="background-color: #F5F3FF;">
                                    <div class="text-muted mb-1" style="font-size: 0.875rem;">รายการที่บันทึก</div>
                                    <div class="fw-bold" style="font-size: 1.5rem; color: #5B21B6;">
                                        {{ $expense->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        .rounded-lg {
                            border-radius: 0.5rem;
                        }

                        .form-control {
                            padding: 0.5rem 1rem;
                            border-color: #E5E7EB;
                        }

                        .form-control:focus {
                            border-color: #3B82F6;
                            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
                        }

                        .btn {
                            padding: 0.5rem 1rem;
                            transition: all 0.2s;
                        }

                        .btn:hover {
                            opacity: 0.9;
                        }
                    </style>



                    @if ($dataexpense->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr style="text-align: center ; ">
                                    <th>วันที่</th>
                                    <th>ประเภท</th>
                                    <th class="text-end">จำนวนเงิน</th>
                                    <th>ผู้บันทึก</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($dataexpense as $item)
                                    <tr style="text-align: center ; ">
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($item->date)->year + 543 }}
                                        </td>
                                        <td>{{ $item->expense_type }}</td>
                                        <td class="text-end">{{ number_format($item->expense_value, 2) }} บาท</td>
                                        <td>
                                            @if ($item->expense_many_to_one_user->is_admin == 1)
                                                เจ้าของร้าน
                                            @else
                                                คุณ{{ $item->expense_many_to_one_user->name }}
                                                {{ $item->expense_many_to_one_user->lname }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">


                                                


                                                
                                                <form action="{{route('expensedelete',['id' => $item->id])}}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('ต้องการลบรายการนี้ใช่หรือไม่?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>



                                        <div class="modal fade" id="editExpenseModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="editExpenseModalLabel" aria-hidden="true"
                                            data-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editExpenseModalLabel">แก้ไขรายจ่าย
                                                            (ยังไม่ได้ทำ)
                                                        </h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="editExpenseForm" method="POST"
                                                            action="{{ route('expenseeditupdate') }}">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="edit_date" class="form-label">วันที่</label>
                                                                <input type="date" class="form-control" id="edit_date"
                                                                    name="date" required value="{{ $item->date }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_type" class="form-label">ประเภท</label>
                                                                <select class="form-control" id="edit_type"
                                                                    name="edit_type" required>
                                                                    <option value="ค่าผ้า"
                                                                        {{ $item->expense_type == 'ค่าผ้า' ? 'selected' : '' }}>
                                                                        ค่าผ้า</option>
                                                                    <option value="ค่าน้ำ"
                                                                        {{ $item->expense_type == 'ค่าน้ำ' ? 'selected' : '' }}>
                                                                        ค่าน้ำ</option>
                                                                    <option value="ค่าไฟ"
                                                                        {{ $item->expense_type == 'ค่าไฟ' ? 'selected' : '' }}>
                                                                        ค่าไฟ</option>
                                                                    <option value="ค่าซ่อมบำรุง"
                                                                        {{ $item->expense_type == 'ค่าซ่อมบำรุง' ? 'selected' : '' }}>
                                                                        ค่าซ่อมบำรุง</option>
                                                                    <option value="ค่าซักรีด"
                                                                        {{ $item->expense_type == 'ค่าซักรีด' ? 'selected' : '' }}>
                                                                        ค่าซักรีด</option>
                                                                    {{-- <option value="ค่าวัสดุสิ้นเปลือง">ค่าวัสดุสิ้นเปลือง</option> --}}
                                                                    <option value="other_expense"
                                                                        @if (!in_array($item->expense_type, ['ค่าผ้า', 'ค่าน้ำ', 'ค่าไฟ', 'ค่าซ่อมบำรุง', 'ค่าซักรีด'])) selected @endif>
                                                                        อื่นๆ</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3" id="div_show_type_other_two" style="display: none ; ">
                                                                <label class="form-label">ระบุประเภทรายจ่ายอื่นๆ ใหม่ <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="edit_type_other" id="edit_type_other"
                                                                    value="">

                                                            </div>
                                                            <div class="mb-3" id="div_show_type_other"
                                                                @if (!in_array($item->expense_type, ['ค่าผ้า', 'ค่าน้ำ', 'ค่าไฟ', 'ค่าซ่อมบำรุง', 'ค่าซักรีด'])) style="display: block ; "
                                                                @else
                                                                    style="display: none ; " @endif>
                                                                <label class="form-label">ระบุประเภทรายจ่ายอื่นๆ <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="edit_type_other" id="edit_type_other"
                                                                    value="{{ $item->expense_type }}"
                                                                    @if (!in_array($item->expense_type, ['ค่าผ้า', 'ค่าน้ำ', 'ค่าไฟ', 'ค่าซ่อมบำรุง', 'ค่าซักรีด'])) required @endif>

                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_amount"
                                                                    class="form-label">จำนวนเงิน</label>
                                                                <input type="number" class="form-control"
                                                                    id="edit_amount" name="amount" step="0.01"
                                                                    value="{{ $item->expense_value }}" required
                                                                    min="0">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">ยกเลิก</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">บันทึก</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // ดึง select ทุกตัวที่มี id = edit_type
                                                var editTypeElements = document.querySelectorAll('[id^="edit_type"]');
                                        
                                                editTypeElements.forEach(function(editType) {
                                                    editType.addEventListener('change', function() {
                                                        // หาว่า div_show_type_other และ div_show_type_other_two ที่อยู่ใน modal ของแถวนี้คืออะไร
                                                        var row = editType.closest('.modal-body');
                                                        var div_show_type_other_two = row.querySelector('[id^="div_show_type_other_two"]');
                                        
                                                        if (editType.value == 'other_expense') {
                                                            div_show_type_other_two.style.display = 'block';
                                                        } else {
                                                            div_show_type_other_two.style.display = 'none';
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                        







                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                    @endif

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $dataexpense->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
