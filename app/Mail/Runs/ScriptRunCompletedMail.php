<?php

namespace App\Mail\Runs;

use App\Models\ScriptRun;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScriptRunCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ScriptRun $run;

    /**
     * Create a new message instance.
     */
    public function __construct(ScriptRun $run)
    {
        $this->run = $run;
    }

    public function build()
    {
        $this->run->load(['project', 'owner', 'script.scriptFile.directory']);
        return $this->subject("Your script run of {$this->run->script->scriptFile->fullPath()} completed")
                    ->view('email.runs.run-completed', [
                        'run' => $this->run,
                    ]);
    }

}
