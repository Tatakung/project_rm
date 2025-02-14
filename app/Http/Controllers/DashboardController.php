<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Orderdetail;
use App\Models\Decoration;
use App\Models\Orderdetailstatus;
use App\Models\Reservation;
use App\Models\AdditionalChange;
use App\Models\Reservationfilterdress;
use App\Models\Reservationfilters;
use App\Models\Typedress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function dashboardcutdress()
    {

        // $orderdetail = Orderdetail::where('type_order', 2)
        //     ->get();
        // $list_count_type = []; 

        // foreach ($orderdetail as $item) {
        //     if (!isset($list_count_type[$item->type_dress])) {
        //         $list_count_type[$item->type_dress] = 1;
        //     } else {
        //         $list_count_type[$item->type_dress]++;
        //     }
        // }
        // dd($list_count_type) ; 
        // // มันก็จะแบ่ง เป็น as จะประกอบด้วย key และ value 
        // $list_type = array_keys($list_count_type);
        // $list_count_type_values = array_values($list_count_type);




        $list_combined = [
            [1, 5000, 4500, 2024],
            [2, 4100, 4800, 2024],
            [3, 6900, 4700, 2024]
        ];





        $list_combined = [];
        $data_orderdetail = Orderdetail::get();

        foreach ($data_orderdetail as $items) {
            $month = $items->updated_at->month;
            $year = $items->updated_at->year;
            $price = $items->price;

            // ใช้ array_column() เพื่อดึงค่า "เดือน" จาก $list_combined
            $column_months = array_column($list_combined, 0);
            $column_years = array_column($list_combined, 3);

            if (in_array($month, $column_months) && in_array($year, $column_years)) {
                // หา key ของเดือนใน $list_combined
                $key = array_search($month, $column_months);
                $list_combined[$key][1] += $price;
            } else {
                // ถ้าไม่มีเดือนนี้ใน list_combined ให้เพิ่มใหม่

                $list_combined[] = [$month, $price, 0, $year];
            }
        }

        foreach ($list_combined as $key => $item) {
            if ($item[0] == 1) {
                $list_combined[$key][0] = 'มกราคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 2) {
                $list_combined[$key][0] = 'กุมภาพันธ์ ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 3) {
                $list_combined[$key][0] = 'มีนาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 4) {
                $list_combined[$key][0] = 'เมษายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 5) {
                $list_combined[$key][0] = 'พฤษภาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 6) {
                $list_combined[$key][0] = 'มิถุนายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 7) {
                $list_combined[$key][0] = 'กรกฎาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 8) {
                $list_combined[$key][0] = 'สิงหาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 9) {
                $list_combined[$key][0] = 'กันยายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 10) {
                $list_combined[$key][0] = 'ตุลาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 11) {
                $list_combined[$key][0] = 'พฤศจิกายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 12) {
                $list_combined[$key][0] = 'ธันวาคม ' . $list_combined[$key][3] + 543;
            }
        }

        // ใช้ array_map() เพื่อแยกข้อมูลเป็น 3 ลิสต์
        $list_one = array_map(function ($item) {
            return $item[0];
        }, $list_combined);

        $list_two = array_map(function ($item) {
            return $item[1];
        }, $list_combined);

        $list_three = array_map(function ($item) {
            return $item[2];
        }, $list_combined);


        $labels = ["มกราคม", "กุมภาพันธ์", "มีนาคม"];
        $incomeData = [5000, 7000, 6000];
        $expenseData = [3000, 4500, 3500];
        $expenseDatas = [3000, 4500, 3500];

        return view('employeecutdress.dashboardcut', compact('labels', 'incomeData', 'expenseData', 'expenseDatas', 'list_one', 'list_two', 'list_three'));
    }








    public function testdashboard()
    {


        $visitors = Expense::select("date", "expense_value", "expense_value")->get();

        $result[] = ['วันที่', 'รายรับ', 'รายจ่าย'];

        foreach ($visitors as $key => $value) {

            $result[++$key] = [$value->date, (int)$value->expense_value, (int)$value->expense_value];
        }
        $monthlySales = [
            'มกราคม' => ['product1' => 500, 'product2' => 350],
            'กุมภาพันธ์' => ['product1' => 400, 'product2' => 250],
            'มีนายน' => ['product1' => 300, 'product2' => 250],
            'Apr' => ['product1' => 750, 'product2' => 500],
            'Mai' => ['product1' => 500, 'product2' => 400],
            'Jun' => ['product1' => 350, 'product2' => 300],
            'Jul' => ['product1' => 300, 'product2' => 250],
            'Aug' => ['product1' => 400, 'product2' => 350],
            'Sep' => ['product1' => 550, 'product2' => 350],
            'Oct' => ['product1' => 600, 'product2' => 400],
            'Nov' => ['product1' => 750, 'product2' => 600],
            'Dec' => ['product1' => 850, 'product2' => 650],
        ];
        return view('employee.testdashboard', compact('result', 'monthlySales'));
    }





    public function jewelrydashboard()
    {
        return view('employeerentjewelry.dashboardjewelry');
    }

    public function dashboard()
    {
        $value_month = now()->month;
        $value_year = now()->year;

        //จำนวนรายการ

        $amount_orderdetail = Orderdetail::whereNot('status_detail', 'อยู่ในตะกร้า');

        if ($value_month != 0) {
            $amount_orderdetail->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $amount_orderdetail->whereYear('updated_at', $value_year);
        }
        $amount_orderdetail = $amount_orderdetail->get();
        $amount_success = $amount_orderdetail->count();
        // foreach ($amount_orderdetail as $amount) {
        //     if ($amount->status_detail == 'ยกเลิกโดยทางร้าน' || $amount->status_detail == 'ยกเลิกโดยลูกค้า') {
        //     }
        //     else {
        //         $amount_success += 1;
        //     }
        // }


        //เงินประกันที่ยังไม่ได้คืน
        $damage_insurance = Orderdetail::query();

        if ($value_month != 0) {
            $damage_insurance->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $damage_insurance->whereYear('updated_at', $value_year);
        }
        $damage_insurance = $damage_insurance->get();
        $damage_insurance_success = 0;
        foreach ($damage_insurance as $damage) {
            if ($damage->status_payment == 2) {
                if ($damage->status_detail == 'กำลังเช่า' || $damage->status_detail == 'ถูกจอง') {
                    $damage_insurance_success += $damage->damage_insurance;
                }
            }
        }

        //รายจ่ายรวม
        $expense_success = Expense::query();

        if ($value_month != 0) {
            $expense_success->whereMonth('date', $value_month);
        }
        if ($value_year != 0) {
            $expense_success->whereYear('date', $value_year);
        }
        $expense_success = $expense_success->sum('expense_value');



        //รายได้รวม
        $income = Orderdetail::query();
        if ($value_month != 0) {
            $income->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $income->whereYear('updated_at', $value_year);
        }
        $income = $income->get();


        $income_success = 0;
        foreach ($income as $value) {
            if ($value->type_order == 1) {
                // ตัดชุด
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'รอดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'เริ่มดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                    $income_success += $value->price + $decoration;

                    if ($value->status_detail == 'รอดำเนินการตัด') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'เริ่มดำเนินการตัด') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุด') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    }
                }
            } elseif ($value->type_order == 2) {
                // เช่าชุด
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    if ($value->status_detail == 'กำลังเช่า') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $value->id)->sum('amount');
                        $income_success += $value->price + $additional;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                }
            } elseif ($value->type_order == 3) {
                // เช่าเครื่องประดับ
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    if ($value->status_detail == 'กำลังเช่า') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'คืนเครื่องประดับแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $value->id)->sum('amount');
                        $income_success += $value->price + $additional;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                }
            } elseif ($value->type_order == 4) {
                // เช่าตัดชุด
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'รอดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'เริ่มดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    if ($value->status_detail == 'กำลังเช่า') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $value->damage_insurance + $decoration;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $value->id)->sum('amount');
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $additional + $decoration;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                }
            }
        }


        // สัดส่วนรายได้แต่ละบริการ


        // เช่าชุด 
        $rent_dress_pie = Orderdetail::where('type_order', 2)
            ->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $rent_dress_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $rent_dress_pie->whereYear('updated_at', $value_year);
        }
        $rent_dress_pie = $rent_dress_pie->get();
        $rent_dress_pie_count = $rent_dress_pie->count();
        $rent_dress_pie_success = 0;
        foreach ($rent_dress_pie as $rent_dress_value) {
            if ($rent_dress_value->status_payment == 1) {
                if ($rent_dress_value->status_detail == 'ถูกจอง') {
                    $rent_dress_pie_success += $rent_dress_value->deposit;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_dress_pie_success += 0;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_dress_pie_success += $rent_dress_value->deposit;
                }
            } elseif ($rent_dress_value->status_payment == 2) {
                if ($rent_dress_value->status_detail == 'กำลังเช่า') {
                    $rent_dress_pie_success += $rent_dress_value->price + $rent_dress_value->damage_insurance;
                } elseif ($rent_dress_value->status_detail == 'ถูกจอง') {
                    $rent_dress_pie_success += $rent_dress_value->price + $rent_dress_value->damage_insurance;
                } elseif ($rent_dress_value->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $rent_dress_value->id)->sum('amount');
                    $rent_dress_pie_success += $rent_dress_value->price + $additional;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_dress_pie_success += 0;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_dress_pie_success += $rent_dress_value->deposit;
                }
            }
        }

        // เช่าเครื่องประดับ
        $rent_jew_pie = Orderdetail::where('type_order', 3)->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $rent_jew_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $rent_jew_pie->whereYear('updated_at', $value_year);
        }
        $rent_jew_pie = $rent_jew_pie->get();
        $rent_jew_pie_count = $rent_jew_pie->count();
        $rent_jew_pie_success = 0;

        foreach ($rent_jew_pie as $rent_jew_value) {
            // เช่าเครื่องประดับ
            if ($rent_jew_value->status_payment == 1) {
                if ($rent_jew_value->status_detail == 'ถูกจอง') {
                    $rent_jew_pie_success += $rent_jew_value->deposit;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_jew_pie_success += 0;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_jew_pie_success += $rent_jew_value->deposit;
                }
            } elseif ($rent_jew_value->status_payment == 2) {
                if ($rent_jew_value->status_detail == 'กำลังเช่า') {
                    $rent_jew_pie_success += $rent_jew_value->price + $rent_jew_value->damage_insurance;
                } elseif ($rent_jew_value->status_detail == 'ถูกจอง') {
                    $rent_jew_pie_success += $rent_jew_value->price + $rent_jew_value->damage_insurance;
                } elseif ($rent_jew_value->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $rent_jew_value->id)->sum('amount');
                    $rent_jew_pie_success += $rent_jew_value->price + $additional;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_jew_pie_success += 0;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_jew_pie_success += $rent_jew_value->deposit;
                }
            }
        }

        // เช่าตัดชุด
        $rent_cut_dress_pie = Orderdetail::where('type_order', 4)->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $rent_cut_dress_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $rent_cut_dress_pie->whereYear('updated_at', $value_year);
        }
        $rent_cut_dress_pie = $rent_cut_dress_pie->get();
        $rent_cut_dress_pie_count = $rent_cut_dress_pie->count();
        $rent_cut_dress_pie_success = 0;
        foreach ($rent_cut_dress_pie as $rent_cut_dress_value) {
            // เช่าตัดชุด
            if ($rent_cut_dress_value->status_payment == 1) {
                if ($rent_cut_dress_value->status_detail == 'รอดำเนินการตัด') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                } elseif ($rent_cut_dress_value->status_detail == 'เริ่มดำเนินการตัด') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                } elseif ($rent_cut_dress_value->status_detail == 'ถูกจอง') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_cut_dress_pie_success += 0;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                }
            } elseif ($rent_cut_dress_value->status_payment == 2) {
                if ($rent_cut_dress_value->status_detail == 'กำลังเช่า') {
                    $decoration = Decoration::where('order_detail_id', $rent_cut_dress_value->id)->sum('decoration_price');
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->price + $rent_cut_dress_value->damage_insurance + $decoration;
                } elseif ($rent_cut_dress_value->status_detail == 'ถูกจอง') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->price + $rent_cut_dress_value->damage_insurance;
                } elseif ($rent_cut_dress_value->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $rent_cut_dress_value->id)->sum('amount');
                    $decoration = Decoration::where('order_detail_id', $rent_cut_dress_value->id)->sum('decoration_price');
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->price + $additional + $decoration;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_cut_dress_pie_success += 0;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                }
            }
        }

        // ตัดชุด
        $cut_dress_pie = Orderdetail::where('type_order', 1)->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $cut_dress_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $cut_dress_pie->whereYear('updated_at', $value_year);
        }
        $cut_dress_pie = $cut_dress_pie->get();
        $cut_dress_pie_count = $cut_dress_pie->count();
        $cut_dress_pie_success = 0;
        foreach ($cut_dress_pie as $cut_dress_value) {
            if ($cut_dress_value->status_payment == 1) {
                if ($cut_dress_value->status_detail == 'รอดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'เริ่มดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุด') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                }
            } elseif ($cut_dress_value->status_payment == 2) {
                $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                $cut_dress_pie_success += $cut_dress_value->price + $decoration;

                if ($cut_dress_value->status_detail == 'รอดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'เริ่มดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุด') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                }
            }
        }



        $list_for_pie = [];

        $list_for_pie[] = $rent_dress_pie_success;
        $list_for_pie[] = $rent_jew_pie_success;
        $list_for_pie[] = $cut_dress_pie_success;
        $list_for_pie[] = $rent_cut_dress_pie_success;



        // $list_for_pie = [4084.0,120.0,1500.0,25800.0] ; 




        // รายได้ - รายจ่าย
        $list_combined = [];
        $data_orderdetail = Orderdetail::query();

        if ($value_month != 0) {
            $data_orderdetail->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $data_orderdetail->whereYear('updated_at', $value_year);
        }
        $data_orderdetail = $data_orderdetail->get();


        foreach ($data_orderdetail as $items) {
            $month = $items->updated_at->month;
            $year = $items->updated_at->year;
            // เช็ครายรับตรงนี้
            $price_totall = 0;
            if ($items->type_order == 1) {
                // ตัดชุด
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'รอดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'เริ่มดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    } elseif ($items->status_detail == 'แก้ไขชุด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                    $price_totall += $items->price + $decoration;

                    if ($items->status_detail == 'รอดำเนินการตัด') {
                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'เริ่มดำเนินการตัด') {
                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    } elseif ($items->status_detail == 'แก้ไขชุด') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    } elseif ($items->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    }
                }
            } elseif ($items->type_order == 2) {
                // เช่าชุด
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    if ($items->status_detail == 'กำลังเช่า') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $items->id)->sum('amount');
                        $price_totall += $items->price + $additional;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                }
            } elseif ($items->type_order == 3) {
                // เช่าเครื่องประดับ
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    if ($items->status_detail == 'กำลังเช่า') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'คืนเครื่องประดับแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $items->id)->sum('amount');
                        $price_totall += $items->price + $additional;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                }
            } elseif ($items->type_order == 4) {
                // เช่าตัดชุด
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'รอดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'เริ่มดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    if ($items->status_detail == 'กำลังเช่า') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $items->damage_insurance + $decoration;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $items->id)->sum('amount');
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $additional + $decoration;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                }
            }


            // ใช้ array_column() เพื่อดึงค่า "เดือน" จาก $list_combined
            $column_months = array_column($list_combined, 0);
            $column_years = array_column($list_combined, 3);

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่

            foreach ($list_combined as $index => $item) {
                if ($item[0] == $month && $item[3] == $year) {
                    // ถ้าพบเดือนและปีตรงกัน ให้เพิ่มรายรับ
                    $list_combined[$index][1] += $price_totall;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }

            // ถ้ายังไม่พบเดือน + ปีซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                // คำนวณรายจ่ายรวม
                $expense_totall = Expense::query();
                if ($value_month != 0) {
                    $expense_totall->whereMonth('date', $value_month);
                }
                if ($value_year != 0) {
                    $expense_totall->whereYear('date', $value_year);
                }
                $expense_totall = $expense_totall->sum('expense_value');

                $list_combined[] = [$month, $price_totall, $expense_totall, $year];
            }
        }

        foreach ($list_combined as $key => $item) {
            if ($item[0] == 1) {
                $list_combined[$key][0] = 'มกราคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 2) {
                $list_combined[$key][0] = 'กุมภาพันธ์ ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 3) {
                $list_combined[$key][0] = 'มีนาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 4) {
                $list_combined[$key][0] = 'เมษายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 5) {
                $list_combined[$key][0] = 'พฤษภาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 6) {
                $list_combined[$key][0] = 'มิถุนายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 7) {
                $list_combined[$key][0] = 'กรกฎาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 8) {
                $list_combined[$key][0] = 'สิงหาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 9) {
                $list_combined[$key][0] = 'กันยายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 10) {
                $list_combined[$key][0] = 'ตุลาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 11) {
                $list_combined[$key][0] = 'พฤศจิกายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 12) {
                $list_combined[$key][0] = 'ธันวาคม ' . $list_combined[$key][3] + 543;
            }
        }
        // ใช้ array_map() เพื่อแยกข้อมูลเป็น 3 ลิสต์
        $label_bar = array_map(function ($item) {
            return $item[0];
        }, $list_combined);

        $income_bar = array_map(function ($item) {
            return $item[1];
        }, $list_combined);

        $expense_bar = array_map(function ($item) {
            return $item[2];
        }, $list_combined);



        // เครื่องประดับที่นิยมเช่ามากที่สุด
        $popular_jewelry = Reservation::whereNotNull('jewelry_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry->whereYear('updated_at', $value_year);
        }
        $popular_jewelry = $popular_jewelry->get();
        $list_popular_jew = [];
        if ($popular_jewelry->isNotEmpty()) {
            foreach ($popular_jewelry as $item_jew) {
                if (!isset($list_popular_jew[$item_jew->jewelry_id])) {
                    $list_popular_jew[$item_jew->jewelry_id] = 1;
                } else {
                    $list_popular_jew[$item_jew->jewelry_id] += 1;
                }
            }
        }
        arsort($list_popular_jew);
        $list_popular_jew = array_slice($list_popular_jew, 0, 4, true);
        // เซตเครื่องประดับที่นิยมเช่ามากที่สุด
        $popular_jewelry_set = Reservation::whereNotNull('jewelry_set_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry_set->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry_set->whereYear('updated_at', $value_year);
        }
        $popular_jewelry_set = $popular_jewelry_set->get();
        $list_popular_jew_set = [];
        if ($popular_jewelry_set->isNotEmpty()) {
            foreach ($popular_jewelry_set as $item_jew_set) {
                if (!isset($list_popular_jew_set[$item_jew_set->jewelry_set_id])) {
                    $list_popular_jew_set[$item_jew_set->jewelry_set_id] = 1;
                } else {
                    $list_popular_jew_set[$item_jew_set->jewelry_set_id] += 1;
                }
            }
        }
        arsort($list_popular_jew_set);
        $list_popular_jew_set = array_slice($list_popular_jew_set, 0, 4, true);
        // ประเภทชุดที่นิยมตัดมากที่สุด
        $popular_cutdress = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'ส่งมอบชุดแล้ว');
        if ($value_month != 0) {
            $popular_cutdress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_cutdress->whereYear('updated_at', $value_year);
        }
        $popular_cutdress = $popular_cutdress->get();
        $list_popular_cut_dress = [];
        if ($popular_cutdress->isNotEmpty()) {
            foreach ($popular_cutdress as $item_cut_dress) {
                if (!isset($list_popular_cut_dress[$item_cut_dress->type_dress])) {
                    $list_popular_cut_dress[$item_cut_dress->type_dress] = 1;
                } else {
                    $list_popular_cut_dress[$item_cut_dress->type_dress] += 1;
                }
            }
        }
        arsort($list_popular_cut_dress);
        $list_popular_cut_dress = array_slice($list_popular_cut_dress, 0, 4, true);
        // $list_popular_dress =
        //     [
        //         ['67', '1', '10'],
        //         ['67', '1', '20'],
        //         ['68', '1', '20'],

        //     ];
        // ชุดที่นิยมเช่ามากที่สุด
        $list_popular_dress = [];

        // เช็คเฉพาะเช่าทั้งชุดอย่างเดียวพอ
        $popular_dress = Reservation::whereNotNull('dress_id')
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status', 'คืนชุดแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_dress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_dress->whereYear('updated_at', $value_year);
        }
        $popular_dress = $popular_dress->get();
        foreach ($popular_dress as $po_dress) {
            $dress = $po_dress->dress_id;
            $type = 30; // สมมติว่าเป็นทั้งชุด

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่
            foreach ($list_popular_dress as $index => $item) {
                if ($item[0] == $dress && $item[2] == $type) {
                    // ถ้าพบ dress_id และประเภทตรงกัน ให้เพิ่มจำนวนครั้ง
                    $list_popular_dress[$index][1] += 1;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }

            // ถ้ายังไม่พบ dress_id + ประเภทซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                $list_popular_dress[] = [$dress, 1, $type];
            }
        }

        // เช็คเฉพาะเสื้อก่อน
        $popular_shirtitems = Reservation::whereNotNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status', 'คืนชุดแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_shirtitems->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_shirtitems->whereYear('updated_at', $value_year);
        }
        $popular_shirtitems = $popular_shirtitems->get();


        foreach ($popular_shirtitems as $po_shirtitems) {
            $dress = $po_shirtitems->dress_id;
            $type = 10; // 10 แทนเสื้อ

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่
            foreach ($list_popular_dress as $index => $item) {
                if ($item[0] == $dress && $item[2] == $type) {
                    // ถ้าพบ dress_id และประเภทตรงกัน ให้เพิ่มจำนวนครั้ง
                    $list_popular_dress[$index][1] += 1;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }

            // ถ้ายังไม่พบ dress_id + ประเภทซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                $list_popular_dress[] = [$dress, 1, $type];
            }
        }


        // เช็คเฉพาะผ้าถุง
        $popular_skirtitems = Reservation::whereNotNull('skirtitems_id')
            ->whereNull('shirtitems_id')
            ->where('status', 'คืนชุดแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_skirtitems->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_skirtitems->whereYear('updated_at', $value_year);
        }
        $popular_skirtitems = $popular_skirtitems->get();
        foreach ($popular_skirtitems as $po_skirtitems) {
            $dress = $po_skirtitems->dress_id;
            $type = 20; // 20 แทนผ้าถุง

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่
            foreach ($list_popular_dress as $index => $item) {
                if ($item[0] == $dress && $item[2] == $type) {
                    // ถ้าพบ dress_id และประเภทตรงกัน ให้เพิ่มจำนวนครั้ง
                    $list_popular_dress[$index][1] += 1;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }
            // ถ้ายังไม่พบ dress_id + ประเภทซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                $list_popular_dress[] = [$dress, 1, $type];
            }
        }
        usort($list_popular_dress, function ($a, $b) {
            return $b[1] - $a[1]; // เปรียบเทียบค่าของ index ที่ 1 (จำนวนครั้ง)
        });
        $list_popular_dress = array_slice($list_popular_dress, 0, 4);
        return view('admin.dash-board', compact('value_month', 'value_year', 'amount_success', 'damage_insurance_success', 'expense_success', 'income_success', 'list_for_pie', 'label_bar', 'income_bar', 'expense_bar', 'rent_dress_pie_count', 'rent_jew_pie_count', 'rent_cut_dress_pie_count', 'cut_dress_pie_count', 'list_popular_jew', 'list_popular_jew_set', 'list_popular_cut_dress', 'list_popular_dress'));
    }
    public function dashboardfiltershop(Request $request)
    {
        $value_month = $request->input('month');
        $value_year = $request->input('year');

        //จำนวนรายการ

        $amount_orderdetail = Orderdetail::whereNot('status_detail', 'อยู่ในตะกร้า');

        if ($value_month != 0) {
            $amount_orderdetail->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $amount_orderdetail->whereYear('updated_at', $value_year);
        }
        $amount_orderdetail = $amount_orderdetail->get();
        $amount_success = $amount_orderdetail->count();
        // foreach ($amount_orderdetail as $amount) {
        //     if ($amount->status_detail == 'ยกเลิกโดยทางร้าน' || $amount->status_detail == 'ยกเลิกโดยลูกค้า') {
        //     }
        //     else {
        //         $amount_success += 1;
        //     }
        // }


        //เงินประกันที่ยังไม่ได้คืน
        $damage_insurance = Orderdetail::query();

        if ($value_month != 0) {
            $damage_insurance->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $damage_insurance->whereYear('updated_at', $value_year);
        }
        $damage_insurance = $damage_insurance->get();
        $damage_insurance_success = 0;
        foreach ($damage_insurance as $damage) {
            if ($damage->status_payment == 2) {
                if ($damage->status_detail == 'กำลังเช่า' || $damage->status_detail == 'ถูกจอง') {
                    $damage_insurance_success += $damage->damage_insurance;
                }
            }
        }

        //รายจ่ายรวม
        $expense_success = Expense::query();

        if ($value_month != 0) {
            $expense_success->whereMonth('date', $value_month);
        }
        if ($value_year != 0) {
            $expense_success->whereYear('date', $value_year);
        }
        $expense_success = $expense_success->sum('expense_value');



        //รายได้รวม
        $income = Orderdetail::query();
        if ($value_month != 0) {
            $income->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $income->whereYear('updated_at', $value_year);
        }
        $income = $income->get();


        $income_success = 0;
        foreach ($income as $value) {
            if ($value->type_order == 1) {
                // ตัดชุด
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'รอดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'เริ่มดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                    $income_success += $value->price + $decoration;

                    if ($value->status_detail == 'รอดำเนินการตัด') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'เริ่มดำเนินการตัด') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุด') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    }
                }
            } elseif ($value->type_order == 2) {
                // เช่าชุด
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    if ($value->status_detail == 'กำลังเช่า') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $value->id)->sum('amount');
                        $income_success += $value->price + $additional;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                }
            } elseif ($value->type_order == 3) {
                // เช่าเครื่องประดับ
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    if ($value->status_detail == 'กำลังเช่า') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'คืนเครื่องประดับแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $value->id)->sum('amount');
                        $income_success += $value->price + $additional;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                }
            } elseif ($value->type_order == 4) {
                // เช่าตัดชุด
                if ($value->status_payment == 1) {
                    if ($value->status_detail == 'รอดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'เริ่มดำเนินการตัด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                } elseif ($value->status_payment == 2) {
                    if ($value->status_detail == 'กำลังเช่า') {
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $value->damage_insurance + $decoration;
                    } elseif ($value->status_detail == 'ถูกจอง') {
                        $income_success += $value->price + $value->damage_insurance;
                    } elseif ($value->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $value->id)->sum('amount');
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $additional + $decoration;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    }
                }
            }
        }


        // สัดส่วนรายได้แต่ละบริการ


        // เช่าชุด 
        $rent_dress_pie = Orderdetail::where('type_order', 2)
            ->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $rent_dress_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $rent_dress_pie->whereYear('updated_at', $value_year);
        }
        $rent_dress_pie = $rent_dress_pie->get();
        $rent_dress_pie_count = $rent_dress_pie->count();
        $rent_dress_pie_success = 0;
        foreach ($rent_dress_pie as $rent_dress_value) {
            if ($rent_dress_value->status_payment == 1) {
                if ($rent_dress_value->status_detail == 'ถูกจอง') {
                    $rent_dress_pie_success += $rent_dress_value->deposit;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_dress_pie_success += 0;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_dress_pie_success += $rent_dress_value->deposit;
                }
            } elseif ($rent_dress_value->status_payment == 2) {
                if ($rent_dress_value->status_detail == 'กำลังเช่า') {
                    $rent_dress_pie_success += $rent_dress_value->price + $rent_dress_value->damage_insurance;
                } elseif ($rent_dress_value->status_detail == 'ถูกจอง') {
                    $rent_dress_pie_success += $rent_dress_value->price + $rent_dress_value->damage_insurance;
                } elseif ($rent_dress_value->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $rent_dress_value->id)->sum('amount');
                    $rent_dress_pie_success += $rent_dress_value->price + $additional;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_dress_pie_success += 0;
                } elseif ($rent_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_dress_pie_success += $rent_dress_value->deposit;
                }
            }
        }

        // เช่าเครื่องประดับ
        $rent_jew_pie = Orderdetail::where('type_order', 3)->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $rent_jew_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $rent_jew_pie->whereYear('updated_at', $value_year);
        }
        $rent_jew_pie = $rent_jew_pie->get();
        $rent_jew_pie_count = $rent_jew_pie->count();
        $rent_jew_pie_success = 0;

        foreach ($rent_jew_pie as $rent_jew_value) {
            // เช่าเครื่องประดับ
            if ($rent_jew_value->status_payment == 1) {
                if ($rent_jew_value->status_detail == 'ถูกจอง') {
                    $rent_jew_pie_success += $rent_jew_value->deposit;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_jew_pie_success += 0;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_jew_pie_success += $rent_jew_value->deposit;
                }
            } elseif ($rent_jew_value->status_payment == 2) {
                if ($rent_jew_value->status_detail == 'กำลังเช่า') {
                    $rent_jew_pie_success += $rent_jew_value->price + $rent_jew_value->damage_insurance;
                } elseif ($rent_jew_value->status_detail == 'ถูกจอง') {
                    $rent_jew_pie_success += $rent_jew_value->price + $rent_jew_value->damage_insurance;
                } elseif ($rent_jew_value->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $rent_jew_value->id)->sum('amount');
                    $rent_jew_pie_success += $rent_jew_value->price + $additional;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_jew_pie_success += 0;
                } elseif ($rent_jew_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_jew_pie_success += $rent_jew_value->deposit;
                }
            }
        }

        // เช่าตัดชุด
        $rent_cut_dress_pie = Orderdetail::where('type_order', 4)->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $rent_cut_dress_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $rent_cut_dress_pie->whereYear('updated_at', $value_year);
        }
        $rent_cut_dress_pie = $rent_cut_dress_pie->get();
        $rent_cut_dress_pie_count = $rent_cut_dress_pie->count();
        $rent_cut_dress_pie_success = 0;
        foreach ($rent_cut_dress_pie as $rent_cut_dress_value) {
            // เช่าตัดชุด
            if ($rent_cut_dress_value->status_payment == 1) {
                if ($rent_cut_dress_value->status_detail == 'รอดำเนินการตัด') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                } elseif ($rent_cut_dress_value->status_detail == 'เริ่มดำเนินการตัด') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                } elseif ($rent_cut_dress_value->status_detail == 'ถูกจอง') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_cut_dress_pie_success += 0;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                }
            } elseif ($rent_cut_dress_value->status_payment == 2) {
                if ($rent_cut_dress_value->status_detail == 'กำลังเช่า') {
                    $decoration = Decoration::where('order_detail_id', $rent_cut_dress_value->id)->sum('decoration_price');
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->price + $rent_cut_dress_value->damage_insurance + $decoration;
                } elseif ($rent_cut_dress_value->status_detail == 'ถูกจอง') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->price + $rent_cut_dress_value->damage_insurance;
                } elseif ($rent_cut_dress_value->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $rent_cut_dress_value->id)->sum('amount');
                    $decoration = Decoration::where('order_detail_id', $rent_cut_dress_value->id)->sum('decoration_price');
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->price + $additional + $decoration;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $rent_cut_dress_pie_success += 0;
                } elseif ($rent_cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $rent_cut_dress_pie_success += $rent_cut_dress_value->deposit;
                }
            }
        }

        // ตัดชุด
        $cut_dress_pie = Orderdetail::where('type_order', 1)->whereNot('status_detail', 'อยู่ในตะกร้า');
        if ($value_month != 0) {
            $cut_dress_pie->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $cut_dress_pie->whereYear('updated_at', $value_year);
        }
        $cut_dress_pie = $cut_dress_pie->get();
        $cut_dress_pie_count = $cut_dress_pie->count();
        $cut_dress_pie_success = 0;
        foreach ($cut_dress_pie as $cut_dress_value) {
            if ($cut_dress_value->status_payment == 1) {
                if ($cut_dress_value->status_detail == 'รอดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'เริ่มดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุด') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                }
            } elseif ($cut_dress_value->status_payment == 2) {
                $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                $cut_dress_pie_success += $cut_dress_value->price + $decoration;

                if ($cut_dress_value->status_detail == 'รอดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'เริ่มดำเนินการตัด') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุด') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                    $cut_dress_pie_success += $cut_dress_value->price + $decoration;
                }
            }
        }



        $list_for_pie = [];

        $list_for_pie[] = $rent_dress_pie_success;
        $list_for_pie[] = $rent_jew_pie_success;
        $list_for_pie[] = $cut_dress_pie_success;
        $list_for_pie[] = $rent_cut_dress_pie_success;




        // รายได้ - รายจ่าย
        $list_combined = [];
        $data_orderdetail = Orderdetail::query();

        if ($value_month != 0) {
            $data_orderdetail->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $data_orderdetail->whereYear('updated_at', $value_year);
        }
        $data_orderdetail = $data_orderdetail->get();


        foreach ($data_orderdetail as $items) {
            $month = $items->updated_at->month;
            $year = $items->updated_at->year;
            // เช็ครายรับตรงนี้
            $price_totall = 0;
            if ($items->type_order == 1) {
                // ตัดชุด
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'รอดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'เริ่มดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    } elseif ($items->status_detail == 'แก้ไขชุด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                    $price_totall += $items->price + $decoration;

                    if ($items->status_detail == 'รอดำเนินการตัด') {
                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'เริ่มดำเนินการตัด') {
                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'ตัดชุดเสร็จสิ้น') {
                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'ส่งมอบชุดแล้ว') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    } elseif ($items->status_detail == 'แก้ไขชุด') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    } elseif ($items->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $decoration;
                    }
                }
            } elseif ($items->type_order == 2) {
                // เช่าชุด
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    if ($items->status_detail == 'กำลังเช่า') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $items->id)->sum('amount');
                        $price_totall += $items->price + $additional;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                }
            } elseif ($items->type_order == 3) {
                // เช่าเครื่องประดับ
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    if ($items->status_detail == 'กำลังเช่า') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'คืนเครื่องประดับแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $items->id)->sum('amount');
                        $price_totall += $items->price + $additional;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                }
            } elseif ($items->type_order == 4) {
                // เช่าตัดชุด
                if ($items->status_payment == 1) {
                    if ($items->status_detail == 'รอดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'เริ่มดำเนินการตัด') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->deposit;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                } elseif ($items->status_payment == 2) {
                    if ($items->status_detail == 'กำลังเช่า') {
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $items->damage_insurance + $decoration;
                    } elseif ($items->status_detail == 'ถูกจอง') {
                        $price_totall += $items->price + $items->damage_insurance;
                    } elseif ($items->status_detail == 'คืนชุดแล้ว') {
                        $additional = AdditionalChange::where('order_detail_id', $items->id)->sum('amount');
                        $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                        $price_totall += $items->price + $additional + $decoration;
                    } elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    } elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                }
            }

            // ใช้ array_column() เพื่อดึงค่า "เดือน" จาก $list_combined
            $column_months = array_column($list_combined, 0);
            $column_years = array_column($list_combined, 3);

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่
            foreach ($list_combined as $index => $item) {
                if ($item[0] == $month && $item[3] == $year) {
                    // ถ้าพบเดือนและปีตรงกัน ให้เพิ่มรายรับ
                    $list_combined[$index][1] += $price_totall;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }
            // ถ้ายังไม่พบเดือน + ปีซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                // คำนวณรายจ่ายรวม
                $expense_totall = Expense::query();
                if ($value_month != 0) {
                    $expense_totall->whereMonth('date', $value_month);
                }
                if ($value_year != 0) {
                    $expense_totall->whereYear('date', $value_year);
                }
                $expense_totall = $expense_totall->sum('expense_value');

                $list_combined[] = [$month, $price_totall, $expense_totall, $year];
            }
        }
        foreach ($list_combined as $key => $item) {
            if ($item[0] == 1) {
                $list_combined[$key][0] = 'มกราคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 2) {
                $list_combined[$key][0] = 'กุมภาพันธ์ ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 3) {
                $list_combined[$key][0] = 'มีนาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 4) {
                $list_combined[$key][0] = 'เมษายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 5) {
                $list_combined[$key][0] = 'พฤษภาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 6) {
                $list_combined[$key][0] = 'มิถุนายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 7) {
                $list_combined[$key][0] = 'กรกฎาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 8) {
                $list_combined[$key][0] = 'สิงหาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 9) {
                $list_combined[$key][0] = 'กันยายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 10) {
                $list_combined[$key][0] = 'ตุลาคม ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 11) {
                $list_combined[$key][0] = 'พฤศจิกายน ' . $list_combined[$key][3] + 543;
            } elseif ($item[0] == 12) {
                $list_combined[$key][0] = 'ธันวาคม ' . $list_combined[$key][3] + 543;
            }
        }
        // ใช้ array_map() เพื่อแยกข้อมูลเป็น 3 ลิสต์
        $label_bar = array_map(function ($item) {
            return $item[0];
        }, $list_combined);

        $income_bar = array_map(function ($item) {
            return $item[1];
        }, $list_combined);

        $expense_bar = array_map(function ($item) {
            return $item[2];
        }, $list_combined);


        // เครื่องประดับที่นิยมเช่ามากที่สุด
        $popular_jewelry = Reservation::whereNotNull('jewelry_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry->whereYear('updated_at', $value_year);
        }
        $popular_jewelry = $popular_jewelry->get();
        $list_popular_jew = [];
        if ($popular_jewelry->isNotEmpty()) {
            foreach ($popular_jewelry as $item_jew) {
                if (!isset($list_popular_jew[$item_jew->jewelry_id])) {
                    $list_popular_jew[$item_jew->jewelry_id] = 1;
                } else {
                    $list_popular_jew[$item_jew->jewelry_id] += 1;
                }
            }
        }
        arsort($list_popular_jew);
        $list_popular_jew = array_slice($list_popular_jew, 0, 4, true);







        // เซตเครื่องประดับที่นิยมเช่ามากที่สุด
        $popular_jewelry_set = Reservation::whereNotNull('jewelry_set_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry_set->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry_set->whereYear('updated_at', $value_year);
        }
        $popular_jewelry_set = $popular_jewelry_set->get();
        $list_popular_jew_set = [];
        if ($popular_jewelry_set->isNotEmpty()) {
            foreach ($popular_jewelry_set as $item_jew_set) {
                if (!isset($list_popular_jew_set[$item_jew_set->jewelry_set_id])) {
                    $list_popular_jew_set[$item_jew_set->jewelry_set_id] = 1;
                } else {
                    $list_popular_jew_set[$item_jew_set->jewelry_set_id] += 1;
                }
            }
        }
        arsort($list_popular_jew_set);
        $list_popular_jew_set = array_slice($list_popular_jew_set, 0, 4, true);


        // ประเภทชุดที่นิยมตัดมากที่สุด
        $popular_cutdress = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'ส่งมอบชุดแล้ว');
        if ($value_month != 0) {
            $popular_cutdress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_cutdress->whereYear('updated_at', $value_year);
        }
        $popular_cutdress = $popular_cutdress->get();
        $list_popular_cut_dress = [];
        if ($popular_cutdress->isNotEmpty()) {
            foreach ($popular_cutdress as $item_cut_dress) {
                if (!isset($list_popular_cut_dress[$item_cut_dress->type_dress])) {
                    $list_popular_cut_dress[$item_cut_dress->type_dress] = 1;
                } else {
                    $list_popular_cut_dress[$item_cut_dress->type_dress] += 1;
                }
            }
        }
        arsort($list_popular_cut_dress);
        $list_popular_cut_dress = array_slice($list_popular_cut_dress, 0, 4, true);




        // ชุดที่นิยมเช่ามากที่สุด
        $list_popular_dress = [];

        // เช็คเฉพาะเช่าทั้งชุดอย่างเดียวพอ
        $popular_dress = Reservation::whereNotNull('dress_id')
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status', 'คืนชุดแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_dress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_dress->whereYear('updated_at', $value_year);
        }
        $popular_dress = $popular_dress->get();
        foreach ($popular_dress as $po_dress) {
            $dress = $po_dress->dress_id;
            $type = 30; // สมมติว่าเป็นทั้งชุด

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่
            foreach ($list_popular_dress as $index => $item) {
                if ($item[0] == $dress && $item[2] == $type) {
                    // ถ้าพบ dress_id และประเภทตรงกัน ให้เพิ่มจำนวนครั้ง
                    $list_popular_dress[$index][1] += 1;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }

            // ถ้ายังไม่พบ dress_id + ประเภทซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                $list_popular_dress[] = [$dress, 1, $type];
            }
        }

        // เช็คเฉพาะเสื้อก่อน
        $popular_shirtitems = Reservation::whereNotNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status', 'คืนชุดแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_shirtitems->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_shirtitems->whereYear('updated_at', $value_year);
        }
        $popular_shirtitems = $popular_shirtitems->get();


        foreach ($popular_shirtitems as $po_shirtitems) {
            $dress = $po_shirtitems->dress_id;
            $type = 10; // 10 แทนเสื้อ

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่
            foreach ($list_popular_dress as $index => $item) {
                if ($item[0] == $dress && $item[2] == $type) {
                    // ถ้าพบ dress_id และประเภทตรงกัน ให้เพิ่มจำนวนครั้ง
                    $list_popular_dress[$index][1] += 1;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }

            // ถ้ายังไม่พบ dress_id + ประเภทซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                $list_popular_dress[] = [$dress, 1, $type];
            }
        }


        // เช็คเฉพาะผ้าถุง
        $popular_skirtitems = Reservation::whereNotNull('skirtitems_id')
            ->whereNull('shirtitems_id')
            ->where('status', 'คืนชุดแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_skirtitems->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_skirtitems->whereYear('updated_at', $value_year);
        }
        $popular_skirtitems = $popular_skirtitems->get();
        foreach ($popular_skirtitems as $po_skirtitems) {
            $dress = $po_skirtitems->dress_id;
            $type = 20; // 20 แทนผ้าถุง

            $found = false; // ใช้ flag เพื่อตรวจสอบว่าพบข้อมูลที่ซ้ำหรือไม่
            foreach ($list_popular_dress as $index => $item) {
                if ($item[0] == $dress && $item[2] == $type) {
                    // ถ้าพบ dress_id และประเภทตรงกัน ให้เพิ่มจำนวนครั้ง
                    $list_popular_dress[$index][1] += 1;
                    $found = true;
                    break; // ออกจากลูป ไม่ต้องเช็คต่อ
                }
            }
            // ถ้ายังไม่พบ dress_id + ประเภทซ้ำ ให้เพิ่มใหม่เข้าไป
            if (!$found) {
                $list_popular_dress[] = [$dress, 1, $type];
            }
        }
        usort($list_popular_dress, function ($a, $b) {
            return $b[1] - $a[1]; // เปรียบเทียบค่าของ index ที่ 1 (จำนวนครั้ง)
        });
        $list_popular_dress = array_slice($list_popular_dress, 0, 4);
        return view('admin.dash-board', compact('value_month', 'value_year', 'amount_success', 'damage_insurance_success', 'expense_success', 'income_success', 'list_for_pie', 'label_bar', 'income_bar', 'expense_bar', 'rent_dress_pie_count', 'rent_jew_pie_count', 'rent_cut_dress_pie_count', 'cut_dress_pie_count', 'list_popular_jew', 'list_popular_jew_set', 'list_popular_cut_dress', 'list_popular_dress'));
    }















































    public function cancelorderrent(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $type_cancel = $request->input('cancelType');
        // ยกเลิกโดยร้าน
        if ($type_cancel == 'store') {
            if ($orderdetail->type_order == 2) {
                $update_orderdetail = Orderdetail::find($id);
                $update_orderdetail->status_detail = 'ยกเลิกโดยทางร้าน';
                $update_orderdetail->save();
                $status = new Orderdetailstatus();
                $status->order_detail_id = $id;
                $status->status = 'ยกเลิกโดยทางร้าน';
                $status->save();
                $reservation = Reservation::find($update_orderdetail->reservation_id);
                $reservation->status = 'ยกเลิกโดยทางร้าน';
                $reservation->status_completed = 1;
                $reservation->save();

                $reservation_filterdress = Reservationfilterdress::where('reservation_id', $reservation->id)->get();
                foreach ($reservation_filterdress as $item) {
                    $update_filter = Reservationfilterdress::find($item->id);
                    $update_filter->status = 'ยกเลิกโดยทางร้าน';
                    $update_filter->status_completed = 1;
                    $update_filter->save();
                }
            } elseif ($orderdetail->type_order == 3) {
                $update_orderdetail = Orderdetail::find($id);
                $update_orderdetail->status_detail = 'ยกเลิกโดยทางร้าน';
                $update_orderdetail->save();
                $status = new Orderdetailstatus();
                $status->order_detail_id = $id;
                $status->status = 'ยกเลิกโดยทางร้าน';
                $status->save();
                $reservation = Reservation::find($update_orderdetail->reservation_id);
                $reservation->status = 'ยกเลิกโดยทางร้าน';
                $reservation->status_completed = 1;
                $reservation->save();

                $reservation_filter = Reservationfilters::where('reservation_id', $reservation->id)->get();
                foreach ($reservation_filter as $item) {
                    $update_filter = Reservationfilters::find($item->id);
                    $update_filter->status = 'ยกเลิกโดยทางร้าน';
                    $update_filter->status_completed = 1;
                    $update_filter->save();
                }
            } elseif ($orderdetail->type_order == 4) {
                if ($orderdetail->status_detail == 'รอดำเนินการตัด' || $orderdetail->status_detail == 'เริ่มดำเนินการตัด') {
                    $update_orderdetail = Orderdetail::find($id);
                    $update_orderdetail->status_detail = 'ยกเลิกโดยทางร้าน';
                    $update_orderdetail->save();
                    $status = new Orderdetailstatus();
                    $status->order_detail_id = $id;
                    $status->status = 'ยกเลิกโดยทางร้าน';
                    $status->save();
                } elseif ($orderdetail->status_detail == 'ถูกจอง') {

                    $update_orderdetail = Orderdetail::find($id);
                    $update_orderdetail->status_detail = 'ยกเลิกโดยทางร้าน';
                    $update_orderdetail->save();
                    $status = new Orderdetailstatus();
                    $status->order_detail_id = $id;
                    $status->status = 'ยกเลิกโดยทางร้าน';
                    $status->save();
                    $reservation = Reservation::find($update_orderdetail->reservation_id);
                    $reservation->status = 'ยกเลิกโดยทางร้าน';
                    $reservation->status_completed = 1;
                    $reservation->save();

                    $reservation_filterdress = Reservationfilterdress::where('reservation_id', $reservation->id)->get();
                    foreach ($reservation_filterdress as $item) {
                        $update_filter = Reservationfilterdress::find($item->id);
                        $update_filter->status = 'ยกเลิกโดยทางร้าน';
                        $update_filter->status_completed = 1;
                        $update_filter->save();
                    }
                }
            }
        }
        // ยกเลิกโดยลูกค้า
        elseif ($type_cancel == 'customer') {

            if ($orderdetail->type_order == 2) {
                $update_orderdetail = Orderdetail::find($id);
                $update_orderdetail->status_detail = 'ยกเลิกโดยลูกค้า';
                $update_orderdetail->save();
                $status = new Orderdetailstatus();
                $status->order_detail_id = $id;
                $status->status = 'ยกเลิกโดยลูกค้า';
                $status->save();
                $reservation = Reservation::find($update_orderdetail->reservation_id);
                $reservation->status = 'ยกเลิกโดยลูกค้า';
                $reservation->status_completed = 1;
                $reservation->save();

                $reservation_filter = Reservationfilterdress::where('reservation_id', $reservation->id)->get();
                foreach ($reservation_filter as $item) {
                    $update_filter = Reservationfilterdress::find($item->id);
                    $update_filter->status = 'ยกเลิกโดยลูกค้า';
                    $update_filter->status_completed = 1;
                    $update_filter->save();
                }
            } elseif ($orderdetail->type_order == 3) {
                $update_orderdetail = Orderdetail::find($id);
                $update_orderdetail->status_detail = 'ยกเลิกโดยลูกค้า';
                $update_orderdetail->save();
                $status = new Orderdetailstatus();
                $status->order_detail_id = $id;
                $status->status = 'ยกเลิกโดยลูกค้า';
                $status->save();
                $reservation = Reservation::find($update_orderdetail->reservation_id);
                $reservation->status = 'ยกเลิกโดยลูกค้า';
                $reservation->status_completed = 1;
                $reservation->save();

                $reservation_filter = Reservationfilters::where('reservation_id', $reservation->id)->get();
                foreach ($reservation_filter as $item) {
                    $update_filter = Reservationfilters::find($item->id);
                    $update_filter->status = 'ยกเลิกโดยลูกค้า';
                    $update_filter->status_completed = 1;
                    $update_filter->save();
                }
            } elseif ($orderdetail->type_order == 4) {


                if ($orderdetail->status_detail == 'รอดำเนินการตัด' || $orderdetail->status_detail == 'เริ่มดำเนินการตัด') {

                    $update_orderdetail = Orderdetail::find($id);
                    $update_orderdetail->status_detail = 'ยกเลิกโดยลูกค้า';
                    $update_orderdetail->save();
                    $status = new Orderdetailstatus();
                    $status->order_detail_id = $id;
                    $status->status = 'ยกเลิกโดยลูกค้า';
                    $status->save();
                } elseif ($orderdetail->status_detail == 'ถูกจอง') {

                    // จะต้องลบ reser filterdress ด้วยนะ 
                    $update_orderdetail = Orderdetail::find($id);
                    $update_orderdetail->status_detail = 'ยกเลิกโดยลูกค้า';
                    $update_orderdetail->save();
                    $status = new Orderdetailstatus();
                    $status->order_detail_id = $id;
                    $status->status = 'ยกเลิกโดยลูกค้า';
                    $status->save();
                    $reservation = Reservation::find($update_orderdetail->reservation_id);
                    $reservation->status = 'ยกเลิกโดยลูกค้า';
                    $reservation->status_completed = 1;
                    $reservation->save();

                    $reservation_filter = Reservationfilterdress::where('reservation_id', $reservation->id)->get();
                    foreach ($reservation_filter as $item) {
                        $update_filter = Reservationfilterdress::find($item->id);
                        $update_filter->status = 'ยกเลิกโดยลูกค้า';
                        $update_filter->status_completed = 1;
                        $update_filter->save();
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'ยกเลิกรายการสำเร็จ');
    }
}
