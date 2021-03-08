{{ Request::header('Content-Type : text/xml') }}
<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ЛюдиИпотеки.рф / RSS-канал]]></title>
        <link><![CDATA[{{route('front.page.news.all')}}]]></link>
        <description><![CDATA[ЛюдиИпотеки.рф – сообщество участников ипотечного рынка]]></description>
        <language><![CDATA[ru-ru]]></language>
		<copyright><![CDATA[©2011 ООО «ЛюдиИпотеки.рф». Все права защищены.]]></copyright>
        <image>
            <url><![CDATA[{{asset('img/logo.gif')}}]]></url>
            <title><![CDATA[ЛюдиИпотеки.рф]]></title>
            <link><![CDATA[{{config('app.url')}}]]></link>
        </image>
        <lastBuildDate><![CDATA[{{$news->first()->created_at->format(DateTime::RSS)}}]]></lastBuildDate>
        @foreach($news as $row)
        <item>
            <title><![CDATA[{{$row->title}}]]></title>
			@if ($row->category->parent_id == 0)
				{{--Url news category without child--}}
				<link><![CDATA[{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}]]></link>
			@else
				{{--Url news category have child--}}
				<link><![CDATA[{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}]]></link>
			@endif
            <description><![CDATA[{{$row->announce}}]]></description>
            <pubDate><![CDATA[{{ $row->created_at->format(DateTime::RSS)}}]]></pubDate>
			@if ($row->category->parent_id == 0)
				{{--Url news category without child--}}
				<guid><![CDATA[{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}]]></guid>
			@else
				{{--Url news category have child--}}
                <guid><![CDATA[{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}]]></guid>
			@endif
        </item>
        @endforeach
    </channel>
</rss>