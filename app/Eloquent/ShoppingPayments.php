<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShoppingPayments extends Model
{
    use Notifiable;

    protected $table = 'shopping_payments';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'shopping_cart_id',
        'type',
        'company',
        'legal_address',
        'postal_code',
        'inn',
        'kpp',
        'name',
        'position',
        'phone',
        'email',
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

    public function cart()
    {
        return $this->hasMany('App\Eloquent\ShoppingCart', 'id', 'shopping_cart_id');
    }
}
