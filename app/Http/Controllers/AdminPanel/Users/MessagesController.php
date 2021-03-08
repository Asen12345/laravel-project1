<?php

namespace App\Http\Controllers\AdminPanel\Users;

use App\Eloquent\CompanyType;
use App\Eloquent\Mailing;
use App\Eloquent\MailingUser;
use App\Eloquent\User;
use App\Http\PageContent\AdminPanel\Users\MessagesPageContent;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Storage;
use Validator;

class MessagesController extends Controller
{
    public function index() {
        $company_types = CompanyType::all();
        return view('admin_panel.messages.messages', [
            'content'       => (new MessagesPageContent())->homePageContent(),
            'company_types' => $company_types,
        ]);
    }

    public function store(Request $request) {
        $add_all = $this->checkboxToBoolean($request->add_all);
        $rules = ([
            'subject'         => ['required', 'string', 'max:255'],
            'text'            => ['required', 'string'],
            'users_id'        => ['required_if:add_all,!=,'],
            'file'            => ['file', 'max:2024'],
        ]);
        $messages = [
            'subject.required'      => 'Тема обязателена к заполнению.',
            'text.required'         => 'Текст сообщения обязателен.',
            'users_id.required'     => 'Введите получателей.',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        DB::beginTransaction();
        try {
            if (!empty($request->file)){
                $file_content = $request->file('file');
                $filename = time() . '.' . $file_content->getClientOriginalExtension();
                $dir_name = Carbon::now()->format('Y-m-d');
                Storage::disk('private')->makeDirectory($dir_name);
                $url = Storage::disk('private')->putFileAs($dir_name, $file_content, $filename);
            }
            $mail = Mailing::create([
                'subject'  => $request->subject,
                'text'     => $request->text,
                'all_user' => $add_all,
                'file_patch' => $url ?? null
            ]);
            if ($add_all === false) {
                foreach ($request->users_id as $user_id) {
                    $user = User::find($user_id);
                    if ($user->invitations === true && $user->active === true && $user->block === false) {
                        MailingUser::create([
                            'mailing_id' => $mail->id,
                            'user_id'    => $user_id
                        ]);
                    }
                }
            } else {
                $users = User::whereNotNull('id')
                    ->where(function ($query) {
                        $query->where('permission', '!=', 'social')
                            ->where('invitations', true)
                            ->where('active', true)
                            ->where('block', false);
                    })->get();
                foreach ($users as $user) {
                    MailingUser::create([
                        'mailing_id' => $mail->id,
                        'user_id'    => $user->id
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return ['error' => $id_index];
        }

        return redirect()->back()
            ->with('success', 'Сообщение отправлено.');

    }

    public function autocompleteUsers (Request $request) {
        $input = $request->input('value');
        $data = User::where(function ($query) use ($input) {
            if($input == '') {
                $query->where('name', 'LIKE', '%' .''. '%');
            } elseif(is_numeric($input)) {
                $query->where('id',  $input)
                    ->orWhere('name', 'LIKE', '%' . $input . '%');
            } else {
                $query->where('name', 'LIKE', '%' . $input . '%')
                    ->orWhere('email', 'LIKE', '%' . $input . '%');
                };
        })
            ->take(15)->select('name', 'id')->get();

        return response()->json($data);
    }

    public function autocompleteCompany(Request $request) {
        if (!empty($request->input('id'))) {
            $company_id = $request->input('id');
            $users = User::whereHas('company', function (Builder $query) use ($company_id) {
                $query->where('type_id', '=', $company_id);
            })->select('id', 'name')->get();
        } elseif (!empty($request->input('type'))) {
            $users = User::where('permission',$request->input('type'))->select('id', 'name')->get();
        } else {
            $users = null;
        }

        return response()->json($users);
    }
}
