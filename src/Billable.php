<?php


namespace TarfinLabs\LaravelPos;


use TarfinLabs\LaravelPos\Concerns\ManagesTransactions;

trait Billable
{
    use ManagesTransactions;
}
