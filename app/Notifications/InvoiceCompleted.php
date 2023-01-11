<?php

namespace App\Notifications;

use App\Models\Review;
use App\Notifications\InvoiceReview;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LaravelBulkSms;

class InvoiceCompleted extends Notification implements ShouldQueue
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
        return ['mail'];
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
            ->line("");
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->user->notify((new InvoiceReview($this->user, $this->invoice))
                ->delay(Carbon::now()->addMinute(10))
        );

        foreach ($this->invoice->metas as $key => $value) {
            Review::create(['product_id' => $value->product_id, 'book_id' => $value->book_id, 'user_id' => $this->invoice->user_id, 'name' => $this->user->name, 'star' => 0, 'message' => '', 'comment' => '', 'status' => 0]);
        }

        return (new MailMessage)
            ->subject('Your Order #' . $this->invoice->id . ' is now complete.')
            ->greeting("Hello, {$this->user->name}!")
            ->line('Your order has been marked as complete on our side.')
            ->line('Review your order information below. **If you don\'t receive your package please call us now.**')
            ->markdown('mail.invoice-table', ['invoice' => $this->invoice, 'h1' => 'Order Complete', 'image' => asset('assets/images/order-delivered.png')])
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
