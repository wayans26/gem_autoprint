<?php

namespace App\Mail;

use App\Models\report_file;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class report_file_mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $id;
    public function __construct($id)
    {
        //
        $this->id = $id;
    }

    public function build()
    {
        $file = report_file::find($this->id);

        return $this->subject("Report File " . $file->file_name)
            ->from(config('mail.mailers.smtp.from', 'no.replydev.reg-gemindonesia.net'), "Support@AtraxSys")
            ->view('Email.report_file', [
                'logoCid'       => url('/logo.png'),   // dipakai di blade: src="{{ $logoCid }}"
                'logoUrl'       => url('/logo.png'),
                'appName'       => "AtraxSys",
                'reportFile'    => $file,
                'downloadUrl'   => url('/report/download/' . $file->id)
            ]);
    }
}
