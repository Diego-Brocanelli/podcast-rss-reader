<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use InvalidArgumentException;
use SimpleXMLElement;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Reader
{
    private string $feed;
    public SimpleXMLElement $rss;

    /**
     * @param string $feed
     */
    public function __construct(string $feed)
    {
        $this->isValidFeed($feed);

        $this->feed = $feed;
    }

    /**
     * @return Reader
     */
    public function parse(): Reader
    {
        $xml = simplexml_load_file( $this->feed );

        if($xml === false){
            throw new InvalidArgumentException('Please enter a valid podcast rss feed.');
        }

        $this->rss = $xml;

        return $this;
    }

    private function isValidFeed($feed): void
    {
        if( $feed === '' ){
            throw new InvalidArgumentException('Please enter a valid rss feed.');
        }

        $file    = file_get_contents($feed);
        $pattern = '/<rss/';
        $analize = preg_match($pattern, $file);
        if($analize === 0){
            throw new InvalidArgumentException('Please enter a valid podcast rss feed.');
        }
    }
}
