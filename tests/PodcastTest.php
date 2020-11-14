<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DiegoBrocanelli\Podcast\Image;
use DiegoBrocanelli\Podcast\Podcast;
use DiegoBrocanelli\Podcast\Reader;

final class PodcastTest extends TestCase
{
    private string $feed = 'https://devnaestrada.com.br/feed.xml';

    /**
     * @test
     */
    public function allEpisodes(): void
    {
        $episodes = (new Podcast(new Reader($this->feed) ))->getEpisodes();

        $this->assertEquals(
            !empty($episodes),
            true
        );
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function getImageInfo(): void
    {
        $image = (new Podcast(new Reader($this->feed) ))->getImageInfo();

        $this->assertInstanceOf(Image::class, $image);

        $this->assertClassHasAttribute('title', Image::class);
        $this->assertClassHasAttribute('url', Image::class);
        $this->assertClassHasAttribute('link', Image::class);

        $this->assertEquals( is_string($image->title), true );
        $this->assertEquals( !empty($image->title), true );

        $this->assertEquals( is_string($image->link), true );
        $this->assertEquals( !empty($image->link), true );

        $this->assertEquals( is_string($image->url), true );
        $this->assertEquals( !empty($image->url), true );
    }

    /**
     * @test
     */
    public function lastBuildDate(): void
    {
        $podcast = (new Podcast(new Reader($this->feed) ))->lastBuildDate();

        $this->assertInstanceOf(\DateTime::class, $podcast);
    }

    /**
     * @test
     */
    public function invalidEmptyFeed(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Podcast(new Reader('') ));
    }

    /**
     * @test
     */
    public function invalidFeed(): void
    {
        $this->expectException(\Exception::class);

        (new Podcast( new Reader('http://diegobrocanelli.com.br') ) );
    }

    /**
     * @test
     */
    public function biggerThen(): void
    {
        $date = new \DateTime('2020-01-01 00:00:00');
        $podcast = new Podcast(new Reader($this->feed) );
        $list = $podcast->biggerThen($date);

        $this->assertEquals(is_array($list), true);
        $this->assertEquals(count($list) > 0, true);
    }

    /**
     * @test
     */
    public function invalidDateTobiggerThen(): void
    {
        $this->expectException(TypeError::class);

        $podcast = new Podcast(new Reader($this->feed) );
        $list = $podcast->biggerThen('');
    }
}