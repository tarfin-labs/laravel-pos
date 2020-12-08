<?php


namespace TarfinLabs\LaravelPos;


class Customer
{
    protected array $customer;

    /**
     * Customer constructor.
     *
     * @param array $customer
     */
    public function __construct(array $customer)
    {
        $this->customer = $customer;
    }
}
