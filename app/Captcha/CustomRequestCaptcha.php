<?php

namespace App\Captcha;

class CustomRequestCaptcha
{
    public function custom()
    {
        return new \ReCaptcha\RequestMethod\CurlPost();
    }
}