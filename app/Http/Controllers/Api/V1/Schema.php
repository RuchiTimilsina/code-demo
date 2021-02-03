<?php

/*** Register start***/
 /**
 * @OA\Schema()
 */
 class Register
 {
    /**
     * User ID
     * @var string
     * @OA\Property(
     *     property="uID",
     *     type="integer",
     *     description="User ID",
     *     default="1",
     *     example="1"
     * )
     */
    public $uID;

    /**
    * Device Type
    * @var string
    * @OA\Property(
    *     property="os",
    *     type="string",
    *     description="os",
    *     default="1 for  ios 2 for android",
    *     example="1 for  ios 2 for android"
    * )
    */
    public $OS;

    /**
    * App ID
    * @var string
    * @OA\Property(
    *     property="appID",
    *     type="string",
    *     description="App ID",
    *     default="token",
    *     example="token"
    * )
    */
    public $appID;

    /**
    * App ID
    * @var string
    * @OA\Property(
    *     property="language",
    *     type="string",
    *     description="Language",
    *     default="eng",
    *     example="eng"
    * )
    */
    public $language;
 }
/*** Register end***/

/*** InAppPurchaseVerification start***/
 /**
 * @OA\Schema()
 */
class InAppPurchaseVerification
{
   /**
    * Receipt Token
    * @var string
    * @OA\Property(
    *     property="receipt_token",
    *     type="string",
    *     description="Receipt Token",
    * )
    */
   public $receipt_token;

}
/*** InAppPurchaseVerification end***/


