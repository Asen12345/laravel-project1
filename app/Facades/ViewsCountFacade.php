<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ViewsCountFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'viewscount';
    }
}