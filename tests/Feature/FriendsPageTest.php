<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FriendsPageTest extends TestCase
{
    #[Test]
    public function the_friends_page_lists_friends_by_group(): void
    {
        $this->get('/friends')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'Friends', 'Subscribe to all of them', 'href="/friends.opml"'], false)
            ->assertSeeInOrder(['People I build with', 'Podcast people', 'Community', 'How people end up here'])
            ->assertSeeInOrder(['Friend name', 'href="https://example.com"', 'href="https://example.com/feed.xml"', 'example.com · their pronouns'], false);
    }

    #[Test]
    public function friends_opml_lists_each_feed_once(): void
    {
        $response = $this->get('/friends.opml')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/x-opml; charset=UTF-8');

        $opml = simplexml_load_string($response->getContent());

        $this->assertNotFalse($opml);
        $this->assertSame('2.0', (string) $opml['version']);
        $this->assertCount(1, $opml->body->outline);
        $this->assertSame('https://example.com/feed.xml', (string) $opml->body->outline[0]['xmlUrl']);
        $this->assertSame('rss', (string) $opml->body->outline[0]['type']);
    }
}
