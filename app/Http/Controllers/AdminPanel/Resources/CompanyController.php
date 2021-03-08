<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\Company;
use App\Eloquent\CompanyType;
use App\Eloquent\User;
use App\Eloquent\UserSocialProfile;
use App\Http\PageContent\AdminPanel\Resources\CompanyPageContent;
use DB;
use ErrorException;
use Exception;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->sort_by)) {
            $sortBy = 'name';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'asc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $query = Company::orderBy($sortBy, $sortOrder);

        // if not set - select with linked experts only
        if (!$request->exists('all')) {
            $query->whereHas('users', function($query) {
                $query->whereIn('permission', ['expert']);
            });
        }

        $type = $query->withCount(['users' => function($query) {
                $query->where('permission', 'expert');
            }])
            ->withCount(['typeCompany as type_count' => function($query){
                $query->select('name');
            }])
            ->paginate('50', array('id', 'name'));


        $content = (new CompanyPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));
        return view('admin_panel.resources.company.index', [
            'content' => $content,
            'data'    => $type,
        ]);
    }

    public function merge(Request $request) {

        $companies = Company::whereIn('id', $request->company)->get();
        $str = null;
        foreach ($companies as $company) {
            $userProfile = UserSocialProfile::where('company_id', $company->id)
                ->whereHas('user', function ($query) {
                    $query->where('permission', 'company');
                })->get();
            if (!$userProfile->isEmpty()) {
                $str = $str . $company->name . ', ';
            };
        }

        if (isset($str)) {
            return redirect()->back()->withErrors(['Компании ' . $str . ' являются пользователями.']);
        }

        $content = (new CompanyPageContent())->mergePageContent();
        $all_companies = Company::all();
        return view('admin_panel.resources.company.merge', [
            'content'        => $content,
            'companies'      => $companies,
            'all_companies'  => $all_companies,
        ]);
    }

    public function merged(Request $request) {
        foreach ($request->company_old as $company_id => $value) {
            $old_array[] = $company_id;
        }
        if (empty($old_array)) {
            return redirect()->back()
                ->withErrors(['error' => 'Не выбраны компании.']);
        }
        UserSocialProfile::whereIn('company_id', $old_array)
            ->update([
                'company_id' => $request->company_new,
            ]);
        foreach ($old_array as $item) {
            if ($item != $request->company_new) {
                Company::find($item)->delete();
            }
        }

        return redirect()
            ->route('admin.resources.company')
            ->with('success', 'Запись успешно обновлена.');
    }


    public function create()
    {
        $content = (new CompanyPageContent())->editAndCreateUserPageContent();
        $company_type = CompanyType::all();
        return view('admin_panel.resources.company.create', [
            'content'        => $content,
            'company_type'   => $company_type
        ]);
    }

    public function store(Request $request) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
            'name_en'  => ['nullable', 'string', 'max:255'],
            'type_id'  => ['required', 'integer'],
        ]);
        Validator::make($request->all(), $rules)->validate();
        Company::create($request->all());
        return redirect()
            ->route('admin.resources.company')
            ->with('success', 'Запись успешно добавлена.');
    }


    public function edit($id) {
        $content = (new CompanyPageContent())->editAndCreateUserPageContent();
        $company_type = CompanyType::all();
        $company = Company::where('id', $id)->first();
        $users = $company->users()->where('permission', 'expert')->get();

        return view('admin_panel.resources.company.edit', [
            'content'       => $content,
            'company'       => $company,
            'users'         => $users,
            'company_type'  => $company_type,
        ]);
    }

    public function update(Request $request , $id) {
        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
            'name_en'  => ['nullable', 'string', 'max:255'],
            'type_id'  => ['required', 'integer'],
        ]);

        Validator::make($request->all(), $rules)->validate();

        DB::beginTransaction();
        try{
            Company::find($id)->update($request->except('tags'));
            UserSocialProfile::where('company_id', $id)
                ->whereHas('user', function($query) {
                    $query->where('permission', 'expert');
                })
                ->update(['company_id' => null]);
            User::where('permission', 'company')
                ->whereHas('socialProfile', function($query) use ($id) {
                    $query->where('permission', 'company')
                        ->where('company_id', $id);
                })
                ->update(['name' => $request->name]);
            if (!empty($request->users)){
                foreach ($request->users as $user_id) {
                    $user = User::find($user_id);
                    $user->socialProfile()->update(['company_id' => $id]);
                    $user->update(['private' => false]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return redirect()
            ->route('admin.resources.company')
            ->with('success', 'Запись успешно обновлена.');
    }

    public function destroy($id) {
        DB::beginTransaction();
        try{
            $userProfile = UserSocialProfile::where('company_id', $id)->get();
            foreach ($userProfile as $profile) {
                if ($profile->user->permission == 'company') {
                    throw new ErrorException('Вы не можете удалить компанию, так как она привязана к пользователю');
                } else {
                    $userProfile->update(['company_id' => null]);
                }
            }
            Company::find($id)->delete();
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
