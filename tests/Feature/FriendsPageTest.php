<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FriendsPageTest extends TestCase
{
    #[Test]
    public function the_friends_page_is_hidden_while_unpublished(): void
    {
        $this->updatePage('/friends', [])->published(false)->save();

        $this->get('/friends')->assertNotFound();
        $this->get('/friends.opml')->assertNotFound();

        $this->get('/')
            ->assertOk()
            ->assertDontSee('href="/friends"', false);
    }

    #[Test]
    public function the_friends_page_lists_friends_by_group(): void
    {
        $this->publishFriendsPage();

        $this->get('/friends')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'Friends test', 'Subscribe to all of them', 'href="/friends.opml"'], false)
            ->assertSeeInOrder(['First group', 'Second group', 'Outro test heading', 'The outro.'])
            ->assertSeeInOrder(['Test friend', 'href="https://www.friend.example.com"', 'href="https://friend.example.com/feed.xml"', 'friend.example.com · they/them', 'A note about them.'], false)
            ->assertDontSee('Empty group');
    }

    #[Test]
    public function friends_opml_lists_each_feed_once(): void
    {
        $this->publishFriendsPage();

        $response = $this->get('/friends.opml')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/x-opml; charset=UTF-8');

        $opml = simplexml_load_string($response->getContent());

        $this->assertNotFalse($opml);
        $this->assertSame('2.0', (string) $opml['version']);
        $this->assertCount(1, $opml->body->outline);
        $this->assertSame('https://friend.example.com/feed.xml', (string) $opml->body->outline[0]['xmlUrl']);
        $this->assertSame('rss', (string) $opml->body->outline[0]['type']);
    }

    private function publishFriendsPage(): void
    {
        $friend = [
            'name' => 'Test friend',
            'pronouns' => 'they/them',
            'url' => 'https://www.friend.example.com',
            'rss' => 'https://friend.example.com/feed.xml',
            'note' => 'A note about them.',
        ];

        $this->updatePage('/friends', [
            'title' => 'Friends test',
            'groups' => [
                ['id' => 'friends-test-first', 'heading' => 'First group', 'friends' => [['id' => 'friends-test-1', ...$friend]]],
                ['id' => 'friends-test-empty', 'heading' => 'Empty group', 'friends' => []],
                ['id' => 'friends-test-second', 'heading' => 'Second group', 'friends' => [['id' => 'friends-test-2', ...$friend]]],
            ],
            'outro_heading' => 'Outro test heading',
            'outro' => 'The outro.',
        ])->published(true)->save();
    }
}
