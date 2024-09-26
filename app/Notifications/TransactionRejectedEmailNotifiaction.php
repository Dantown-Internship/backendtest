<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionRejectedEmailNotifiaction extends Notification
{
    use Queueable;

    public function __construct(protected Transaction $transaction) {}

  
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

   
    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format( $this->transaction->amount, 2);
        
        return (new MailMessage)
                    ->from("info@checker.com", "Checker")
                    ->subject('Transaction Has Been Rejected')
                    ->greeting("Hi {$this->transaction->user->name},")
                    ->line("This is to in inform you that your transaction of N{$amount} has been rejected")
                    ->line('Thank you.');
    }

  
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
