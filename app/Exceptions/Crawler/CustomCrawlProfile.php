<?php
namespace App\Exceptions\Crawler;

use Spatie\Crawler\CrawlProfile;
use Psr\Http\Message\UriInterface;

class CustomCrawlProfile extends CrawlProfile
{
    /** @var callable */
    protected $profile;

    public function shouldCrawlCallback(callable $callback)
    {
        $this->profile = $callback;
    }

    /*
     * Determine if the given url should be crawled.
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        if ($url->getScheme() . '://' . $url->getHost() !== config('app.url')) {
            return false;
        }
        if (strpos($url, '?') !== false) {
            return false;
        }
        if ($url->getPath() == null) {
            return false;
        }
        return true;
    }
}