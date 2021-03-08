<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\DataTemplate;
use App\Eloquent\DebugMode;
use App\Http\PageContent\AdminPanel\Resources\DataTemplatePageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataTemplateController extends Controller
{

    public function index()
    {
        $data_template = DataTemplate::all()->keyBy('page')->toArray();
        return view('admin_panel.resources.data_template', [
            'content' => (new DataTemplatePageContent())->homePageContent(),
            'data_template' => $data_template,
        ]);
    }

    public function update(Request $request)
    {
        DataTemplate::where('page', 'password_recovery')->update([
            'before' => $request->password_recovery_before,
            'after'  => $request->password_recovery_after,
        ]);
        DataTemplate::where('page', 'register_page')->update([
            'before' => $request->register_page_before,
            'after'  => $request->register_page_after,
        ]);
        return redirect()->back()
            ->with('success', 'Настройка успешно сохранена.');
    }
}
