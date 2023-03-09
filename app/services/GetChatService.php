<?php

namespace App\Services;

use App\Models\Message;

class GetChatService
{
    public $receiverId;
    public $senderId;
    public $output;

    public function getMessageChatData($receiverId, $senderId, $output)
    {

        $this->receiverId = $receiverId;
        $this->senderId = $senderId;
        $this->output = $output;

        /*
        *  Cette requête est la même que la requête situe en bas de celle-ci.
        *  SELECT * FROM messages as m JOIN users as s ON m.messageEnvoye_id = s.messageEnvoye_id
        *  WHERE (messageRecu_id = $recu_id AND messageEnvoye_id = $envoye_id) 
        *  OR (messageEnvoye_id = $recu_id AND messageRecu_id = $envoye_id) 
        */

        $messages = Message::with('user')
            ->where(function ($query) {
                $query->where('messageRecu_id', '=', $this->receiverId)
                    ->where('messageEnvoye_id', '=', $this->senderId);
            })
            ->orWhere(function ($query) {
                $query->where('messageRecu_id', '=', $this->senderId)
                    ->where('messageEnvoye_id', '=', $this->receiverId);
            })
            ->orderBy('msg_id')
            ->get();

        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if ($message->messageEnvoye_id == $senderId) {
                    // il y a un message en fond bleu si les messages sont les votre.

                    $this->output .=
                        '
                        <div class="chat outgoing">
                            <div class="details">
                                <p>' . $message->message . '</p>
                            </div>
                        </div>
                        ';
                } else {
                    // il y a un message en fond blanc si les messages ne sont pas les votre avec l'image de l'expéditeur.

                    $this->output .=
                        '<div class="chat incoming">
                                <img class="rounded-circle" width="46" height="46" src="' . $message->user->image . '" alt="" />
                                <div class="details">
                                    <p class="my-1">' . $message->message . '</p>
                                </div>
                        </div>';
                }
            }
        }

        return $this->output;
    }
}
