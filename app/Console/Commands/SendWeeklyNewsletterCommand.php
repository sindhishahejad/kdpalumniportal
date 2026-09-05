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
    protected $signature = 'newsletter:send';
    protected $description = 'Compile and send the weekly newsletter digest to all registered users';

    public function handle()
    {
        $this->info('Compiling weekly digest data...');

        $successStories = SuccessStory::latest()->take(3)->get();
        $jobs = JobPosting::latest()->take(3)->get();
        $notices = Notice::latest()->take(3)->get();

        $users = User::all();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new WeeklyNewsletter($successStories, $jobs, $notices));
        }

        $this->info('Weekly newsletter queued successfully for ' . $users->count() . ' users!');
    }
}