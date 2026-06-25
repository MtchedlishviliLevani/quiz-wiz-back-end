<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
	use Queueable;

	public string $token;

	public string $email;

	/**
	 * Create a new notification instance.
	 */
	public function __construct($token, $email)
	{
		$this->token = $token;
		$this->email = $email;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(object $notifiable): MailMessage
	{
		$frontendUrl = config('app.frontend_url');

		$url = "{$frontendUrl}/reset-password?token={$this->token}&email={$this->email}";

		return (new MailMessage)
			->subject('Reset Your Password')
			->view('emails.reset-password', ['url' => $url, 'name' => $notifiable->username]);
	}
}
