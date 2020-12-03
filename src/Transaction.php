<?php


namespace TarfinLabs\LaravelPos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $guarded = [];

    protected $table = 'laravel_pos_transactions';

    protected $dates = [
        'paid_at',
    ];

    public function billable():morphTo
    {
        return $this->morphTo();
    }
}
