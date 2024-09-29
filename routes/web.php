<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DressController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JewelryController;
use App\Http\Controllers\ManageorderController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// แอดมิน
Route::middleware(['web', 'is_admin'])->group(function () {
    Route::get('/adminhome', [AdminController::class, 'adminhome'])->name('admin.home');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register'); //สมัครสมาชิก
    Route::get('/admin/employeetotal', [RegisterController::class, 'employeetotal'])->name('employeetotal'); //พนักงานทั้งหมด
    Route::get('/admin/employeedetail/{id}/show', [RegisterController::class, 'employeedetail'])->name('admin.employeedetail'); //รายละเอียดพนักงาน
    Route::post('/admin/changestatusemployee/{id}', [RegisterController::class, 'changestatusemployee'])->name('admin.changestatusemployee'); //เปลี่ยนสถานะของพนักงาน

    //กลุ่มชุด
    Route::get('/admin/adddress-form', [DressController::class, 'formadddress'])->name('admin.formadddress'); //แบบฟอร์มเพิ่มชุด
    Route::get('/admin/dresslist', [DressController::class, 'dresslist'])->name('admin.dresslist');
    Route::get('/admin/historydressadjust/{id}', [DressController::class, 'historydressadjust'])->name('admin.historydressadjust');
    Route::get('/admin/historydressrepair/{id}', [DressController::class, 'historydressrepair'])->name('admin.historydressrepair');


    
    Route::post('/admin/adddress-form/save', [DressController::class, 'savedress'])->name('admin.savedress'); //บันทึกข้อมูล 
    Route::get('/gettypename/{typename}', [DressController::class, 'autodresscode']);
    Route::get('/admin/dresstotal', [DressController::class, 'dresstotal'])->name('admin.dresstotal'); //ชุดทั้งหมด
    Route::get('/admin/typedress/{id}', [DressController::class, 'typedress'])->name('admin.typedress'); //หลังจากแยกประเภทชุด
    Route::get('/admin/typedress/dressdetail/{id}', [DressController::class, 'dressdetail'])->name('admin.dressdetail'); //รายะลเอียดย่อย
    Route::post('/admin/typedress/dressdetail/updatedressnoyes/{id}', [DressController::class, 'updatedressnoyes'])->name('admin.updatedressnoyes'); //อัปเดตชุดnoyes
    Route::post('/admin/typedress/dressdetail/updatedressyesshirt/{id}', [DressController::class, 'updatedressyesshirt'])->name('admin.updatedressyesshirt'); //อัปเดตชุดyesshirt
    Route::post('/admin/typedress/dressdetail/updatedressyesskirt/{id}', [DressController::class, 'updatedressyesskirt'])->name('admin.updatedressyesskirt'); //อัปเดตชุดyesskirt

    // Route::post('/admin/typedress/dressdetail/updateprice/{id}', [DressController::class, 'updateprice'])->name('admin.updateprice'); //อัปเดตราคา
    Route::post('/admin/typedress/dressdetail/addmeasumentno/{id}', [DressController::class, 'addmeasumentno'])->name('admin.addmeasumentno'); //เพิ่มการวัดno
    Route::post('/admin/typedress/dressdetail/addmeasumentyesshirt/{id}', [DressController::class, 'addmeasumentyesshirt'])->name('admin.addmeasumentyesshirt'); //เพิ่มการวัดyesshirt
    Route::post('/admin/typedress/dressdetail/addmeasumentyesskirt/{id}', [DressController::class, 'addmeasumentyesskirt'])->name('admin.addmeasumentyesskirt'); //เพิ่มการวัดyesskirt


    Route::post('/admin/typedress/dressdetail/updatemeasument/{id}', [DressController::class, 'updatemeasument'])->name('admin.updatemeasument'); //อัปเดตข้อมูลการวัด
    Route::delete('/admin/typedress/dressdetail/deletemeasument/{id}', [DressController::class, 'deletemeasument'])->name('admin.deletemeasument'); //อัปเดตข้อมูลการวัด
    Route::post('/admin/typedress/dressdetail/addimage/{id}', [DressController::class, 'addimage'])->name('admin.addimage'); //เพิ่มรูปภาพ







    //กลุ่มเครื่องประดับ
    Route::get('/admin/addjewelry-form', [JewelryController::class, 'formaddjewelry'])->name('admin.formaddjewelry'); //แบบฟอร์มเพิ่มเครื่องประดับ
    Route::post('/admin/addjewelry-form/save', [JewelryController::class, 'savejewelry'])->name('admin.savejewelry'); //บันทึกข้อมูล 
    Route::get('/admin/jewelrytotal', [JewelryController::class, 'jewelrytotal'])->name('admin.jewelrytotal'); //เครื่องประดับทั้งหมด
    Route::get('/admin/typejewelry/{id}', [JewelryController::class, 'typejewelry'])->name('admin.typejewelry'); //หลังจากแยกประเภทเครื่องประดับ
    Route::get('/admin/typejewelry/jewelrydetail/{id}', [JewelryController::class, 'jewelrydetail'])->name('admin.jewelrydetail'); //รายะลเอียดย่อย
    Route::post('/admin/typejewelry/jewelrydetail/addjewelryimage/{id}', [JewelryController::class, 'addjewelryimage'])->name('admin.addjewelryimage'); //เพิ่มรูปภาพ
    Route::post('/admin/typejewelry/jewelrydetail/updatejewelry/{id}', [JewelryController::class, 'updatejewelry'])->name('admin.updatejewelry'); //อัปเดตเครื่องประดับ
    Route::post('/admin/typejewelry/jewelrydetail/updatepricejewelry/{id}', [JewelryController::class, 'updatepricejewelry'])->name('admin.updatepricejewelry'); //อัปเดตราคาเครื่องประดับ


    Route::get('/admin/profile', [RegisterController::class, 'profile'])->name('admin.adminprofile'); //โปรไฟล์แอดมิน
    Route::post('/admin/profile/updateprofile/{user}', [RegisterController::class, 'updateprofile'])->name('admin.updateprofile'); //แก้ไขโปรไฟล์


    Route::get('/admin/changepassword', [RegisterController::class, 'changepassword'])->name('admin.changepassword'); //หน้าเปลี่ยนรหัสผ่าน 
    Route::post('/admin/updatepassword', [RegisterController::class, 'password_action'])->name('admin.updatepassword');

    Route::get('/admin/expense', [DressController::class, 'expense'])->name('admin.expense'); //บันทึกค่าใช้จ่าย
    Route::post('/admin/saveexpense', [DressController::class, 'saveexpense'])->name('admin.saveexpense'); //บันทึกค่าใช้จ่าย




    //ทดสอบ
    Route::get('/admin/testtest', [DressController::class, 'testtest'])->name('admin.testtest');
    Route::get('/admin/search', [DressController::class, 'search'])->name('admin.search');

    Route::get('/admin/searchstatusdress', [DressController::class, 'searchstatusdress'])->name('admin.searchstatusdress');
});







//สำหรับพนักงานและแอดมิน
Route::middleware(['web', 'auth'])->group(function () {
    // Route::get('/homepage', [HomeController::class, 'homepage'])->name('homepage'); // หน้าแรก
    Route::get('/employee/homepage', [EmployeeController::class, 'homepage'])->name('employee.homepage'); // หน้าแรก
    Route::get('/employee/addorder', [EmployeeController::class, 'addorder'])->name('employee.addorder');
    Route::get('/employee/selectdate', [EmployeeController::class, 'selectdate'])->name('employee.selectdate');
    Route::get('/employee/clean', [EmployeeController::class, 'clean'])->name('employee.clean');
    Route::get('/employee/dressadjust', [EmployeeController::class, 'dressadjust'])->name('employee.dressadjust');
    Route::get('/employee/cutdressadjust', [EmployeeController::class, 'cutdressadjust'])->name('employee.cutdressadjust');
    Route::get('/employee/listdressreturn', [EmployeeController::class, 'listdressreturn'])->name('employee.listdressreturn');

    Route::get('/employee/repair', [EmployeeController::class, 'repair'])->name('employee.repair');
    Route::get('/employee/calendar', [EmployeeController::class, 'calendar'])->name('employee.calendar');


    Route::post('/employee/clean/update-status', [EmployeeController::class, 'cleanupdatestatus'])->name('employee.cleanupdatestatus');
    Route::post('/employee/clean/update-statustwo', [EmployeeController::class, 'cleanupdatestatuspagetwo'])->name('employee.cleanupdatestatuspagetwo');


    Route::post('/employee/clean/buttoncleanrowpageone/{id}', [EmployeeController::class, 'buttoncleanrowpageone'])->name('employee.buttoncleanrowpageone');
    Route::post('/employee/clean/buttoncleanrowpagetwo/{id}', [EmployeeController::class, 'buttoncleanrowpagetwo'])->name('employee.buttoncleanrowpagetwo');

    Route::post('/employee/clean/buttonrepairrowpageone/{id}', [EmployeeController::class, 'buttonrepairrowpageone'])->name('employee.buttonrepairrowpageone');


    Route::post('/employee/repair/update-status', [EmployeeController::class, 'repairupdatestatus'])->name('employee.repairupdatestatus');
    Route::post('/employee/repair/update-statusrepairupdatestatustoclean', [EmployeeController::class, 'repairupdatestatustoclean'])->name('employee.repairupdatestatustoclean');
    Route::post('/employee/repair/update-statusrepairupdatestatustocleanorready', [EmployeeController::class, 'repairupdatestatustocleanorready'])->name('employee.repairupdatestatustocleanorready');

    Route::post('/employee/clean/afterwashtorepair', [EmployeeController::class, 'afterwashtorepair'])->name('employee.afterwashtorepair');




    Route::get('/employee/reserve-dress', [EmployeeController::class, 'reservedress'])->name('employee.reservedress');

    Route::get('/employee/addcutdress', [EmployeeController::class, 'addcutdress'])->name('employee.addcutdress'); //เพิ่มตัดชุด
    Route::post('/employee/addcutdress/savecutdress', [EmployeeController::class, 'savecutdress'])->name('employee.savecutdress');

    Route::get('/employee/cart', [EmployeeController::class, 'cart'])->name('employee.cart'); //ตะกร้าสินค้า
    Route::post('/employee/cart/deletelist/{id}', [EmployeeController::class, 'deletelist'])->name('employee.deletelist'); //ลบรายการในตะกร้า
    Route::get('/employee/card/manageitem/{id}', [EmployeeController::class, 'manageitem'])->name('employee.manageitem'); //จัดการตาม item แยกตาม type_order
    // Route::post('/employee/manageitem/deletemeaitem/{id}', [EmployeeController::class, 'deletemeaitem'])->name('employee.deletemeaitem'); //ลบdeletemeasurement ใน item
    Route::get('/employee/manageitem/deletemeasurementitem/{id}', [EmployeeController::class, 'deletemeasurementitem'])->name('employee.deletemeasurementitem'); //ลบdeletemeasurement ใน item
    Route::get('/employee/manageitem/deletefittingitem/{id}', [EmployeeController::class, 'deletefittingitem'])->name('employee.deletefittingitem'); //ลบdeletefittng ใน item

    // Route::post('/employee/manageitem/savemanageitem/{id}', [ManageorderController::class, 'savemanageitem'])->name('employee.savemanageitem'); //บันทึกจัดการตาม item


    //เพิ่มเช่าชุดลงในตะกร้า
    Route::get('/employee/selectdate/success', [ManageorderController::class, 'selectdatesuccess'])->name('employee.selectdatesuccess'); //เช่าวันที่เช่าชุด
    Route::get('/employee/typerentdress', [ManageorderController::class, 'typerentdress'])->name('employee.typerentdress'); //เช่าชุดหน้าเลือกประเภทชุด
    Route::get('/employee/typerentdress/show/{id}', [ManageorderController::class, 'typerentdressshow'])->name('employee.typerentdressshow'); //หลังจากที่เลือกประเภทชุดแล้ว
    Route::post('/employee/typerentdress/show/addrentdresscart', [ManageorderController::class, 'addrentdresscart'])->name('employee.addrentdresscart'); //เช่าชุดเพิ่มลงในตะกร้า
    Route::get('/employee/show/filtermea', [ManageorderController::class, 'filtermea'])->name('employee.filtermea'); //


    Route::post('/employee/manageitem/savemanageitemcutdress/{id}', [ManageorderController::class, 'savemanageitemcutdress'])->name('employee.savemanageitemcutdress'); //บันทึกของตัดชุด item
    Route::post('/employee/manageitem/savemanageitemrentdress/{id}', [ManageorderController::class, 'savemanageitemrentdress'])->name('employee.savemanageitemrentdress'); //บันทึกของเช่าชุด item
    Route::post('/employee/manageitem/savemanageitemrentjewelry/{id}', [ManageorderController::class, 'savemanageitemrentjewelry'])->name('employee.savemanageitemrentjewelry'); //บันทึกของเช่าเครื่องประดับ item


    //เพิ่มเครื่องประดับลงตะกร้า
    Route::get('/employee/typerentjewelry', [ManageorderController::class, 'typerentjewelry'])->name('employee.typerentjewelry'); //เช่าเครื่องประดับหน้าเลือกประเภทเครื่องประดับ
    Route::get('/employee/typerentjewelry/show/{id}', [ManageorderController::class, 'typerentjewelryshow'])->name('employee.typerentjewelryshow'); //หลังจากที่เลือกประเภทเครื่องประดับแล้ว
    Route::post('/employee/typerentjewelry/show/addrentjewelrycart', [ManageorderController::class, 'addrentjewelrycart'])->name('employee.addrentjewelrycart'); //เช่าเครื่องประดับเพิ่มลงในตะกร้า


    //เพิ่มเช่าตัดลงในตะกร้า
    Route::get('/employee/addcutrent', [EmployeeController::class, 'addcutrent'])->name('employee.addcutrent'); //เพิ่มเช่าตัด
    Route::post('/employee/addcutrent/savecutrent', [EmployeeController::class, 'savecutrent'])->name('employee.savecutrent'); //บันทึกเช่าตัดลงในตะกร้า
    Route::post('/employee/manageitem/savemanageitemcutrent/{id}', [ManageorderController::class, 'savemanageitemcutrent'])->name('employee.savemanageitemcutrent'); //บันทึกของเช่าตัดชุด item
    // Route::post('/employee/cart/confirmorder/{id}', [EmployeeController::class, 'confirmorder'])->name('employee.confirmorder'); //ยืนยันออเดอร์ทั้งหมด
    Route::get('/employee/cart/confirmorder/{id}', [EmployeeController::class, 'confirmorder'])->name('employee.confirmorder'); //ยืนยันออเดอร์ทั้งหมด
    Route::post('/employee/cart/confirmorder/save{id}', [EmployeeController::class, 'confirmordersave'])->name('employee.confirmordersave'); //ยืนยันออเดอร์ทั้งหมด



    //หน้าแสดงออเดอร์ทั้งหมด
    Route::get('/employee/ordertotal', [OrderController::class, 'ordertotal'])->name('employee.ordertotal'); //ออเดอร์ทั้งหมด

    Route::get('/employee/searchordertotal', [OrderController::class, 'searchordertotal'])->name('employee.searchordertotal'); //ออเดอร์ทั้งหมด



    Route::get('/employee/ordertotal/detail/{id}', [OrderController::class, 'ordertotaldetail'])->name('employee.ordertotaldetail'); //ออเดอร์detail แยก

    Route::get('/employee/ordertotal/detail/show/{id}', [OrderController::class, 'ordertotaldetailshow'])->name('employee.ordertotaldetailshow'); //ออเดอร์orderdetail_id เลย


    //action ต่างๆภายใน detail
    Route::post('/employee/ordertotal/detail/show/addfitting/{id}', [OrderController::class, 'actionaddfitting'])->name('employee.actionaddfitting'); //
    Route::post('/employee/ordertotal/detail/show/addcost/{id}', [OrderController::class, 'actionaddcost'])->name('employee.actionaddcost'); //
    Route::post('/employee/ordertotal/detail/show/updatefitting/{id}', [OrderController::class, 'actionupdatefitting'])->name('employee.actionupdatefitting'); //
    Route::delete('/employee/ordertotal/detail/show/deletefitting/{id}', [OrderController::class, 'actiondeletefitting'])->name('employee.actiondeletefitting'); //
    Route::post('/employee/ordertotal/detail/show/updatecost/{id}', [OrderController::class, 'actionupdatecost'])->name('employee.actionupdatecost'); //

    Route::delete('/employee/ordertotal/detail/show/deletecost/{id}', [OrderController::class, 'actiondeletecost'])->name('employee.actiondeletecost'); //
    Route::post('/employee/ordertotal/detail/show/adddecoration/{id}', [OrderController::class, 'actionadddecoration'])->name('employee.actionadddecoration'); //
    Route::post('/employee/ordertotal/detail/show/updatemeadress/{id}', [OrderController::class, 'actionupdatemeadress'])->name('employee.actionupdatemeadress'); //
    Route::post('/employee/ordertotal/detail/show/addmeaorderdetail/{id}', [OrderController::class, 'actionaddmeaorderdetail'])->name('employee.actionaddmeaorderdetail'); //
    Route::post('/employee/ordertotal/detail/show/updatemeaorderdetail/{id}', [OrderController::class, 'actionupdatemeaorderdetail'])->name('employee.actionupdatemeaorderdetail'); //
    Route::delete('/employee/ordertotal/detail/show/deletemeaorderdetail/{id}', [OrderController::class, 'actiondeletemeaorderdetail'])->name('employee.actiondeletemeaorderdetail'); //
    Route::post('/employee/ordertotal/detail/show/addimagerent/{id}', [OrderController::class, 'actionaddimagerent'])->name('employee.actionaddimagerent'); //
    Route::post('/employee/ordertotal/detail/show/updatestatusrentdress/{id}', [OrderController::class, 'actionupdatestatusrentdress'])->name('employee.actionupdatestatusrentdress'); //อัปเดตสถานะเช่าชุด
    Route::post('/employee/ordertotal/detail/show/updatestatusadjustdress/{id}', [OrderController::class, 'actionupdatestatusadjustdress'])->name('employee.actionupdatestatusadjustdress'); //อัปเดต

    Route::post('/employee/ordertotal/detail/show/updatedecoration/{id}', [OrderController::class, 'actionupdatedecoration'])->name('employee.actionupdatedecoration'); //
    Route::delete('/employee/ordertotal/detail/show/deletedecoration/{id}', [OrderController::class, 'actiondeletedecoration'])->name('employee.actiondeletedecoration'); //




    Route::post('/employee/ordertotal/detail/show/updatestatusrentjewelry/{id}', [OrderController::class, 'actionupdatestatusrentjewelry'])->name('employee.actionupdatestatusrentjewelry'); //อัปเดตสถานะเช่าเครื่องประดับ
    Route::post('/employee/ordertotal/detail/show/updatestatusrentcut/{id}', [OrderController::class, 'actionupdatestatusrentcut'])->name('employee.actionupdatestatusrentcut'); //อัปเดตสถานะเช่าตัดชุด
    Route::post('/employee/ordertotal/detail/show/updatestatuscutdress/{id}', [OrderController::class, 'actionupdatestatuscutdress'])->name('employee.actionupdatestatuscutdress'); //อัปเดตสถานะตัดชุด
    Route::post('/employee/ordertotal/detail/show/updatedatecutdress/{id}', [OrderController::class, 'actionupdatedatecutdress'])->name('employee.actionupdatedatecutdress'); //อัปเดตวันที่ตัดชุด
    Route::post('/employee/ordertotal/detail/show/updatenotecutdress/{id}', [OrderController::class, 'actionupdatenotecutdress'])->name('employee.actionupdatenotecutdress'); //อัปเดต




    // Route::get('/testtab', function () {
    //     return view('testtab');
    // });
    Route::get('/testtab', [DressController::class, 'testtab']); 


    Route::get('/employee/adddresstocart', [OrderController::class, 'adddresstocart'])->name('adddresstocart'); 
    Route::get('/employee/searchadddresstocart', [OrderController::class, 'searchadddresstocart'])->name('searchadddresstocart'); 
    Route::post('/employee/addtocart', [OrderController::class, 'addtocart'])->name('addtocart'); //เพิ่มชุด/เสื้อ/กระโปรง/ผผ้าถุง ลงบนตะกร้า 




});
