<?php

namespace App\Http\Controllers\AdminPanel\Shop;

use App\Eloquent\ShoppingCart;
use App\Eloquent\User;
use App\Http\PageContent\AdminPanel\Shop\OrdersPageContent;
use App\Repositories\Back\Shop\ShoppingCartRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{

    public function index (Request $request)
    {
        $parameters = $request->all();

        if (empty($request->sort_by)) {
            $sortBy = 'id';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $content = (new OrdersPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $orders = (new ShoppingCartRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->with('user')
            ->withCount(['purchases as total_count' => function ($query) {
                $query->select(DB::raw('SUM(price) as total_count'));
            }]);

        if (count($parameters) > 0) {
            $orders = $orders->orderBy($sortBy, $sortOrder)->paginate(30);
            $orders->appends($parameters);
        } else {
            $orders = $orders->orderBy($sortBy, $sortOrder)->paginate(30);
        }


        return view('admin_panel.shop.order.index', [
            'content'      => $content,
            'orders'       => $orders,
            'sort_by'      => $sortBy,
            'sort_order'   => $sortOrder
        ]);
    }

    public function buyers (Request $request) {

        if (empty($request->sort_by)) {
            $sortBy = 'id';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $content = (new OrdersPageContent())->byersPageContent($request->except('_token', 'sort_by', 'sort_order'));

        $buyers = User::whereHas('cart')
            ->withCount('cart')
            ->orderBy($sortBy, $sortOrder)
            ->paginate('30');;

        $filter_data = $request->except('_token');

        return view('admin_panel.shop.order.buyers', [
            'content'      => $content,
            'buyers'       => $buyers,
            'filter_data'  => $filter_data,
            'sort_by'      => $sortBy,
            'sort_order'   => $sortOrder
        ]);
    }

    public function orderPage($id)
    {
        $content     = (new OrdersPageContent())->editPageContent();
        $order  = ShoppingCart::where('id', $id)
            ->withCount(['purchases as total_count' => function ($query) {
                $query->select(DB::raw('SUM(price) as total_count'));
            }])
            ->with('purchases')
            ->with('user')
            ->first();

        return view('admin_panel.shop.order.edit', [
            'content'    => $content,
            'order'      => $order,
        ]);
    }

    public function orderUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if ($request->status !== null) {
                (new ShoppingCartRepository())->getById($id)
                    ->update([
                        'status' => $request->status,
                    ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();

        }
        return redirect()
            ->route('admin.shop.researches.orders')
            ->with('success', 'Статус Заказа обновлен');
    }

    public function checkBox(Request $request){

        DB::beginTransaction();
        try {
            if ($request->active !== null) {
                (new ShoppingCartRepository())->getById($request->id)
                    ->update([
                        'status' => $request->active,
                    ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return response()->json(array('success' => 'false', 'error' => $id_index));
        }

        return response()->json(array('success' => 'ok', 'mess' => 'Запись успешно обновлена.'));
    }
}
