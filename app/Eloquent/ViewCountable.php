<?php


namespace App\Eloquent;


trait ViewCountable
{
    // model's views count key
    protected $views_count_key = 'views_count';

    /**
     * Increment views count in current model data
     * Update database row with new value
     */
    public function incrementViewsCount()
    {
        $key = $this->views_count_key;
        $this->$key += 1;
        $this->save();
    }
}