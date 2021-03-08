<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShoppingCart extends Model
{
    use Notifiable;

    protected $table = 'shopping_carts';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'status',
        'total',
        'remind'
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

    public function purchases()
    {
        return $this->hasMany('App\Eloquent\ShoppingPurchase', 'shopping_cart_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\Eloquent\User', 'id', 'user_id');
    }

    public function payment()
    {
        return $this->hasOne('App\Eloquent\ShoppingPayments', 'shopping_cart_id', 'id');
    }
}
