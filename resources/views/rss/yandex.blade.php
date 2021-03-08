{{ Request::header('Content-Type : text/xml') }}
<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ЛюдиИпотеки.рф / RSS-канал]]></title>
        <link><![CDATA[{{route('front.yandex.rss')}}]]></link>
        <description><![CDATA[ЛюдиИпотеки.рф – сообщество участников ипотечного рынка]]></description>
        <language><![CDATA[ru-ru ]]></language>
        <copyright><![CDATA[©2011 ООО «ЛюдиИпотеки.рф». Все права защищены.]]></copyright>
		<yandex:logo type="square"><![CDATA[{{asset('img/logosquad.png')}}]]></yandex:logo>
        <image>
            <url><![CDATA[{{asset('img/logo.gif')}}]]></url>
            <title><![CDATA[ЛюдиИпотеки.рф]]></title>
            <link><![CDATA[{{config('app.url')}}]]></link>
        </image>
        <lastBuildDate>{{\Carbon\Carbon::now()->format(DateTime::RSS)}}</lastBuildDate>
        @forelse ($news as $record)
            <item>
                <title><![CDATA[{{$record->title}}]]></title>
                @if ($record->category->parent_id == 0)
                    {{--Url news category without child--}}
                    <link><![CDATA[{{route('front.page.news.category.entry', ['url_section' => $record->category->url_en, 'url_news' => $record->url_en])}}]]></link>
                @else
                    {{--Url news category have child--}}
                    <link><![CDATA[{{route('front.page.news.sub_category.entry', ['url_section' => $record->category->parent->url_en, 'url_sub_section' => $record->category->url_en, 'url_news' => $record->url_en])}}]]></link>
                @endif
                <description><![CDATA[{{$record->announce}}]]></description>
				<yandex:full-text><![CDATA[{{$record->text}}]]></yandex:full-text>
                @if ($record->authir_show == true && !empty($record->author_text_val))
                    <author><![CDATA[{{$record->author_text_val}}]]></author>
                @endif
                <pubDate><![CDATA[{{$record->created_at->format(DateTime::RSS)}}]]></pubDate>
                @if ($record->category->parent_id == 0)
                    {{--Url news category without child--}}
                    <guid><![CDATA[{{route('front.page.news.category.entry', ['url_section' => $record->category->url_en, 'url_news' => $record->url_en])}}]]></guid>
                @else
                    {{--Url news category have child--}}
                    <guid><![CDATA[{{route('front.page.news.sub_category.entry', ['url_section' => $record->category->parent->url_en, 'url_sub_section' => $record->category->url_en, 'url_news' => $record->url_en])}}]]></guid>
                @endif
            </item>
        @empty
        @endforelse
    </channel>
</rss>