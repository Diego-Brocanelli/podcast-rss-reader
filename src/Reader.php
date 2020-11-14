<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use Exception;
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
        if($feed === ''){
            throw new InvalidArgumentException('Please enter a valid rss feed.');
        }

        $this->feed = $feed;
    }

    /**
     * @return Reader
     */
    public function parse(): Reader
    {
        $file = file_get_contents($this->feed);
        $xml  = new SimpleXmlElement($file);

        foreach ($xml as $elemente) {
            $this->rss = $elemente;
        }

        return $this;
    }
}