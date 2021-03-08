<?php

namespace App\Http\Controllers\AdminPanel\Shop;

use App\Eloquent\MailTemplate;
use App\Http\PageContent\AdminPanel\Resources\SettingPageContent;
use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsMailTemplateController extends Controller
{
    public function index () {

        $content = (new SettingPageContent())->templateMailPageContent();

        $templates = MailTemplate::where('template_id', 'changing_order_status')->orWhere('template_id', 'purchase')->orWhere('template_id', 'order')->orderBy('id', 'asc')->paginate('50', array('id', 'name', 'text', 'template_id'));

        return view('admin_panel.resources.mail_template.shop_mail_templates', ['content' => $content, 'templates' => $templates]);
    }

    public function edit($id) {

        $content = (new SettingPageContent())->editPageContent($id);

        $template = (new MailTemplateRepository())->getByColumn($id, 'template_id');

        return view('admin_panel.resources.mail_template.shop_mail_template_edit', [
            'content'  => $content,
            'template' => $template
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'    => 'bail|required',
            'text'    => 'required|min:3|max:1000',
            'subject' => 'min:3|max:1000',
        ]);

        (new MailTemplateRepository())->updateById($id, $data);

        return redirect()
            ->route('admin.shop.researches.settings.templates')
            ->with('success', 'Успешно сохранено.');
    }
}
