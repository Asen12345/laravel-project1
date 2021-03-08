<?php

use Illuminate\Support\Facades\Route;

/*Admin Panel*/
Route::group(array('prefix' => '/admin', 'as' => 'admin.', 'middleware' => 'clearCache'), function () {
    /* auth form */
    Route::group(['middleware' => ['guest:admin']], function () {
        Route::get('/login', 'AdminPanel\AdminAuth\AdminLoginController@showLoginForm')->name('login');
        Route::post('/login', 'AdminPanel\AdminAuth\AdminLoginController@login')->name('login.submit');
    });

    /*Admin Panel*/
    Route::group(['middleware' => ['auth:admin']], function () {

        Route::get('/dashboard', 'AdminPanel\Dashboard\MainController@index')->name('dashboard.index');

    /*Admins/Redactors*/
        Route::group(['middleware' => ['isAdmin:admins']], function () {
            Route::get('/admins', 'AdminPanel\Users\AdminsManageController@index')->name('admins.index');
            Route::post('/admins', 'AdminPanel\Users\AdminsManageController@store')->name('admins.store');
            Route::get('/admins/create', 'AdminPanel\Users\AdminsManageController@create')->name('admins.create');
            Route::put('/admins/{id}', 'AdminPanel\Users\AdminsManageController@update')->name('admins.update');
            Route::delete('/admins/{id}', 'AdminPanel\Users\AdminsManageController@destroy')->name('admins.destroy');
            Route::get('/admins/{id}/edit', 'AdminPanel\Users\AdminsManageController@edit')->name('admins.edit');
            Route::post('/admins/{id}/edit', 'AdminPanel\Users\AdminsManageController@edit')->name('admins.edit.sort');
            Route::get('/admins/sort', 'AdminPanel\Users\AdminsManageController@index')->name('admins.sort');
        });

    /*Expert/Company*/
        Route::group(['middleware' => ['isAdmin']], function () {
            Route::get('/users', 'AdminPanel\Users\UsersManageController@index')->name('users.index');
            Route::post('/users', 'AdminPanel\Users\UsersManageController@store')->name('users.store');
            Route::get('/users/create', 'AdminPanel\Users\UsersManageController@create')->name('users.create');
            Route::put('/users/{id}', 'AdminPanel\Users\UsersManageController@update')->name('users.update');
            Route::delete('/users/{id}', 'AdminPanel\Users\UsersManageController@destroy')->name('users.destroy');
            Route::get('/users/{id}/edit/setting', 'AdminPanel\Users\UsersManageController@edit')->name('users.edit');
            Route::get('/users/{id}/edit/privacy', 'AdminPanel\Users\UsersManageController@edit')->name('users.privacy.edit');
            Route::get('/users/{id}/edit/services', 'AdminPanel\Users\UsersManageController@edit')->name('users.services.edit');
            Route::post('/users/{id}/edit', 'AdminPanel\Users\UsersManageController@edit')->name('users.edit.sort');
            Route::get('/users/sort', 'AdminPanel\Users\UsersManageController@index')->name('users.sort');

    /*Messages*/
            Route::get('/message', 'AdminPanel\Users\MessagesController@index')->name('users.messages.index');
            Route::post('/message/store', 'AdminPanel\Users\MessagesController@store')->name('users.messages.store');
            Route::post('/message/store/autocomplete-user', 'AdminPanel\Users\MessagesController@autocompleteUsers')->name('users.message.autocomplete.users');
            Route::post('/message/store/autocomplete-company', 'AdminPanel\Users\MessagesController@autocompleteCompany')->name('users.message.autocomplete.company');
        });

    /*Category*/
        Route::group(['middleware' => ['isRedactor:news']], function () {
            Route::get('/news/category', 'AdminPanel\News\CategoryController@index')->name('category.index');
            Route::post('/news/category', 'AdminPanel\News\CategoryController@store')->name('category.store');
            Route::get('/news/category/create', 'AdminPanel\News\CategoryController@create')->name('category.create');
            Route::put('/news/category/{id}', 'AdminPanel\News\CategoryController@update')->name('category.update');
            Route::delete('/news/category/{id}', 'AdminPanel\News\CategoryController@destroy')->name('category.destroy');
            Route::get('/news/category/{id}/edit', 'AdminPanel\News\CategoryController@edit')->name('category.edit');
            Route::get('/news/category/sort', 'AdminPanel\News\CategoryController@index')->name('category.sort');

            /*News Scene*/
            Route::get('/news/scene', 'AdminPanel\News\SceneController@index')->name('scene.index');
            Route::post('/news/scene', 'AdminPanel\News\SceneController@store')->name('scene.store');
            Route::get('/news/scene/create', 'AdminPanel\News\SceneController@create')->name('scene.create');
            Route::put('/news/scene/{id}', 'AdminPanel\News\SceneController@update')->name('scene.update');
            Route::delete('/news/scene/{id}', 'AdminPanel\News\SceneController@destroy')->name('scene.destroy');
            Route::get('/news/scene/{id}/edit', 'AdminPanel\News\SceneController@edit')->name('scene.edit');
            Route::get('/news/scene/sort', 'AdminPanel\News\SceneController@index')->name('scene.sort');
            /*News Pre-upload and Upload*/
            Route::post('/resources/image/upload-image', 'Manager\UploadManagerController@uploadImage')->name('resource.upload.image');
            Route::post('/resources/image/pre-upload-image', 'Manager\UploadManagerController@preUploadImage')->name('resource.pre_upload.image');

            /*News Scene Groups*/
            Route::get('/news/scene-groups', 'AdminPanel\News\SceneGroupController@index')->name('scene-group.index');
            Route::post('/news/scene-groups', 'AdminPanel\News\SceneGroupController@store')->name('scene-group.store');
            Route::get('/news/scene-groups/create', 'AdminPanel\News\SceneGroupController@create')->name('scene-group.create');
            Route::put('/news/scene-groups/{id}', 'AdminPanel\News\SceneGroupController@update')->name('scene-group.update');
            Route::delete('/news/scene-groups/{id}', 'AdminPanel\News\SceneGroupController@destroy')->name('scene-group.destroy');
            Route::get('/news/scene-groups/{id}/edit', 'AdminPanel\News\SceneGroupController@edit')->name('scene-group.edit');
            Route::get('/news/scene-groups/sort', 'AdminPanel\News\SceneGroupController@index')->name('scene-group.sort');
        });

    /*The News*/
        Route::get('/news/news', 'AdminPanel\News\NewsController@index')->name('news.index');
        Route::post('/news/news', 'AdminPanel\News\NewsController@store')->name('news.store');
        Route::get('/news/news/create', 'AdminPanel\News\NewsController@create')->name('news.create');
        Route::put('/news/news/{id}', 'AdminPanel\News\NewsController@update')->name('news.update');
        Route::delete('/news/news/{id}', 'AdminPanel\News\NewsController@destroy')->name('news.destroy');
        Route::get('/news/news/{id}/edit', 'AdminPanel\News\NewsController@edit')->name('news.edit');
        Route::get('/news/news/sort', 'AdminPanel\News\NewsController@index')->name('news.sort');
        /*Autocomplete Authors News*/
        Route::post('/news/news/autocomplete-author', 'AdminPanel\News\NewsController@autocompleteAuthor')->name('news.autocomplete.author');

    /*Shop Researches*/
        /*Category*/
        Route::get('/shop/researches/category', 'AdminPanel\Shop\CategoryController@index')->name('shop.researches.category');
        Route::get('/shop/researches/category/create', 'AdminPanel\Shop\CategoryController@create')->name('shop.researches.category.create');
        Route::post('/shop/researches/category/story', 'AdminPanel\Shop\CategoryController@store')->name('shop.researches.category.store');
        Route::get('/shop/researches/category/story', 'AdminPanel\Shop\CategoryController@index')->name('shop.researches.category.sort');
        Route::get('/shop/researches/category/edit/{id}', 'AdminPanel\Shop\CategoryController@edit')->name('shop.researches.category.edit');
        Route::put('/shop/researches/category/update/{id}', 'AdminPanel\Shop\CategoryController@update')->name('shop.researches.category.update');
        Route::delete('/shop/researches/category/destroy/{id}', 'AdminPanel\Shop\CategoryController@destroy')->name('shop.researches.category.destroy');

        /*Authors*/
        Route::get('/shop/researches/authors', 'AdminPanel\Shop\AuthorController@index')->name('shop.researches.authors');
        Route::get('/shop/researches/authors/create', 'AdminPanel\Shop\AuthorController@create')->name('shop.researches.authors.create');
        Route::post('/shop/researches/authors/story', 'AdminPanel\Shop\AuthorController@store')->name('shop.researches.authors.store');
        Route::get('/shop/researches/authors/story', 'AdminPanel\Shop\AuthorController@index')->name('shop.researches.authors.sort');
        Route::get('/shop/researches/authors/edit/{id}', 'AdminPanel\Shop\AuthorController@edit')->name('shop.researches.authors.edit');
        Route::put('/shop/researches/authors/update/{id}', 'AdminPanel\Shop\AuthorController@update')->name('shop.researches.authors.update');
        Route::delete('/shop/researches/authors/destroy/{id}', 'AdminPanel\Shop\AuthorController@destroy')->name('shop.researches.authors.destroy');
        /*Authors Pre-upload and Upload*/
        Route::post('/shop/researches/authors/image/upload-image', 'AdminPanel\Shop\AuthorController@uploadImage')->name('author.resource.upload.image');
        Route::post('/shop/researches/authors/image/pre-upload-image', 'AdminPanel\Shop\AuthorController@preUploadImage')->name('author.resource.pre_upload.image');

        /*Researches*/
        Route::get('/shop/researches', 'AdminPanel\Shop\ResearchesController@index')->name('shop.researches');
        Route::get('/shop/researches/create', 'AdminPanel\Shop\ResearchesController@create')->name('shop.researches.create');
        Route::post('/shop/researches/story', 'AdminPanel\Shop\ResearchesController@store')->name('shop.researches.store');
        Route::get('/shop/researches/sort', 'AdminPanel\Shop\ResearchesController@index')->name('shop.researches.sort');
        Route::get('/shop/researches/edit/{id}', 'AdminPanel\Shop\ResearchesController@edit')->name('shop.researches.edit');
        Route::put('/shop/researches/update/{id}', 'AdminPanel\Shop\ResearchesController@update')->name('shop.researches.update');
        Route::delete('/shop/researches/destroy/{id}', 'AdminPanel\Shop\ResearchesController@destroy')->name('shop.researches.destroy');
        /*Authors Pre-upload and Upload*/
        Route::post('/shop/researches/image/upload-image', 'AdminPanel\Shop\ResearchesController@uploadImage')->name('researches.resource.upload.image');
        Route::post('/shop/researches/image/pre-upload-image', 'AdminPanel\Shop\ResearchesController@preUploadImage')->name('researches.resource.pre_upload.image');

        /*Settings Template*/
        Route::get('/shop/researches/mail-template', 'AdminPanel\Shop\SettingsMailTemplateController@index')->name('shop.researches.settings.templates');
        Route::get('/shop/researches/mail-template/{id}', 'AdminPanel\Shop\SettingsMailTemplateController@edit')->name('shop.researches.settings.templates.edit');
        Route::put('/shop/researches/mail-template/{id}', 'AdminPanel\Shop\SettingsMailTemplateController@update')->name('shop.researches.settings.templates.update');
        /*Settings bank details*/
        Route::get('/shop/researches/bank-detail', 'AdminPanel\Shop\BankDetailController@index')->name('shop.researches.settings.bank.detail');
        Route::put('/shop/researches/bank-detail/update', 'AdminPanel\Shop\BankDetailController@update')->name('shop.researches.settings.bank.detail.update');
        /*Metatags*/
        Route::get('/shop/researches/metatags', 'AdminPanel\Shop\MetaTagsController@index')->name('shop.researches.settings.metatags');
        Route::get('/shop/researches/metatags/edit/{id}', 'AdminPanel\Shop\MetaTagsController@edit')->name('shop.researches.settings.metatags.edit');
        Route::put('/shop/researches/metatags/update/{id}', 'AdminPanel\Shop\MetaTagsController@update')->name('shop.researches.settings.metatags.update');


        /*Orders*/
        Route::get('/shop/researches/orders', 'AdminPanel\Shop\OrdersController@index')->name('shop.researches.orders');

        Route::get('/shop/researches/buyers', 'AdminPanel\Shop\OrdersController@buyers')->name('shop.researches.buyers');
        Route::get('/shop/researches/buyers/sort', 'AdminPanel\Shop\OrdersController@buyers')->name('shop.researches.buyers.sort');

        Route::get('/shop/researches/orders/order/{id}', 'AdminPanel\Shop\OrdersController@orderPage')->name('shop.researches.orders.order');
        Route::post('/shop/researches/orders/order/{id}', 'AdminPanel\Shop\OrdersController@orderUpdate')->name('shop.researches.orders.order.update');
        Route::get('/shop/researches/orders/sort', 'AdminPanel\Shop\OrdersController@index')->name('shop.researches.orders.sort');

        /*Update main checkbox*/
        Route::post('/shop/researches/orders/checkbox/update', 'AdminPanel\Shop\OrdersController@checkBox')->name('shop.researches.orders.update');





        Route::group(['middleware' => ['isAdmin']], function () {
            /*Настройки*/
            Route::get('/newsletter', 'AdminPanel\Newsletter\NewsletterController@index')->name('newsletter.index');
            Route::post('/newsletter/update', 'AdminPanel\Newsletter\NewsletterController@update')->name('newsletter.update');
            /*Объяаления*/
            Route::get('/newsletter/ads-offers', 'AdminPanel\Newsletter\AdsOffersController@index')->name('newsletter.ads.offers');
            Route::post('/newsletter/ads-offers/update', 'AdminPanel\Newsletter\AdsOffersController@update')->name('newsletter.ads.offers.update');
            /*Новости блогов*/
            Route::get('/newsletter/news', 'AdminPanel\Newsletter\BlogPostController@index')->name('newsletter.news.index');
            Route::post('/newsletter/to-newsletter', 'AdminPanel\Newsletter\BlogPostController@toNewsletter')->name('newsletter.news.active');
            Route::get('/newsletter/news/sort', 'AdminPanel\Newsletter\BlogPostController@index')->name('newsletter.news.sort');
            /*Subscribers*/
            Route::get('/newsletter/subscribers', 'AdminPanel\Newsletter\SubscriberController@index')->name('newsletter.subscribers.index');

            Route::get('/newsletter/subscribers/sort', 'AdminPanel\Newsletter\SubscriberController@index')->name('newsletter.subscribers.sort');


            /*Предпросмотр следующей рассылки*/
            Route::get('/newsletter/show', 'AdminPanel\Newsletter\NewsletterController@showNewsletter')->name('newsletter.show');
            /*Просмотр шаблона*/
            Route::get('/newsletter/template/show', 'AdminPanel\Newsletter\NewsletterController@showTemplate')->name('newsletter.template.show');



            Route::get('/newsletter/subscribers/add-user', 'AdminPanel\Newsletter\SubscriberController@create')->name('newsletter.subscribers.create');
            Route::post('/newsletter/subscribers/subscribe', 'AdminPanel\Newsletter\SubscriberController@subscribe')->name('newsletter.subscribers.subscribe');
            Route::post('/newsletter/subscribers/unsubscribe/{email}', 'AdminPanel\Newsletter\SubscriberController@unsubscribe')->name('newsletter.subscribers.unsubscribe');

            /*Unsubscribed*/
            Route::get('/newsletter/unsubscribed', 'AdminPanel\Newsletter\UnsubscribedController@index')->name('newsletter.unsubscribed.index');
            Route::get('/newsletter/unsubscribed/sort', 'AdminPanel\Newsletter\UnsubscribedController@index')->name('newsletter.unsubscribed.sort');
            Route::post('/newsletter/unsubscribed/destroy/{id}', 'AdminPanel\Newsletter\UnsubscribedController@destroy')->name('newsletter.unsubscribed.destroy');

        });



    /*Blogs*/
        Route::group(['middleware' => ['isRedactor:blogs']], function () {
            Route::get('/blogs', 'AdminPanel\Blog\BlogController@index')->name('blogs.index');
            Route::post('/blogs/edit', 'AdminPanel\Blog\BlogController@edit')->name('blogs.edit');
            Route::delete('/blogs/delete/{id}', 'AdminPanel\Blog\BlogController@delete')->name('blogs.delete');
            Route::get('/blogs/sort', 'AdminPanel\Blog\BlogController@index')->name('blogs.sort');

    /*Blog Posts*/
            Route::get('/blogs/posts/{id}', 'AdminPanel\BlogPost\BlogPostsController@posts')->name('posts.index');
            Route::get('/blogs/posts/sort/{id}', 'AdminPanel\BlogPost\BlogPostsController@posts')->name('posts.sort');
            Route::get('/blogs/posts/edit/{id}', 'AdminPanel\BlogPost\BlogPostsController@edit')->name('posts.edit');
            Route::post('/blogs/posts/update/{id}', 'AdminPanel\BlogPost\BlogPostsController@update')->name('posts.update');
            /*Ajax*/
            Route::post('/blog/post/update-active', 'AdminPanel\BlogPost\BlogPostsController@activate')->name('post.activate');
            Route::delete('/blogs/posts/delete/{id}', 'AdminPanel\BlogPost\BlogPostsController@delete')->name('posts.delete');
            /*Autocomplete Blog Tags*/
            Route::post('/blogs/edit/autocomplete', 'AdminPanel\BlogPost\BlogPostsController@autocomplete')->name('posts.edit.autocomplete');

        /*All posts listing*/
            Route::get('/blogs/all-posts', 'AdminPanel\BlogPost\BlogPostsController@posts')->name('posts.all.index');
            Route::get('/blogs/all-posts/create', 'AdminPanel\BlogPost\BlogPostsController@create')->name('posts.create');
            Route::post('/blogs/all-posts/store', 'AdminPanel\BlogPost\BlogPostsController@store')->name('posts.store');
            Route::post('/blogs/all-posts/store/auto-complete-blog', 'AdminPanel\BlogPost\BlogPostsController@autoCompleteBlog')->name('posts.auto.complete.blog');

            Route::get('/blogs/all-posts/sort', 'AdminPanel\BlogPost\BlogPostsController@posts')->name('posts.all.sort');

        /*Blog Comments*/
            Route::get('/blog/comments/{id}', 'AdminPanel\Comment\CommentController@comments')->name('comments.index');
            Route::get('/blog/comments/sort/{id}', 'AdminPanel\Comment\CommentController@comments')->name('comments.sort');
            Route::get('/blog/all-comments', 'AdminPanel\Comment\CommentController@comments')->name('comments.all.index');
            Route::get('/blog/all-comments/sort', 'AdminPanel\Comment\CommentController@comments')->name('comments.all.sort');
            Route::get('/blog/comments/edit/{id}', 'AdminPanel\Comment\CommentController@edit')->name('comments.edit');
            Route::post('/blog/comments/update/{id}', 'AdminPanel\Comment\CommentController@update')->name('comments.update');
            Route::delete('/blogs/comments/delete/{id}', 'AdminPanel\Comment\CommentController@delete')->name('comments.delete');
            Route::post('/blog/activate', 'AdminPanel\Comment\CommentController@activate')->name('comments.activate');
            Route::get('/blog/all-comments/{post_id}', 'AdminPanel\Comment\CommentController@index')->name('blogs.comments');

        /*Subscribers Blog*/
            Route::get('/blog/subscribers/{blog_id?}', 'AdminPanel\Subscriber\SubscriberController@index')->name('subscriber.index');
            Route::get('/blog/subscribers/sort/{blog_id?}', 'AdminPanel\Subscriber\SubscriberController@index')->name('subscriber.sort');
            Route::delete('/blog/subscribers/delete/{id}', 'AdminPanel\Subscriber\SubscriberController@delete')->name('subscriber.delete');

            /*Update active subscriber*/
            Route::post('/blog/subscribers/activate', 'AdminPanel\Subscriber\SubscriberController@activate')->name('subscriber.activate');
        });

    /*Anons*/
        Route::group(['middleware' => ['isAdmin']], function () {
            Route::get('/anons', 'AdminPanel\Anons\AnonsController@index')->name('anons.index');
            Route::get('/anons/sort', 'AdminPanel\Anons\AnonsController@index')->name('anons.sort');
            Route::get('/anons/create', 'AdminPanel\Anons\AnonsController@create')->name('anons.create');
            Route::post('/anons/store', 'AdminPanel\Anons\AnonsController@store')->name('anons.store');
            Route::get('/anons/edit/{anons_id}', 'AdminPanel\Anons\AnonsController@edit')->name('anons.edit');
            Route::post('/anons/update/{anons_id}', 'AdminPanel\Anons\AnonsController@update')->name('anons.update');
            Route::delete('/anons/destroy/{anons_id}', 'AdminPanel\Anons\AnonsController@destroy')->name('anons.destroy');
            /*Update main checkbox*/
            Route::post('/anons/checkbox/update', 'AdminPanel\Anons\AnonsController@checkBox')->name('anons.main.update');
        });

    /*Text Page*/
        Route::group(['middleware' => ['isAdmin']], function () {
            Route::get('/page', 'AdminPanel\TextPage\TextPageController@index')->name('text.page.index');
            Route::get('/page/sort', 'AdminPanel\TextPage\TextPageController@index')->name('text.page.sort');
            Route::get('/page/edit/{id}', 'AdminPanel\TextPage\TextPageController@edit')->name('text.page.edit');
            Route::get('/page/create', 'AdminPanel\TextPage\TextPageController@create')->name('text.page.create');
            Route::post('/page/update/{id}', 'AdminPanel\TextPage\TextPageController@update')->name('text.page.update');
            Route::post('/page/store', 'AdminPanel\TextPage\TextPageController@store')->name('text.page.store');
            Route::delete('/page/destroy/{id}', 'AdminPanel\TextPage\TextPageController@destroy')->name('text.page.destroy');
        });

    /*Topic*/
        Route::group(['middleware' => ['isRedactor:topics']], function () {
            Route::get('/topic', 'AdminPanel\Topic\TopicController@index')->name('topic.index');
            Route::get('/topic/sort', 'AdminPanel\Topic\TopicController@index')->name('topic.sort');
            Route::get('/topic/create', 'AdminPanel\Topic\TopicController@create')->name('topic.create');
            Route::post('/topic/store', 'AdminPanel\Topic\TopicController@store')->name('topic.store');
            Route::get('/topic/edit/{topic_id}', 'AdminPanel\Topic\TopicController@edit')->name('topic.edit');
            Route::post('/topic/update/{topic_id}', 'AdminPanel\Topic\TopicController@update')->name('topic.update');
            Route::delete('/topic/destroy/{topic_id}', 'AdminPanel\Topic\TopicController@destroy')->name('topic.destroy');
            /*Update main checkbox*/
            Route::post('/topic/checkbox/update', 'AdminPanel\Topic\TopicController@checkBox')->name('topic.main.update');
            /*Auto complete users*/
            Route::post('/topic/autocomplete', 'AdminPanel\Topic\TopicController@autocomplete')->name('topic.autocomplete');

    /*Topic answer*/
            Route::get('/topic/answer', 'AdminPanel\Topic\AnswerController@index')->name('answer.index');
            Route::get('/topic/answer/sort', 'AdminPanel\Topic\AnswerController@index')->name('answer.sort');
            Route::get('/topic/answer/create', 'AdminPanel\Topic\AnswerController@create')->name('answer.create');
            Route::post('/topic/answer/store', 'AdminPanel\Topic\AnswerController@store')->name('answer.store');
            Route::get('/topic/answer/edit/{answer_id}', 'AdminPanel\Topic\AnswerController@edit')->name('answer.edit');
            Route::post('/topic/answer/update/{answer_id}', 'AdminPanel\Topic\AnswerController@update')->name('answer.update');
            Route::delete('/topic/answer/destroy/{answer_id}', 'AdminPanel\Topic\AnswerController@destroy')->name('answer.destroy');
            /*Auto complete topic*/
            /*Topic*/
            Route::post('/topic/answer/autocomplete', 'AdminPanel\Topic\AnswerController@autocomplete')->name('answer.autocomplete');
            /*User*/
            Route::post('/topic/answer/autocomplete/user', 'AdminPanel\Topic\AnswerController@userAutocomplete')->name('answer.user.autocomplete');
            /*Update main checkbox*/
            Route::post('/topic/answer/checkbox/update', 'AdminPanel\Topic\AnswerController@checkBox')->name('answer.main.update');
        });

/*Resources*/
    /*Company*/
        Route::group(['middleware' => ['isAdmin:settings']], function () {
            Route::get('/resources/company', 'AdminPanel\Resources\CompanyController@index')->name('resources.company');
            Route::get('/resources/company/create', 'AdminPanel\Resources\CompanyController@create')->name('resources.company.create');
            Route::post('/resources/company/store', 'AdminPanel\Resources\CompanyController@store')->name('resources.company.store');
            Route::post('/resources/company/edit/{id}', 'AdminPanel\Resources\CompanyController@edit')->name('resources.company.edit');
            Route::put('/resources/company/update/{id}', 'AdminPanel\Resources\CompanyController@update')->name('resources.company.update');
            Route::post('/resources/company/merge', 'AdminPanel\Resources\CompanyController@merge')->name('resources.company.merge');
            Route::post('/resources/company/merged', 'AdminPanel\Resources\CompanyController@merged')->name('resources.company.merged');
            Route::get('/resources/company/sort', 'AdminPanel\Resources\CompanyController@index')->name('resources.company.sort');
            Route::get('/resources/company/edit/{id}', 'AdminPanel\Resources\CompanyController@edit')->name('resources.company.edit');
            Route::delete('/resources/company/destroy/{id}', 'AdminPanel\Resources\CompanyController@destroy')->name('resources.company.destroy');

            /*Company type*/
            Route::get('/resources/company-type', 'AdminPanel\Resources\CompanyTypeController@index')->name('resources.company.type');
            Route::get('/resources/company-type/sort', 'AdminPanel\Resources\CompanyTypeController@index')->name('resources.company.type.sort');
            Route::get('/resources/company-type/create', 'AdminPanel\Resources\CompanyTypeController@create')->name('resources.company.type.create');
            Route::post('/resources/company-type/store', 'AdminPanel\Resources\CompanyTypeController@store')->name('resources.company.type.store');
            Route::get('/resources/company-type/edit/{id}', 'AdminPanel\Resources\CompanyTypeController@edit')->name('resources.company.type.edit');
            Route::put('/resources/company-type/update/{id}', 'AdminPanel\Resources\CompanyTypeController@update')->name('resources.company.type.update');
            Route::delete('/resources/company-type/destroy/{id}', 'AdminPanel\Resources\CompanyTypeController@destroy')->name('resources.company.type.destroy');

            /*Countries*/
            Route::get('/resources/countries', 'AdminPanel\Resources\CountriesController@index')->name('resources.countries');
            Route::get('/resources/countries/sort', 'AdminPanel\Resources\CountriesController@index')->name('resources.countries.sort');

            /*Regions*/
            Route::get('/resources/countries/{id}/regions/', 'AdminPanel\Resources\RegionsController@index')->name('resources.regions');
            Route::get('/resources/countries/{id}/regions/sort', 'AdminPanel\Resources\RegionsController@index')->name('resources.regions.sort');

            /*Cities*/
            Route::get('/resources/countries/city/', 'AdminPanel\Resources\CityController@index')->name('resources.city');
            Route::get('/resources/countries/city/sort', 'AdminPanel\Resources\CityController@index')->name('resources.city.sort');

            /*Tags*/
            Route::get('/resources/tags', 'AdminPanel\Resources\TagsController@index')->name('resources.tags');
            Route::get('/resources/tags/create', 'AdminPanel\Resources\TagsController@create')->name('resources.tags.create');
            Route::post('/resources/tags/store', 'AdminPanel\Resources\TagsController@store')->name('resources.tags.store');
            Route::get('/resources/tags/sort', 'AdminPanel\Resources\TagsController@index')->name('resources.tags.sort');
            Route::delete('/resources/tags/destroy/{id}', 'AdminPanel\Resources\TagsController@destroy')->name('resources.tags.destroy');
            Route::get('/resources/tags/edit/{id}', 'AdminPanel\Resources\TagsController@edit')->name('resources.tags.edit');
            Route::put('/resources/tags/update/{id}', 'AdminPanel\Resources\TagsController@update')->name('resources.tags.update');

            /*MetaTags*/
            Route::get('/resources/metatags', 'AdminPanel\Resources\MetaTagsController@index')->name('resources.metatags');
            Route::get('/resources/metatags/create', 'AdminPanel\Resources\MetaTagsController@create')->name('resources.metatags.create');
            Route::post('/resources/metatags/store', 'AdminPanel\Resources\MetaTagsController@store')->name('resources.metatags.store');
            Route::delete('/resources/metatags/destroy/{id}', 'AdminPanel\Resources\MetaTagsController@destroy')->name('resources.metatags.destroy');
            Route::get('/resources/metatags/edit/{id}', 'AdminPanel\Resources\MetaTagsController@edit')->name('resources.metatags.edit');
            Route::put('/resources/metatags/update/{id}', 'AdminPanel\Resources\MetaTagsController@update')->name('resources.metatags.update');
            Route::get('/resources/metatags/sort', 'AdminPanel\Resources\MetaTagsController@index')->name('resources.metatags.sort');

            /*Debug mode*/
            Route::get('/resources/debug-mode', 'AdminPanel\Resources\DebugModeController@index')->name('resources.debug.mode');
            Route::post('/resources/debug-mode/update', 'AdminPanel\Resources\DebugModeController@update')->name('resources.debug.mode.update');
            /*Mail template*/
            Route::get('/resources/mail-template', 'AdminPanel\Resources\SettingsMailTemplateController@index')->name('resources.mail.templates');
            Route::get('/resources/mail-template/{id}', 'AdminPanel\Resources\SettingsMailTemplateController@edit')->name('resources.mail.template.edit');
            Route::put('/resources/mail-template/{id}', 'AdminPanel\Resources\SettingsMailTemplateController@update')->name('resources.mail.template.update');


            /*Data templates*/
            Route::get('/resources/data-template', 'AdminPanel\Resources\DataTemplateController@index')->name('resources.data.template');
            Route::post('/resources/data-template/update', 'AdminPanel\Resources\DataTemplateController@update')->name('resources.data.template.update');
        });

    /*Feedback*/
        Route::group(['middleware' => ['isAdmin']], function () {
            Route::get('/feedback', 'AdminPanel\Feedback\FeedbackController@index')->name('feedback.index');
            Route::get('/feedback/create', 'AdminPanel\Feedback\FeedbackController@create')->name('feedback.create');
            Route::post('/feedback/store', 'AdminPanel\Feedback\FeedbackController@store')->name('feedback.store');
            Route::delete('/feedback/destroy/{id}', 'AdminPanel\Feedback\FeedbackController@destroy')->name('feedback.destroy');
            /*Ajax*/
            Route::post('/feedback/update', 'AdminPanel\Feedback\FeedbackController@update')->name('feedback.update');
        });

    /*Banners*/
        Route::group(['middleware' => ['isAdmin']], function () {
            Route::get('/banner', 'AdminPanel\Banner\BannerController@index')->name('banner.index');
            Route::get('/banner/sort', 'AdminPanel\Banner\BannerController@index')->name('banner.sort');
            Route::get('/banner/edit/{id}', 'AdminPanel\Banner\BannerController@edit')->name('banner.edit');
            Route::get('/banner/create', 'AdminPanel\Banner\BannerController@create')->name('banner.create');
            Route::post('/banner/update/{id}', 'AdminPanel\Banner\BannerController@update')->name('banner.update');
            Route::post('/banner/store', 'AdminPanel\Banner\BannerController@store')->name('banner.store');

            Route::get('/banner/settings', 'AdminPanel\Banner\BannerController@settings')->name('banner.setting');
            Route::post('/banner/settings/ajax', 'AdminPanel\Banner\BannerController@ajaxSetting')->name('banner.ajax.setting');

            Route::delete('/banner/destroy/{id}', 'AdminPanel\Banner\BannerController@destroy')->name('banner.destroy');
            Route::post('/banner/clear-statistic/{id}', 'AdminPanel\Banner\BannerController@clearClearStatistic')->name('banner.clear.statistic');
            /*Ajax get data [blogs, blogs post, Announce]*/
            Route::post('/banner/ajax', 'AdminPanel\Banner\BannerController@ajaxUpload')->name('banner.ajax.upload');

            /*Widgets*/
            Route::get('/widgets', 'AdminPanel\Widgets\WidgetController@index')->name('widgets.index');
            Route::get('/widgets/sort', 'AdminPanel\Widgets\WidgetController@index')->name('widgets.sort');
            Route::get('/widgets/edit/{id}', 'AdminPanel\Widgets\WidgetController@edit')->name('widgets.edit');
            Route::post('/widgets/update/{id}', 'AdminPanel\Widgets\WidgetController@update')->name('widgets.update');

            Route::post('/widgets/ajax', 'AdminPanel\Widgets\WidgetController@ajaxUpload')->name('widgets.ajax.upload');

        });




    });

    Route::get('/logout', 'AdminPanel\AdminAuth\AdminLoginController@logout')->name('logout');

});

/*Front Routes*/
Route::group(['as' => 'front.', 'middleware' => ['debug', 'notBlocked']], function () {

    /*RSS*/
    Route::get('/blogs/rss', 'Front\Rss\RssController@blogsRss')->name('blogs.rss');
    Route::get('/blogs/{type}/rss', 'Front\Rss\RssController@blogTypeRss')->name('blogs.type.rss');
    /*Site Map*/
    Route::get('/main/sitemap','Front\SiteMap\SiteMapController@index')->name('site.map');

    Route::post('/newsletter/subscribe', 'Front\Newsletter\NewsletterController@subscribe')->name('newsletter.subscribe');

    Route::get('/blogs/rss/entry/{blog_id}', 'Front\Rss\RssController@blogRss')->name('blog.rss');
    Route::get('/news/rss', 'Front\Rss\RssController@newsRss')->name('news.rss');
    Route::get('/anons/rss', 'Front\Rss\RssController@anonsRss')->name('anons.rss');

    Route::get('/news/rss/section/{url_section}/{url_sub_section?}', 'Front\Rss\RssController@newsRss')->name('section.news.rss');

    Route::get('/news/rss/themes/271', 'Front\Rss\RssController@yandexRss')->name('yandex.rss');

    /*Home page*/
    Route::get('/', 'Front\HomePageController@index')->name('home');

    /*News page*/
    Route::get('/news', 'Front\News\NewsController@index')->name('page.news.all');

    Route::get('/news/index/user/{author_user_id}', 'Front\News\NewsController@index')->name('page.news.user.all');

    Route::get('/news/index/section/add-news', 'Front\News\NewsController@createNews')->name('page.news.add');
    Route::post('/news/index/section/add-news', 'Front\News\NewsController@storeNews')->name('page.news.store');
    Route::get('/news/index/section/{url_section}/{url_sub_section?}', 'Front\News\NewsController@index')->name('page.news.category');


    Route::get('/news/index/section/{url_section}/entry/{url_news}', 'Front\News\NewsController@newsPage')->name('page.news.category.entry');

    Route::get('/news/index/section/{url_section}/{url_sub_section}/entry/{url_news}', 'Front\News\NewsController@newsPage')->name('page.news.sub_category.entry');

    /*News scene page*/
    Route::post('/news/index/themes/', 'Front\News\NewsController@filterScene')->name('page.news.filter.scene');
    Route::get('/news/index/themes/{id}', 'Front\News\NewsController@index')->name('page.news.scene');


    /*Blogs page*/
    /*Post Page*/
    Route::get('/blogs/posts/', 'Front\Posts\PostsController@allPosts')->name('page.posts.all');
    Route::get('/blogs/{permission}/entry/{blog_id}/post/{post_id}', 'Front\Posts\PostsController@post')->name('page.post');


    /*Shop Researches*/
    Route::get('/shop/researches', 'Front\Shop\ResearchesController@index')->name('page.shop');
    /*Поиск*/
    Route::get('/shop/researches/search', 'Front\Shop\ResearchesController@search')->name('page.shop.search');

    Route::get('/shop/researches/section/{url_section}/{url_sub_section?}', 'Front\Shop\ResearchesController@index')->name('page.shop.researches.category');
     //для отсортировки нижнего rout(не разделяет параметры) ->where(['url_section' => '^(?!entry).*$'])//

    Route::get('/shop/researches/entry/{id}', 'Front\Shop\ResearchesController@researchesPage')->where(['id' => '[0-9]+'])->name('page.shop.researches.category.entry');

    //Route::get('/shop/researches/section/{url_section}/{url_sub_section}/entry/{id}', 'Front\Shop\ResearchesController@researchesPage')->name('page.shop.researches.sub_category.entry');

    /*Authors*/
    Route::get('/shop/researchesauthors', 'Front\Shop\AuthorsResearchesController@index')->name('page.shop.researches.authors');
    Route::get('/shop/researchesauthors/entry/{id}', 'Front\Shop\AuthorsResearchesController@pageAuthor')->name('page.shop.author');

    /*Researches authors*/
    Route::get('/shop/researches/author/{id}', 'Front\Shop\AuthorsResearchesController@researchesAuthor')->name('page.shop.researches.author');

    Route::post('/shop/researchesauthors/subscribe', 'Front\Shop\AuthorsResearchesController@subscribeAuthor')->name('page.shop.researches.author.subscribe');
    Route::post('/shop/researchesauthors/subscribe-form/{id}', 'Front\Shop\AuthorsResearchesController@subscribeAuthorForm')->name('page.shop.researches.author.subscribe.form');


    /*Cart*/
    Route::get('/shop/researches/shopping-cart', 'Front\Shop\ShoppingCartController@showCart')->name('shop.researches.shopping.cart');
    Route::post('/shop/researches/shopping-cart', 'Front\Shop\ShoppingCartController@showCart')->name('shop.researches.shopping.cart.remind');




    Route::post('/shop/researches/{id}/add-cart', 'Front\Shop\ShoppingCartController@addCart')->name('shop.researches.shopping.add.cart');
    Route::post('/shop/researches/{id}/delete-cart', 'Front\Shop\ShoppingCartController@deleteCart')->name('shop.researches.shopping.delete.cart');

    Route::get('/shop/researches/checkout/{type}', 'Front\Shop\ShoppingCartController@checkout')->name('shop.researches.shopping.checkout');

    Route::post('/shop/researches/entry/{id}/feedback', 'Front\Shop\ResearchesController@feedbackSend')->name('page.shop.researches.feedback');

    Route::post('/shop/researches/shopping-cart/success', 'Front\Shop\ShoppingCartController@checkPaid')->name('shop.researches.shopping.check_paid');


    /*Downloads Demo*/
    Route::post('/shop/researches/download-demo', 'Front\Shop\ResearchesController@downloadDemo')->name('page.shop.researches.download.demo');

    Route::post('/shop/researches/free-demo', 'Front\Shop\ResearchesController@freeDemo')->name('page.shop.researches.download.free');





    /*Post Page by Tags*/
    Route::get('/blogs/posts/tags/{name}', 'Front\Posts\PostsController@tagPosts')->name('page.posts.tag');

    /*Blogs, Blog and Sort*/
    Route::get('/blogs/{sort?}', 'Front\Blog\BlogController@allBlogs')->name('page.blogs.all');
    Route::get('/blogs/{permission}/entry/{blog_id}/', 'Front\Blog\BlogController@blog')->name('page.blog');

    /*Votes Like & Dislike*/
    Route::post('/blog/post/vote', 'Front\Blog\PostVoteController@vote')->name('post.votes');

    /*Subscribe Blog not auth user (for auth only ajax)*/
    Route::post('/blog/subscribe/{blog_id}', 'Front\Posts\SubscriberController@subscribe')->name('blog.subscribe');

    /*Experts Page*/
    Route::get('/people/index/type/experts/', 'Front\People\ExpertController@index')->name('page.people.experts.index');

    /*Companies Page*/
    Route::get('/people/index/type/company/', 'Front\People\CompanyController@index')->name('page.people.companies.index');

    /*Company and Expert page*/
    Route::get('/people/show/user/{id}', 'Front\People\MainUserController@userPage')->name('page.people.user');

    Route::get('/people/index/activity/{id}', 'Front\People\PeopleFilterController@index')->name('page.people.filter.company');

    /*Anonses page*/
    Route::get('/main/anons', 'Front\Anons\AnonsController@index')->name('page.anons');
    Route::get('/main/anons/enter/{anons_id}', 'Front\Anons\AnonsController@anonsPage')->name('page.anons.page');

    /*Topic page*/
    Route::get('/main/topic', 'Front\Topic\TopicController@index')->name('page.topic');
    Route::get('/main/anons/topic/{url_en}', 'Front\Topic\TopicController@topicPage')->name('page.topic.page');
    Route::post('/main/topic/send', 'Front\Topic\TopicController@sendAnswer')->name('page.topic.send');

    Route::post('/main/topic/update', 'Front\Topic\TopicController@updateAnswer')->name('page.topic.update');

    Route::get('/main/topic/{url_en}/edit', 'Front\Topic\TopicController@editAnswer')->name('page.topic.answer.edit');

    /*Feedback*/
    Route::get('/main/feedback', 'Front\Feedback\FeedbackController@index')->name('page.feedback');
    Route::post('/main/feedback/send', 'Front\Feedback\FeedbackController@send')->name('page.feedback.send');

    /*Text Pages*/
    Route::get('/main/content/page/{url_en}', 'Front\TextPage\TextPageController@index')->name('page.text.page');

    /*Setting Account*/
    Route::get('/account/settings/{type?}', 'Front\Setting\AccountController@index')->name('setting.account');
    Route::post('/account/settings/{type}', 'Front\Setting\AccountController@updateAccount')->name('setting.account.update');

    /*Unsubscribe and notification ajax*/
    Route::post('/account/settings/subscriptions/update', 'Front\Setting\AccountController@subscriptionsBlogUpdate')->name('setting.account.subscriptions.update');

    Route::delete('/account/settings/comments/destroy/{id}', 'Front\Setting\AccountController@commentDestroy')->name('setting.account.comments.destroy');
    Route::get('/account/settings/message/not-read', 'Front\Message\MessagesController@messagesNotRead')->name('setting.account.massage.not_read');

    Route::get('/account/settings/message/administration', 'Front\Message\MessagesController@messagesAdministration')->name('setting.account.massage.administration');

    Route::get('/account/settings/message/thread/{id}', 'Front\Message\MessagesController@messagePage')->name('setting.account.message.page');
    Route::post('/account/settings/message/thread/{id}', 'Front\Message\MessagesController@messagePage')->name('setting.account.message.page');

    /*Оповещения*/
    Route::get('/account/settings/message/notification', 'Front\Message\MessagesController@alertMessagePage')->name('setting.account.alert');
    Route::post('/account/settings/message/notification/destroy/{id}', 'Front\Message\MessagesController@alertMessageDestroy')->name('setting.account.alert.destroy');

    Route::post('/account/settings/message/thread/{thread}/{id}/message-delete', 'Front\Message\MessagesController@messageDelete')->name('setting.account.message.delete');

    Route::get('/account/settings/message/thread/{id}/delete', 'Front\Message\MessagesController@threadDestroy')->name('setting.account.thread.destroy');
    Route::post('/account/settings/message/thread/{id}/send', 'Front\Message\MessagesController@messageSend')->name('setting.account.message.page.send');

    /*Message from admin*/
    Route::get('/account/settings/message/thread/{id}/admin-delete', 'Front\Message\MessagesController@threadAdminDestroy')->name('setting.account.thread.admin.destroy');

    Route::post('/account/settings/message/file/download/{id}', 'Front\Message\MessagesController@downloadFile')->name('setting.account.message.admin.download');

    Route::group(['middleware' => ['auth:user']], function () {
        /*Account Blog*/
        Route::get('/account/settings/blog/create', 'Front\Blog\CreateBlogController@createBlog')->name('setting.account.blog.create');
        Route::post('/account/settings/blog/create', 'Front\Blog\CreateBlogController@storeBlog')->name('setting.account.blog.store');

        /*Post Create Update Delete*/
        Route::get('/account/settings/post/create', 'Front\Blog\CreateBlogController@createPost')->name('setting.account.post.create');
        Route::post('/account/settings/post/store', 'Front\Blog\CreateBlogController@storePost')->name('setting.account.post.store');
        Route::get('/account/settings/post/edit/{post_id}', 'Front\Blog\CreateBlogController@editPost')->name('setting.account.post.edit');
        Route::post('/account/settings/post/update/{post_id}', 'Front\Blog\CreateBlogController@updatePost')->name('setting.account.post.update');
        Route::delete('/account/settings/post/destroy/{post_id}', 'Front\Blog\CreateBlogController@destroyPost')->name('setting.account.post.destroy');

        /*Discussion*/
        Route::post('/discussion/post/{post_id}', 'Front\Posts\DiscussionController@send')->name('page.post.discussion.send');

        /*Add friend*/
        Route::post('/people/friend/add/', 'Front\Friend\FriendController@addFriend')->name('page.people.friend.add');
        Route::post('/people/friend/accept/', 'Front\Friend\FriendController@acceptFriend')->name('page.people.friend.accept');
        Route::post('/people/friend/cancel/', 'Front\Friend\FriendController@cancelFriend')->name('page.people.friend.cancel');
        Route::post('/people/friend/delete/', 'Front\Friend\FriendController@deleteFriend')->name('page.people.friend.delete');

        /*Send first message*/
        Route::post('/people/send-message', 'Front\Message\MessagesController@sendFirst')->name('resource.message.send.first');

        /*Pre Upload and Upload image*/
        Route::post('/account/image/upload-image', 'Manager\UploadManagerController@uploadImage')->name('resource.upload.image');
        Route::post('/account/image/pre-upload-image', 'Manager\UploadManagerController@preUploadImage')->name('resource.pre_upload.image');

    });

    Route::post('/image/upload-custom-image', 'Manager\UploadManagerController@uploadCustomImage')->name('resource.upload.custom-image');

    /*Registration*/
    Route::get('/authorization/register', 'Front\RegisterPageController@index')->name('register');
    Route::post('/authorization/auto-complete', 'Front\RegisterPageController@autoCompleteCity')->name('register.autocomplete');
    Route::post('/authorization/auto-complete/company', 'Front\RegisterPageController@autoCompleteCompany')->name('register.company.autocomplete');
    Route::post('/check/company/is-user', 'Front\RegisterPageController@checkCompanyIsUser')->name('check.company.is.user');
    Route::post('/authorization/register/user', 'Front\RegisterPageController@registerExpert')->name('register.user');
    Route::post('/authorization/register/company', 'Front\RegisterPageController@registerCompany')->name('register.company');

});

/*User Register*/
Route::get('/user/logout', 'Front\UserAuth\LoginController@logout')->name('user.login.logout');
Route::group(array('as' => 'user.'), function () {
    Route::post('/user/auth', 'Front\UserAuth\LoginController@login')->name('login');
    Route::post('/forgot-password', 'Front\UserAuth\ForgotPasswordController@sendResetLinkEmail')->name('login.forgot.password');
    Route::post('/ulogin', 'Front\UserAuth\UloginAuthController@login')->name('login.ulogin');
});

/*System Route*/
/*Generate slug*/
Route::post('/generate-slug','Controller@generateSlug')->name('generate.slug');

/*Counter Link route*/
Route::post('/count-link', 'Controller@countLink')->name('count.link');

/*Counter Link route*/
Route::post('/api/webhook', 'HookController@webHook')->name('rbk.webhook');

Route::get('/reset/password', 'Front\UserAuth\ForgotPasswordController@regenerateNewPassword')->name('api.password.reset');

/*Unsubscribe link*/
Route::get('/user/unsubscribe', 'Front\HomePageController@unsubscribe')->name('api.user.unsubscribe');

/*Researches Unsubscribe*/
Route::get('/researches/unsubscribe', 'Front\HomePageController@unsubscribeResearches')->name('api.user.unsubscribe.researches');

/*Unsubscribe Blog*/
Route::get('/user/unsubscribe-blog', 'Front\HomePageController@unsubscribeBlog')->name('api.user.unsubscribe.blog');

Route::get('/search', 'SearchController@index')->name('search.full');