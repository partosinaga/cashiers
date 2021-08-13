<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    public $primaryKey = 'id';
    protected $table = 'transactions';

    public $fillable = [
        'uuid',
        'user_id',
        'device_timestamp',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
