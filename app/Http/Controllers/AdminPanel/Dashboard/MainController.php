<?php

namespace App\Http\Controllers\AdminPanel\Dashboard;

use App\Eloquent\Blog;
use App\Eloquent\News;
use App\Eloquent\ShoppingCart;
use App\Eloquent\TopicAnswer;
use App\Eloquent\User;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index() {

        $usersNotActive        = User::where('active', false)->count();
        $newsNotActive         = News::where('published', false)->count();
        $blogsNotActive        = Blog::where('active', false)->count();
        $topicAnswersNotActive = TopicAnswer::where('published', false)->count();
        $ordersNotActive       = ShoppingCart::where('status', 'waiting')->count();

        return view('admin_panel.dashboard.index', [
            'usersNotActive'        => $usersNotActive,
            'newsNotActive'         => $newsNotActive,
            'blogsNotActive'        => $blogsNotActive,
            'topicAnswersNotActive' => $topicAnswersNotActive,
            'ordersNotActive'       => $ordersNotActive,
        ]);
    }
}
