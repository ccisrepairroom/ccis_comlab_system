<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\FacilityMonitoringPageController;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use App\Filament\Pages\Auth\EditProfile;
use App\Models\Facility;
use Livewire\Livewire;
use App\Livewire\HomePage;
use App\Livewire\EquipmentPage;
use App\Livewire\FacilityMonitoringPage;
use App\Livewire\EquipmentMonitoringPage;
use App\Livewire\FacilitiesPage;
use App\Livewire\SuppliesAndMaterialsPage;
use App\Livewire\SuppliesAndMaterialsMonitoring;
use App\Livewire\RequestPage;
use App\Livewire\RequestFillPage;
use App\Livewire\MyRequestsPage;
use App\Livewire\MyRequestDetailPage;
use App\Livewire\EquipmentDetailPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\RecoverAccountPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;
















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

Route::get('/admin', function () {
    return redirect('ccis_erma/login');
});

Route::get('download-request-form', function () {
    // Define the path to the file stored in the 'app/Filament/Resources/request_form'
    $filePath = app_path('Filament/Resources/request_form/request_form.pdf');

    // Check if the file exists and return it for download
    if (file_exists($filePath)) {
        return Response::download($filePath);
    } else {
        abort(404, 'File not found.');
    }
})->name('download.request.form');
Route::get('/download-user-manual', function () {
    $filePath = app_path('Filament/Resources/user_manual/user_manual.pdf');
    return response()->download($filePath);
})->name('download.user.manual');




Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('ccis_comlab_system')->group(function () {
       
    });
});




Route::get('/facility-monitoring/{facility}', FacilityMonitoringPage::class)->name('facility-monitoring-page');
Route::get('/equiment-monitoring/{equipment}', EquipmentMonitoringPage::class)->name('equipment-monitoring-page');
Route::get('/supplies-and-materials-monitoring/{supply}', SuppliesAndMaterialsMonitoring::class)->name('supplies-and-materials-monitoring');



Route::get('/', HomePage::class);
Route::get('/equipment', EquipmentPage::class)->name('equipment');
// Route::get('/facilities', FacilitiesPage::class);
Route::get('/supplies-and-materials', SuppliesAndMaterialsPage::class);
Route::get('/requests', RequestPage::class);
Route::get('/equipment/{equip}', EquipmentDetailPage::class);






Route::middleware('guest')->group(function(){
    Route::get('/signin', LoginPage::class)->middleware('throttle:5,2');
    // Route::get('/recover-account', RecoverAccountPage::class)->name('password.request');
    // Route::get('/reset/{token}',ResetPasswordPage::class)->name('password.reset');
});

Route::middleware('auth')->group(function(){
    Route::get('/signout', function(){
        auth()->logout();
        return redirect('/');
    });
    Route::get('/request-form', RequestFillPage::class);
    Route::get('/my-requests', MyRequestsPage::class)->name('myrequests');
    // Route::get('/my-request-detail', MyRequestDetailPage::class);
    Route::get('/success', SuccessPage::class)->name('success');
    Route::get('/cancel', CancelPage::class);
 

});









require __DIR__ . '/auth.php';
