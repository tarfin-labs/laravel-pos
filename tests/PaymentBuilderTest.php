<?php


namespace TarfinLabs\LaravelPos\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use TarfinLabs\LaravelPos\Card;
use TarfinLabs\LaravelPos\LaravelPosFacade as LaravelPos;
use TarfinLabs\LaravelPos\Order;
use TarfinLabs\LaravelPos\PaymentBuilder;

class PaymentBuilderTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function TestResolveBankConfigByBankIssuer(){
        $card = new Card('5401341234567891', '', '', '', '');
        $paymentBuilder = LaravelPos::builder();
        $paymentBuilder->setConfigWithIssuer($card->getCardIssuer());
        $this->assertSame($paymentBuilder->getBankConfig()['name'], 'T.C.ZİRAAT BANKASI A.Ş.');
    }

    /**
     * @test
     */
    public function TestPaymentBuilderRequest(){

        Http::fake();

        $card = new Card('5401341234567891', '', '', '', '');
        $order = new Order(Str::random(), 19, 1);
        $paymentBuilder = LaravelPos::builder();
        $paymentBuilder
            ->bank('ZİRAAT BANKASI')
            ->card($card)
            ->order($order)
            ->okUrl('http://test.tarfin.com/ok')
            ->failUrl('http://test.tarfin.com/fail')
            ->charge();

        Http::assertSent(function($request) use ($paymentBuilder, $card, $order){
            return $request->url() === $paymentBuilder->getBankConfig()['base_url']
                && $request['pan'] == $card->getNumber()
                && $request['cv2'] == $card->getCvv()
                && $request['taksit'] == $order->getInstallment()
                && $request['amount'] == $order->getAmount();
        });
    }
}
