<?php


namespace TarfinLabs\LaravelPos\Tests;

use TarfinLabs\LaravelPos\Transaction;

class TransactionTest extends FeatureTestCase
{
    /** @test */
    public function check_3d_hash()
    {
        $data = [
            "md" => "454671:7E12CB14D241813E325561959E48BB4177CDF7FD7D7AA80F1EEAE75C9E421FE7:4023:##190200000",
            "cavv" => "AAABCBFCEgAAAAAhV0ISAAAAAAA=",
            "AuthCode" => "135792",
            "oid" => "H8VSnG07S68povhz",
            "mdStatus" => "1",
            "eci" => "05",
            "clientid" => "190200000",
            "rnd" => "J2tvib39eI7vxmpNNtWX",
            "ProcReturnCode" => "00",
            "Response" => "Approved",
            "bank" => "ziraat",
            "HASH" => "4FeSaBJ9J+1NjU/SuhStwzRNEsE=",
            "HASHPARAMS" => "clientid:oid:AuthCode:ProcReturnCode:Response:mdStatus:cavv:eci:md:rnd:",
            "HASHPARAMSVAL" => "190200000H8VSnG07S68povhz13579200Approved1AAABCBFCEgAAAAAhV0ISAAAAAAA=05454671:7E12CB14D241813E325561959E48BB4177CDF7FD7D7AA80F1EEAE75C9E421FE7:4023:##190200000J2tvib39eI7vxmpNNtWX",
        ];

        $user = $this->createUser();
        $this->assertTrue($user->check3DHash($data));
    }
    /**
     * @test
     */
    public function TestTransactionHandleSuccessfully(){
        $request = [
            "md" => "454671:7E12CB14D241813E325561959E48BB4177CDF7FD7D7AA80F1EEAE75C9E421FE7:4023:##190200000",
            "cavv" => "AAABCBFCEgAAAAAhV0ISAAAAAAA=",
            "maskedCreditCard" => "4546 71** **** 7894",
            "amount" => "1",
            "taksit" => "1",
            "currency" => "949",
            "AuthCode" => "135792",
            "oid" => "H8VSnG07S68povhz",
            "mdStatus" => "1",
            "eci" => "05",
            "clientid" => "190200000",
            "rnd" => "J2tvib39eI7vxmpNNtWX",
            "ProcReturnCode" => "00",
            "Response" => "Approved",
            "bank" => "ziraat",
            "TransId" => "20339MveG12427",
            "EXTRA_CARDBRAND" => "VISA",
            "EXTRA_TRXDATE" => "20201204 12:47:29",
            "HASH" => "4FeSaBJ9J+1NjU/SuhStwzRNEsE=",
            "HASHPARAMS" => "clientid:oid:AuthCode:ProcReturnCode:Response:mdStatus:cavv:eci:md:rnd:",
            "HASHPARAMSVAL" => "190200000H8VSnG07S68povhz13579200Approved1AAABCBFCEgAAAAAhV0ISAAAAAAA=05454671:7E12CB14D241813E325561959E48BB4177CDF7FD7D7AA80F1EEAE75C9E421FE7:4023:##190200000J2tvib39eI7vxmpNNtWX",
        ];

        $user = $this->createUser();
        $user->handlePayment($request);

        $this->assertDatabaseHas((new Transaction())->getTable(), [
            'order_id' => $request['oid'],
            'transaction_id' => $request['TransId'],
        ]);
    }
}
