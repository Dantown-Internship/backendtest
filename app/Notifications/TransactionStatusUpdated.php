<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Transaction;

class TransactionStatusUpdated extends Notification
{
    use Queueable;

    protected $transaction;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
            ->line('Your transaction with ID ' . $this->transaction->id . ' has been ' . $this->transaction->status . '.')
            ->action('View Transactions', url('/transactions'))
            ->line('Thank you for using our application!');
    }
}
