<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RankingInteraction extends Notification
{
    use Queueable;

    protected $user;
    protected $ranking;
    protected $type;

    public function __construct($user, $ranking, $type)
    {
        $this->user = $user;
        $this->ranking = $ranking;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->profile_photo_path,
            'user_avatar_url' => $this->user->profile_photo_url,
            'ranking_id' => $this->ranking->id,
            'ranking_title' => $this->ranking->title,
            'type' => $this->type,
            'message' => $this->getMessage(),
        ];
    }

    protected function getMessage()
    {
        switch ($this->type) {
            case 'vote': return "ha votat en el teu rànquing";
            case 'comment': return "ha comentat en el teu rànquing";
            case 'like': return "li ha agradat el teu rànquing";
            case 'favorite': return "ha guardat a favorits el teu rànquing";
            default: return "ha interactuat amb el teu rànquing";
        }
    }
}