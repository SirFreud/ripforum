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
    
}
