<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Image
{
    private string $title;
    private string $url;
    private string $link;

    public function __construct(string $title, string $url, string $link)
    {
        $this->title = $title;
        $this->url   = $url;
        $this->link  = $link;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
