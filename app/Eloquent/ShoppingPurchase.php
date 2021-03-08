<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShoppingPurchase extends Model
{
    use Notifiable;

    protected $table = 'shopping_purchases';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'research_id',
        'shopping_cart_id',
        'price',
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
        return $this->belongsTo('App\Eloquent\ShoppingCart', 'shopping_cart_id', 'id');
    }

    public function research()
    {
        return $this->hasOne('App\Eloquent\Researches', 'id', 'research_id');
    }
}
