{{ Request::header('Content-Type : text/xml') }}
<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ЛюдиИпотеки.рф / RSS-канал]]></title>
        <link><![CDATA[{{route('front.page.anons')}}]]></link>
        <description><![CDATA[ЛюдиИпотеки.рф – сообщество участников ипотечного рынка]]></description>
        <language><![CDATA[ru-ru]]></language>
		<copyright><![CDATA[©2011 ООО «ЛюдиИпотеки.рф». Все права защищены.]]></copyright>
        <image>
            <url><![CDATA[{{asset('img/logo.gif')}}]]></url>
            <title><![CDATA[ЛюдиИпотеки.рф]]></title>
            <link><![CDATA[{{config('app.url')}}]]></link>
        </image>
        <lastBuildDate><![CDATA[{{$announces->first()->created_at->format(DateTime::RSS)}}]]></lastBuildDate>
        @foreach($announces as $row)
        <item>
            <title><![CDATA[{{$row->title}}]]></title>
            <link><![CDATA[{{route('front.page.anons.page', ['anons_id' => $row->id])}}]]></link>
            <description><![CDATA[{{$row->meta_description}}]]></description>
            <pubDate><![CDATA[{{$row->created_at->format(DateTime::RSS)}}]]></pubDate>
            <guid><![CDATA[{{route('front.page.anons.page', ['anons_id' => $row->id])}}]]></guid>
        </item>
        @endforeach
    </channel>
</rss>