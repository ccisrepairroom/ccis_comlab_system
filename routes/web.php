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
use App\Livewire\Auth\ForgotPasswordPage;
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
/*Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('ccis_comlab_system')->group(function () {
        // Define the route for the Filament profile page
        Route::get('/profile', EditProfile::class)->name('filament.admin.auth.profile');
    });
});*/

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
/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/equipment-monitorings', [MonitoringController::class, 'index'])->name('equipment-monitorings.index');
});*/




Route::get('/facility-monitoring/{facility}', FacilityMonitoringPage::class)->name('facility-monitoring-page');
Route::get('/equiment-monitoring/{equipment}', EquipmentMonitoringPage::class)->name('equipment-monitoring-page');
Route::get('/supplies-and-materials-monitoring/{supply}', SuppliesAndMaterialsMonitoring::class)->name('supplies-and-materials-monitoring');



// Route::get('/', [Home::class, 'render'])->name('home');
Route::get('/', HomePage::class);
Route::get('/equipment', EquipmentPage::class);
// Route::get('/facilities', FacilitiesPage::class);
Route::get('/supplies-and-materials', SuppliesAndMaterialsPage::class);
Route::get('/requests', RequestPage::class);
Route::get('/request-form', RequestFillPage::class);
Route::get('/success', SuccessPage::class);

// Route::get('/my-requests', MyRequestsPage::class);
// Route::get('/my-request-detail', MyRequestDetailPage::class);
Route::get('/equipment/{equip}', EquipmentDetailPage::class);


Route::get('/public-login', LoginPage::class);
Route::get('/register-page', RegisterPage::class);
Route::get('/forgot-password-page', ForgotPasswordPage::class);
Route::get('/reset-password-page', ResetPasswordPage::class);

Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);













// Route::get('/downloadpdf', [FacilityMonitoringPage::class, 'downloadpdf'])->name('downloadpdf');





require __DIR__ . '/auth.php';
