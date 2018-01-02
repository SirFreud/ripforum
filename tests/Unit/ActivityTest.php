<?php

namespace Tests\Unit;

use App\Activity;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivityTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();
        $thread = factory('App\Thread')->create();
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_reply_is_created()
    {
        $this->signIn();

        $reply = factory('App\Reply')->create();

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        // Given we have a thread and another thread from a week ago    
        factory('App\Thread', 2)->create(['user_id' => auth()->id()]);

        // Take the first thread and set the 'created_at' to one week ago
        auth()->user()->activity()->first()->update([
            'created_at' => Carbon::now()->subWeek()
        ]);

        // When we fetch their feed 
        $feed = Activity::feed(auth()->user(), 50);

        // Then it should be returned in the proper format
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('m-d-Y')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('m-d-Y')
        ));
    }    
}
