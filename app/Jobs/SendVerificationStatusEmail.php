<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\DentistVerificationStatus;

class SendVerificationStatusEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $status;
    protected $notes;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $status, ?string $notes = null)
    {
        $this->user = $user;
        $this->status = $status;
        $this->notes = $notes;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new DentistVerificationStatus($this->user, $this->status, $this->notes));
    }
}
