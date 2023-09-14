<?php

namespace App\Models;

use App\Models\Concerns\InvoiceTrait;
use App\Models\Concerns\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, InvoiceTrait, Searchable;

    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
