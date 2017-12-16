<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $reply = factory('App\Reply')->create();
        $this->post('threads/1/replies', $reply->toArray());
    }
    
    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have an authenticated user and an existing thread
        $this->be($user = factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        // When the user adds a reply to the thread
        $reply = factory('App\Reply')->make();
        $this->post($thread->path() . '/replies', $reply->toArray());
        // Their reply should be visible on the page 
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
