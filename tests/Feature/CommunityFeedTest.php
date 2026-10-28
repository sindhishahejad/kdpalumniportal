<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_feed(): void
    {
        $response = $this->get('/feed');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_community_feed(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        Post::create([
            'user_id' => $user->id,
            'title' => 'Welcome to KDP Engineering Community',
            'body' => 'Here we share project tips and diploma exam strategies.',
            'type' => 'knowledge',
        ]);

        $response = $this->actingAs($user)->get('/feed');

        $response->assertStatus(200);
        $response->assertSee('Knowledge Feed &amp; Challenge Board', false);
        $response->assertSee('Welcome to KDP Engineering Community');
        $response->assertSee('💡 Knowledge', false);
    }

    public function test_user_can_publish_new_knowledge_post(): void
    {
        $user = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $response = $this->actingAs($user)->post('/feed', [
            'type' => 'knowledge',
            'title' => 'How to setup Arduino IDE on Ubuntu',
            'body' => 'Step 1: Download tarball. Step 2: Install rules.',
        ]);

        $response->assertRedirect('/feed');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'How to setup Arduino IDE on Ubuntu',
            'type' => 'knowledge',
        ]);
    }

    public function test_user_can_filter_feed_by_type(): void
    {
        $user = User::factory()->create(['role' => 'faculty', 'is_approved' => true]);

        $knowledgePost = Post::create([
            'user_id' => $user->id,
            'title' => 'Diploma Exam Formula Sheet',
            'body' => 'Formula sheet for civil mechanics.',
            'type' => 'knowledge',
        ]);

        $challengePost = Post::create([
            'user_id' => $user->id,
            'title' => 'Weekly Microcontroller Bug Challenge',
            'body' => 'Find the bug in this ISR handler.',
            'type' => 'challenge',
        ]);

        $response = $this->actingAs($user)->get('/feed?type=challenge');
        $response->assertStatus(200);
        $response->assertSee('Weekly Microcontroller Bug Challenge');
        $response->assertDontSee('Diploma Exam Formula Sheet');
    }

    public function test_user_can_search_feed_by_keyword(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        Post::create([
            'user_id' => $user->id,
            'title' => 'Introduction to Flutter Architecture',
            'body' => 'BLoC and Riverpod best practices.',
            'type' => 'knowledge',
        ]);

        Post::create([
            'user_id' => $user->id,
            'title' => 'AutoCAD Drafting Standards',
            'body' => 'Symbols for mechanical drafting.',
            'type' => 'knowledge',
        ]);

        $response = $this->actingAs($user)->get('/feed?q=Flutter');
        $response->assertStatus(200);
        $response->assertSee('Introduction to Flutter Architecture');
        $response->assertDontSee('AutoCAD Drafting Standards');
    }

    public function test_user_can_reply_to_post(): void
    {
        $author = User::factory()->create(['role' => 'faculty', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $post = Post::create([
            'user_id' => $author->id,
            'title' => 'Challenge #4: Invert a Binary Tree',
            'body' => 'Write an iterative function in C or C++.',
            'type' => 'challenge',
        ]);

        $response = $this->actingAs($student)->post("/posts/{$post->id}/comments", [
            'body' => 'Here is my solution using a queue BFS: ```c void invert() {} ```',
        ]);

        $response->assertSessionHas('status');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $student->id,
        ]);
    }
}
