<?php


namespace TarfinLabs\LaravelPos;


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

    /**
     * Card constructor.
     * @param  string  $number
     * @param  string  $expireYear
     * @param  string  $expireMonth
     * @param  string  $cvv
     * @param  string  $holderName
     * @param  string  $type
     */
    public function __construct(
        string $number,
        string $expireYear,
        string $expireMonth,
        string $cvv,
        string $holderName,
        string $type
    ) {
        $this->number = $number;
        $this->expireYear = $expireYear;
        $this->expireMonth = $expireMonth;
        $this->cvv = $cvv;
        $this->holderName = $holderName;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getExpireYear(): string
    {
        return $this->expireYear;
    }

    /**
     * @return string
     */
    public function getExpireMonth(): string
    {
        return $this->expireMonth;
    }

    /**
     * @return string
     */
    public function getCvv(): string
    {
        return $this->cvv;
    }

    /**
     * @return string
     */
    public function getHolderName(): string
    {
        return $this->holderName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
