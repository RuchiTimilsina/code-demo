<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\DeviceInfo;
use App\Models\UserSubscription;
use App\Exports\SubscriptionExport;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use DateInterval;

class SubscriptionController extends ApiBaseController
{
    public function __construct(DeviceInfo $deviceInfo, UserSubscription $userSubscription)
    {
        parent::__construct();
        $this->deviceInfo   =   $deviceInfo;
        $this->userSubscription   =   $userSubscription;

    }
     /**
     * @OA\Post(
     *      path="/api/subscription/inAppPurchaseVerification",
     *      operationId="inAppPurchaseVerification",
     *      tags={"Subscription"},
     *      summary="In-App Purchase Verification",
     *      description="Validate receipt and returns expiry date",
     *      @OA\RequestBody(
     *          required=true,
     *          description="In app purchase verification",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/InAppPurchaseVerification"),
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description ="Internal Server Error",
     *     ),
     *     security={
     *        {
     *             "Bearer": {}
     *         }
     *     }
     * )
     */

    public function inAppPurchaseVerification(Request $request)
    {
        try {
            $auth = $request->header('Authorization');
            if (!$auth) {
                return $this->jsonResponse('fail', 'unauthorized', $this->empty_obj, '401');
            }

            $clientToken =  str_replace('Bearer ', '', $auth);

            $result =   $this->deviceInfo->getuID($clientToken);
            if(!$result) {
                return $this->jsonResponse('fail', 'unauthorized', $this->empty_obj, '401');
            } else {
                if (!$request->receipt_token) {
                    return response()->json(['error' => 'Receipt token is empty.'], 405);
                }

                $os =   $result->os;

                $lastChar   =   substr($request->receipt_token, -1);

                if(is_numeric($lastChar) == true)
                {
                    $remainder =    $lastChar % 2;

                    if ($remainder != 0)
                    {
                        if ($os == 1)
                        {
                            $currentDateTime    =   gmdate('Y-m-d H:i:s', strtotime('+6hours'));
                            $converted          =   DateTime::createFromFormat("Y-m-d H:i:s", $currentDateTime);
                            $date               =   $converted->add(new DateInterval("P1Y"));
                            $expiry_date        =   $date->format('Y-m-d H:i:s');


                        } else {
                            $expiry_date = gmdate('Y-m-d H:i:s', strtotime('+1year'));
                        }
                        $getCurrentStatus = $this->userSubscription->getSubscriptionDetail($result->id);

                        $status =   (!$getCurrentStatus) ? 'started': 'renewed';

                        // cancel previous subscription
                        $cancel = UserSubscription::where('device_info_id', $result->id)->update(['status' => 'canceled']);

                        //insert new subscription details
                        $user_subscription = new UserSubscription;
                        $user_subscription->device_info_id = $result->id;
                        $user_subscription->type = $os;
                        $user_subscription->status = $status;
                        $user_subscription->start_date = gmdate('Y-m-d H:i:s');
                        $user_subscription->end_date = $expiry_date;
                        $user_subscription->receipt_base64_data = $request->receipt_token;
                        $user_subscription->save();

                        return $this->jsonResponse('ok', 'success', $user_subscription, '200');

                    }
                }
                return $this->jsonResponse('ok', 'success', 'Receipt token verified.', '200');

            }

        } catch (\Exception $e) {
            return $this->jsonResponse('fail', 'Failed', $e->getMessage(), '500');
        }
    }

     /**
     * @OA\GET(
     *      path="/api/subscription/getSubscriptionStatus",
     *      operationId="getSubscriptionStatus",
     *      tags={"Subscription"},
     *      summary="Get Subscription Status",
     *      description="Get User Subscription Status",
     *      @OA\Response(
     *          response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description ="Internal Server Error",
     *     ),
     *     security={
     *        {
     *             "Bearer": {}
     *         }
     *     }
     * )
     */
    public function getSubscriptionStatus(Request $request)
    {
        try {
            $auth = $request->header('Authorization');
            if (!$auth) {
                return $this->jsonResponse('fail', 'unauthorized', $this->empty_obj, '401');
            }

            $clientToken =  str_replace('Bearer ', '', $auth);

            $result =   $this->deviceInfo->getuID($clientToken);
            if(!$result) {
                return $this->jsonResponse('fail', 'unauthorized', $this->empty_obj, '401');
            } else {
                $subscriptionDetail = $this->userSubscription->getSubscriptionDetail($result->id);
                return $this->jsonResponse('ok', 'success', $subscriptionDetail, '200');

            }

        } catch(\Exception $e) {
            return $this->jsonResponse('fail', 'Failed', $e->getMessage(), '500');
        }

    }

    /**
     * @OA\GET(
     *      path="/api/subscription/getReport",
     *      operationId="getReport",
     *      tags={"Subscription"},
     *      summary="Get Subscription Report",
     *      description="Get Subscription Report",
     *      @OA\Response(
     *          response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description ="Internal Server Error",
     *     ),
     *     security={
     *        {
     *             "Bearer": {}
     *         }
     *     }
     * )
     */
    public function getReport(Request $request)
    {
        try {
            $auth = $request->header('Authorization');
            if (!$auth) {
                return $this->jsonResponse('fail', 'unauthorized', $this->empty_obj, '401');
            }

            $clientToken =  str_replace('Bearer ', '', $auth);

            $result =   $this->deviceInfo->getuID($clientToken);
            if(!$result) {
                return $this->jsonResponse('fail', 'unauthorized', $this->empty_obj, '401');
            } else {
                return Excel::download(new SubscriptionExport($result->id), 'report.xlsx');

                // return $this->jsonResponse('ok', 'success', $subscriptionDetail, '200');

            }

        } catch(\Exception $e) {
            return $this->jsonResponse('fail', 'Failed', $e->getMessage(), '500');
        }

    }

}
