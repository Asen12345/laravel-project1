<?php

namespace App\Http\Controllers\Front\TextPage;

use App\Eloquent\TextPage;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\TextPage\TextPageContent;

class TextPageController extends Controller
{
    public function index($url_en)
    {
        $textPage = TextPage::where('published', true)->where('url_en', $url_en)->first();

        return view('front.page.text_page.text_page', [
            'textPage'     => $textPage,
            'content_page' => (new TextPageContent())->indexPageContent($textPage)
        ]);
    }
}
