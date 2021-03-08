@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/timepicker/css/timepicki.css')}}" rel="stylesheet">
@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{$content['title']}}</h3>
            </div>
            <div style="background:#ffffff;margin:0;padding:0;font-size:12px;font-family:Tahoma, Verdana, Arial, Helvetica, sans-serif;color:#333333;" link="#0072e1" alink="#0072e1" vlink="#0072e1">
			 <div style="background:#ffffff;font-size:12px;font-family:Tahoma, Verdana, Arial, Helvetica, sans-serif;">
			  <div style="padding:20px;margin:0;">
                @if (!empty($newsletterAdsAdnOffers))
                    <div style="background:#ff0000;color:#ffffff;padding:4px 10px;font-weight:bold;font-size:13px;">
                        ОБЪЯВЛЕНИЯ И БИЗНЕС-ПРЕДЛОЖЕНИЯ
                    </div>
                    <div style="padding:10px;">
                        <p>{!! $newsletterAdsAdnOffers->text !!}</p>
                    </div>
                @endif

                @if ($mainNews->isNotEmpty())
                    <div style="background:#001850;color:#ffffff;padding:4px 10px;font-weight:bold;font-size:13px;">
                        ВАЖНЫЕ НОВОСТИ
                    </div>
                    <div style="padding:10px;">
                        @foreach ($mainNews as $row)
                            @if ($row->category->parent_id == 0)
                                {{--Url news category without child--}}
                               <div>&bull; <a href="{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->name}}</a> | {{ \Carbon\Carbon::parse($row->created_at)->isoFormat("DD MMMM YYYY") }} |</div>
                            @else
                                {{--Url news category have child--}}
                                <div>&bull; <a href="{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->name}}</a> | {{ \Carbon\Carbon::parse($row->created_at)->isoFormat("DD MMMM YYYY") }} |</div>
                            @endif
                        @endforeach
                    </div>
                @endif

                @foreach($categoryNewsWithNews as $category)
                    @if ($category->news->isNotEmpty())
                        <div style="background:#001850;color:#ffffff;padding:4px 10px;font-weight:bold;font-size:13px;">
                            <span style="text-transform:uppercase;color:#ffffff;">{{ $category->name }}</span>
                        </div>
                        <div style="padding:10px;">
                                @foreach ($category->news as $row)
                                    @if ($row->category->parent_id == 0)
                                        {{--Url news category without child--}}
                                        <div>&bull; <a href="{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->name}}</a> | {{ \Carbon\Carbon::parse($row->created_at)->isoFormat("DD MMMM YYYY") }} |</div>
                                    @else
                                        {{--Url news category have child--}}
                                        <div>&bull; <a href="{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->name}}</a> | {{ \Carbon\Carbon::parse($row->created_at)->isoFormat("DD MMMM YYYY") }} |</div>
                                    @endif
                                @endforeach
                        </div>
                    @endif
                @endforeach
                @if (!empty($topic))
                    <div style="background:#001850;color:#ffffff;padding:4px 10px;font-weight:bold;font-size:13px;">
                        <span style="text-transform:uppercase;color:#ffffff;">Тема дня - {{ $topic->title }}</span>
                    </div>
                    <div style="padding:10px;">
                        <p>{!! $topic->text !!}</p>
                            @foreach($topic->subscriber as $user)
                                <div>&bull; {{ $user->name }}
                                    @if (!empty($user->socialProfile->company_post))
                                        | {{ $user->socialProfile->company_post}} |
                                    @endif
                                    @if (!empty($user->socialProfile->company_post))
                                        | {{ $user->company->name }} |
                                    @endif
                                </div>
                            @endforeach
                    </div>
                @endif

                @if ($blogsNews->isNotEmpty())
                    <div style="background:#001850;color:#ffffff;padding:4px 10px;font-weight:bold;font-size:13px;">
                        НОВОСТИ БЛОГОВ
                    </div>
                    <div style="padding:10px;">
                            @foreach($blogsNews as $post)
                                <div>&bull; 
                                    <a href="{{route('front.page.post', ['permission' => $post->user->permission, 'blog_id' => $post->blog->id, 'post_id' => $post->id])}}" class="li-link-blue">{{$post->title}}</a>
                                </div>
                            @endforeach
                    </div>
                @endif

                @if ($anons->isNotEmpty())
                    <div style="background:#001850;color:#ffffff;padding:4px 10px;font-weight:bold;font-size:13px;">
                        МЕРОПРИЯТИЯ
                    </div>
                    <div style="padding:10px;">
                            @foreach($anons as $row)
                                <div>&bull; 
                                    <a href="{{route('front.page.anons.page', ['anons_id' => $row->id])}}" class="li-link-blue">{{$row->title}}</a> | {{\Carbon\Carbon::parse($row->date)->isoFormat("DD MMMM YYYY")}}
                                </div>
                            @endforeach
                    </div>
                @endif
              </div>
			         @if (!empty($footer))
                    <hr/>
                        {!! $footer->footer_text !!}
                    <hr/>
                    {!! str_replace('[ссылка]', '<a href="#">по ссылке</a>', $footer->unsubscribe_text) !!}
                @endif
			 </div>
			</div>
        </div>
    </div>
@endsection