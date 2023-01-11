<?php

namespace App\Notifications;

use Bluedot\LaravelBulkSms\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LaravelBulkSms;

class InvoiceProcessing extends Notification implements ShouldQueue
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
        return ['mail-sms-customer', 'invoice:' . $this->invoice->id];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsChannel::class];
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toSms($notifiable)
    {
        return (new LaravelBulkSms)
            ->to($this->invoice->shipping_address->mobile)
            ->line("Dear Book Lover, your order has been confirmed successfully. Please be ready for receiving your desired books.\n\nThanks for being with boiferry.com");
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $total   = $this->invoice->total + $this->invoice->shipping + $this->invoice->gift_wrap - $this->invoice->coupon_discount;
        $payment = ($this->invoice->payment == 'cod') ? "# Please keep {$total} taka ready to receive the package." : "# Payment Received with Thanks :)";
        return (new MailMessage)
            ->subject('Processing Order #' . $this->invoice->id)
            ->greeting("Hello, {$this->user->name}!")
            ->line($payment)
            ->line('Review your order information below. We are processing your order right now. You can track shipping status of your order in our website.')
            ->markdown('mail.invoice-table', ['invoice' => $this->invoice, 'image' => asset('assets/images/order-card.png'), 'h1' => 'Order Paid'])
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
