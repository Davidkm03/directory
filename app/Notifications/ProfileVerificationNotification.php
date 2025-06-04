<?php

namespace App\Notifications;

use App\Models\DentistProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileVerificationNotification extends Notification
{
    use Queueable;

    protected $profile;

    /**
     * Create a new notification instance.
     */
    public function __construct(DentistProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->profile->verification_status;
        $subject = match($status) {
            'approved' => 'Tu perfil profesional ha sido aprobado',
            'rejected' => 'Acción requerida: Actualización de tu perfil profesional',
            default => 'Actualización sobre tu perfil profesional',
        };
        
        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hola ' . $notifiable->name);
            
        if ($status === 'approved') {
            $message->line('¡Buenas noticias! Tu perfil profesional ha sido verificado y aprobado.')
                    ->line('Tu perfil ahora es visible públicamente en nuestra plataforma.')
                    ->action('Ver mi perfil', url('/dentist/profile/verification'));
        } elseif ($status === 'rejected') {
            $message->line('Tu perfil profesional ha sido revisado y requiere algunas modificaciones antes de ser aprobado.')
                    ->line('Por favor, revisa los comentarios de nuestro equipo:')
                    ->line($this->profile->verification_notes ?: 'Sin comentarios específicos.')
                    ->action('Actualizar mi perfil', url('/dentist/profile/verification'));
        } else {
            $message->line('Tu perfil ha sido actualizado a estado: ' . ucfirst($status))
                    ->line('Puedes verificar el estado actual en tu panel de control.')
                    ->action('Ver estado', url('/dentist/profile/verification'));
        }
        
        return $message->line('Gracias por ser parte de nuestra comunidad odontológica.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'profile_id' => $this->profile->id,
            'status' => $this->profile->verification_status,
            'message' => match($this->profile->verification_status) {
                'approved' => 'Tu perfil profesional ha sido aprobado y verificado.',
                'rejected' => 'Tu perfil requiere modificaciones antes de ser aprobado.',
                default => 'El estado de tu perfil ha sido actualizado.'
            },
            'notes' => $this->profile->verification_notes
        ];
    }
}
