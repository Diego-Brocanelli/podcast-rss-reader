<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use DateTime;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Episode
{
    private string $title;
    private string $link;
    private DateTime $pubDate;
    private string $guid;
    private string $comments;
    private string $category;
    private string $description;
    private array $audio;

    public function __construct(
        string $title,
        string $link,
        DateTime $pubDate,
        string $guid,
        string $comments,
        string $category,
        string $description,
        array $audio
    )
    {
        $this->title       = $title;
        $this->link        = $link;
        $this->pubDate     = $pubDate;
        $this->guid        = $guid;
        $this->comments    = $comments;
        $this->category    = $category;
        $this->description = $description;
        $this->audio       = $audio;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getPubDate(): DateTime
    {
        return $this->pubDate;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getComments(): string
    {
        return $this->comments;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAudio(): array
    {
        return $this->audio;
    }
}
