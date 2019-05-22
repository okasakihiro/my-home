<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    /**
     * Relationship table 'exchange_rate'
     */
    protected $table = 'exchange_rate';

    public $timestamps = false;
}
