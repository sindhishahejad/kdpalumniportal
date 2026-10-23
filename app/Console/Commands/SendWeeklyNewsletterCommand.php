<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuccessStory;
use App\Models\JobPosting;
use App\Models\Notice;
use App\Mail\WeeklyNewsletter;
use Illuminate\Support\Facades\Mail;

class SendWeeklyNewsletterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send 
                            {--to= : Send test digest directly to a single email address}
                            {--preview : Preview newsletter contents in terminal without sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile and send the weekly campus digest and newsletter to approved portal members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Compiling weekly digest data...');

        $successStories = SuccessStory::latest()->take(3)->get();
        $jobs = JobPosting::where('is_active', true)->latest()->take(3)->get();
        $notices = Notice::latest()->take(3)->get();

        $eligibleUsers = User::where('is_approved', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        // 1. Preview Mode
        if ($this->option('preview')) {
            $this->line('');
            $this->comment('==================================================');
            $this->comment('       KDP ALUMNI WEEKLY DIGEST PREVIEW           ');
            $this->comment('==================================================');
            $this->line("Eligible Active Recipients: {$eligibleUsers->count()} members");
            $this->line('');

            $this->info("🌟 Alumni Spotlights ({$successStories->count()}):");
            foreach ($successStories as $story) {
                $this->line(" • {$story->title} ({$story->alumni_name}, Batch {$story->batch_year})");
            }
            if ($successStories->isEmpty()) {
                $this->line('   (No stories this week)');
            }

            $this->info("💼 Opportunities & Jobs ({$jobs->count()}):");
            foreach ($jobs as $job) {
                $this->line(" • {$job->title} at {$job->company} ({$job->location})");
            }
            if ($jobs->isEmpty()) {
                $this->line('   (No active jobs)');
            }

            $this->info("📢 Campus Notices ({$notices->count()}):");
            foreach ($notices as $notice) {
                $this->line(" • {$notice->title}");
            }
            if ($notices->isEmpty()) {
                $this->line('   (No active notices)');
            }

            $this->comment('==================================================');
            $this->comment('Preview complete. No emails were queued or dispatched.');
            return Command::SUCCESS;
        }

        // 2. Direct Single-Recipient Test Mode
        if ($targetEmail = $this->option('to')) {
            $this->info("Dispatching test digest to: {$targetEmail}...");
            Mail::to($targetEmail)->queue(new WeeklyNewsletter($successStories, $jobs, $notices));
            $this->info("Test digest email queued successfully for {$targetEmail}!");
            return Command::SUCCESS;
        }

        // 3. Batch Dispatch to Approved Users
        $recipientCount = 0;
        foreach ($eligibleUsers as $user) {
            Mail::to($user->email)->queue(new WeeklyNewsletter($successStories, $jobs, $notices));
            $recipientCount++;
        }

        $this->info("Weekly newsletter queued successfully for {$recipientCount} approved members!");
        return Command::SUCCESS;
    }
}
