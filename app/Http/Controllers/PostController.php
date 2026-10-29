<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user.profile', 'comments.user', 'submissions.user'])->latest();

        // General type filter: knowledge vs challenge
        if ($request->filled('type') && in_array($request->type, ['knowledge', 'challenge'])) {
            $query->where('type', $request->type);
        }

        // Specific question type filter: discussion, mcq, fill_blank, coding
        if ($request->filled('question_type') && in_array($request->question_type, ['discussion', 'mcq', 'fill_blank', 'coding'])) {
            $query->where('question_type', $request->question_type);
        }

        // Keyword search in title or body
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('starter_code', 'like', "%{$search}%");
            });
        }

        $counts = [
            'all' => Post::count(),
            'knowledge' => Post::where('type', 'knowledge')->count(),
            'challenge' => Post::where('type', 'challenge')->count(),
            'mcq' => Post::where('question_type', 'mcq')->count(),
            'fill_blank' => Post::where('question_type', 'fill_blank')->count(),
            'coding' => Post::where('question_type', 'coding')->count(),
        ];

        $posts = $query->paginate(10)->withQueryString();

        return view('posts.index', compact('posts', 'counts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'in:knowledge,challenge'],
            'question_type' => ['nullable', 'in:discussion,mcq,fill_blank,coding'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['nullable', 'in:easy,medium,hard'],
            // MCQ specific:
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string', 'max:255'],
            'correct_option' => ['nullable'],
            // Fill in the blank specific:
            'correct_blank' => ['nullable', 'string'],
            // Coding challenge specific:
            'programming_language' => ['nullable', 'string', 'max:50'],
            'starter_code' => ['nullable', 'string'],
            'expected_output' => ['nullable', 'string'],
        ]);

        $questionType = $validated['question_type'] ?? ($validated['type'] === 'challenge' ? 'coding' : 'discussion');

        // Handle Image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('feed-images', 'public');
        }

        $options = null;
        $correctAnswer = null;

        if ($questionType === 'mcq') {
            // Filter non-empty options and re-index
            $rawOptions = array_values(array_filter($request->input('options', []), fn($v) => !is_null($v) && trim((string)$v) !== ''));
            if (count($rawOptions) < 2) {
                return back()->withInput()->with('error', 'MCQ questions require at least 2 options.');
            }
            $options = $rawOptions;
            
            // Determine correct option
            $correctOptionIndex = $request->input('correct_option');
            if ($correctOptionIndex === null || !isset($options[$correctOptionIndex])) {
                $correctAnswer = $options[0];
            } else {
                $correctAnswer = $options[$correctOptionIndex];
            }
        } elseif ($questionType === 'fill_blank') {
            $correctAnswer = $request->input('correct_blank', $request->input('correct_answer'));
            if (empty($correctAnswer)) {
                return back()->withInput()->with('error', 'Please specify the accepted answer(s) for the blank.');
            }
        } elseif ($questionType === 'coding') {
            $correctAnswer = $request->input('expected_output', $request->input('correct_answer'));
        }

        $request->user()->posts()->create([
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
            'type' => $validated['type'],
            'question_type' => $questionType,
            'image_path' => $imagePath,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'explanation' => $validated['explanation'] ?? null,
            'starter_code' => $validated['starter_code'] ?? null,
            'programming_language' => $validated['programming_language'] ?? ($questionType === 'coding' ? 'python' : null),
            'difficulty' => $validated['difficulty'] ?? 'medium',
        ]);

        $typeLabel = match($questionType) {
            'mcq' => 'Multiple-choice challenge',
            'fill_blank' => 'Fill-in-the-blank challenge',
            'coding' => 'Algorithmic coding challenge',
            default => 'Knowledge post',
        };

        return redirect()->route('posts.index')->with('status', "{$typeLabel} published successfully to the campus feed!");
    }

    public function submitAnswer(Request $request, Post $post)
    {
        $user = $request->user();
        $answer = trim((string)$request->input('answer', ''));

        if ($post->isMcq()) {
            if ($answer === '') {
                return back()->with('error', 'Please select an option before submitting.');
            }

            // Compare selected option (either index or exact string)
            $isCorrect = false;
            if (is_numeric($answer) && isset($post->options[(int) $answer])) {
                $selectedText = $post->options[(int) $answer];
                $isCorrect = (trim((string)$selectedText) === trim((string)$post->correct_answer) || (string)$answer === trim((string)$post->correct_answer));
                $answer = $selectedText; // Store text for clarity
            } else {
                $isCorrect = (mb_strtolower(trim($answer)) === mb_strtolower(trim((string)$post->correct_answer)));
            }

            $feedback = $isCorrect
                ? '🎉 Correct choice! Excellent understanding.'
                : '❌ Incorrect choice. Review the solution or try again!';

        } elseif ($post->isFillBlank()) {
            if ($answer === '') {
                return back()->with('error', 'Please type your answer into the blank.');
            }

            // Multiple acceptable answers separated by comma, semicolon, or pipe
            $accepted = preg_split('/[,;|]/', (string) $post->correct_answer);
            $normalizedUserAnswer = mb_strtolower(preg_replace('/\s+/', ' ', trim($answer)));

            $isCorrect = false;
            foreach ($accepted as $cand) {
                $normalizedCandidate = mb_strtolower(preg_replace('/\s+/', ' ', trim((string)$cand)));
                if ($normalizedCandidate !== '' && $normalizedUserAnswer === $normalizedCandidate) {
                    $isCorrect = true;
                    break;
                }
            }

            $feedback = $isCorrect
                ? '🎉 Correct! Your answer matched the accepted solution.'
                : '❌ Not quite right. Compare with the technical terminology or try again.';

        } elseif ($post->isCoding()) {
            if ($answer === '') {
                return back()->with('error', 'Please provide your code or solution output.');
            }

            $submittedCode = $answer;
            $userOutput = trim((string)$request->input('code_output', ''));
            $expected = trim((string)$post->correct_answer);

            $isCorrect = false;
            if ($expected === '') {
                // If author didn't set expected output, any non-trivial solution code passes
                $isCorrect = strlen($submittedCode) >= 10;
                $feedback = '✅ Solution verified and submitted successfully!';
            } else {
                // Normalize newlines and compare
                $normalizedExpected = preg_replace('/\r\n|\r/', "\n", trim($expected));
                $normalizedUserOutput = preg_replace('/\r\n|\r/', "\n", trim($userOutput));
                $normalizedCode = preg_replace('/\r\n|\r/', "\n", trim($submittedCode));

                if ($normalizedUserOutput !== '' && $normalizedUserOutput === $normalizedExpected) {
                    $isCorrect = true;
                    $feedback = '🎉 Verification Passed! Output matched expected solution perfectly.';
                } elseif (str_contains($normalizedCode, $normalizedExpected)) {
                    $isCorrect = true;
                    $feedback = '🎉 Verification Passed! Expected result confirmed in solution code.';
                } else {
                    $isCorrect = false;
                    $feedback = '❌ Output mismatch. Expected: "' . Str::limit($expected, 60) . '", Received: "' . ($userOutput ? Str::limit($userOutput, 60) : 'none') . '"';
                }
            }
        } else {
            return back()->with('error', 'This post is a general discussion and does not accept challenge submissions.');
        }

        // Record or update user's submission
        PostSubmission::updateOrCreate(
            [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ],
            [
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'feedback' => $feedback,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_correct' => $isCorrect,
                'feedback' => $feedback,
                'explanation' => $post->explanation,
                'success_rate' => $post->fresh()->successRate(),
            ]);
        }

        $flashType = $isCorrect ? 'status' : 'error';
        return back()->with($flashType, $feedback);
    }

    public function destroy(Post $post)
    {
        $user = auth()->user();

        // Allow Admins to moderate/delete any post, or author to delete their own
        if ($user->role !== 'admin' && $post->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized. You can only delete your own posts.');
        }

        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post or challenge removed successfully.');
    }
}
