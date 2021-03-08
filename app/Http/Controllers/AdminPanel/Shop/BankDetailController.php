<?php

namespace App\Http\Controllers\AdminPanel\Shop;

use App\Eloquent\BankDetail;
use App\Http\PageContent\AdminPanel\Resources\BankDetailPageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankDetailController extends Controller
{
    public function index() {
        $bank = BankDetail::first();
        return view('admin_panel.shop.bank_detail', [
            'content'   => (new BankDetailPageContent())->homePageContent(),
            'bank'      => $bank,
        ]);

    }
    public function update(Request $request) {
        $bankDetail = BankDetail::first();
        if (empty($bankDetail)) {
            BankDetail::create($request->all());
        } else {
            $bankDetail->update($request->all());
        }

        return redirect()->back()
            ->with('success', 'Настройка успешно сохранена.');
    }
}
