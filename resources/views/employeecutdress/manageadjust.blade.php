@extends('layouts.adminlayout')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4" style="text-align: center;">รายละเอียดการปรับแก้ไขตัดชุด</h2>
        
        <div class="card mb-4">
            <div class="card-header">
                ข้อมูลการวัดตัวของลูกค้า (นิ้ว)
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($dress_adjusts as $item)
                    <div class="col-md-3 mb-3">
                        <label for="chest">{{$item->name}}:</label>
                        <input type="number" class="form-control" id="chest" value="{{$item->new_size}}"  step="0.1" required>
                    </div>
                    @endforeach
                
                </div>
                
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                รายละเอียดการแก้ไข/หรือเพิ่มเติม
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="edit_details">รายละเอียดที่ต้องแก้ไข:</label>
                    <textarea class="form-control" id="edit_details" name="edit_details" rows="4" required></textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                รายการเพิ่มเติมพิเศษ
            </div>
            <div class="card-body">
                <div id="special-items">
                    <!-- JavaScript จะเพิ่ม input fields ที่นี่ -->
                    <div class="row mb-2 special-item">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="special_items[${specialItemCount}][description]" placeholder="รายละเอียด" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control special-item-cost" name="special_items[${specialItemCount}][cost]" placeholder="ราคา (บาท)" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-special-item">ลบ</button>
                        </div>
                    </div>









                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-special-item">เพิ่มรายการพิเศษ</button>
            </div>
        </div>

        <div class="form-group mb-4">
            <label for="new_delivery_date">วันที่นัดส่งมอบใหม่:</label>
            <input type="date" class="form-control" id="new_delivery_date" name="new_delivery_date" required>
        </div>

        <div class="form-group mb-4">
            <label for="total_additional_cost">ค่าใช้จ่ายเพิ่มเติมรวม (บาท):</label>
            <input type="number" class="form-control" id="total_additional_cost" name="total_additional_cost" readonly>
        </div>

        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        <a href="" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
<script>
    let specialItemCount = 0;

    function addSpecialItem() {
        specialItemCount++;
        const newItem = `
            <div class="row mb-2 special-item">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="special_items[${specialItemCount}][description]" placeholder="รายละเอียด" required>
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control special-item-cost" name="special_items[${specialItemCount}][cost]" placeholder="ราคา (บาท)" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-special-item">ลบ</button>
                </div>
            </div>
        `;
        $('#special-items').append(newItem);
    }

    $(document).ready(function() {
        $('#add-special-item').click(addSpecialItem);

        $(document).on('click', '.remove-special-item', function() {
            $(this).closest('.special-item').remove();
            updateTotalCost();
        });

        $(document).on('input', '.special-item-cost', updateTotalCost);

        function updateTotalCost() {
            let total = 0;
            $('.special-item-cost').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total_additional_cost').val(total.toFixed(2));
        }
    });
</script>
@endsection
