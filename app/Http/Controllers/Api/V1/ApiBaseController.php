<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

 /**
 * @OA\Info(
 *      version="0.0.0",
 *      title="Code Challenge",
 *      description="Code-Challenge API",
 *      @OA\Contact(
 *          email="ruchitimilsina27@gmail.com"
 *      )
 * )
 */

 /**
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="apiKey",
 *     description="Use Bearer token to authorize user",
 *     name="Authorization",
 *     in="header",
 * )
 *
 */

 /**
 * @OA\Get(
 *     path="/",
 *     description="Home page",
 *     @OA\Response(response="default", description="Welcome page")
 * )
 */
class ApiBaseController extends Controller
{
    protected $response = [];

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->empty_obj = new \stdClass();
    }

    public function jsonResponse($status,$message,$result,$status_code)
    {
         $this->response['status'] = $status;
        $this->response['message'] = $message;
        $this->response['results'] = $result;
        $this->response['statusCode'] = $status_code;
        return response()->json($this->response,200);
    }
}
