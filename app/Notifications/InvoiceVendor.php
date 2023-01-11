<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceVendor extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $invoice, $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $invoice, $email)
    {
        $this->user    = $user;
        $this->invoice = $invoice;
        $this->email   = $email;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['mail-vendor', 'invoice:' . $this->invoice->id];
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
        $notifiable->email = $this->email;

        return (new MailMessage)
            ->subject('New Order #' . $this->invoice->id)
            ->greeting("Dear Seller!")
            ->line('**You received a new order in Seller Center!**')
            ->line('Order Number is **' . $this->invoice->id . '**. Please pack the order **ASAP** and drop at our nerest distribution center.')
            ->action('View Order', route('seller.seller_home') . '/invoice/' . $this->invoice->id)
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
