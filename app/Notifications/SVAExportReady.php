<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Storage;

class SVAExportReady extends Notification
{
    use Queueable;

    public $filename;

    public $realFilename;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($filename, $realFilename)
    {
        $this->filename = $filename;

        $this->realFilename = $realFilename;
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
        $file = Storage::url($this->filename);

        return (new MailMessage)
                ->attach($file, [
                    'as' => $this->realFilename
                ])
                ->subject($this->realFilename)
                ->markdown('emails.sva.export_ready');
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
