<?php


namespace TarfinLabs\LaravelPos\Tests\Fixtures;


use Illuminate\Database\Eloquent\Model;
use TarfinLabs\LaravelPos\Billable;

class User extends Model
{
    use Billable;

    protected $guarded = [];
}
