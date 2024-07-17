<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification
{
    use Queueable;


    private $transaction;
    private $status;
    private $mode;

    public function __construct(object $transaction, string $status, array|null $mode=['mail','database', 'broadcast'])
    {
        $this->transaction = $transaction;
        $this->status = $status;
        $this->mode = $mode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    { 
        return $this->mode;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Your transaction status was updated')
            ->line('Transaction Amount: ' . $this->transaction->amount)
            ->line('Transaction Type: ' . $this->transaction->type)
            ->line('New Status: ' . $this->status)
            ->action('View Transaction', route('transaction.index'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'transaction_id' => $this->transaction->id,
            'status' => $this->status,
            'amount' => $this->transaction->amount,
            'type' => $this->transaction->type,
            'message' => "Your ".ucfirst($this->transaction->type)." transaction of ". number_format($this->transaction->amount,2).' was '. ucwords($this->status),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'transaction_id' => $this->transaction->id,
            'status' => $this->status,
            'amount' => $this->transaction->amount,
            'type' => $this->transaction->type,
            'message' => "Your ".ucfirst($this->transaction->type)." transaction of ". number_format($this->transaction->amount,2).' was '. ucwords($this->status),
        ];
    }
}
