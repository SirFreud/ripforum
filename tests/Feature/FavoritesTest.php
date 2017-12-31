<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function guests_cannot_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        // The URI will be /replies/id/favorites
        $reply = factory('App\Reply')->create();  // This also created a thread in the process

        // If I post to a favorite endpoint
        $this->post('replies/' . $reply->id . '/favorites');

        // It should be recorded in the DB
        $this->assertCount(1, $reply->favorites);

    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        // The URI will be /replies/id/favorites
        $reply = factory('App\Reply')->create();  // This also created a thread in the process
        try
        {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');

        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice');
        }

        // It should be recorded in the DB
        $this->assertCount(1, $reply->favorites);
    }
}
