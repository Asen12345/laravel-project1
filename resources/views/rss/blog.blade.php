{{ Request::header('Content-Type : text/xml') }}
<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ЛюдиИпотеки.рф / RSS-канал]]></title>
        <link><![CDATA[{{route('front.blog.rss', [$blog->id])}}]]></link>
        <description><![CDATA[ЛюдиИпотеки.рф – сообщество участников ипотечного рынка]]></description>
        <language><![CDATA[ru-ru]]></language>
		<copyright><![CDATA[©2011 ООО «ЛюдиИпотеки.рф». Все права защищены.]]></copyright>
        <image>
            <url><![CDATA[{{asset('img/logo.gif')}}]]></url>
            <title><![CDATA[ЛюдиИпотеки.рф]]></title>
            <link><![CDATA[{{config('app.url')}}]]></link>
        </image>
        <lastBuildDate><![CDATA[{{$blog->posts->first()->created_at->format(DateTime::RSS)}}]]></lastBuildDate>
        @foreach($blog->posts as $post)
        <item>
            <title><![CDATA[{{$post->title}}]]></title>
            <link><![CDATA[{{route('front.page.post', ['permission' => $blog->user->permission, 'blog_id' => $blog->id , 'post_id' => $post->id])}}]]></link>
            <description><![CDATA[{{$post->announce}}]]></description>
            <pubDate><![CDATA[{{$post->created_at->format(DateTime::RSS)}}]]></pubDate>
            <guid><![CDATA[{{route('front.page.post', ['permission' => $blog->user->permission, 'blog_id' => $blog->id , 'post_id' => $post->id])}}]]></guid>
        </item>
        @endforeach
    </channel>
</rss>