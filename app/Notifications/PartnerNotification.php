<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PartnerNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;
    protected $actionUrl;
    protected $type;

    public function __construct($title, $body, $actionUrl = null, $type = 'info')
    {
        $this->title = $title;
        $this->body = $body;
        $this->actionUrl = $actionUrl;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        // Selalu simpan ke database, web push optional
        $channels = ['database'];

        // Tambahkan WebPush hanya jika user punya subscription
        if ($notifiable->pushSubscriptions()->count() > 0) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->body,
            'type' => $this->type,
            'action_url' => $this->actionUrl,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->icon('/icon.png')
            ->body($this->body)
            ->action('View', 'view_notification')
            ->data(['url' => $this->actionUrl ?? '/']);
    }
}
