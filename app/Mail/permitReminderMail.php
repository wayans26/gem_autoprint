<?php

namespace App\Mail;

use App\Models\bussines_unit;
use App\Models\license_permit;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class permitReminderMail extends Mailable implements ShouldQueue
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
        $permit = license_permit::find($this->id);
        return $this->subject("Reminder: License & Permit {$permit->permit_no} akan berakhir")
            ->from(config('mail.mailers.smtp.from', 'no.replydev.reg-gemindonesia.net'), "AtraxSys")
            ->view('Email.licenses_notification', [
                'appName' => "AtraxSys",
                'license' => $permit,
                'status' => Carbon::now()->gt($permit->permit_end_date) ? "expired" : "active",
                'daysLeft' => Carbon::now()->diffInDays($permit->permit_end_date),
                'actionUrl' => url('/user/asset/licenses/permit/open/' . $this->id),
                'businessUnitName' => bussines_unit::find($permit->business_unit_id)->name,
                'logoCid' => url('/logo.png'),   // dipakai di blade: src="{{ $logoCid }}"
                'logoUrl' => url('/logo.png'),    // kalau mau fallback via URL, isi ini
            ]);
    }
}
