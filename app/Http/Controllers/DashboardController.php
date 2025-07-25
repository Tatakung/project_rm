<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Orderdetail;
use App\Models\Decoration;
use App\Models\Orderdetailstatus;
use App\Models\Reservation;
use App\Models\AdditionalChange;
use App\Models\Cancelbyemployee;
use App\Models\Jewelry;
use App\Models\Reservationfilterdress;
use App\Models\Reservationfilters;
use App\Models\Typedress;
use App\Models\Dress;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //



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
                        //
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    }
                } elseif ($value->status_payment == 2) {
                    // $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                    // $income_success += $value->price + $decoration;

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

                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
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
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $cut_dress_pie_success += 0;
                }
            } elseif ($cut_dress_value->status_payment == 2) {
                // $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                // $cut_dress_pie_success += $cut_dress_value->price + $decoration;

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
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $cut_dress_pie_success += 0;
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
                    elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                    elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    }

                } elseif ($items->status_payment == 2) {
                    // $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                    // $price_totall += $items->price + $decoration;

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

                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'แก้ไขชุดเสร็จสิ้น') {

                        $price_totall += $items->price;
                    }
                    elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                    elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
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
                if ($month != 0) {
                    $expense_totall->whereMonth('date', $month);
                }
                if ($year != 0) {
                    $expense_totall->whereYear('date', $year);
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


        // รายรับแยกตามประเภทชุด
        $orderdetailtypedress = Orderdetail::where('type_order', 2);  // ดึงข้อมูลมาเฉพาะการเช่าชุด
        if ($value_month != 0) {
            $orderdetailtypedress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtypedress->whereYear('updated_at', $value_year);
        }
        $orderdetailtypedress = $orderdetailtypedress->get();


        $list_type_dress = [];
        foreach ($orderdetailtypedress as $item) {
            // ราคาจริง
            $real_price_type = 0;
            // เช่าชุด
            if ($item->status_payment == 1) {
                if ($item->status_detail == 'ถูกจอง') {
                    $real_price_type += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_type += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_type += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $real_price_type += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_type += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $real_price_type += $item->price + $additional;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_type += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_type += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;  // เดือน
            $Year = $item->updated_at->year;    // ปี
            $Dress = $item->type_dress;         // ประเภทชุด
            $found = false;
            foreach ($list_type_dress as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Dress) {
                    $list_type_dress[$index][3] += $real_price_type; // อัปเดตข้อมูลโดยใช้ index แทน
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_dress[] = [$Month, $Year, $Dress, $real_price_type];
            }
        }

        $monthsData = [];
        $revenueData = [];
        $months = [
            'มกราคม',
            'กุมภาพันธ์',
            'มีนาคม',
            'เมษายน',
            'พฤษภาคม',
            'มิถุนายน',
            'กรกฎาคม',
            'สิงหาคม',
            'กันยายน',
            'ตุลาคม',
            'พฤศจิกายน',
            'ธันวาคม'
        ];
        foreach ($list_type_dress as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsData)) {
                $monthsData[] = $monthLabel;
            }
        }

        $allDressTypes = array_unique(array_column($list_type_dress, 2));
        foreach ($allDressTypes as $dressType) {
            $revenueData[$dressType] = array_fill(0, count($monthsData), 0);
        }


        foreach ($list_type_dress as $data) {
            $month = $data[0]; //เดือน
            $year = $data[1]; //ปี
            $dressType = $data[2]; //ประเภทชุด
            $revenue = $data[3]; // รายรับ
            $thaiYear = $year + 543; //แปลง ค.ศ. เป็น พ.ศ 
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsData);
            if ($index !== false) {
                if (!isset($revenueData[$dressType])) {
                    $revenueData[$dressType] = array_fill(0, count($monthsData), 0);
                }
                $revenueData[$dressType][$index] += $revenue;
            }
        }


        // $monthsData = ['ตุลาคม 2567', 'พฤศจิกายน 2567'];
        // $revenueData = [
        //     'ชุดราตรี' => [30000, 29000],
        //     'ชุดไทย'   => [36000, 19000],
        //     'ชุดเดรส'  => [16000, 14000],
        //     'ชุดลูกไม้'  => [2500, 0]
        // ];


        // รายรับแยกตามประเภทเครื่องประดับ
        $list_for_typejew = [];
        $orderdetailtypejewelry_list = Orderdetail::where('type_order', 3)->get();  // ดึงข้อมูลมาเฉพาะการเช่าเครื่องประดับ
        foreach ($orderdetailtypejewelry_list as $typejew) {
            if ($typejew->detail_many_one_re->jewelry_id) {
                $list_for_typejew[] = $typejew->id;
            }
        }
        $orderdetailtypejewelry = Orderdetail::whereIn('id', $list_for_typejew);

        if ($value_month != 0) {
            $orderdetailtypejewelry->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtypejewelry->whereYear('updated_at', $value_year);
        }
        $orderdetailtypejewelry = $orderdetailtypejewelry->get();

        $list_type_jewelry = [];
        foreach ($orderdetailtypejewelry as $item) {
            // ราคาจริง
            $real_price_jewelry = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $real_price_jewelry += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $real_price_jewelry += $item->price + $additional;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Jewelry = $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name;
            $found = false;
            foreach ($list_type_jewelry as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Jewelry) {
                    $list_type_jewelry[$index][3] += $real_price_jewelry;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_jewelry[] = [$Month, $Year, $Jewelry, $real_price_jewelry];
            }
        }

        $monthsDataJewelry = [];
        $revenueDataJewelry = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_type_jewelry as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelry)) {
                $monthsDataJewelry[] = $monthLabel;
            }
        }

        $allJewelryTypes = array_unique(array_column($list_type_jewelry, 2));
        foreach ($allJewelryTypes as $jewelryType) {
            $revenueDataJewelry[$jewelryType] = array_fill(0, count($monthsDataJewelry), 0);
        }

        foreach ($list_type_jewelry as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelryType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelry);
            if ($index !== false) {
                if (!isset($revenueDataJewelry[$jewelryType])) {
                    $revenueDataJewelry[$jewelryType] = array_fill(0, count($monthsDataJewelry), 0);
                }
                $revenueDataJewelry[$jewelryType][$index] += $revenue;
            }
        }





        // รายรับแยกตามเซตเครื่องประดับ



        $orderdetailJewelrySet_list = Orderdetail::where('type_order', 3)->get(); // ดึงข้อมูลเฉพาะการเช่าเซตเครื่องประดับ
        $jew_set_list = [];
        foreach ($orderdetailJewelrySet_list as $type_jew_set) {
            if ($type_jew_set->detail_many_one_re->jewelry_set_id) {
                $jew_set_list[] = $type_jew_set->id;
            }
        }
        $orderdetailJewelrySet = Orderdetail::whereIn('id', $jew_set_list);
        if ($value_month != 0) {
            $orderdetailJewelrySet->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailJewelrySet->whereYear('updated_at', $value_year);
        }
        $orderdetailJewelrySet = $orderdetailJewelrySet->get();

        $list_jewelry_set = [];
        foreach ($orderdetailJewelrySet as $item) {
            // ราคาจริง
            $real_price_jewelry_set = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry_set += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry_set += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry_set += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $real_price_jewelry_set += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry_set += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $real_price_jewelry_set += $item->price + $additional;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry_set += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry_set += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $JewelrySet = $item->detail_many_one_re->resermanytoonejewset->set_name; // เปลี่ยนจาก type_jewelry เป็น jewelry_set
            $found = false;
            foreach ($list_jewelry_set as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $JewelrySet) {
                    $list_jewelry_set[$index][3] += $real_price_jewelry_set;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_jewelry_set[] = [$Month, $Year, $JewelrySet, $real_price_jewelry_set];
            }
        }

        $monthsDataJewelrySet = [];
        $revenueDataJewelrySet = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_jewelry_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelrySet)) {
                $monthsDataJewelrySet[] = $monthLabel;
            }
        }

        $allJewelrySets = array_unique(array_column($list_jewelry_set, 2));
        foreach ($allJewelrySets as $jewelrySet) {
            $revenueDataJewelrySet[$jewelrySet] = array_fill(0, count($monthsDataJewelrySet), 0);
        }

        foreach ($list_jewelry_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelrySet = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelrySet);
            if ($index !== false) {
                if (!isset($revenueDataJewelrySet[$jewelrySet])) {
                    $revenueDataJewelrySet[$jewelrySet] = array_fill(0, count($monthsDataJewelrySet), 0);
                }
                $revenueDataJewelrySet[$jewelrySet][$index] += $revenue;
            }
        }




        // รายรับแยกตามประเภทตัดชุด
        $orderdetailtailoring = Orderdetail::where('type_order', 1); // ดึงข้อมูลมาเฉพาะการตัดชุด

        if ($value_month != 0) {
            $orderdetailtailoring->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtailoring->whereYear('updated_at', $value_year);
        }
        $orderdetailtailoring = $orderdetailtailoring->get();

        $list_type_tailoring = [];
        foreach ($orderdetailtailoring as $item) {
            // ราคาจริง
            $real_price_tailoring = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'รอดำเนินการตัด') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'เริ่มดำเนินการตัด') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_tailoring += $item->price + $decoration;
                } elseif ($item->status_detail == 'แก้ไขชุด') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->deposit;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_tailoring += $item->deposit;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_tailoring += 0;
                }


            } elseif ($item->status_payment == 2) {
                // $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                // $real_price_tailoring += $item->price + $decoration;

                if ($item->status_detail == 'รอดำเนินการตัด') {
                    $real_price_tailoring += $item->price;
                } elseif ($item->status_detail == 'เริ่มดำเนินการตัด') {
                    $real_price_tailoring += $item->price;
                } elseif ($item->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->price;
                } elseif ($item->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_tailoring += $item->price + $decoration;
                } elseif ($item->status_detail == 'แก้ไขชุด') {
                    
                    $real_price_tailoring += $item->price ;
                } elseif ($item->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->price ;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_tailoring += $item->deposit;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_tailoring += 0;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Tailoring = $item->type_dress;
            $found = false;
            foreach ($list_type_tailoring as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Tailoring) {
                    $list_type_tailoring[$index][3] += $real_price_tailoring;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_tailoring[] = [$Month, $Year, $Tailoring, $real_price_tailoring];
            }
        }

        $monthsDataTailoring = [];
        $revenueDataTailoring = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataTailoring)) {
                $monthsDataTailoring[] = $monthLabel;
            }
        }

        $allTailoringTypes = array_unique(array_column($list_type_tailoring, 2));
        foreach ($allTailoringTypes as $tailoringType) {
            $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
        }

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $tailoringType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataTailoring);
            if ($index !== false) {
                if (!isset($revenueDataTailoring[$tailoringType])) {
                    $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
                }
                $revenueDataTailoring[$tailoringType][$index] += $revenue;
            }
        }
        // รายรับแยกตามชุดที่เช่าตัดชุด
        $orderdetailRentalTailoring = Orderdetail::where('type_order', 4); // ดึงข้อมูลมาเฉพาะการเช่าตัดชุด

        if ($value_month != 0) {
            $orderdetailRentalTailoring->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailRentalTailoring->whereYear('updated_at', $value_year);
        }
        $orderdetailRentalTailoring = $orderdetailRentalTailoring->get();

        $list_rental_tailoring = [];
        foreach ($orderdetailRentalTailoring as $item) {
            // ราคาจริง
            $real_price_rental_tailoring = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'รอดำเนินการตัด') {
                    $real_price_rental_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'เริ่มดำเนินการตัด') {
                    $real_price_rental_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_rental_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_rental_tailoring += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_rental_tailoring += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_rental_tailoring += $item->price + $item->damage_insurance + $decoration;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_rental_tailoring += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_rental_tailoring += $item->price + $additional + $decoration;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_rental_tailoring += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_rental_tailoring += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Tailoring = $item->type_dress;
            $found = false;
            foreach ($list_rental_tailoring as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Tailoring) {
                    $list_rental_tailoring[$index][3] += $real_price_rental_tailoring;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_rental_tailoring[] = [$Month, $Year, $Tailoring, $real_price_rental_tailoring];
            }
        }

        $monthsDataRentalTailoring = [];
        $revenueDataRentalTailoring = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_rental_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataRentalTailoring)) {
                $monthsDataRentalTailoring[] = $monthLabel;
            }
        }

        $allTailoringTypes = array_unique(array_column($list_rental_tailoring, 2));
        foreach ($allTailoringTypes as $tailoringType) {
            $revenueDataRentalTailoring[$tailoringType] = array_fill(0, count($monthsDataRentalTailoring), 0);
        }

        foreach ($list_rental_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $tailoringType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataRentalTailoring);
            if ($index !== false) {
                if (!isset($revenueDataRentalTailoring[$tailoringType])) {
                    $revenueDataRentalTailoring[$tailoringType] = array_fill(0, count($monthsDataRentalTailoring), 0);
                }
                $revenueDataRentalTailoring[$tailoringType][$index] += $revenue;
            }
        }

        return view('admin.dash-board', compact('monthsDataJewelrySet', 'revenueDataJewelrySet', 'monthsDataJewelry', 'revenueDataJewelry', 'monthsData', 'revenueData', 'value_month', 'value_year', 'amount_success', 'damage_insurance_success', 'expense_success', 'income_success', 'list_for_pie', 'label_bar', 'income_bar', 'expense_bar', 'rent_dress_pie_count', 'rent_jew_pie_count', 'rent_cut_dress_pie_count', 'cut_dress_pie_count', 'monthsDataTailoring', 'revenueDataTailoring', 'monthsDataRentalTailoring', 'revenueDataRentalTailoring'));
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
                        //
                        $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                        $income_success += $value->price + $decoration;
                    } elseif ($value->status_detail == 'แก้ไขชุด') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
                    }
                } elseif ($value->status_payment == 2) {
                    // $decoration = Decoration::where('order_detail_id', $value->id)->sum('decoration_price');
                    // $income_success += $value->price + $decoration;

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

                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                        $income_success += $value->price;
                    } elseif ($value->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $income_success += $value->deposit;
                    } elseif ($value->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $income_success += 0;
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
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $cut_dress_pie_success += 0;
                }
            } elseif ($cut_dress_value->status_payment == 2) {
                // $decoration = Decoration::where('order_detail_id', $cut_dress_value->id)->sum('decoration_price');
                // $cut_dress_pie_success += $cut_dress_value->price + $decoration;

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
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $cut_dress_pie_success += $cut_dress_value->price;
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $cut_dress_pie_success += $cut_dress_value->deposit;
                } elseif ($cut_dress_value->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $cut_dress_pie_success += 0;
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
                    elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                    elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
                    }

                } elseif ($items->status_payment == 2) {
                    // $decoration = Decoration::where('order_detail_id', $items->id)->sum('decoration_price');
                    // $price_totall += $items->price + $decoration;

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

                        $price_totall += $items->price;
                    } elseif ($items->status_detail == 'แก้ไขชุดเสร็จสิ้น') {

                        $price_totall += $items->price;
                    }
                    elseif ($items->status_detail == 'ยกเลิกโดยลูกค้า') {
                        $price_totall += $items->deposit;
                    }
                    elseif ($items->status_detail == 'ยกเลิกโดยทางร้าน') {
                        $price_totall += 0;
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
                if ($month != 0) {
                    $expense_totall->whereMonth('date', $month);
                }
                if ($year != 0) {
                    $expense_totall->whereYear('date', $year);
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

        



        // รายรับแยกตามประเภทชุด
        $orderdetailtypedress = Orderdetail::where('type_order', 2);  // ดึงข้อมูลมาเฉพาะการเช่าชุด
        if ($value_month != 0) {
            $orderdetailtypedress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtypedress->whereYear('updated_at', $value_year);
        }
        $orderdetailtypedress = $orderdetailtypedress->get();


        $list_type_dress = [];
        foreach ($orderdetailtypedress as $item) {
            // ราคาจริง
            $real_price_type = 0;
            // เช่าชุด
            if ($item->status_payment == 1) {
                if ($item->status_detail == 'ถูกจอง') {
                    $real_price_type += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_type += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_type += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $real_price_type += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_type += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $real_price_type += $item->price + $additional;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_type += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_type += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;  // เดือน
            $Year = $item->updated_at->year;    // ปี
            $Dress = $item->type_dress;         // ประเภทชุด
            $found = false;
            foreach ($list_type_dress as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Dress) {
                    $list_type_dress[$index][3] += $real_price_type; // อัปเดตข้อมูลโดยใช้ index แทน
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_dress[] = [$Month, $Year, $Dress, $real_price_type];
            }
        }

        $monthsData = [];
        $revenueData = [];
        $months = [
            'มกราคม',
            'กุมภาพันธ์',
            'มีนาคม',
            'เมษายน',
            'พฤษภาคม',
            'มิถุนายน',
            'กรกฎาคม',
            'สิงหาคม',
            'กันยายน',
            'ตุลาคม',
            'พฤศจิกายน',
            'ธันวาคม'
        ];
        foreach ($list_type_dress as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsData)) {
                $monthsData[] = $monthLabel;
            }
        }

        $allDressTypes = array_unique(array_column($list_type_dress, 2));
        foreach ($allDressTypes as $dressType) {
            $revenueData[$dressType] = array_fill(0, count($monthsData), 0);
        }


        foreach ($list_type_dress as $data) {
            $month = $data[0]; //เดือน
            $year = $data[1]; //ปี
            $dressType = $data[2]; //ประเภทชุด
            $revenue = $data[3]; // รายรับ
            $thaiYear = $year + 543; //แปลง ค.ศ. เป็น พ.ศ 
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsData);
            if ($index !== false) {
                if (!isset($revenueData[$dressType])) {
                    $revenueData[$dressType] = array_fill(0, count($monthsData), 0);
                }
                $revenueData[$dressType][$index] += $revenue;
            }
        }


        // $monthsData = ['ตุลาคม 2567', 'พฤศจิกายน 2567'];
        // $revenueData = [
        //     'ชุดราตรี' => [30000, 29000],
        //     'ชุดไทย'   => [36000, 19000],
        //     'ชุดเดรส'  => [16000, 14000],
        //     'ชุดลูกไม้'  => [2500, 0]
        // ];


        // รายรับแยกตามประเภทเครื่องประดับ
        $list_for_typejew = [];
        $orderdetailtypejewelry_list = Orderdetail::where('type_order', 3)->get();  // ดึงข้อมูลมาเฉพาะการเช่าเครื่องประดับ
        foreach ($orderdetailtypejewelry_list as $typejew) {
            if ($typejew->detail_many_one_re->jewelry_id) {
                $list_for_typejew[] = $typejew->id;
            }
        }
        $orderdetailtypejewelry = Orderdetail::whereIn('id', $list_for_typejew);

        if ($value_month != 0) {
            $orderdetailtypejewelry->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtypejewelry->whereYear('updated_at', $value_year);
        }
        $orderdetailtypejewelry = $orderdetailtypejewelry->get();

        $list_type_jewelry = [];
        foreach ($orderdetailtypejewelry as $item) {
            // ราคาจริง
            $real_price_jewelry = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $real_price_jewelry += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $real_price_jewelry += $item->price + $additional;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Jewelry = $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name;
            $found = false;
            foreach ($list_type_jewelry as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Jewelry) {
                    $list_type_jewelry[$index][3] += $real_price_jewelry;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_jewelry[] = [$Month, $Year, $Jewelry, $real_price_jewelry];
            }
        }

        $monthsDataJewelry = [];
        $revenueDataJewelry = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_type_jewelry as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelry)) {
                $monthsDataJewelry[] = $monthLabel;
            }
        }

        $allJewelryTypes = array_unique(array_column($list_type_jewelry, 2));
        foreach ($allJewelryTypes as $jewelryType) {
            $revenueDataJewelry[$jewelryType] = array_fill(0, count($monthsDataJewelry), 0);
        }

        foreach ($list_type_jewelry as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelryType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelry);
            if ($index !== false) {
                if (!isset($revenueDataJewelry[$jewelryType])) {
                    $revenueDataJewelry[$jewelryType] = array_fill(0, count($monthsDataJewelry), 0);
                }
                $revenueDataJewelry[$jewelryType][$index] += $revenue;
            }
        }





        // รายรับแยกตามเซตเครื่องประดับ



        $orderdetailJewelrySet_list = Orderdetail::where('type_order', 3)->get(); // ดึงข้อมูลเฉพาะการเช่าเซตเครื่องประดับ
        $jew_set_list = [];
        foreach ($orderdetailJewelrySet_list as $type_jew_set) {
            if ($type_jew_set->detail_many_one_re->jewelry_set_id) {
                $jew_set_list[] = $type_jew_set->id;
            }
        }
        $orderdetailJewelrySet = Orderdetail::whereIn('id', $jew_set_list);
        if ($value_month != 0) {
            $orderdetailJewelrySet->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailJewelrySet->whereYear('updated_at', $value_year);
        }
        $orderdetailJewelrySet = $orderdetailJewelrySet->get();

        $list_jewelry_set = [];
        foreach ($orderdetailJewelrySet as $item) {
            // ราคาจริง
            $real_price_jewelry_set = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry_set += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry_set += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry_set += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $real_price_jewelry_set += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_jewelry_set += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $real_price_jewelry_set += $item->price + $additional;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_jewelry_set += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_jewelry_set += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $JewelrySet = $item->detail_many_one_re->resermanytoonejewset->set_name; // เปลี่ยนจาก type_jewelry เป็น jewelry_set
            $found = false;
            foreach ($list_jewelry_set as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $JewelrySet) {
                    $list_jewelry_set[$index][3] += $real_price_jewelry_set;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_jewelry_set[] = [$Month, $Year, $JewelrySet, $real_price_jewelry_set];
            }
        }

        $monthsDataJewelrySet = [];
        $revenueDataJewelrySet = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_jewelry_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelrySet)) {
                $monthsDataJewelrySet[] = $monthLabel;
            }
        }

        $allJewelrySets = array_unique(array_column($list_jewelry_set, 2));
        foreach ($allJewelrySets as $jewelrySet) {
            $revenueDataJewelrySet[$jewelrySet] = array_fill(0, count($monthsDataJewelrySet), 0);
        }

        foreach ($list_jewelry_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelrySet = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelrySet);
            if ($index !== false) {
                if (!isset($revenueDataJewelrySet[$jewelrySet])) {
                    $revenueDataJewelrySet[$jewelrySet] = array_fill(0, count($monthsDataJewelrySet), 0);
                }
                $revenueDataJewelrySet[$jewelrySet][$index] += $revenue;
            }
        }




        // รายรับแยกตามประเภทตัดชุด
        $orderdetailtailoring = Orderdetail::where('type_order', 1); // ดึงข้อมูลมาเฉพาะการตัดชุด

        if ($value_month != 0) {
            $orderdetailtailoring->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtailoring->whereYear('updated_at', $value_year);
        }
        $orderdetailtailoring = $orderdetailtailoring->get();

        $list_type_tailoring = [];
        foreach ($orderdetailtailoring as $item) {
            // ราคาจริง
            $real_price_tailoring = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'รอดำเนินการตัด') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'เริ่มดำเนินการตัด') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_tailoring += $item->price + $decoration;
                } elseif ($item->status_detail == 'แก้ไขชุด') {
                    $real_price_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->deposit;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_tailoring += $item->deposit;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_tailoring += 0;
                }


            } elseif ($item->status_payment == 2) {
                // $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                // $real_price_tailoring += $item->price + $decoration;

                if ($item->status_detail == 'รอดำเนินการตัด') {
                    $real_price_tailoring += $item->price;
                } elseif ($item->status_detail == 'เริ่มดำเนินการตัด') {
                    $real_price_tailoring += $item->price;
                } elseif ($item->status_detail == 'ตัดชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->price;
                } elseif ($item->status_detail == 'ส่งมอบชุดแล้ว') {
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_tailoring += $item->price + $decoration;
                } elseif ($item->status_detail == 'แก้ไขชุด') {
                    
                    $real_price_tailoring += $item->price ;
                } elseif ($item->status_detail == 'แก้ไขชุดเสร็จสิ้น') {
                    $real_price_tailoring += $item->price ;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_tailoring += $item->deposit;
                }
                elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_tailoring += 0;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Tailoring = $item->type_dress;
            $found = false;
            foreach ($list_type_tailoring as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Tailoring) {
                    $list_type_tailoring[$index][3] += $real_price_tailoring;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_tailoring[] = [$Month, $Year, $Tailoring, $real_price_tailoring];
            }
        }

        $monthsDataTailoring = [];
        $revenueDataTailoring = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataTailoring)) {
                $monthsDataTailoring[] = $monthLabel;
            }
        }

        $allTailoringTypes = array_unique(array_column($list_type_tailoring, 2));
        foreach ($allTailoringTypes as $tailoringType) {
            $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
        }

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $tailoringType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataTailoring);
            if ($index !== false) {
                if (!isset($revenueDataTailoring[$tailoringType])) {
                    $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
                }
                $revenueDataTailoring[$tailoringType][$index] += $revenue;
            }
        }
        // รายรับแยกตามชุดที่เช่าตัดชุด
        $orderdetailRentalTailoring = Orderdetail::where('type_order', 4); // ดึงข้อมูลมาเฉพาะการเช่าตัดชุด

        if ($value_month != 0) {
            $orderdetailRentalTailoring->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailRentalTailoring->whereYear('updated_at', $value_year);
        }
        $orderdetailRentalTailoring = $orderdetailRentalTailoring->get();

        $list_rental_tailoring = [];
        foreach ($orderdetailRentalTailoring as $item) {
            // ราคาจริง
            $real_price_rental_tailoring = 0;

            if ($item->status_payment == 1) {
                if ($item->status_detail == 'รอดำเนินการตัด') {
                    $real_price_rental_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'เริ่มดำเนินการตัด') {
                    $real_price_rental_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_rental_tailoring += $item->deposit;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_rental_tailoring += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_rental_tailoring += $item->deposit;
                }
            } elseif ($item->status_payment == 2) {
                if ($item->status_detail == 'กำลังเช่า') {
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_rental_tailoring += $item->price + $item->damage_insurance + $decoration;
                } elseif ($item->status_detail == 'ถูกจอง') {
                    $real_price_rental_tailoring += $item->price + $item->damage_insurance;
                } elseif ($item->status_detail == 'คืนชุดแล้ว') {
                    $additional = AdditionalChange::where('order_detail_id', $item->id)->sum('amount');
                    $decoration = Decoration::where('order_detail_id', $item->id)->sum('decoration_price');
                    $real_price_rental_tailoring += $item->price + $additional + $decoration;
                } elseif ($item->status_detail == 'ยกเลิกโดยทางร้าน') {
                    $real_price_rental_tailoring += 0;
                } elseif ($item->status_detail == 'ยกเลิกโดยลูกค้า') {
                    $real_price_rental_tailoring += $item->deposit;
                }
            }

            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Tailoring = $item->type_dress;
            $found = false;
            foreach ($list_rental_tailoring as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Tailoring) {
                    $list_rental_tailoring[$index][3] += $real_price_rental_tailoring;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_rental_tailoring[] = [$Month, $Year, $Tailoring, $real_price_rental_tailoring];
            }
        }

        $monthsDataRentalTailoring = [];
        $revenueDataRentalTailoring = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        foreach ($list_rental_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataRentalTailoring)) {
                $monthsDataRentalTailoring[] = $monthLabel;
            }
        }

        $allTailoringTypes = array_unique(array_column($list_rental_tailoring, 2));
        foreach ($allTailoringTypes as $tailoringType) {
            $revenueDataRentalTailoring[$tailoringType] = array_fill(0, count($monthsDataRentalTailoring), 0);
        }

        foreach ($list_rental_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $tailoringType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataRentalTailoring);
            if ($index !== false) {
                if (!isset($revenueDataRentalTailoring[$tailoringType])) {
                    $revenueDataRentalTailoring[$tailoringType] = array_fill(0, count($monthsDataRentalTailoring), 0);
                }
                $revenueDataRentalTailoring[$tailoringType][$index] += $revenue;
            }
        }

        return view('admin.dash-board', compact('monthsDataJewelrySet', 'revenueDataJewelrySet', 'monthsDataJewelry', 'revenueDataJewelry', 'monthsData', 'revenueData', 'value_month', 'value_year', 'amount_success', 'damage_insurance_success', 'expense_success', 'income_success', 'list_for_pie', 'label_bar', 'income_bar', 'expense_bar', 'rent_dress_pie_count', 'rent_jew_pie_count', 'rent_cut_dress_pie_count', 'cut_dress_pie_count', 'monthsDataTailoring', 'revenueDataTailoring', 'monthsDataRentalTailoring', 'revenueDataRentalTailoring'));
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
   
        $cel = new Cancelbyemployee() ; 
        $cel->order_detail_id = $id ; 
        $cel->employee_id = Auth::user()->id;
        $cel->save() ; 
        return redirect()->back()->with('success', 'ยกเลิกรายการสำเร็จ');
    }

    public function cancelordercut(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $type_cancel = $request->input('cancelType');
        if ($type_cancel == 'store') {
            // ยกเลิกโดยทางร้าน
            $update_orderdetail = Orderdetail::find($id);
            $update_orderdetail->status_detail = 'ยกเลิกโดยทางร้าน';
            $update_orderdetail->save();
            $status = new Orderdetailstatus();
            $status->order_detail_id = $id;
            $status->status = 'ยกเลิกโดยทางร้าน';
            $status->save();
        } elseif ($type_cancel == 'customer') {
            // ยกเลิกโดยลูกค้า
            $update_orderdetail = Orderdetail::find($id);
            $update_orderdetail->status_detail = 'ยกเลิกโดยลูกค้า';
            $update_orderdetail->save();
            $status = new Orderdetailstatus();
            $status->order_detail_id = $id;
            $status->status = 'ยกเลิกโดยลูกค้า';
            $status->save();
        }
        return redirect()->back()->with('success', 'ยกเลิกรายการสำเร็จ');
    }


    public function dashboardpopular()
    {
        $value_year = now()->year;
        $value_month = now()->month;
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
        //แบ่งประเภท
        $l_for_type_jew = [];
        foreach ($list_popular_jew as $index_one_j => $index_two_j) {
            $ch_type_jew = Jewelry::where('id', $index_one_j)->value('type_jewelry_id');
            if (!in_array($ch_type_jew, $l_for_type_jew)) {
                $l_for_type_jew[] = $ch_type_jew;
            }
        }


        // แผนภูมิจำนวนครั้งแบ่งตามประเภทเครื่องประดับ
        $popular_jewelry_chart = Reservation::whereNotNull('jewelry_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry_chart->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry_chart->whereYear('updated_at', $value_year);
        }
        $popular_jewelry_chart = $popular_jewelry_chart->get();
        $list_chart_jew = [];
        foreach ($popular_jewelry_chart as $chart_jew) {
            $month_chart_jew = $chart_jew->updated_at->month;
            $year_chart_jew = $chart_jew->updated_at->year;
            $type_chart_jew = $chart_jew->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name;
            $found_jew = false;
            foreach ($list_chart_jew as $index => $data) {
                if ($data[0] ==  $month_chart_jew && $data[1] == $year_chart_jew && $data[2] == $type_chart_jew) {
                    $list_chart_jew[$index][3] += 1;
                    $found_jew = true;
                    break;
                }
            }
            if (!$found_jew) {
                $list_chart_jew[] = [$month_chart_jew, $year_chart_jew, $type_chart_jew, 1];
            }
        }
        $monthsDataJewelry_chart = [];
        $revenueDataJewelry_chart = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        foreach ($list_chart_jew as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelry_chart)) {
                $monthsDataJewelry_chart[] = $monthLabel;
            }
        }

        $allJewelryTypes = array_unique(array_column($list_chart_jew, 2));
        foreach ($allJewelryTypes as $jewelryType) {
            $revenueDataJewelry_chart[$jewelryType] = array_fill(0, count($monthsDataJewelry_chart), 0);
        }

        foreach ($list_chart_jew as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelryType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelry_chart);
            if ($index !== false) {
                if (!isset($revenueDataJewelry_chart[$jewelryType])) {
                    $revenueDataJewelry_chart[$jewelryType] = array_fill(0, count($monthsDataJewelry_chart), 0);
                }
                $revenueDataJewelry_chart[$jewelryType][$index] += $revenue;
            }
        }




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
        // แสดงผลแผนภูมิแท่งแบ่งตามเซตเครื่องประดับ
        $popular_jewelry_set_chart = Reservation::whereNotNull('jewelry_set_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry_set_chart->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry_set_chart->whereYear('updated_at', $value_year);
        }
        $popular_jewelry_set_chart = $popular_jewelry_set_chart->get();

        $list_chart_jew_set = [];
        foreach ($popular_jewelry_set_chart as $chart_set_jew) {
            $month_chart_jew_set = $chart_set_jew->updated_at->month;
            $year_chart_jew_set = $chart_set_jew->updated_at->year;
            $type_chart_jew_set = $chart_set_jew->resermanytoonejewset->set_name;
            $found_jew_set = false;
            foreach ($list_chart_jew_set as $index => $data) {
                if ($data[0] ==  $month_chart_jew_set && $data[1] == $year_chart_jew_set && $data[2] == $type_chart_jew_set) {
                    $list_chart_jew_set[$index][3] += 1;
                    $found_jew_set = true;
                    break;
                }
            }
            if (!$found_jew_set) {
                $list_chart_jew_set[] = [$month_chart_jew_set, $year_chart_jew_set, $type_chart_jew_set, 1];
            }
        }

        $monthsDataJewelryset_chart = [];
        $revenueDataJewelryset_chart = [];
        foreach ($list_chart_jew_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelryset_chart)) {
                $monthsDataJewelryset_chart[] = $monthLabel;
            }
        }


        $allJewelryTypesset = array_unique(array_column($list_chart_jew_set, 2));
        foreach ($allJewelryTypesset as $jewelryType) {
            $revenueDataJewelryset_chart[$jewelryType] = array_fill(0, count($monthsDataJewelryset_chart), 0);
        }

        foreach ($list_chart_jew_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelryType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelryset_chart);
            if ($index !== false) {
                if (!isset($revenueDataJewelryset_chart[$jewelryType])) {
                    $revenueDataJewelryset_chart[$jewelryType] = array_fill(0, count($monthsDataJewelryset_chart), 0);
                }
                $revenueDataJewelryset_chart[$jewelryType][$index] += $revenue;
            }
        }


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
        $list_popular_cut_dress = array_slice($list_popular_cut_dress, 0, 12, true);


        // dd($list_popular_cut_dress) ; 
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

        // $list_popular_dress =
        //     [
        //         ['67', '1', '10'],
        //         ['67', '1', '20'],
        //         ['68', '1', '20'],

        //     ];
        // หาประเภทชุด
        $list_for_tab_type_dress = [];
        foreach ($list_popular_dress as $nobody) {
            $ty_dr = Dress::where('id', $nobody[0])->value('type_dress_id');
            if (!in_array($ty_dr, $list_for_tab_type_dress)) {
                $list_for_tab_type_dress[] = $ty_dr;
            }
        }


        // แผนภูมิแท่ง
        // รายรับแยกตามประเภทชุด
        $orderdetailtypedress = Orderdetail::whereIn('type_order', [2, 4])
            ->where('status_detail', 'คืนชุดแล้ว');
        if ($value_month != 0) {
            $orderdetailtypedress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtypedress->whereYear('updated_at', $value_year);
        }
        $orderdetailtypedress = $orderdetailtypedress->get();


        $list_type_dress_data = [];
        foreach ($orderdetailtypedress as $item) {


            $Month = $item->updated_at->month;  // เดือน
            $Year = $item->updated_at->year;    // ปี
            $Dress = $item->type_dress;         // ประเภทชุด
            $found = false;
            foreach ($list_type_dress_data as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Dress) {
                    $list_type_dress_data[$index][3] += 1; // อัปเดตข้อมูลโดยใช้ index แทน
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_dress_data[] = [$Month, $Year, $Dress, 1];
            }
        }

        $monthsDatadress = [];
        $revenueDatadress = [];

        foreach ($list_type_dress_data as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDatadress)) {
                $monthsDatadress[] = $monthLabel;
            }
        }

        $allDressTypes = array_unique(array_column($list_type_dress_data, 2));
        foreach ($allDressTypes as $dressType) {
            $revenueDatadress[$dressType] = array_fill(0, count($monthsDatadress), 0);
        }


        foreach ($list_type_dress_data as $data) {
            $month = $data[0]; //เดือน
            $year = $data[1]; //ปี
            $dressType = $data[2]; //ประเภทชุด
            $revenue = $data[3]; // รายรับ
            $thaiYear = $year + 543; //แปลง ค.ศ. เป็น พ.ศ 
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            $index = array_search($monthLabel, $monthsDatadress);
            if ($index !== false) {
                if (!isset($revenueDatadress[$dressType])) {
                    $revenueDatadress[$dressType] = array_fill(0, count($monthsDatadress), 0);
                }
                $revenueDatadress[$dressType][$index] += $revenue;
            }
        }






        // แผนภูมิ
        $orderdetailtailoring = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'ส่งมอบชุดแล้ว');

        if ($value_month != 0) {
            $orderdetailtailoring->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtailoring->whereYear('updated_at', $value_year);
        }
        $orderdetailtailoring = $orderdetailtailoring->get();

        $list_type_tailoring = [];
        foreach ($orderdetailtailoring as $item) {


            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Tailoring = $item->type_dress;
            $found = false;
            foreach ($list_type_tailoring as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Tailoring) {
                    $list_type_tailoring[$index][3] += 1;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_tailoring[] = [$Month, $Year, $Tailoring, 1];
            }
        }

        $monthsDataTailoring = [];
        $revenueDataTailoring = [];

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataTailoring)) {
                $monthsDataTailoring[] = $monthLabel;
            }
        }

        $allTailoringTypes = array_unique(array_column($list_type_tailoring, 2));
        foreach ($allTailoringTypes as $tailoringType) {
            $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
        }

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $tailoringType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataTailoring);
            if ($index !== false) {
                if (!isset($revenueDataTailoring[$tailoringType])) {
                    $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
                }
                $revenueDataTailoring[$tailoringType][$index] += $revenue;
            }
        }
        return view('admin.dashboardpopular', compact('value_year', 'value_month', 'list_popular_jew', 'list_popular_jew_set', 'list_popular_dress', 'list_popular_cut_dress', 'l_for_type_jew', 'list_for_tab_type_dress', 'monthsDataJewelry_chart', 'revenueDataJewelry_chart', 'monthsDataJewelryset_chart', 'revenueDataJewelryset_chart', 'monthsDatadress', 'revenueDatadress', 'monthsDataTailoring', 'revenueDataTailoring'));
    }
    public function dashboardpopularfiltershop(Request $request)
    {
        $value_year = $request->input('year');
        $value_month = $request->input('month');
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
        //แบ่งประเภท
        $l_for_type_jew = [];
        foreach ($list_popular_jew as $index_one_j => $index_two_j) {
            $ch_type_jew = Jewelry::where('id', $index_one_j)->value('type_jewelry_id');
            if (!in_array($ch_type_jew, $l_for_type_jew)) {
                $l_for_type_jew[] = $ch_type_jew;
            }
        }


        // แผนภูมิจำนวนครั้งแบ่งตามประเภทเครื่องประดับ
        $popular_jewelry_chart = Reservation::whereNotNull('jewelry_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry_chart->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry_chart->whereYear('updated_at', $value_year);
        }
        $popular_jewelry_chart = $popular_jewelry_chart->get();
        $list_chart_jew = [];
        foreach ($popular_jewelry_chart as $chart_jew) {
            $month_chart_jew = $chart_jew->updated_at->month;
            $year_chart_jew = $chart_jew->updated_at->year;
            $type_chart_jew = $chart_jew->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name;
            $found_jew = false;
            foreach ($list_chart_jew as $index => $data) {
                if ($data[0] ==  $month_chart_jew && $data[1] == $year_chart_jew && $data[2] == $type_chart_jew) {
                    $list_chart_jew[$index][3] += 1;
                    $found_jew = true;
                    break;
                }
            }
            if (!$found_jew) {
                $list_chart_jew[] = [$month_chart_jew, $year_chart_jew, $type_chart_jew, 1];
            }
        }
        $monthsDataJewelry_chart = [];
        $revenueDataJewelry_chart = [];
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        foreach ($list_chart_jew as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelry_chart)) {
                $monthsDataJewelry_chart[] = $monthLabel;
            }
        }

        $allJewelryTypes = array_unique(array_column($list_chart_jew, 2));
        foreach ($allJewelryTypes as $jewelryType) {
            $revenueDataJewelry_chart[$jewelryType] = array_fill(0, count($monthsDataJewelry_chart), 0);
        }

        foreach ($list_chart_jew as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelryType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelry_chart);
            if ($index !== false) {
                if (!isset($revenueDataJewelry_chart[$jewelryType])) {
                    $revenueDataJewelry_chart[$jewelryType] = array_fill(0, count($monthsDataJewelry_chart), 0);
                }
                $revenueDataJewelry_chart[$jewelryType][$index] += $revenue;
            }
        }




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
        // แสดงผลแผนภูมิแท่งแบ่งตามเซตเครื่องประดับ
        $popular_jewelry_set_chart = Reservation::whereNotNull('jewelry_set_id')
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->where('status_completed', 1);
        if ($value_month != 0) {
            $popular_jewelry_set_chart->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $popular_jewelry_set_chart->whereYear('updated_at', $value_year);
        }
        $popular_jewelry_set_chart = $popular_jewelry_set_chart->get();

        $list_chart_jew_set = [];
        foreach ($popular_jewelry_set_chart as $chart_set_jew) {
            $month_chart_jew_set = $chart_set_jew->updated_at->month;
            $year_chart_jew_set = $chart_set_jew->updated_at->year;
            $type_chart_jew_set = $chart_set_jew->resermanytoonejewset->set_name;
            $found_jew_set = false;
            foreach ($list_chart_jew_set as $index => $data) {
                if ($data[0] ==  $month_chart_jew_set && $data[1] == $year_chart_jew_set && $data[2] == $type_chart_jew_set) {
                    $list_chart_jew_set[$index][3] += 1;
                    $found_jew_set = true;
                    break;
                }
            }
            if (!$found_jew_set) {
                $list_chart_jew_set[] = [$month_chart_jew_set, $year_chart_jew_set, $type_chart_jew_set, 1];
            }
        }

        $monthsDataJewelryset_chart = [];
        $revenueDataJewelryset_chart = [];
        foreach ($list_chart_jew_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataJewelryset_chart)) {
                $monthsDataJewelryset_chart[] = $monthLabel;
            }
        }


        $allJewelryTypesset = array_unique(array_column($list_chart_jew_set, 2));
        foreach ($allJewelryTypesset as $jewelryType) {
            $revenueDataJewelryset_chart[$jewelryType] = array_fill(0, count($monthsDataJewelryset_chart), 0);
        }

        foreach ($list_chart_jew_set as $data) {
            $month = $data[0];
            $year = $data[1];
            $jewelryType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataJewelryset_chart);
            if ($index !== false) {
                if (!isset($revenueDataJewelryset_chart[$jewelryType])) {
                    $revenueDataJewelryset_chart[$jewelryType] = array_fill(0, count($monthsDataJewelryset_chart), 0);
                }
                $revenueDataJewelryset_chart[$jewelryType][$index] += $revenue;
            }
        }


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
        $list_popular_cut_dress = array_slice($list_popular_cut_dress, 0, 12, true);


        // dd($list_popular_cut_dress) ; 
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

        // $list_popular_dress =
        //     [
        //         ['67', '1', '10'],
        //         ['67', '1', '20'],
        //         ['68', '1', '20'],

        //     ];
        // หาประเภทชุด
        $list_for_tab_type_dress = [];
        foreach ($list_popular_dress as $nobody) {
            $ty_dr = Dress::where('id', $nobody[0])->value('type_dress_id');
            if (!in_array($ty_dr, $list_for_tab_type_dress)) {
                $list_for_tab_type_dress[] = $ty_dr;
            }
        }


        // แผนภูมิแท่ง
        // รายรับแยกตามประเภทชุด
        $orderdetailtypedress = Orderdetail::whereIn('type_order', [2, 4])
            ->where('status_detail', 'คืนชุดแล้ว');
        if ($value_month != 0) {
            $orderdetailtypedress->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtypedress->whereYear('updated_at', $value_year);
        }
        $orderdetailtypedress = $orderdetailtypedress->get();


        $list_type_dress_data = [];
        foreach ($orderdetailtypedress as $item) {


            $Month = $item->updated_at->month;  // เดือน
            $Year = $item->updated_at->year;    // ปี
            $Dress = $item->type_dress;         // ประเภทชุด
            $found = false;
            foreach ($list_type_dress_data as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Dress) {
                    $list_type_dress_data[$index][3] += 1; // อัปเดตข้อมูลโดยใช้ index แทน
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_dress_data[] = [$Month, $Year, $Dress, 1];
            }
        }

        $monthsDatadress = [];
        $revenueDatadress = [];

        foreach ($list_type_dress_data as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDatadress)) {
                $monthsDatadress[] = $monthLabel;
            }
        }

        $allDressTypes = array_unique(array_column($list_type_dress_data, 2));
        foreach ($allDressTypes as $dressType) {
            $revenueDatadress[$dressType] = array_fill(0, count($monthsDatadress), 0);
        }


        foreach ($list_type_dress_data as $data) {
            $month = $data[0]; //เดือน
            $year = $data[1]; //ปี
            $dressType = $data[2]; //ประเภทชุด
            $revenue = $data[3]; // รายรับ
            $thaiYear = $year + 543; //แปลง ค.ศ. เป็น พ.ศ 
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            $index = array_search($monthLabel, $monthsDatadress);
            if ($index !== false) {
                if (!isset($revenueDatadress[$dressType])) {
                    $revenueDatadress[$dressType] = array_fill(0, count($monthsDatadress), 0);
                }
                $revenueDatadress[$dressType][$index] += $revenue;
            }
        }






        // แผนภูมิ
        $orderdetailtailoring = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'ส่งมอบชุดแล้ว');

        if ($value_month != 0) {
            $orderdetailtailoring->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $orderdetailtailoring->whereYear('updated_at', $value_year);
        }
        $orderdetailtailoring = $orderdetailtailoring->get();

        $list_type_tailoring = [];
        foreach ($orderdetailtailoring as $item) {


            $Month = $item->updated_at->month;
            $Year = $item->updated_at->year;
            $Tailoring = $item->type_dress;
            $found = false;
            foreach ($list_type_tailoring as $index => $data) {
                if ($data[0] == $Month && $data[1] == $Year && $data[2] == $Tailoring) {
                    $list_type_tailoring[$index][3] += 1;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $list_type_tailoring[] = [$Month, $Year, $Tailoring, 1];
            }
        }

        $monthsDataTailoring = [];
        $revenueDataTailoring = [];

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;
            if (!in_array($monthLabel, $monthsDataTailoring)) {
                $monthsDataTailoring[] = $monthLabel;
            }
        }

        $allTailoringTypes = array_unique(array_column($list_type_tailoring, 2));
        foreach ($allTailoringTypes as $tailoringType) {
            $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
        }

        foreach ($list_type_tailoring as $data) {
            $month = $data[0];
            $year = $data[1];
            $tailoringType = $data[2];
            $revenue = $data[3];
            $thaiYear = $year + 543;
            $monthLabel = $months[$month - 1] . ' ' . $thaiYear;

            $index = array_search($monthLabel, $monthsDataTailoring);
            if ($index !== false) {
                if (!isset($revenueDataTailoring[$tailoringType])) {
                    $revenueDataTailoring[$tailoringType] = array_fill(0, count($monthsDataTailoring), 0);
                }
                $revenueDataTailoring[$tailoringType][$index] += $revenue;
            }
        }
        return view('admin.dashboardpopular', compact('value_year', 'value_month', 'list_popular_jew', 'list_popular_jew_set', 'list_popular_dress', 'list_popular_cut_dress', 'l_for_type_jew', 'list_for_tab_type_dress', 'monthsDataJewelry_chart', 'revenueDataJewelry_chart', 'monthsDataJewelryset_chart', 'revenueDataJewelryset_chart', 'monthsDatadress', 'revenueDatadress', 'monthsDataTailoring', 'revenueDataTailoring'));
    }
}
