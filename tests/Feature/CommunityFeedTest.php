<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_alumni_or_admin_can_publish_mcq_with_image_and_custom_options(): void
    {
        Storage::fake('public');
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $image = UploadedFile::fake()->image('circuit_schematic.png', 400, 300);

        $response = $this->actingAs($alumni)->post('/feed', [
            'type' => 'challenge',
            'question_type' => 'mcq',
            'title' => 'Inverting Op-Amp Gain Calculation',
            'body' => 'Look at the attached circuit diagram and find closed loop gain Av.',
            'image' => $image,
            'options' => ['-10', '+10', '-100', 'Infinity'],
            'correct_option' => 0,
            'explanation' => 'Gain for inverting operational amplifier is Av = -Rf / R1 = -10.',
        ]);

        $response->assertRedirect('/feed');
        $response->assertSessionHas('status');

        $post = Post::where('title', 'Inverting Op-Amp Gain Calculation')->first();
        $this->assertNotNull($post);
        $this->assertEquals('mcq', $post->question_type);
        $this->assertEquals(4, count($post->options));
        $this->assertEquals('-10', $post->correct_answer);
        $this->assertNotNull($post->image_path);
        Storage::disk('public')->assertExists($post->image_path);
    }

    public function test_student_submits_mcq_answer_and_it_is_verified_instantly(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $student1 = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $student2 = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $mcqPost = Post::create([
            'user_id' => $alumni->id,
            'type' => 'challenge',
            'question_type' => 'mcq',
            'title' => 'Binary Search Time Complexity',
            'body' => 'What is the worst-case time complexity of binary search on a sorted array?',
            'options' => ['O(1)', 'O(n)', 'O(log n)', 'O(n^2)'],
            'correct_answer' => 'O(log n)',
            'explanation' => 'Binary search divides the search space in half in each step, taking O(log n).',
        ]);

        // Student 1 submits correct answer
        $res1 = $this->actingAs($student1)->post(route('posts.submit', $mcqPost), [
            'answer' => 'O(log n)',
        ]);
        $res1->assertSessionHas('status');
        $this->assertDatabaseHas('post_submissions', [
            'post_id' => $mcqPost->id,
            'user_id' => $student1->id,
            'is_correct' => true,
        ]);

        // Student 2 submits wrong answer
        $res2 = $this->actingAs($student2)->post(route('posts.submit', $mcqPost), [
            'answer' => 'O(n)',
        ]);
        $res2->assertSessionHas('error');
        $this->assertDatabaseHas('post_submissions', [
            'post_id' => $mcqPost->id,
            'user_id' => $student2->id,
            'is_correct' => false,
        ]);

        $this->assertEquals(50, $mcqPost->fresh()->successRate());
    }

    public function test_author_can_publish_fill_in_the_blank_and_student_answer_is_verified(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $post = Post::create([
            'user_id' => $admin->id,
            'type' => 'challenge',
            'question_type' => 'fill_blank',
            'title' => '8051 Special Function Register',
            'body' => 'In 8051 microcontroller, the default math accumulator register is called ___.',
            'correct_answer' => 'ACC, Accumulator, Register A',
            'explanation' => 'Register ACC (Accumulator) is used in all ALU operations.',
        ]);

        // Student answers correctly with case-insensitivity
        $res = $this->actingAs($student)->post(route('posts.submit', $post), [
            'answer' => 'accumulator',
        ]);
        $res->assertSessionHas('status');
        $this->assertDatabaseHas('post_submissions', [
            'post_id' => $post->id,
            'user_id' => $student->id,
            'is_correct' => true,
        ]);

        // Another student answers incorrectly
        $studentFail = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $resFail = $this->actingAs($studentFail)->post(route('posts.submit', $post), [
            'answer' => 'Program Counter',
        ]);
        $resFail->assertSessionHas('error');
        $this->assertDatabaseHas('post_submissions', [
            'post_id' => $post->id,
            'user_id' => $studentFail->id,
            'is_correct' => false,
        ]);
    }

    public function test_author_can_publish_coding_challenge_and_student_code_is_verified(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $codingPost = Post::create([
            'user_id' => $alumni->id,
            'type' => 'challenge',
            'question_type' => 'coding',
            'title' => 'Sum of Even Numbers up to N',
            'body' => 'Write a function that calculates the sum of all even integers from 1 to 10.',
            'programming_language' => 'python',
            'difficulty' => 'easy',
            'starter_code' => "def sum_evens():\n    pass",
            'correct_answer' => '30',
            'explanation' => 'Evens are 2 + 4 + 6 + 8 + 10 = 30.',
        ]);

        // Student submits valid code and expected output
        $res = $this->actingAs($student)->post(route('posts.submit', $codingPost), [
            'answer' => "def sum_evens():\n    return sum(x for x in range(1, 11) if x % 2 == 0)",
            'code_output' => '30',
        ]);

        $res->assertSessionHas('status');
        $this->assertDatabaseHas('post_submissions', [
            'post_id' => $codingPost->id,
            'user_id' => $student->id,
            'is_correct' => true,
        ]);
    }
}
