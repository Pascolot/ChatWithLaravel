<?php

namespace App\Actions;

use App\Models\Message;

class CreateMessageAction
{
    public function handle($receiverId, $senderId, $message): void
    {
        if (!empty($message)) {
            Message::create([
                'messageRecu_id' => $receiverId,
                'messageEnvoye_id' => $senderId,
                'message' => $message,
            ]);

            return;
        }

        return;
    }
}
