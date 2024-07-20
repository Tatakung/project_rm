@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <!-- เริ่มต้นแถบค้นหา -->
        <div class="mb-4 p-3" style="background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.searchstatusdress') }}" method="GET" id="searchForm">
                @csrf
                <div class="form-group">
                    <label for="search_status_of_dress" class="form-label">เลือกสถานะชุด</label>
                    <select name="search_status_of_dress" id="search_status_of_dress" class="form-select">
                        <option value="ทั้งหมด" {{ $status == 'ทั้งหมด' ? 'selected' : '' }}>ทั้งหมด</option>
                        <option value="พร้อมให้เช่า" {{ $status == 'พร้อมให้เช่า' ? 'selected' : '' }}>พร้อมให้เช่า</option>
                        <option value="ถูกจองแล้ว" {{ $status == 'ถูกจองแล้ว' ? 'selected' : '' }}>ถูกจองแล้ว</option>
                    </select>
                </div>
                <input type="hidden" name="type_dress_id" value="{{ $type_dress_id }}">
            </form>
        </div>
        <!-- สิ้นสุดแถบค้นหา -->

        <div class="row">
            @foreach ($data as $index => $item)
                <div class="col-md-3 mb-4">
                    <a href="{{ route('admin.dressdetail', ['id' => $item->id , 'separable' => $item->separable]) }}">
                        {{-- <input type="text" value="{{$item->separable}}"> --}}
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="card-title">รหัสชุด&nbsp;{{ $item->dress_code_new }}{{ $item->dress_code }}</p>
                                @if ($item->dressimages->isNotEmpty())
                                    <img src="{{ asset('storage/' . $item->dressimages->first()->dress_image) }}"
                                        alt="" style="max-height: 300px; width: auto; margin: auto; display: block;">
                                @endif
                                <p>สถานะชุด: {{$item->dress_status}}</p>
                                @if($item->separable == 1)
                                <p><i class="bi bi-x-circle-fill text-danger"></i> ไม่สามารถเช่าแยกได้</p>
                                @elseif($item->separable == 2)
                                <p><i class="bi bi-check-circle-fill text-success"></i> สามารถเช่าแยกได้</p>
                                @endif
                            </div>
                        </div>
                    {{-- </a> --}}
                </div>
                @if (($index + 1) % 4 == 0)
        </div>
        <div class="row mt-4">
            @endif
            @endforeach
        </div>
    </div>

    <script>
        document.getElementById('search_status_of_dress').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    </script>
@endsection
