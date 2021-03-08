<?php


namespace App\Services\ViewsCount;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class ViewsCountService
{
    // cookie data array
    public $cookie_data;

    // cookie root key
    const ROOT_KEY = 'v';

    // model -> cookie key assoc
    // names are short to help stay short as possible
    const KEYS = [
        'App\Eloquent\Banner'        => 'b',
        'App\Eloquent\BlogPost'      => 'bl',
        'App\Eloquent\News'          => 'n',
        'App\Eloquent\Researches'    => 'r',
        'App\Eloquent\User'          => 'u'
    ];

    // all processable models must use this trait
    const COUNTABLE_TRAIT = 'App\Eloquent\ViewCountable';

    /**
     * ViewsCountService constructor.
     */
    public function __construct()
    {
        if (!Cookie::has(self::ROOT_KEY)) {
            $this->cookie_data = $this->initCookieDataArray();
            // enqueue in case last operation here
            $this->enqueueCookie();
            return;
        }

        $this->cookie_data = json_decode(Cookie::get(self::ROOT_KEY), true);
    }

    /**
     * Handle model's views count
     * Increment if
     *
     * @param Model $model
     */
    public function process(Model $model)
    {
        $classname = get_class($model);

        // if class processable - exit
        if (!$this->isProcessable($model)) {
            return;
        }

        // if model was already viewed, just do nothing
        if ($this->isAlreadyVisited(self::KEYS[$classname], $model->id)) {
            return;
        }

        $this->incrementViews(self::KEYS[$classname], $model);
    }

    /**
     * Determine is current model processable
     * Model name must be registered in KEYS assoc array
     * and use ViewCountable trait
     *
     * @param Model $model
     * @return bool
     */
    protected function isProcessable(Model $model)
    {
        $class = get_class($model);
        // class doesn't registered in our keys
        if (!isset(self::KEYS[$class])) {
            return false;
        }

        // class doesn't uses ViewCountable trait
        $traits = class_uses($model);

        if(!$traits || !isset($traits[self::COUNTABLE_TRAIT])) {
            return false;
        }

        return true;
    }

    /**
     * Check is model id already in cookie array
     * Its mean model was viewed before
     *
     * @param $cookie_key
     * @param $model_id
     * @return bool
     */
    protected function isAlreadyVisited($cookie_key, $model_id)
    {
        return isset($this->cookie_data[$cookie_key][$model_id]);
    }

    /**
     * Increment views count in model's table row and update cookie data
     *
     * @param $cookie_key
     * @param Model $model
     */
    protected function incrementViews($cookie_key, Model $model)
    {
        // perform model updating
        $model->incrementViewsCount();

        // build views array as hash table with id as key,
        // to prevent whole array scan for searching required id
        $this->cookie_data[$cookie_key][$model->id] = true;
        $this->enqueueCookie();
    }

    /**
     * Build initial cookie data array
     *
     * @return array[]
     */
    private function initCookieDataArray()
    {
        $cookie_data = [];
        foreach(self::KEYS as $class => $key) {
            $cookie_data[$key] = [];
        }

        return $cookie_data;
    }

    /**
     * Enqueue cookie to next response
     * TTL = 5 years (like forever)
     */
    private function enqueueCookie()
    {
        $ttl = 60 * 24 * 365 * 5;
        Cookie::queue(self::ROOT_KEY, json_encode($this->cookie_data), $ttl);
    }
}