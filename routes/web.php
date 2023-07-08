<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request) {
    $grantCode = $request->get('code');
    $response = Http::asForm()->withHeaders([
        'Content-Type'=>'application/x-www-form-urlencoded',
        'Authorization'=>'Basic M2o5NjQ3dWs0M3IwNTI5aDgxM3VmN3ZxZGs6OWtrMzk2dGFscmU0a2gxNDljaG0ya201Y2tjbnNocWE0ZzUyY3NxaWgzM2xhZDVrMmc3'
    ])->post('https://fsdevtutor.auth.us-west-2.amazoncognito.com/oauth2/token',[
        'grant_type'=>'authorization_code',
        'code'=>$grantCode,
        'redirect_uri'=>'https://cognito.fsdevtutor.shop/'
    ]);

    $decodedResponse = json_decode($response);
    $accessToken = $decodedResponse->access_token;
    $userInfoResponse = Http::asForm()->withHeaders([
        'Content-Type'=>'application/x-www-form-urlencoded',
        'Authorization'=>'Bearer '.$accessToken
    ])->get('https://fsdevtutor.auth.us-west-2.amazoncognito.com/oauth2/userInfo');
    $data = json_decode($userInfoResponse);

    return view('index',['data'=>$data]);
});
