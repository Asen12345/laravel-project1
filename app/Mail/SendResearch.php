<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZipArchive;

class SendResearch extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $to_email;
    public $to_name;
    public $from_email;
    public $from_name;
    public $shoppingCart;

    /**
     * Create a new message instance.
     *
     * @param $shoppingCart
     */
    public function __construct($shoppingCart)
    {
        $this->to_email     = $shoppingCart->user->email;
        $this->to_name      = $shoppingCart->user->name;
        $this->shoppingCart = $shoppingCart;
        $this->from_email   = config('mail.from.address');
        $this->from_name    = config('mail.from.name');
    }

    /**
     * Build the message.
     *
     * @return $this
     * @throws \Exception
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('order', 'template_id');

        if ($this->shoppingCart->status == 'paid') {
            $status = 'Оплачено';
        } elseif ($this->shoppingCart->status == 'waiting') {
            $status = 'Ожидание';
        } elseif ($this->shoppingCart->status == 'send') {
            $status = 'Отправлен';
        } elseif ($this->shoppingCart->status == 'started' || $this->shoppingCart->status == 'cancelled') {
            $status = 'Незаконченный';
        }

        $data = [
            'user_from'    => 'Администрация',
            'user_to'      => $this->to_name,
            'number'       => $this->shoppingCart->id,
            'status'       => $status ?? 'не известен',
            'template'     => $template['template_id'],
        ];

        $pathZip = storage_path() . '/app/private/temp_zip/' . $this->shoppingCart->id . '_research.zip';

        if (!empty($pathZip)){
            $zip = new ZipArchive;
            $zip->open($pathZip, ZipArchive::CREATE|ZIPARCHIVE::OVERWRITE);

            foreach ($this->shoppingCart->purchases as $purchase) {
                $file = storage_path('app/private'. $purchase->research->file);
                $pathinfo = pathinfo($file);
                if (\File::isFile($file)) {
                    $zip->addFile($file, $pathinfo['filename'] . '/' . $pathinfo['basename']);
                } else {
                    $zip->addEmptyDir($pathinfo['filename']);
                    $zip->addFromString($pathinfo['filename'] . '/info.txt', __('zipper.dont_have_file'));
                }
            }
            $zip->close();
        }

        $this->shoppingCart->update([
            'status' => 'send'
        ]);


        $mail = $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);

        if (!empty($pathZip)) {
            $mail->attach($pathZip);
        }

        return $mail;
    }
}
