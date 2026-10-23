<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_can_delete_own_post(): void
    {
        $author = User::factory()->create();
        $post = Post::create([
            'user_id' => $author->id,
            'title' => 'My Algorithm Challenge',
            'body' => 'Solve this binary tree question: ```php ... ```',
            'type' => 'challenge',
        ]);

        $response = $this->actingAs($author)->delete("/posts/{$post->id}");
        $response->assertRedirect('/feed');
        $response->assertSessionHas('status');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_admin_can_moderate_and_delete_any_post(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $spamPost = Post::create([
            'user_id' => $student->id,
            'title' => 'Inappropriate / Spam Post',
            'body' => 'Spam content to be removed by admin.',
            'type' => 'knowledge',
        ]);

        $response = $this->actingAs($admin)->delete("/posts/{$spamPost->id}");
        $response->assertRedirect('/feed');
        $response->assertSessionHas('status');

        $this->assertDatabaseMissing('posts', ['id' => $spamPost->id]);
    }

    public function test_unauthorized_user_cannot_delete_another_users_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create(['role' => 'student']);

        $post = Post::create([
            'user_id' => $author->id,
            'title' => 'Original Post',
            'body' => 'Content here',
            'type' => 'knowledge',
        ]);

        $response = $this->actingAs($otherUser)->delete("/posts/{$post->id}");
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_admin_or_author_can_delete_reply(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $post = Post::create([
            'user_id' => $admin->id,
            'title' => 'Challenge #1',
            'body' => 'Question body',
            'type' => 'challenge',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $student->id,
            'body' => 'Here is my solution!',
        ]);

        // Student can delete their own reply
        $response = $this->actingAs($student)->delete("/comments/{$comment->id}");
        $response->assertSessionHas('status');
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        // Admin can also delete replies
        $comment2 = Comment::create([
            'post_id' => $post->id,
            'user_id' => $student->id,
            'body' => 'Inappropriate solution',
        ]);

        $adminResponse = $this->actingAs($admin)->delete("/comments/{$comment2->id}");
        $adminResponse->assertSessionHas('status');
        $this->assertDatabaseMissing('comments', ['id' => $comment2->id]);
    }
}
