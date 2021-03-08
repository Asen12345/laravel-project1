<?php

namespace App\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NewsletterCount extends Model
{
    use Notifiable;

    protected $table = 'newsletter_counts';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail_count',
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

    /*accessor*/
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat("DD MMMM YYYY, H:mm");
    }
}
