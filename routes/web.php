<?php

// routes/web.php

use Illuminate\Support\Facades\Route;

Route::get('/test-session', function (\Illuminate\Http\Request $request) {
	$request->session()->put('foo', 'bar');
	return $request->session()->get('foo');
});
