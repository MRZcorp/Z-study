<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        $resetUrl = URL::route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false);

        return (new MailMessage)
            ->subject('Reset Password Z-Study')
            ->view('emails.password_reset', [
                'name' => $notifiable->name ?? 'Pengguna',
                'resetUrl' => $resetUrl,
            ]);
    }
}
