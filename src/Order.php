<?php


namespace TarfinLabs\LaravelPos;


class Order
{
    protected $id;
    protected float $amount;
    protected ?int $installment;
    protected string $rnd;

    /**
     * Order constructor.
     * @param $id
     * @param float $amount
     * @param int $installment
     */
    public function __construct($id, float $amount, int $installment = null)
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
     * @return int|null
     */
    public function getInstallment(): ?int
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
