<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newsletter;

    protected $email;

    public $tries = 5;

    public $backoff = 600;
    /**
     * Create a new job instance.
     */
    public function __construct(Newsletter $newsletter, $email = null)
    {
        $this->newsletter = $newsletter;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data['view'] = 'emails.newsletter';
        $data['subject'] = $this->newsletter->subject;
        $data['email'] = env('MAIL_FROM_ADDRESS');
        $data['message'] = $this->newsletter->content;

        if ($this->email) {
            Mail::to($this->email)->queue(new SendMail($data));
        } else {
            $emails = User::where('is_active', 1)->pluck('email')->toArray();
            foreach ($emails as $email) {
                Mail::to($email)->queue(new SendMail($data));
            }
        }
    }
}
