@extends('layouts.adminlayout')

@section('content')
    {{-- <form action="" method="GET" class="flex items-center">
    <input type="text" name="search" placeholder="ค้นหา" class="border border-gray-300 p-2 rounded-lg">
    <button type="submit" class="ml-2 p-2 bg-blue-500 text-white rounded-lg">ค้นหา</button>
</form> --}}


    <form action="{{ route('admin.search') }}" method="GET" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="name">ชื่อ</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="ค้นหาชื่อ">
        </div>
        <div class="form-group">
            <label for="name">นามสกุล</label>
            <input type="text" class="form-control" id="lname" name="lname" placeholder="ค้นหานามสกุล">
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

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">

    <!-- FullCalendar CSS & JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

    <style>
        #calendar_dress {
            max-width: 900px;
            margin: 0 auto;
        }

        #calendar_shirt {
            max-width: 900px;
            margin: 50px auto;
            /* เพิ่มระยะห่างเพื่อป้องกันการทับซ้อน */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarElSkirt = document.getElementById('calendar_skirt');
            var calendarSkirt = new FullCalendar.Calendar(calendarElSkirt, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($date_reservations_skirt as $reservation)
                        {
                            @php
                                $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                $customer = App\Models\Customer::find($customer_id);
                            @endphp

                            title:
                                'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ:{{ $reservation->status }}',
                                start: '{{ $reservation->start_date }}',
                                end:
                                '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                color: '{{ $reservation->status == 'ถูกจอง' ? '#3788d8' : '#257e4a' }}'
                        },
                    @endforeach
                ],
                locale: 'th'
            });
            calendarSkirt.render();

            var calendarElShirt = document.getElementById('calendar_shirt');
            var calendarShirt = new FullCalendar.Calendar(calendarElShirt, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($date_reservations_shirt as $reservation)
                        {
                            @php
                                $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                $customer = App\Models\Customer::find($customer_id);
                            @endphp

                            title:
                                'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ:{{ $reservation->status }}',
                                start: '{{ $reservation->start_date }}',
                                end:
                                '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                color: '{{ $reservation->status == 'ถูกจอง' ? '#3788d8' : '#257e4a' }}'
                        },
                    @endforeach
                ],
                locale: 'th'
            });
            calendarShirt.render();
            var calendarElDress = document.getElementById('calendar_dress');
            var calendarDress = new FullCalendar.Calendar(calendarElDress, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($date_reservations_dress as $reservation)
                        {
                            @php
                                $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                $customer = App\Models\Customer::find($customer_id);
                            @endphp

                            title:
                                'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ:{{ $reservation->status }}',
                                start: '{{ $reservation->start_date }}',
                                end:
                                '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                color: '{{ $reservation->status == 'ถูกจอง' ? '#3788d8' : '#257e4a' }}'
                        },
                    @endforeach
                ],
                locale: 'th'
            });
            calendarDress.render();
        });
    </script>
@endsection
