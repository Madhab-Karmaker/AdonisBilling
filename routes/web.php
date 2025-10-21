<?php

Route::get('/test', function () {
    return response()->json(['message' => 'Hello from web route!']);
});
