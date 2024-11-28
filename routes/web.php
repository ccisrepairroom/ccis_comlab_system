<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringController;
use Filament\Facades\Filament;
use App\Filament\Pages\Auth\EditProfile;



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
    return redirect('ccis_comlab_system/login');
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



require __DIR__ . '/auth.php';
