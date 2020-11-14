<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use DateTime;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Episode
{
    public string $title;
    public string $link;
    public DateTime $pubDate;
    public string $guid;
    public string $comments;
    public string $category;
    public string $description;
    public array $audio;    
}