<?php

namespace App\Http\Controllers\AdminPanel\Feedback;

use App\Eloquent\FeedbackSubject;
use App\Http\PageContent\AdminPanel\Feedback\FeedbackPageContent;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->sort_by)) {
            $sortBy = 'id';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'asc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $feedback = FeedbackSubject::orderBy($sortBy, $sortOrder)
            ->paginate('30');

        $content = (new FeedbackPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));
        return view('admin_panel.feedback.feedback', [
            'content' => $content,
            'data'    => $feedback,
        ]);
    }

    public function destroy($id)
    {
        FeedbackSubject::find($id)->delete();
        return redirect()->back()->with('success', 'Запись удачно удалена');
    }

    public function create()
    {
        $content = (new FeedbackPageContent())->editAndCreateUserPageContent();
        return view('admin_panel.feedback.create', [
            'content'   => $content
        ]);
    }

    public function edit($id)
    {
        $feedback = FeedbackSubject::find($id);
        $content = (new FeedbackPageContent())->editAndCreateUserPageContent();
        return view('admin_panel.feedback.create', [
            'content'   => $content,
            'feedback'  => $feedback,
        ]);
    }

    public function store(Request $request)
    {
        $rules = ([
            'subject'  => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email'],
        ]);
        Validator::make($request->all(), $rules)->validate();
        FeedbackSubject::create($request->all());
        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Запись успешно добавлена.');
    }

    public function update(Request $request)
    {
        $feedback = FeedbackSubject::find($request->id);

        if ($request->type == 'email') {
            if (!filter_var($request->value, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['response' => 'Не верный формат']);
            }
        }
        DB::beginTransaction();
        try {
            if ($request->type == 'subject') {
                $feedback->update([
                    'subject'  => $request->value
                ]);
            }
            if ($request->type == 'email') {

                $feedback->update([
                    'email'  => $request->value
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return response()->json(['response' => $id_index]);
        }

        return response()->json(['response' => 'Запись успешно обновлена.']);
    }
}
