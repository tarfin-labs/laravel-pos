<?php


namespace TarfinLabs\LaravelPos\Tests;

use TarfinLabs\LaravelPos\Transaction;

class TransactionTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function TestTransactionHandleSuccessfully(){

        $request = [
            "ReturnOid" => "BWJ1KOrfY8aHY2fL",
            "TRANID" => null,
            "PAResSyntaxOK" => "true",
            "firmaadi" => "hakan özdemir",
            "islemtipi" => "Auth",
            "lang" => "tr",
            "merchantID" => "190200000",
            "maskedCreditCard" => "4546 71** **** 7894",
            "amount" => "19",
            "sID" => "1",
            "ACQBIN" => "454672",
            "Ecom_Payment_Card_ExpDate_Year" => "26",
            "EXTRA_CARDBRAND" => "VISA",
            "MaskedPan" => "454671***7894",
            "acqStan" => "304462",
            "clientIp" => "159.146.18.29",
            "iReqDetail" => null,
            "EXTRA_KAZANILANPUAN" => "000000010.00",
            "okUrl" => "http://pos.test/ok",
            "md" => "454671:A48A90147FC236CCD3D41093426C710CFB622166644480DC42EBA6F104EA677B:4102:##190200000",
            "ProcReturnCode" => "00",
            "payResults_dsId" => "1",
            "taksit" => "1",
            "vendorCode" => null,
            "TransId" => "20338RDYA16513",
            "EXTRA_TRXDATE" => "20201203 17:03:23",
            "Ecom_Payment_Card_ExpDate_Month" => "12",
            "storetype" => "3d_pay",
            "iReqCode" => null,
            "Response" => "Approved",
            "SettleId" => "1695",
            "mdErrorMsg" => "Authenticated",
            "ErrMsg" => null,
            "PAResVerified" => "false",
            "cavv" => "AAABABCQAgAAAAAhVZACAAAAAAA=",
            "digest" => "digest",
            "HostRefNum" => "033817304462",
            "callbackCall" => "true",
            "AuthCode" => "098716",
            "failUrl" => "http://pos.test/fail",
            "cavvAlgorithm" => "2",
            "xid" => "r/pMqC5ZpDPe26gKf3HbDwEEXMw=",
            "encoding" => "ISO-8859-9",
            "currency" => "949",
            "oid" => "BWJ1KOrfY8aHY2fL",
            "mdStatus" => "1",
            "dsId" => "1",
            "eci" => "05",
            "version" => "2.0",
            "EXTRA_CARDISSUER" => "ZİRAAT BANKASI",
            "clientid" => "190200000",
            "bank" => "ziraat",
            "txstatus" => "Y",
            "_charset_" => "UTF-8",
            "HASH" => "azVhhNQXzdGK5sFFHvc4BgbHGW4=",
            "rnd" => "+swQy9TdzMq+GDNwg/7h",
            "HASHPARAMS" => "clientid:oid:AuthCode:ProcReturnCode:Response:mdStatus:cavv:eci:md:rnd:",
            "HASHPARAMSVAL" => "190200000BWJ1KOrfY8aHY2fL09871600Approved1AAABABCQAgAAAAAhVZACAAAAAAA=05454671:A48A90147FC236CCD3D41093426C710CFB622166644480DC42EBA6F104EA677B:4102:##190200000",
        ];

        $user = $this->createUser();
        $user->handlePayment($request);

        $this->assertDatabaseHas((new Transaction())->getTable(), [
            'order_id' => $request['ReturnOid'],
            'transaction_id' => $request['TransId'],
        ]);
    }
}
