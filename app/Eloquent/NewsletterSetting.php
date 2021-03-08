<?php

namespace App\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NewsletterSetting extends Model
{
    use Notifiable;

    protected $table = 'newsletter_settings';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'weekdays',
        'send_time',
        'email',
        'footer_text',
        'unsubscribe_text',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'weekdays' => 'json',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /*accessor*/
    public function getSendTimeAttribute($value)
    {
        return Carbon::createFromIsoFormat('HH:mm:ss', $value)->isoFormat('HH:mm');
    }
}
