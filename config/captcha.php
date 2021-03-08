<?php
/*
 * Secret key and Site key get on https://www.google.com/recaptcha
 * */
return [
    'secret' => env('CAPTCHA_SECRET', '6LciF-kUAAAAAF-1FmpIcauVphSQqpXSZZjequDO'),
    'sitekey' => env('CAPTCHA_SITEKEY', '6LciF-kUAAAAAB_B9CfbACuJlknHpo4xhxBG-QJc'),
    /**
     * @var string|null Default ``null``.
     * Custom with function name (example customRequestCaptcha) or class@method (example \App\CustomRequestCaptcha@custom).
     * Function must be return instance, read more in repo ``https://github.com/thinhbuzz/laravel-google-captcha-examples``
     */
    'request_method' => '\App\Captcha\CustomRequestCaptcha@custom',
    'options' => [
        'multiple' => false,
        'lang'     => app()->getLocale(),
    ],
    'attributes' => [
        'theme'  => 'light'
    ],
];