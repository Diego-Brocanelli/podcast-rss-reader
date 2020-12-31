<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use DateTime;
use DiegoBrocanelli\Podcast\Reader;
use DiegoBrocanelli\Podcast\Episode;
use DiegoBrocanelli\Podcast\Image;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Podcast
{
    public string $title;
    public string $link;
    public string $description;
    public DateTime $lastBuildDate;
    public DateTime $pubDate;
    public string $language;
    /** @var Episode[] */
    public array $episodes;
    /** @var Reader[] */
    private array $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $parser = $reader->parse();
        $xml    = $parser->rss;
        $info   = (array)$xml->channel;

        $this->title         = $info['title'];
        $this->link          = $info['link'];
        $this->description   = is_string($info['description']) ? $info['description'] : '';
        $this->lastBuildDate = new DateTime($info['lastBuildDate']);
        $this->pubDate       = array_key_exists('pubDate', $info) ? new DateTime($info['pubDate']) : new DateTime();
        $this->language      = $info['language'];

        $this->reader = $info;
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
            'pubDate'       => $this->pubDate,
            'language'      => $this->language,
        ];
    }

    /**
     * @return Image
     */
    public function getImageInfo(): Image
    {
        $info = (array)$this->reader['image'];

        $title = $info['title'] ?? '';
        $url   = $info['url']   ?? '';
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
        $item = (array)$this->reader['item'];
        foreach ($item as $value) {
            $value = (array)$value;

            if (!array_key_exists('enclosure', $value)) {
                throw new \Exception('The feed is possibly not a valid podcast.');
            }

            $atributes = (array)$value['enclosure'];

            $title       = $value['title'];
            $link        = $value['link'];
            $pubDate     = new DateTime($value['pubDate']);
            $guid        = $value['guid'];
            $comments    = $value['comments'];
            $category    = '';
            $description = is_string($value['description']) ? $value['description'] : 'not provided' ;
            $audio       = $atributes['@attributes'];

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
