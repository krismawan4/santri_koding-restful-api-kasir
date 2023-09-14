<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeHasRole($query, $role)
    {
        return $query->where('id', auth()->id())
            ->where('role', $role);
    }
}
