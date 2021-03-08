<?php

namespace App\Http\Controllers\AdminPanel\Newsletter;

use App\Eloquent\UnsubscribedUser;
use App\Http\PageContent\AdminPanel\Newsletter\NewsletterPageContent;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UnsubscribedController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('email')) {
            $this->validate($request, [
                'email'     => 'string',
            ]);
            $users = UnsubscribedUser::where('email', 'LIKE', '%' . $request->input('email') . '%')
                ->select('id', 'email');
        } else {
            $users = UnsubscribedUser::whereNotNull('id')->select('id', 'email');
        }

        $content = (new NewsletterPageContent())->indexUnsubscribedPageContent();

        return view('admin_panel.newsletter.unsubscribed.index', [
            'content' => $content,
            'users'   => $users->paginate('30'),
            'filter'  => $request->input('email') ?? null,
            'count'   => $users->count(),
        ]);

    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            UnsubscribedUser::where('id', $id)->delete();

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();

            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);

        }

        return redirect()
            ->back()
            ->with('success', 'Запись успешно удалена.');
    }
}
