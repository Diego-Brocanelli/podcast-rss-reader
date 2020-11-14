<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use DateTime;
use DiegoBrocanelli\Podcast\Reader;
use DiegoBrocanelli\Podcast\Episode;
use DiegoBrocanelli\Podcast\Image;
use SimpleXMLElement;

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
    public array $episodes;

    private SimpleXMLElement $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $parser = $reader->parse();
        $xml    = $parser->rss;
        $info   = (array)$xml;

        $this->title         = $info['title'];
        $this->link          = $info['link'];
        $this->description   = $info['description'];
        $this->lastBuildDate = new DateTime($info['lastBuildDate']);
        $this->pubDate       = new DateTime($info['pubDate']);
        $this->language      = $info['language'];

        $this->reader = $xml;
    }

    /**
     * @return array
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
        $info = (array)$this->reader->image;
        
        $image       = new Image();
        $image->title = $info['title'];
        $image->url   = $info['url'];
        $image->link  = $info['link'];

        return $image;
    }

    /**
     * @return array
     */
    public function getEpisodes(): array
    {
        foreach ($this->reader->item as $value) {
            $value = (array)$value;

            $atributes = (array)$value['enclosure'];

            $episode              = new Episode();
            $episode->title       = $value['title'];
            $episode->link        = $value['link'];
            $episode->pubDate     = new DateTime($value['pubDate']);
            $episode->guid        = $value['guid'];
            $episode->comments    = $value['comments'];
            $episode->category    = '';
            $episode->description = is_string($value['description']) ? $value['description'] : '';
            $episode->audio       = $atributes['@attributes'];
            
            $this->episodes[] = $episode;
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
     * @param DateTime $date
     * @return array
     */
    public function biggerThen(DateTime $date): array
    {
        $list = [];
        foreach($this->getEpisodes() as $episode){

            $diff = $episode->pubDate->diff($date);

            if($diff->invert === 0){
                break;
            }

            $list[] = $episode;
        }

        return $list;
    }
}