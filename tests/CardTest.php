<?php


namespace TarfinLabs\LaravelPos\Tests;


use TarfinLabs\LaravelPos\Card;
use TarfinLabs\LaravelPos\Exceptions\CardException;

class CardTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function TestCardDetectValidBrand(){

        $card = new Card('4546711234567894', '', '', '', '');

        $this->assertSame($card->getCardBrand(), CARD::CARD_TYPE_VISA);
    }

    /**
     * @test
     */
    public function TestCardDetectValidWithMasterCardBrand(){

        $card = new Card('5401341234567891', '', '', '', '');

        $this->assertSame($card->getCardBrand(), CARD::CARD_TYPE_MASTER);
    }

    /**
     * @test
     */
    public function TestCardDetectInvalidCardBrand(){

        $this->expectException(CardException::class);

        $card = new Card('1234401341234567891', '', '', '', '');
    }


    /**
     * @test
     */
    public function TestDetectBankFromBinNumber(){
        $card = new Card('5401341234567891', '', '', '', '');

        $cardIssuer = $card->getCardIssuer();

        $this->assertSame($cardIssuer, 'T.C.ZİRAAT BANKASI A.Ş.');
    }
}
