<?php

use think\facade\Route;

Route::get('/logs', 'Qsnh\Think\Log\Controllers\LogViewerController@index');