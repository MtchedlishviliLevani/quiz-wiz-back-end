<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
	use Queueable;

	public function toMail($notifiable): MailMessage
	{
		return (new MailMessage())
			->subject('Please verify your email')
			->view('emails.verify-email', [
				'url'  => $this->verificationUrl($notifiable),
				'name' => $notifiable->username,
			]);
	}
}
