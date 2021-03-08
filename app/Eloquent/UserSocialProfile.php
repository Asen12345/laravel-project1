<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserSocialProfile extends Model
{
    use Notifiable;

    protected $table = 'user_social_profiles';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'first_name',
        'last_name',
        'country_id',
        'city_id',
        'company_post',
        'image',
        'work_phone',
        'mobile_phone',
        'skype',
        'web_site',
        'work_email',
        'personal_email',
        'about_me',
        'face_book',
        'linked_in',
        'v_kontakte',
        'odnoklasniki'
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

    public function getCityCollection() {
        return $this->hasOne('App\Eloquent\GeoCity', 'id', 'city_id');
    }

    public function user() {
        return $this->hasOne('App\Eloquent\User', 'id', 'user_id');
    }

    public function getCity() {
        $collection = $this->hasOne('App\Eloquent\GeoCity', 'id', 'city_id')->first();
        return ($collection->title . ', ' . $collection->title_en);
    }

    /*accessor*/
    public function getFaceBookAttribute($value)
    {
        return $this->addHttp($value);
    }
    /*accessor*/
    public function getLinkedInAttribute($value)
    {
        return $this->addHttp($value);
    }
    /*accessor*/
    public function getVKontakteAttribute($value)
    {
        return $this->addHttp($value);
    }
    /*accessor*/
    public function getOdnoklasnikiAttribute($value)
    {
        return $this->addHttp($value);
    }
    public function setFaceBookAttribute($value)
    {
        $this->attributes['face_book'] = $this->addHttp($value);
    }
    /*Mutator*/
    public function setLinkedInAttribute($value)
    {
        $this->attributes['linked_in'] = $this->addHttp($value);
    }
    /*Mutator*/
    public function setVKontakteAttribute($value)
    {
        $this->attributes['v_kontakte'] = $this->addHttp($value);
    }
    /*Mutator*/
    public function setOdnoklasnikiAttribute($value)
    {
        $this->attributes['odnoklasniki'] = $this->addHttp($value);
    }
    /*Mutator*/
    public function setWebSiteAttribute($value)
    {
        $this->attributes['web_site'] = $this->addHttp($value);
    }
    public function getWebSiteAttribute($value)
    {
        return $this->addHttp($value);
    }

    function addHttp($url) {
        if ($url !== null && !preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }
}
