<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MockBigBlueButtonController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MeetingController::class, 'index'])->name('meeting.index');
Route::post('/join-meeting', [MeetingController::class, 'join'])->name('meeting.join');

// Local Mock BigBlueButton API Endpoints
Route::get('/mock-bbb/api/create', [MockBigBlueButtonController::class, 'create'])->name('mock.bbb.create');
Route::get('/mock-bbb/api/join', [MockBigBlueButtonController::class, 'join'])->name('mock.bbb.join');


