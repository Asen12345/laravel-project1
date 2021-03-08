<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\DebugMode;
use App\Http\PageContent\AdminPanel\Resources\DebugModePageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DebugModeController extends Controller
{
    public function index() {
        $debug_setting = DebugMode::first();
        return view('admin_panel.resources.debug_mode', [
            'content'       => (new DebugModePageContent())->homePageContent(),
            'debug_setting' => $debug_setting,
        ]);

    }
    public function update(Request $request) {
        $request['debug'] = $this->checkboxToBoolean($request->debug);
        DebugMode::first()->update($request->all());
        return redirect()->back()
            ->with('success', 'Настройка успешно сохранена.');
    }
}
