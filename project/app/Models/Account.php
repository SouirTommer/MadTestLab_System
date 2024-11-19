<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    protected $table = 'Accounts';
    protected $primaryKey = 'AccountID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'AccountID', 'Password', 'Role', 'AccountStatus', 'Credentials', 'IV'
    ];

    protected $hidden = [
        'Password', 'IV',
    ];
}