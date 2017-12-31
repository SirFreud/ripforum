<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {        
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {            
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // Given we have a thread that includes replies
        $reply = factory('App\Reply')
            ->create(['thread_id' => $this->thread->id]);
        // When we visit that thread page, we should see the replies
        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {

        $channel = create('App\Channel');
        $thread_in_channel = create('App\Thread', ['channel_id' => $channel->id]);
        $thread_not_in_channel = create('App\Thread');
        $this->get('/threads/' . $channel->slug)
            ->assertSee($thread_in_channel->title)
            ->assertDontSee($thread_not_in_channel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // Given we have 3 threads w/ 3, 2, and 0 replies respectively
        $threadWithTwoReplies = factory('App\Thread')->create();
        $reply = factory('App\Reply', 2)->create(['thread_id' => $threadWithTwoReplies]);

        $threadWithThreeReplies = factory('App\Thread')->create();
        $reply = factory('App\Reply', 3)->create(['thread_id' => $threadWithThreeReplies]);

        $threadWithNoReplies = $this->thread;

        // When I filter all threads by popularity
        $response = $this->getJson('threads?popular=1')->json();
        // Then they should be returned from most to least replies
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));

    }
}
