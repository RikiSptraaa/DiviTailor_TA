<?php

use Carbon\Carbon;
// use Barryvdh\DomPDF\PDF;
use Dompdf\Options;
use App\Models\GroupOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\OrderController;
use App\Admin\Controllers\GroupOrderController;
// use Mpdf\Mpdf as Mpdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('/sidebar', 'admin.index');


// Route::get('/orders/cetak/{order}', [OrderController::class, 'print']);
// Route::get('/borongan/cetak/{borongan}', function ($id) {
//     $borongan = GroupOrder::find($id);
//     $carbon = new Carbon;
//     $pdf = pdf::loadView('pdf.borongan', compact('carbon', 'borongan'));
//     ob_clean();
//     // response()->header('Content-type', 'application/pdf');
//     return $pdf->download('Borongan- ' . $borongan->invoice_number);
//     // exit();
// });
