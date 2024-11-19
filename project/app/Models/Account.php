<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    protected $table = 'Accounts';
    protected $primaryKey = 'AccountID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Username', 'Password', 'Role', 'AccountStatus', 'Credentials', 'IV'
    ];

    protected $hidden = [
        'Password', 'IV',
    ];
}