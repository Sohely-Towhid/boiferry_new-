<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceReview extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $invoice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $invoice)
    {
        $this->user    = $user;
        $this->invoice = $invoice;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['mail-customer', 'invoice:' . $this->invoice->id];
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
            ->subject('Hey Superstar! Could you please leave a review?')
            ->greeting("Hello, {$this->user->name}!")
            ->line('Thank you for shopping with "BoiFerry" recently — we hope you were happy with your order.')
            ->line('We\'d love to know how you found the experience of using "BoiFerry" — so would like to invite you to rate us on our website and our Facebook page. It will only take a few clicks and will be invaluable to us!')
            ->markdown('mail.invoice-table', ['invoice' => false, 'h1' => 'Rate Us', 'image' => asset('assets/images/order-ratings.png')])
            ->action('Post Review', url('/my-account/review'))
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
