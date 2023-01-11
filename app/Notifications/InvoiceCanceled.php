<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCanceled extends Notification implements ShouldQueue
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
            ->subject('Order Canceled #' . $this->invoice->id)
            ->greeting("Hello, {$this->user->name}!")
            ->line('Sorry, your order has been canceled. If you believe this is a mistake please call our helpline.')
            ->line('Here is a summary of your canceled order:')
            ->markdown('mail.invoice-table', ['invoice' => $this->invoice, 'h1' => 'Order Canceled', 'image' => asset('assets/images/order-canceled.png')])
            ->action('View Invoice', url('/my-account/order/' . $this->invoice->id));
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
