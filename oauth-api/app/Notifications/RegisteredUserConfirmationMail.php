<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisteredUserConfirmationMail extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Account - OTP Included')
            ->greeting('Hello ' . $notifiable['first_name'] . '!')
            ->line('Thank you for registering with us. To complete your registration, please use the following OTP to verify your account:')
            ->line('**Your OTP Code: ' . $notifiable['otp'] . '**')
            ->line('After verification, you can login to your account using:')
            ->line('Email: ' . $notifiable['email'])
            ->line('Password: ' . $notifiable['otp'])
            ->line('If you didn\'t request this, please ignore this email.')
            ->salutation('Regards, ' . config('app.name'));
    }

    public function toDatabase($notifiable)
    {
        return[
            'first_name'  => $notifiable->first_name,
            'email' => $notifiable->email,
        ];
    }
    public function toArray($notifiable)
    {
        return [
            
        ];
    }
}
