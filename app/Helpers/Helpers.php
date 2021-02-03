<?php

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }

}
if( ! function_exists('returnValidation')) {
    function returnValidation($errors)
    {
        array_splice($errors, -2, 2, implode(PHP_EOL, array_slice($errors, -2)));
        $errors = implode(PHP_EOL, $errors);
        $response = [];
        $response['status'] = 'error';
        $response['message'] = $errors;
        $response['results'] = new \stdClass();
        $response['statusCode'] = "302";
        return response()->json($response, 200);
    }
}




if (!function_exists('sendHttpResponse')) {
  function sendHttpResponse($data,$message, $status) {

      $response['data']       =   $data;
      $response['message']    =   $message;
      $response['status']     =   $status;
      return $response;
  }
}


if (!function_exists('generateRandomCode')) {
    function generateRandomCode() {
        $chars = "0123456789.ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 30; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        return $res;
    }
}




