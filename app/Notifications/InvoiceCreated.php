<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreated extends Notification implements ShouldQueue
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
            ->subject('New Order #' . $this->invoice->id)
            ->greeting("Hello, {$this->user->name}!")
            ->line('# Thank you for your order!')
            ->line('Review your order information below. We\'ll drop you another email when your order ships.')
            ->markdown('mail.invoice-table', ['invoice' => $this->invoice, 'image' => asset('assets/images/order-place.png'), 'h1' => 'Order Placed'])
            ->action('View Invoice', url('/my-account/order/' . $this->invoice->id))
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
