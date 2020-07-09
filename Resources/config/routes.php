<?php

/*
 * This file is part of the Omed project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::post('login', 'OmedAuthController@login')->name('omed.auth.login');
    Route::post('logout', 'OmedAuthController@logout')->name('omed.auth.logout');
    Route::post('refresh', 'OmedAuthController@refresh')->name('omed.auth.refresh');
    Route::post('me', 'OmedAuthController@me')->name('omed.auth.profile');
});

Route::middleware('auth:api')->resource('omed_user', 'OmedUserController', [
    'path' => config('omed_user.route_prefix.user'),
]);
