{{-- @extends('layouts.adminlayout')
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h5>ข้อมูลเซต</h5>
            <p>ชื่อเซต : {{$jewelryset->set_name}}</p>
<p>ราคาเช่า : {{$jewelryset->set_price}}</p>
</div>
<div class="col-md-6">
    <h5>รายการในเซต</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รายการ</th>
                <th>ราคาเช่า</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Jewelrysetitem as $item)
            <tr>
                <td>
                    {{$item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name}} {{$item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter}}{{$item->jewitem_m_to_o_jew->jewelry_code}}
                </td>
                <td>{{$item->jewitem_m_to_o_jew->jewelry_price}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
@endsection --}}

@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    {{-- ส่วนหัวข้อ --}}
    <div class="row mb-3">
        <div class="col">  
            <h5>รายละเอียดเซตเครื่องประดับ</h5>
        </div>
        
            <a href="{{route('showjewsetrentedhistory',['id' => $jewelryset->id])}}" class="btn btn-primary" style="margin-right: 15px;">
                        <i class="bi bi-clock-history"></i> ประวัติการเช่า
                    </a>
    </div>

    {{-- ส่วนข้อมูลพื้นฐานของเซต --}}
    <div class="card mb-4 shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <p class="text-muted mb-1">ชื่อเซต</p>
                        <p class="h6">{{ $jewelryset->set_name }}</p>
                    </div>
                </div>
                <div class="row col-6">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <p class="text-muted mb-1">ราคาเช่ารวม</p>
                            <p class="h6">{{ number_format($jewelryset->set_price, 2) }} บาท</p>
                        </div>
                    </div>


                </div>
                {{-- <div class="col-md-6">
                    <div class="mb-2">
                        <p class="text-muted mb-1">รหัสเซต</p>
                        <p class="h6">SET{{ $jewelryset->id }}</p>
                    </div>
                </div> --}}

 
                   
  

            </div>
        </div>
    </div>

    {{-- ส่วนตารางรายการเครื่องประดับ --}}
    <div class="card shadow">
        <div class="card-body">
            <h5 class="card-title mb-4">รายการเครื่องประดับในเซต</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อรายการ</th>
                            <th>ราคาเช่า</th>
                            <th>สถานะ</th>
                            <th width="80px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($Jewelrysetitem as $item)
                        <tr>
                            <td>
                                <div class="row col">
                                    {{-- ชื่อประเภทเครื่องประดับ + รหัส --}}
                                    <span class="d-block">
                                        {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }} {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                    </span>
                                    
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium ">
                                    {{ number_format($item->jewitem_m_to_o_jew->jewelry_price, 2) }} บาท
                                </span>
                            </td>
                            <td>
                                {{-- สถานะการเช่า (รอการพัฒนา) --}}
                                <span style="color: #EBAF00;">รอดำเนินการ</span>
                            </td>
                            <td class="text-center col-4">
                                <a href="{{route('admin.jewelrydetail',['id' => $item->jewelry_id ])}}"
                                    data-bs-toggle="tooltip"
                                    title="ดูรายละเอียด">
                                    <button class="btn btn-secondary">ดูรายละเอียด</button>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                ไม่พบรายการเครื่องประดับในเซตนี้
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="ml-2"
    @if($is_admin==1 )
    style="display: block ; "
    @elseif($is_admin==0 )
    style="display: none ; "
    @endif>










    @endsection