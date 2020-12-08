<?php


namespace TarfinLabs\LaravelPos\Tests;


use TarfinLabs\LaravelPos\Card;
use TarfinLabs\LaravelPos\Order;
use TarfinLabs\LaravelPos\PaymentBuilder;

class PaymentBuilderTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function TestResolveBankConfigByBankIssuer(){
        $card = new Card('5401341234567891', '', '', '', '');
        $paymentBuilder = new PaymentBuilder();
        $paymentBuilder->setConfigWithIssuer($card->getCardIssuer());
        $this->assertSame($paymentBuilder->getBankConfig()['name'], 'T.C.ZİRAAT BANKASI A.Ş.');
    }
}
