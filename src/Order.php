<?php


namespace TarfinLabs\LaravelPos;


class Order
{
    protected $id;
    protected string $amount;
    protected string $installment;
    protected string $rnd;

    /**
     * Order constructor.
     * @param $id
     * @param  string  $amount
     * @param  string  $installment
     */
    public function __construct($id, string $amount, string $installment)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->installment = $installment;
        $this->rnd = microtime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getInstallment(): string
    {
        return $this->installment;
    }

    /**
     * @return float|string
     */
    public function getRnd()
    {
        return $this->rnd;
    }
}
