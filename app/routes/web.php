<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\NilaiController;
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

Route::get('/', [UserController::class, 'index'])->name('index')->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->name('login')->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/home', function(){
    return view('home');
})->name('home')->middleware('auth');

Route::prefix('/u')->group(function(){
    Route::prefix('/rekap')->group(function(){
        Route::get('/', [RekapController::class, 'read'])->name('rekap')->middleware('teacher');
        Route::post('/submit-filter', [RekapController::class, 'index'])->name('submitrekap')->middleware('teacher');
        Route::get('/edit', [RekapController::class, 'edit'])->name('editrekap')->middleware('teacher');
        Route::post('/edit', [RekapController::class, 'store'])->name('rekap.store')->middleware('teacher');
    });
    Route::prefix('/tugas')->group(function(){
        Route::get('/', [TugasController::class, 'read'])->name('tugas')->middleware('teacher');
        Route::post('/submit-filter', [TugasController::class, 'index'])->name('submittugas')->middleware('teacher');
        Route::get('/add', [TugasController::class, 'add'])->name('addTugas')->middleware('teacher');
        Route::post('/add', [TugasController::class, 'store'])->name('addTugas.store')->middleware('teacher');
        Route::get('/edit/{id}', [TugasController::class, 'showedit'])->name('edittugas')->middleware('teacher');
        Route::post('/edit/{id}', [TugasController::class, 'edit'])->name('editTugas.store')->middleware('teacher');
        Route::delete('/delete-tugas/{id}', [TugasController::class, 'deleteTugas'])->name('delete-tugas')->middleware('teacher');
    });
    Route::prefix('/nilai')->group(function(){
        Route::get('/', [NilaiController::class, 'readNilai'])->name('nilai')->middleware('teacher');
        Route::get('/submit-filter', [NilaiController::class, 'index'])->middleware('teacher');
        Route::post('/submit-filter', [NilaiController::class, 'index'])->name('submitnilai')->middleware('teacher');
        Route::post('/submit-graduation', [NilaiController::class, 'graduate'])->name('submit-graduation')->middleware('admin');
    });
    Route::prefix('/setting')->group(function(){
        Route::get('/', [AdminController::class, 'read'])->name('setting')->middleware('admin');
        Route::get('/akun', [AdminController::class, 'akun'])->name('akun')->middleware('admin');
        Route::post('/akun', [AdminController::class, 'addAkun'])->name('addAkun')->middleware('admin');
        Route::get('/kelas', [AdminController::class, 'kelas'])->name('kelas')->middleware('admin');
        Route::post('/kelas',[AdminController::class,'store'])->name('kelas.store')->middleware('admin');
        Route::get('/murid', [AdminController::class, 'murid'])->name('murid')->middleware('admin');
        Route::post('/submit-murid', [AdminController::class, 'indexMurid'])->name('submitmurid')->middleware('admin');
        Route::get('/guru', [AdminController::class, 'guru'])->name('guru')->middleware('admin');
        Route::post('/submit-guru', [AdminController::class, 'indexguru'])->name('submitguru')->middleware('admin');
        Route::delete('/delete-student', [AdminController::class, 'deleteStudent'])->name('delete-student')->Middleware('admin');
        Route::delete('/delete-t    eacher', [AdminController::class, 'deleteTeacher'])->name('delete-teacher')->middleware('admin');
        Route::get('/tahun', [AdminController::class, 'tahun'])->name('tahun')->middleware('admin');
        Route::post('/tahun', [AdminController::class, 'addTahun'])->name('addTahun')->middleware('admin');

    });
});

Route::match(['get', 'post'], '/rekap', [StudentController::class, 'rekap'])->name('srekap')->middleware('student');
Route::match(['get', 'post'], '/tugas', [StudentController::class, 'tugas'])->name('stugas')->middleware('student');
// Route::get('/tugas', [StudentController::class, 'tugas'])->name('stugas')->middleware('student');
