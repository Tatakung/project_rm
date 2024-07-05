<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DressController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JewelryController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// แอดมิน
Route::middleware(['web', 'is_admin'])->group(function () {
    Route::get('/adminhome', [AdminController::class, 'adminhome'])->name('admin.home');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register'); //สมัครสมาชิก
    Route::get('/admin/employeetotal', [RegisterController::class, 'employeetotal'])->name('employeetotal'); //พนักงานทั้งหมด
    Route::get('/admin/employeedetail/{id}/show', [RegisterController::class, 'employeedetail'])->name('admin.employeedetail'); //รายละเอียดพนักงาน
    Route::post('/admin/changestatusemployee/{id}', [RegisterController::class, 'changestatusemployee'])->name('admin.changestatusemployee'); //เปลี่ยนสถานะของพนักงาน

    //กลุ่มชุด
    Route::get('/admin/adddress-form', [DressController::class, 'formadddress'])->name('admin.formadddress'); //แบบฟอร์มเพิ่มชุด
    Route::post('/admin/adddress-form/save', [DressController::class, 'savedress'])->name('admin.savedress'); //บันทึกข้อมูล 
    Route::get('/gettypename/{typename}', [DressController::class, 'autodresscode']);
    Route::get('/admin/dresstotal', [DressController::class, 'dresstotal'])->name('admin.dresstotal'); //ชุดทั้งหมด
    Route::get('/admin/typedress/{id}', [DressController::class, 'typedress'])->name('admin.typedress'); //หลังจากแยกประเภทชุด
    Route::get('/admin/typedress/dressdetail/{id}', [DressController::class, 'dressdetail'])->name('admin.dressdetail'); //รายะลเอียดย่อย
    Route::post('/admin/typedress/dressdetail/updatedress/{id}', [DressController::class, 'updatedress'])->name('admin.updatedress'); //อัปเดตชุด
    Route::post('/admin/typedress/dressdetail/updateprice/{id}', [DressController::class, 'updateprice'])->name('admin.updateprice'); //อัปเดตราคา
    Route::post('/admin/typedress/dressdetail/addmeasument/{id}', [DressController::class, 'addmeasument'])->name('admin.addmeasument'); //เพิ่มการวัด
    Route::post('/admin/typedress/dressdetail/updatemeasument/{id}', [DressController::class, 'updatemeasument'])->name('admin.updatemeasument'); //อัปเดตข้อมูลการวัด
    Route::delete('/admin/typedress/dressdetail/deletemeasument/{id}', [DressController::class, 'deletemeasument'])->name('admin.deletemeasument'); //อัปเดตข้อมูลการวัด
    Route::post('/admin/typedress/dressdetail/addimage/{id}', [DressController::class, 'addimage'])->name('admin.addimage'); //เพิ่มรูปภาพ

    // Route::get('/dresses/create', [DressController::class, 'create'])->name('dresses.create');
    // Route::post('/dresses', [DressController::class, 'store'])->name('dresses.store');    

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
});







//สำหรับพนักงานและแอดมิน
Route::middleware(['web', 'auth'])->group(function () {
    // Route::get('/homepage', [HomeController::class, 'homepage'])->name('homepage'); // หน้าแรก
    Route::get('/employee/homepage', [EmployeeController::class, 'homepage'])->name('employee.homepage'); // หน้าแรก
    Route::get('/employee/addorder', [EmployeeController::class, 'addorder'])->name('employee.addorder'); 
    Route::get('/employee/addcutdress', [EmployeeController::class, 'addcutdress'])->name('employee.addcutdress'); 



});
