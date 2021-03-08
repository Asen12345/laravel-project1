<?php

namespace App\Eloquent;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use PhpParser\ErrorHandler\Collecting;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;

class User extends Authenticatable implements ViewableContract
{
    use Notifiable;
    use Viewable;
    use ViewCountable;

    protected $guard = 'user';
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'company',
        'password',
        'open_password',
        'active',
        'block',
        'private',
        'invitations',
        'permission',
        'notifications_subscribed',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                       => 'increments',
        'name'                     => 'string',
        'email'                    => 'string',
        'company'                  => 'string',
        'password'                 => 'string',
        'open_password'            => 'string',
        'role'                     => 'string',
        'active'                   => 'boolean',
        'block'                    => 'boolean',
        'invitations'              => 'boolean',
        'notifications_subscribed' => 'boolean',
        'remember_token'           => 'string',
        'created_at'               => 'timestamp',
        'updated_at'               => 'timestamp',
        'active_at'                => 'timestamp',
        'email_verified_at'        => 'timestamp',
        'last_login_at'            => 'timestamp',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'active_at',
        'email_verified_at',
    ];


    /*public function shouldBeSearchable()
    {
        if ($this->active == true) {
            return true;
        } else {
            return false;
        }
    }
    public function toSearchableArray()
    {
        $array = $this->toArray();
        return array('id' => $array['id'], 'title' => $array['name'], 'table' => 'users', 'update_at' => Carbon::parse($array['updated_at'])->toTimeString());
    }*/


    public function socialProfile() {
        return $this->hasOne('App\Eloquent\UserSocialProfile', 'user_id', 'id');
    }
    public function privacy() {
        return $this->hasOne('App\Eloquent\UserPrivacy', 'user_id', 'id');
    }
    public function company() {
        return $this->hasOneThrough('App\Eloquent\Company', 'App\Eloquent\UserSocialProfile', 'user_id', 'id', 'id', 'company_id');
    }
    public function news()
    {
        return $this->hasMany('App\Eloquent\News', 'author_user_id', 'id');
    }
    public function blog()
    {
        return $this->hasOne('App\Eloquent\Blog', 'user_id', 'id');
    }
    public function blogPosts()
    {
        return $this->hasmanyThrough('App\Eloquent\BlogPost', 'App\Eloquent\Blog', 'user_id', 'blog_id', 'id', 'id');
    }

    public function blogsSubscribe() {
        return $this->hasmanyThrough('App\Eloquent\Blog', 'App\Eloquent\BlogPostSubscriber', 'user_id', 'id', 'id', 'blog_id');
    }

    public function friends()
    {
        return $this->hasMany('App\Eloquent\Friends', 'user_id', 'id');
    }

    public function services()
    {
        return $this->hasMany('App\Eloquent\UserService', 'user_id', 'id');
    }

    public function requestFriends()
    {
        return $this->hasMany('App\Eloquent\Friends', 'friend_id', 'id');
    }
    public function friendsUsers()
    {
        return $this->hasmanyThrough('App\Eloquent\User', 'App\Eloquent\Friends', 'user_id', 'id', 'id', 'friend_id');
    }

    public function thread() {
        return $this->hasmanyThrough('App\Eloquent\MessageThread', 'App\Eloquent\MessageParticipant', 'user_id', 'id', 'id', 'thread_id');
    }
    public function messages() {
        return $this->hasmanyThrough('App\Eloquent\Message', 'App\Eloquent\MessageParticipant', 'user_id', 'thread_id', 'id', 'thread_id');
    }

    public function allMessages() {
        $messages = collect();
        foreach ($this->thread as $subject) {
            $subject->messages->each(function( $fixture ) use ($messages) {
                $messages->push( $fixture );
            });
        }
        return $messages;
    }

    /*Return user who send me request friend*/
    public function requestFriendsUsers()
    {
        return $this->hasmanyThrough('App\Eloquent\User', 'App\Eloquent\Friends', 'friend_id', 'id', 'id', 'user_id');
    }

    /**
     * @param int $max
     * @param int $excite_id
     * @return Collecting|null
     */
    public function otherStaff($max = 3, $excite_id) {
        if ($this->company) {
           $users = $this->company->users->where('active', true)->where('private', 0)->where('id', '!=', $excite_id);
            if ($users->count() < $max) {
                return $users->all();
            } else {
                return $users->random($max);
            }
        } else {
            return null;
        }
    }

    public function topicAnswers()
    {
        return $this->hasMany('App\Eloquent\TopicAnswer', 'user_id', 'id');
    }

    public function topicSubscribers()
    {
        return $this->hasMany('App\Eloquent\TopicSubscriber', 'user_id', 'id');
    }

    public function mailingAdminMessage()
    {
        return $this->hasMany('App\Eloquent\MailingUser', 'user_id', 'id');
    }
    public function mailingAdmin()
    {
        return $this->hasMany('App\Eloquent\MailingUser', 'user_id', 'id');
    }

    public function topics() {
        return $this->hasmanyThrough('App\Eloquent\Topic', 'App\Eloquent\TopicSubscriber', 'user_id', 'id', 'id', 'topic_id');
    }

    public function notifyCommentsRecord()
    {
        return $this->hasMany('App\Eloquent\TopicSubscriber', 'user_id', 'id');
    }

    public function comments() {
        return $this->hasMany('App\Eloquent\BlogPostDiscussion', 'user_id', 'id');
    }

    public function cityName($id) {
        $city = GeoCity::find($id);
        return $city->title . ' , ' . $city->title_en;
    }

    public function privacyNotAuth($type)
    {
        $privacy = $this->privacy->$type;
        if ($privacy == 'friends' || $privacy == 'auth_only' || $privacy == 'none') {
            return false;
        } else {
            return true;
        }
    }

    public function cart(){
        return $this->hasMany('App\Eloquent\ShoppingCart', 'user_id', 'id');
    }

    /**
     * Determine is user has expert type account
     *
     * @return bool
     */
    public function isExpert()
    {
        return $this->permission === 'expert';
    }

    /**
     * Update user password
     *
     * @param string $password Password string
     * @param string $hash Hashed password string
     */
    public function updatePassword($password, $hash)
    {
        $this->password = $hash;
        $this->open_password = $password;
        $this->save();
    }
}
