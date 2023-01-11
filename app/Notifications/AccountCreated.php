<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $invoice, $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['mail-customer', 'ac:' . $this->user->id];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("Hello, {$this->user->name}!")
            ->markdown('mail.invoice-table', ['invoice' => false, 'h1' => 'Account Created', 'image' => asset('assets/images/user-welcome.png')])
            ->line('We\'re excited to have you get started. No need to reply to this email. Just use the password below to sign in.')
            ->line('Password for your account is: **' . $this->password . '**')
            ->action("Sign In", url('/login'))
            ->line('If you have any questions, just call us, always happy to help out.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
