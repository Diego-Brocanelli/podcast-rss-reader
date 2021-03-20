<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use DateTime;
use DiegoBrocanelli\Podcast\Reader;
use DiegoBrocanelli\Podcast\Episode;
use DiegoBrocanelli\Podcast\Image;
use InvalidArgumentException;
use Laminas\Feed\Reader\Feed\Rss;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Podcast
{
    public string $title;
    public string $link;
    public string $description;
    public DateTime $lastBuildDate;
    public DateTime $dateCreated;
    public DateTime $dateModified;
    public DateTime $pubDate;
    public string $language;
    /** @var Episode[] */
    public array $episodes;
    private Rss $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $parser = $reader->parse();
        $xml    = $parser->rss;

        $this->title         = $xml->getTitle() ?? '';
        $this->link          = $xml->getLink()  ?? '';
        $this->description   = $xml->getDescription() ?? '';
        $this->lastBuildDate = $xml->getLastBuildDate();
        $this->dateCreated   = $xml->getDateCreated() ?? new DateTime();
        $this->dateModified  = $xml->getDateModified();
        $this->pubDate       = $xml->getDateModified();
        $this->language      = $xml->getLanguage() ?? '';
        $this->reader        = $xml;
    }

    /**
     * @return array<string,string|DateTime>
     */
    public function info(): array
    {
        return [
            'title'         => $this->title,
            'link'          => $this->link,
            'description'   => $this->description,
            'lastBuildDate' => $this->lastBuildDate,
            'dateCreated'   => $this->dateCreated,
            'dateModified'  => $this->dateModified,
            'pubDate'       => $this->pubDate,
            'language'      => $this->language,
        ];
    }

    /**
     * @return Image
     */
    public function getImageInfo(): Image
    {
        $info = $this->reader->getImage();

        $title = $info['title'] ?? '';
        $url   = $info['uri']   ?? '';
        $link  = $info['link']  ?? '';

        $image = new Image(
            $title,
            $url,
            $link
        );

        return $image;
    }

    /**
     * @return array<int,Episode>
     */
    public function getEpisodes(): array
    {
        foreach ($this->reader as $item) {
            if ($item->getEnclosure() === null) {
                throw new \InvalidArgumentException('The feed is possibly not a valid podcast.');
            }

            $pattern = '/audio/';
            $analize = preg_match($pattern, $item->getEnclosure()->type);
            if ($analize === 0) {
                throw new \InvalidArgumentException('The feed is possibly not a valid podcast.');
            }

            $title       = $item->getTitle();
            $link        = $item->getLink() ?? '';
            $pubDate     = $item->getDateCreated() ?? new DateTime();
            $guid        = $item->getLink() ?? '';
            $comments    = $item->getCommentFeedLink() ? $item->getCommentFeedLink() : '';
            $category    = '';
            $description = $item->getDescription();
            $audio       = (array)$item->getEnclosure();

            $this->episodes[] = new Episode(
                $title,
                $link,
                $pubDate,
                $guid,
                $comments,
                $category,
                $description,
                $audio
            );
        }

        return $this->episodes;
    }

    /**
     * @return DateTime
     */
    public function lastBuildDate(): DateTime
    {
        return $this->lastBuildDate;
    }

    /**
     * @return array<int,Episode>
     */
    public function biggerThen(DateTime $date): array
    {
        $list = [];
        foreach ($this->getEpisodes() as $episode) {
            $diff = $episode->getPubDate()->diff($date);

            if ($diff->invert === 0) {
                break;
            }

            $list[] = $episode;
        }

        return $list;
    }
}
