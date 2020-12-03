<?php


namespace TarfinLabs\LaravelPos\Tests;


use PHPUnit\Framework\TestCase;
use TarfinLabs\LaravelPos\Card;
use TarfinLabs\LaravelPos\Order;
use TarfinLabs\LaravelPos\PaymentBuilder;

class LaravelPosTest extends TestCase
{
    public function PaymentTest(){

        $card = new Card('5555555655565555', '21', '11', 473, 'HAKAN ÖZDEMİR', Card::CARD_TYPE_VISA);
        $order = new Order();
        $paymentBuilder = new PaymentBuilder();
        /*$paymentBuilder
            ->card($card)
            ->okUrl('https://api.tarfin.com/ok')
            ->failUrl('https://api.tarfin.com/ok')
            ->order()*/
    }
}
