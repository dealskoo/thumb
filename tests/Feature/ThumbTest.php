<?php

namespace Dealskoo\Thumb\Tests\Feature;

use Dealskoo\Thumb\Tests\Post;
use Dealskoo\Thumb\Tests\Product;
use Dealskoo\Thumb\Tests\TestCase;
use Dealskoo\Thumb\Tests\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class ThumbTest extends TestCase
{
    use RefreshDatabase;

    public function test_thumb_up()
    {
        Event::fake();
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $user->thumbUp($post);
        Event::assertDispatched(\Dealskoo\Thumb\Events\Event::class, function ($event) use ($user, $post) {
            $thumb = $event->thumb;
            return $thumb->thumbable instanceof Post && $thumb->user instanceof User && $thumb->user->id == $user->id && $thumb->thumbable->id == $post->id && $thumb->up == 1 && $thumb->down == 0;
        });
        $this->assertTrue($user->hasThumb($post));
        $this->assertTrue($user->hasThumbUp($post));
        $this->assertFalse($user->hasThumbDown($post));
        $this->assertTrue($post->isThumbedBy($user));
    }

    public function test_thumb_down()
    {
        Event::fake();
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $user->thumbDown($post);
        Event::assertDispatched(\Dealskoo\Thumb\Events\Event::class, function ($event) use ($user, $post) {
            $thumb = $event->thumb;
            return $thumb->thumbable instanceof Post && $thumb->user instanceof User && $thumb->user->id == $user->id && $thumb->thumbable->id == $post->id && $thumb->up == 0 && $thumb->down == 1;
        });
        $this->assertTrue($user->hasThumb($post));
        $this->assertFalse($user->hasThumbUp($post));
        $this->assertTrue($user->hasThumbDown($post));
        $this->assertTrue($post->isThumbedBy($user));
    }

    public function test_toggle_thumb()
    {
        Event::fake();
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $user->toggleThumb($post);
        Event::assertDispatched(\Dealskoo\Thumb\Events\Event::class, function ($event) use ($user, $post) {
            $thumb = $event->thumb;
            return $thumb->thumbable instanceof Post && $thumb->user instanceof User && $thumb->user->id == $user->id && $thumb->thumbable->id == $post->id && $thumb->up == 1 && $thumb->down == 0;
        });
        $this->assertTrue($user->hasThumb($post));
        $this->assertTrue($user->hasThumbUp($post));
        $user->toggleThumb($post);
        Event::assertDispatched(\Dealskoo\Thumb\Events\Event::class, function ($event) use ($user, $post) {
            $thumb = $event->thumb;
            return $thumb->thumbable instanceof Post && $thumb->user instanceof User && $thumb->user->id == $user->id && $thumb->thumbable->id == $post->id && $thumb->up == 0 && $thumb->down == 1;
        });
        $this->assertTrue($user->hasThumb($post));
        $this->assertTrue($user->hasThumbDown($post));
    }

    public function test_has_thumb()
    {
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $user->toggleThumb($post);
        $this->assertTrue($user->hasThumb($post));
    }

    public function test_has_thumb_up()
    {
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $user->thumbUp($post);
        $this->assertTrue($user->hasThumbUp($post));
    }

    public function test_has_thumb_down()
    {
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $user->thumbDown($post);
        $this->assertTrue($user->hasThumbDown($post));
    }

    public function test_thumbs()
    {
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $post1 = Post::create(['title' => 'test guide1']);
        $user->thumbUp($post);
        $user->thumbDown($post1);
        $this->assertTrue($user->hasThumbUp($post));
        $this->assertTrue($user->hasThumbDown($post1));
        $this->assertCount(2, $user->thumbs);
    }

    public function test_get_thumb_items()
    {
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $post1 = Post::create(['title' => 'test guide1']);
        $user->thumbUp($post);
        $user->thumbDown($post1);
        $this->assertTrue($user->hasThumbUp($post));
        $this->assertTrue($user->hasThumbDown($post1));
        $this->assertCount(2, $user->getThumbItems(Post::class)->get());
    }

    public function test_get_thumb_up_items()
    {
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $post1 = Post::create(['title' => 'test guide1']);
        $user->thumbUp($post);
        $user->thumbDown($post1);
        $this->assertTrue($user->hasThumbUp($post));
        $this->assertTrue($user->hasThumbDown($post1));
        $this->assertCount(1, $user->getThumbUpItems(Post::class)->get());
    }

    public function test_get_thumb_down_items()
    {
        $user = User::create(['name' => 'user']);
        $post = Post::create(['title' => 'test guide']);
        $post1 = Post::create(['title' => 'test guide1']);
        $user->thumbUp($post);
        $user->thumbDown($post1);
        $this->assertTrue($user->hasThumbUp($post));
        $this->assertTrue($user->hasThumbDown($post1));
        $this->assertCount(1, $user->getThumbDownItems(Post::class)->get());
    }

    public function test_thumbers()
    {
        $user = User::create(['name' => 'user']);
        $user1 = User::create(['name' => 'user1']);
        $post = Post::create(['title' => 'test guide']);
        $user->thumbUp($post);
        $this->assertTrue($user->hasThumbUp($post));
        $user1->thumbDown($post);
        $this->assertTrue($user1->hasThumbDown($post));
        $this->assertCount(2, $post->thumbs);
        $this->assertCount(1, $post->thumbsUp()->get());
        $this->assertCount(1, $post->thumbsDown()->get());
        $this->assertCount(2, $post->thumbers);
    }
}
