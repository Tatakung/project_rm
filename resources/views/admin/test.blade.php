<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/src/output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/src/hero.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
    <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>

    <link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>

    <title>Tunthree</title>




</head>

<body>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/a7046885ac.js" crossorigin="anonymous"></script>

    <div style="display: flex; background-color: #F5F3FF;">

        <section class="sticky bg-white rounded-2xl p-2" id="nav-content" style="height: 100vh; width: 180px; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; margin-left: 2%; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
            <div class="w-full lg:w-auto flex-grow lg:flex lg:flex-col bg-white lg:bg-transparent text-black">

                <div style="display: grid; place-items: center; margin-bottom: 30px;">
                    <img src="images/Logo.jpg" alt="Logo" style="width: 80px; height: auto; margin-bottom: -10px;">
                    <div class="text-black text-lg ">Tunthree</div>
                </div>



                <a class="inline-block py-2 px-3 text-gray-500 no-underline lg:flex lg:flex-col items-start justify-start mb-1 transition duration-300 ease-in-out hover:text-blue-700 hover:text-sm" href="" id="Users">
                    <div class="mr-2 text-base flex items-center">
                        <i class="fa-solid fa-user mr-2"></i>Users
                    </div>
                </a>

                <a class="inline-block py-2 px-3 text-gray-500 no-underline lg:flex lg:flex-col items-start justify-start mb-1 transition duration-300 ease-in-out hover:bg-transparent hover:text-blue-700 hover:text-sm" href="{{ route('emroom')}}">
                    <div class="mr-2 text-base flex items-center">
                        <i class="fa-solid fa-door-open mr-1"></i>Room
                    </div>
                </a>

                <a class="inline-block py-2 px-3 text-blue-500 no-underline lg:flex lg:flex-col items-start justify-start mb-1 transition duration-300 ease-in-out hover:bg-transparent hover:text-blue-700 hover:text-sm" href="={{ route('checkin') }}">
                    <div class="mr-2 text-base flex items-center ">
                        <i class="fa-solid fa-person-walking-luggage mr-1"></i>Check In
                    </div>
                </a>

                <a class="inline-block py-2 px-3 text-gray-500 no-underline lg:flex lg:flex-col items-start justify-start mb-1 transition duration-300 ease-in-out hover:bg-transparent hover:text-blue-700 hover:text-sm" href="{{ route('checkout') }}">
                    <div class="mr-2 text-base flex items-center">
                        <i class="fa-solid fa-person-walking-luggage fa-flip-horizontal mr-1"></i>Check Out
                    </div>
                </a>

                <a class="inline-block py-2 px-3 text-gray-500 no-underline lg:flex lg:flex-col items-start justify-start mb-1 transition duration-300 ease-in-out hover:bg-transparent hover:text-blue-700 hover:text-sm" href="{{ route('store') }}" id="store">
                    <div class="mr-2 text-base flex items-center">
                        <i class="fa-solid fa-house-circle-check mr-1"></i>Store
                    </div>
                </a>

                <a class="inline-block py-2 px-3 text-gray-500 no-underline lg:flex lg:flex-col items-start justify-start mb-1 transition duration-300 ease-in-out hover:bg-transparent hover:text-blue-700 hover:text-sm" href="{{ route('maintenanceroom') }}" id="maintenanceroom">
                    <div class="mr-2 text-base flex items-center">
                        <i class="fa-solid fa-tools mr-1"></i>Maintenance
                    </div>
                </a>

                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-block py-2 px-3 text-gray-500 no-underline lg:flex lg:flex-col items-start justify-start mb-6 transition duration-300 ease-in-out hover:bg-transparent hover:text-red-500 hover:text-sm" style="position: absolute; bottom: 10px;" id="Logout">
                    <div class="mr-2 text-base flex items-center">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i>Logout
                    </div>
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
            </div>
        </section>

        <section class="ml-10 bg-white" id="room-table" style="width:1100px; padding-left: 2.5%; padding-right: 2.5%; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
            <div class="max-w-screen-xl mx-auto py-10">
                <div class="px-2 p-2 flex justify-between items-center">
                    <h1 class="text-4xl mb-10 max-xl:px-4">Check-In</h1>
                    <input id="checkin-date" type="text" class="flatpickr input px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="เลือกวันที่เช็คอิน" data-default-date="today" />
                </div>
                <table class="w-full border-collapse bg-white rounded-lg overflow-hidden shadow-lg">
                    <thead>
                        <tr class="text-lg bg-gray-300">
                            <th class="px-4 py-2">ชื่อผู้จอง</th>
                            <th class="px-4 py-2">วันที่เช็คอิน</th>
                            <th class="px-4 py-2">รายละเอียด</th>
                            <th class="px-4 py-2">สถานะ</th>
                            <th class="px-4 py-2" style="padding-right: 5%;">เช็คอิน</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="booking-rows">
                        @if(isset($bookings) && $bookings->isNotEmpty())
                        @php
                        $groupedBookings = [];
                        foreach ($bookings as $booking) {
                        foreach ($booking->bookingDetails->where('room_id', NULL) as $detail) {
                        $groupedBookings[$detail->booking_id][] = $detail;
                        }
                        }
                        @endphp

                        @foreach($groupedBookings as $bookingId => $details)
                        <tr class="border-b border-gray-200 cursor-pointer" onclick="toggleDropdown('{{ $bookingId }}')">
                            <td class="px-4 py-2">
                                <span class="font-semibold">{{ $details[0]->booking_name }}</span>

                                @if(count($details) == 1 && $details[0]->extra_bed_count > 0)
                                <span class="text-red-500 text-sm ">มีเตียงเสริม</span>
                                @endif

                                @if(count($details) > 1)
                                <span class="text-blue-500 text-sm "> ({{ count($details) }} ห้อง)</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                {{ $details[0]->checkin_date }}
                            </td>
                            <td class="py-2 px-4">
                                <a href="{{ route('checkindetail', ['id' => $details[0]->booking->id]) }}" class="text-blue-500 hover:text-blue-700 transition duration-300">
                                    <button class="py-2 px-4 rounded-md hover:underline focus:outline-none focus:shadow-outline-blue active:text-blue-800" type="button">
                                        รายละเอียด
                                    </button>
                                </a>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <span class="w-2 h-2 me-1 bg-yellow-300 rounded-full mr-1"></span>
                                    {{ $details[0]->booking_detail_status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if(count($details) == 1 && $details[0]->booking_detail_status === 'รอเลือกห้อง')
                                <button onclick="showModal('{{ $details[0]->booking->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-300">
                                    เช็คอิน
                                </button>
                                @else
                                <button class="bg-gray-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed" disabled>
                                    เช็คอิน
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- หากมีมากกว่า 1 รายการจอง จะแสดง dropdown -->
                        @if(count($details) > 1)
                        <tr id="dropdown-{{ $bookingId }}" class="hidden">
                            <td colspan="5" class="bg-gray-100 p-4 border border-gray-300">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="px-4 py-2">ลำดับที่</th>
                                            <th class="px-4 py-2">วันที่เช็คอิน</th>
                                            <th class="px-4 py-2">สถานะ</th>
                                            <th class="px-4 py-2">เช็คอิน</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($details as $index => $detail)
                                        <tr class="text-center border-b border-gray-300">
                                            <td class="px-4 py-2 relative">
                                                <span class="inline-block">{{ $index + 1 }}</span>
                                                @if($detail->extra_bed_count > 0)
                                                <span class="absolute right-6 text-red-500 text-sm mt-0.5">มีเตียงเสริม</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">{{ $detail->checkin_date }}</td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <span class="w-2 h-2 me-1 bg-yellow-300 rounded-full mr-1"></span>
                                                    {{ $detail->booking_detail_status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                @if($detail->booking_detail_status === 'รอเลือกห้อง')
                                                <button onclick="showModal('{{ $detail->booking->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition duration-300">
                                                    เช็คอิน
                                                </button>
                                                @else
                                                <span class="text-gray-500">ไม่สามารถเช็คอินได้</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
        </section>

        <script>
            function toggleDropdown(bookingName) {
                const dropdown = document.getElementById('dropdown-' + bookingName);
                dropdown.classList.toggle('hidden');
            }

            function showModal(bookingId, checkinDate) {
                // ใส่ลอจิกเพื่อแสดงโมดัลที่นี่
                console.log(`Show modal for booking ID: ${bookingId}, Check-in Date: ${checkinDate}`);
            }
        </script>
        <div id="userInformationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex justify-center items-center">
            <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">กรอกข้อมูลผู้เข้าพัก</h2>
                <form id="check-in-form" action="{{ route('updateBookingDetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="booking_id" id="modal_booking_id">

                    <div id="guest-forms-container">

                        <div class="mb-4">
                            <label for="room_id" class="block mb-2">เลือกห้อง:</label>
                            <select name="room_id" id="room_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled selected>กรุณาเลือกห้องที่ว่าง</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id }}">ห้อง {{ $room->room_name }}</option>
                                @endforeach
                            </select>
                        </div>


                        {{-- แม่มัน --}}
                        <div id="text1" class="guest-form mb-4 border-b border-gray-300 pb-4">


                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium">ชื่อ:</label>
                                <input type="text" id="name" name="name_[]" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ชื่อ">
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="id_card" class="block text-sm font-medium">บัตรประชาชน:</label>
                                    <input type="text" id="id_card" name="id_card" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="บัตรประชาชน">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium">เบอร์โทร:</label>
                                    <input type="text" id="phone" name="phone" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เบอร์โทร">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium">ที่อยู่:</label>
                                <input type="text" id="address" name="address" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="บ้านเลขที่/หมู่บ้าน">
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="sub_district" class="block text-sm font-medium">ตำบล/แขวง:</label>
                                    <input id="sub_district" name="sub_district" type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ตำบล">
                                </div>
                                <div>
                                    <label for="district" class="block text-sm font-medium">อำเภอ/เขต:</label>
                                    <input id="district" name="district" type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="อำเภอ">
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium">จังหวัด:</label>
                                    <input id="province" name="province" type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="จังหวัด">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="postcode" class="block text-sm font-medium">รหัสไปรษณีย์:</label>
                                <input id="postcode" name="postcode" type="text" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="รหัสไปรษณีย์">
                            </div>



                            <div class="mb-4">
                                <label for="extra_bed_count" class="block text-sm font-medium">จำนวนเตียงเสริม:</label>
                                <input type="number" id="extra_bed_count" name="extra_bed_count" min="0" value="0" class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="จำนวนเตียงเสริม">
                            </div>






                        </div>

                        <button type="button" id="add_name" class="flex items-center gap-2 text-blue-500 hover:text-blue-700">
                            <i class="fas fa-plus-circle"></i> เพิ่มข้อมูลผู้เข้าพัก
                        </button>

                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 text-gray-600 hover:text-gray-800">ยกเลิก</button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">บันทึกข้อมูล</button>
                        </div>
                </form>
            </div>
        </div>

        <script>
            var show_text = document.getElementById('text');
            var add_name = document.getElementById('add_name');
            var count = 1;
            add_name.addEventListener('click', function() {
                count++;
                var create_div = document.createElement('div');
                create_div.id = 'text' + count;
                input =
                    '<hr>' +
                '<button class="btn btn-danger" type="button" onclick="removediv('+count+')">ลบ</button> ' +
                    '<div class="mb-4">' +
                    '<label for="name" class="block text-sm font-medium">ชื่อ:</label>' +
                    '<input type="text"  id="name"  name="name_['+count+']" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ชื่อ">' +
                    '</div>' +
                    '<div class="grid grid-cols-2 gap-4 mb-4">' +
                    '<div>' +
                    '<label for="id_card" class="block text-sm font-medium">บัตรประชาชน:</label>' +
                    '<input type="text" id="id_card"  name="id_card_['+count+']" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="บัตรประชาชน">' +
                    '</div>' +
                    '<div>' +
                    '<label for="phone" class="block text-sm font-medium">เบอร์โทร:</label>' +
                    '<input type="text" id="phone"  name="phone_['+count+']" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เบอร์โทร">' +
                    '</div>' +
                    '</div>' +
                    '<div class="mb-4">' +
                    '<label for="address" class="block text-sm font-medium">ที่อยู่:</label>' +
                    '<input type="text" id="address"  name="address_['+count+']" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="บ้านเลขที่/หมู่บ้าน">' +
                    '</div>' +
                    '<div class="grid grid-cols-3 gap-4 mb-4">' +
                    '<div>' +
                    '<label for="sub_district" class="block text-sm font-medium">ตำบล/แขวง:</label>' +
                    '<input id="sub_district"  name="sub_district_['+count+']" type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ตำบล">' +
                    '</div>' +
                    '<div>' +
                    '<label for="district" class="block text-sm font-medium">อำเภอ/เขต:</label>' +
                    '<input id="district"  name="district_['+count+']" type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="อำเภอ">' +
                    '</div>' +
                    '<div>' +
                    '<label for="province" class="block text-sm font-medium">จังหวัด:</label>' +
                    '<input id="province"  name="province_['+count+']" type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="จังหวัด">' +
                    '</div>' +
                    '</div>' +
                    '<div class="mb-4">' +
                    '<label for="postcode" class="block text-sm font-medium">รหัสไปรษณีย์:</label>' +
                    '<input id="postcode" name="postcode_['+count+']" type="text" required class="w-full px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="รหัสไปรษณีย์">' +
                    '</div>';

                create_div.innerHTML = input;
                show_text.appendChild(create_div);
            });

            function removediv(count) {
                var delete_div = document.getElementById('text' + count);
                delete_div.remove();
            }
        </script>

        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://kit.fontawesome.com/a7046885ac.js" crossorigin="anonymous"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr("#checkin-date", {
                    dateFormat: "Y-m-d",
                    defaultDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        filterBookings(dateStr);
                    }
                });

                function filterBookings(date) {
                    const rows = document.querySelectorAll('.booking-row');
                    let visibleRows = 0;

                    rows.forEach(row => {
                        if (row.dataset.checkinDate === date) {
                            row.style.display = '';
                            visibleRows++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const noBookingsMessage = document.getElementById('no-bookings-message');
                    if (visibleRows === 0) {
                        noBookingsMessage.classList.remove('hidden');
                    } else {
                        noBookingsMessage.classList.add('hidden');
                    }
                }

                // Initial filtering
                filterBookings(flatpickr.formatDate(new Date(), "Y-m-d"));
            });

            function showModal(bookingId) {
                document.getElementById('modal_booking_id').value = bookingId;
                document.getElementById('userInformationModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('userInformationModal').classList.add('hidden');
                document.getElementById('check-in-form').reset();
            }

            $.Thailand({
                database: 'https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/database/db.json', // เพิ่มลิงก์ไปยัง database
                $district: $("#sub_district"), // input ของตำบล
                $amphoe: $("#district"), // input ของอำเภอ
                $province: $("#province"), // input ของจังหวัด
                $zipcode: $("#postcode") // input ของรหัสไปรษณีย์
            });
        </script>


</body>

</html>