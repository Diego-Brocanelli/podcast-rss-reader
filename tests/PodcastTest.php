<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DiegoBrocanelli\Podcast\Image;
use DiegoBrocanelli\Podcast\Podcast;
use DiegoBrocanelli\Podcast\Reader;

final class PodcastTest extends TestCase
{
    private string $feed = 'https://devnaestrada.com.br/feed.xml';
    private array $feedList = [
        'https://devnaestrada.com.br/feed.xml',
        'https://www.lambda3.com.br/feed/podcast/',
        'https://www.likeaboss.com.br/episodios/feed/like-a-boss/',
        'https://hipsters.tech/feed/podcast/',
        'https://databasecast.com.br/feed/',
        'https://jovemnerd.com.br/feed-nerdcast/',
        'https://feeds.buzzsprout.com/1651339.rss',
        'https://toadcast.com.br/qt-series/toadcast-ciencia/feed/',
    ];

    /** @test */
    public function analyseFeedList(): void
    {
        foreach ($this->feedList as $feed){
            $reader = new Reader($feed);
            $episodes = (new Podcast($reader))->getEpisodes();

            $this->assertEquals(
                !empty($episodes),
                true
            );

            $episode = $episodes[0];

            $this->assertEquals( is_string($episode->getTitle() ), true );
            $this->assertEquals( !empty($episode->getTitle() ), true );

            $this->assertEquals( is_string($episode->getLink() ), true );

            $this->assertEquals( $episode->getPubDate() instanceof DateTime, true );

            $this->assertEquals( is_string($episode->getGuid() ), true );

            $this->assertEquals( is_string($episode->getComments() ), true );

            $this->assertEquals( is_string($episode->getCategory() ), true );

            $this->assertEquals( is_string( $episode->getDescription() ), true );

            $this->assertEquals( is_array($episode->getAudio() ), true );
            $this->assertEquals( !empty($episode->getAudio() ), true );
            $this->assertEquals( !empty($episode->getAudio()['url'] ), true );
            $this->assertEquals( !empty($episode->getAudio()['type'] ), true );
        }
    }

    // /** @test */
    public function allEpisodes(): void
    {
        $episodes = (new Podcast(new Reader($this->feed) ))->getEpisodes();

        $this->assertEquals(
            !empty($episodes),
            true
        );

        $episode = $episodes[0];

        $this->assertEquals( is_string($episode->getTitle() ), true );
        $this->assertEquals( !empty($episode->getTitle() ), true );

        $this->assertEquals( is_string($episode->getLink() ), true );

        $this->assertEquals( $episode->getPubDate() instanceof DateTime, true );

        $this->assertEquals( is_string($episode->getGuid() ), true );

        $this->assertEquals( is_string($episode->getComments() ), true );

        $this->assertEquals( is_string($episode->getCategory() ), true );

        $this->assertEquals( is_string( $episode->getDescription() ), true );

        $this->assertEquals( is_array($episode->getAudio() ), true );
        $this->assertEquals( !empty($episode->getAudio() ), true );
        $this->assertEquals( !empty($episode->getAudio()['url'] ), true );
        $this->assertEquals( !empty($episode->getAudio()['type'] ), true );
    }

    /** @test */
    public function getEpisodesToInvalidPodcastFeed(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        ( new Podcast( new Reader('https://www.lambda3.com.br/feed') ) )->getEpisodes();
    }

    /** @test */
    public function getInfo(): void
    {
        $info = (new Podcast(new Reader($this->feed) ))->info();

        $this->assertEquals( array_key_exists('title', $info), true );
        $this->assertEquals( array_key_exists('link', $info), true );
        $this->assertEquals( array_key_exists('description', $info), true );
        $this->assertEquals( array_key_exists('lastBuildDate', $info), true );
        $this->assertEquals( array_key_exists('pubDate', $info), true );
        $this->assertEquals( array_key_exists('language', $info), true );

        $this->assertEquals( is_string($info['title']), true );
        $this->assertEquals( !empty($info['title']), true );
        $this->assertEquals( is_string($info['link']), true );
        $this->assertEquals( !empty($info['link']), true );
        $this->assertEquals( is_string($info['description']), true );
        $this->assertEquals( !empty($info['description']), true );
        $this->assertInstanceOf(\DateTime::class, $info['lastBuildDate']);
        $this->assertInstanceOf(\DateTime::class, $info['pubDate']);
        $this->assertEquals( is_string($info['language']), true );
        $this->assertEquals( !empty($info['language']), true );
    }

    // /** @test */
    public function getImageInfo(): void
    {
        $image = (new Podcast(new Reader($this->feed) ))->getImageInfo();

        $this->assertInstanceOf(Image::class, $image);

        $this->assertEquals( is_string($image->getTitle() ), true );
        $this->assertEquals( !empty($image->getTitle() ), true );

        $this->assertEquals( is_string($image->getLink() ), true );
        $this->assertEquals( !empty($image->getLink() ), true );

        $this->assertEquals( is_string($image->getUrl() ), true );
        $this->assertEquals( !empty($image->getUrl() ), true );
    }

    // /** @test */
    public function lastBuildDate(): void
    {
        $podcast = (new Podcast(new Reader($this->feed) ))->lastBuildDate();

        $this->assertInstanceOf(\DateTime::class, $podcast);
    }

    /** @test */
    public function invalidEmptyFeed(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Podcast(new Reader('') ));
    }

    /** @test */
    public function invalidFeed(): void
    {
        $this->expectException(\Laminas\Feed\Reader\Exception\RuntimeException::class);

        (new Podcast( new Reader('http://diegobrocanelli.com.br') ) );
    }

    /** @test */
    public function biggerThen(): void
    {
        $date = new \DateTime('2020-01-01 00:00:00');
        $podcast = new Podcast(new Reader($this->feed) );
        $list = $podcast->biggerThen($date);

        $this->assertEquals(is_array($list), true);
        $this->assertEquals(count($list) > 0, true);
    }

    /** @test */
    public function invalidDateTobiggerThen(): void
    {
        $this->expectException(TypeError::class);

        $podcast = new Podcast(new Reader($this->feed) );
        $list = $podcast->biggerThen('');
    }
}
