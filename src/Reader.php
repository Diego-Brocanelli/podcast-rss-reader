<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Podcast;

use InvalidArgumentException;
use Laminas\Feed\Reader\Reader as LaminasReader;
use Laminas\Feed\Reader\Feed\Rss;
use Laminas\Feed\Reader\Feed\FeedInterface;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Reader
{
    private string $feed;
    public Rss $rss;

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
        $this->rss = LaminasReader::import($this->feed);

        return $this;
    }

    private function isValidFeed(string $feed): void
    {
        if ($feed === '') {
            throw new InvalidArgumentException('Please enter a valid rss feed.');
        }

        $feed = LaminasReader::import($feed);

        if ($feed->valid() === false) {
            throw new InvalidArgumentException('Please enter a valid rss feed.');
        }
    }
}
