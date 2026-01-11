<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct()
	{
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
		$temporarySignedUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(120),
			['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
		);

		$frontendUrl = config('app.frontend_url');
		$urlComponents = parse_url($temporarySignedUrl);

		$finalUrl = $frontendUrl . '/verify-email?' . $urlComponents['query'] . '&path=' . urlencode($urlComponents['path']);

		return (new MailMessage)
			->subject('Please verify your email')
			->view('emails.verify-email', ['url' => $finalUrl, 'name' => $notifiable->username]);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return [
		];
	}
}
