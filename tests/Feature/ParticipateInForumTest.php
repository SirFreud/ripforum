<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $reply = factory('App\Reply')->create();
        $this->withExceptionHandling()
            ->post('threads/channel/1/replies', $reply->toArray())
            ->assertRedirect(route('login'));
    }
    
    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we hdave an authenticated user and an existing thread
        $this->be($user = factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        // When the user adds a reply to the thread
        $reply = factory('App\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());
        // Their reply should be visible on the page 
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {

        $this->withExceptionHandling()->signIn();

        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make(['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
    
}
