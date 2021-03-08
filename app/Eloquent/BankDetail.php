<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class BankDetail extends Model
{
    use Notifiable;

    protected $table = 'bank_details';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'inn',
        'kpp',
        'recipient',
        'beneficiary_bank',
        'r_account',
        'bic',
        'k_account',
        'name_signatory',
        'position_signatory',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
