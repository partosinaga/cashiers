<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionItems extends Model
{
    public $primaryKey = 'id';
    protected $table = 'transaction_items';

    public $fillable = [
        'uuid',
        'transaction_id',
        'title',
        'qty',
        'price',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
