<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetFakeDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // ตรวจสอบว่ามีการตั้งค่า FAKE_DATE หรือไม่
        $fakeDate = env('FAKE_DATE', null);

        if ($fakeDate) {
            // เซตวันเวลาเป็นวันที่ที่ตั้งไว้
            Carbon::setTestNow(Carbon::parse($fakeDate));
        }

        // ส่งต่อการร้องขอไปยัง controller
        $response = $next($request);

        // รีเซ็ตเวลาเมื่อเสร็จสิ้น
        Carbon::setTestNow();

        return $response;
    }











}
