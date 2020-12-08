<?php


namespace TarfinLabs\LaravelPos;


use TarfinLabs\LaravelPos\Exceptions\CardException;

class Card
{
    public const CARD_TYPE_VISA = 1;
    public const CARD_TYPE_MASTER = 2;

    protected string $number;
    protected string $expireYear;
    protected string $expireMonth;
    protected string $cvv;
    protected string $holderName;
    protected string $type;
    protected ?string $issuer;

    /**
     * Card constructor.
     *
     * @param string $number
     * @param string $expireYear
     * @param string $expireMonth
     * @param string $cvv
     * @param string $holderName
     * @throws CardException
     */
    public function __construct(
        string $number,
        string $expireYear,
        string $expireMonth,
        string $cvv,
        string $holderName
    ) {
        $this->number = $number;
        $this->expireYear = $expireYear;
        $this->expireMonth = $expireMonth;
        $this->cvv = $cvv;
        $this->holderName = $holderName;
        $this->type = $this->getCardBrand();
        $this->issuer = $this->resolveIssuer();
    }

    /**
     * Find card issuer from given bin list.
     *
     * @return false|mixed
     */
    protected function resolveIssuer(){

        if (file_exists(config('laravel-pos.bin_file_path'))){
            $file = file_get_contents(config('laravel-pos.bin_file_path'));
            $binList = json_decode($file, true);
            $foundBin = array_filter($binList, function ($item) {
                return $item['bin'] == substr($this->number,0,6);
            });

            return $foundBin[key($foundBin)]['bank'];
        }

        return false;
    }

    /**
     * Check card brand for visa and mastercard.
     *
     * @return int
     * @throws CardException
     */
    public function getCardBrand()
    {
        if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $this->number)) {
            return self::CARD_TYPE_VISA;
        } elseif (preg_match('/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/', $this->number)) {
            return self::CARD_TYPE_MASTER;
        }

        throw new CardException('Invalid card brand.');
    }

    /**
     * Credit card issuer.
     *
     * @return false|mixed|string|null
     */
    public function getCardIssuer()
    {
        return $this->issuer;
    }

    /**
     * Credit card pan number.
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Credit card expire year.
     *
     * @return string
     */
    public function getExpireYear(): string
    {
        return $this->expireYear;
    }

    /**
     * Credit card expire month.
     *
     * @return string
     */
    public function getExpireMonth(): string
    {
        return $this->expireMonth;
    }

    /**
     * Credit card cvv code.
     *
     * @return string
     */
    public function getCvv(): string
    {
        return $this->cvv;
    }

    /**
     * Credit card owner name.
     *
     * @return string
     */
    public function getHolderName(): string
    {
        return $this->holderName;
    }

    /**
     * Credit card type visa or mastercard
     * .
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
