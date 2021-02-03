<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\DeviceInfo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use DB;
use Validator;


class AuthController extends ApiBaseController
{
    public function __construct(DeviceInfo $deviceInfo)
    {
        parent::__construct();
        $this->deviceInfo   =   $deviceInfo;

    }
     /**
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="Registration",
     *      description="Returns token",
     *      @OA\RequestBody(
     *          required=true,
     *          description="User data",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Register"),
     *           )
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="invalid credentials",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Cannot create token",
     *     ),
     * )
     */
    public function postRegister(Request $request) {

        $validator = \Validator::make($request->all(), [
            'uID' => 'required',
            'appID' => 'required',
            'os' => 'required',
            'language' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return returnValidation($errors);
        }

        \DB::beginTransaction();
        try {
            $uID    =   $request->input('uID');
            $appID  =   $request->input('appID');
            $os     =   $request->input('os');
            $lang   =   $request->input('language');

            if ($os == "1" || $os == "2")
            {
                //add device info
                $result =   $this->deviceInfo->getDeviceInfo($uID, $appID);
                if (!$result) {

                    $token  =   generateRandomCode();

                    $devInfo         =   new DeviceInfo;
                    $devInfo->uID    =   $uID;
                    $devInfo->os     =   $os;
                    $devInfo->appID  =   $appID;
                    $devInfo->language      =  $lang;
                    $devInfo->client_token  =  $token;

                    $devInfo->save();
                    $clientToken    =   $devInfo->client_token;
                } else {
                    $clientToken    =   $result->client_token;
                }
            } else {
                return $this->jsonResponse('fail', 'Not valid device type.', $this->empty_obj, '401');
            }


        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->jsonResponse('fail', 'User Registration Failed.', $this->empty_obj, '401');
        }
        \DB::commit();

        return $this->jsonResponse('ok', 'User Registered Successfully.', $clientToken, '200');
    }
}
