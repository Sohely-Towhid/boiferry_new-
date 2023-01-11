<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $invoice, $lines, $link, $type, $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $subject, $lines, $link = [], $type = 'success')
    {
        $this->user    = $user;
        $this->type    = $type;
        $this->lines   = $lines;
        $this->subject = $subject;
        $this->link    = $link;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['mail-vendor', 'user:' . $this->user->id];
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
        $mail = new MailMessage();
        if ($this->type == 'error') {
            $mail = $mail->error();
        }
        foreach ($this->lines as $key => $line) {
            $mail = $mail->line($line);
        }
        if ($this->link) {
            $mail = $mail->action($this->link[1], $this->link[0]);
        }
        return $mail
            ->greeting("Hello {$this->user->name} !")
            ->subject($this->subject)
            ->line('Thank you for being with us!');
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
