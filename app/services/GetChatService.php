<?php

namespace App\Services;

use App\Models\Message;

class GetChatService
{
    public $recu_id;
    public $envoye_id;
    public $sorti;

    public function getMessageChatData($recu_id, $envoye_id, $sorti)
    {

        $this->recu_id = $recu_id;
        $this->envoye_id = $envoye_id;
        $this->sorti = $sorti;

        /*
        *  Cette requête est la même que la requête situe en bas de celle-ci.
        *  SELECT * FROM messages as m JOIN users as s ON m.messageEnvoye_id = s.messageEnvoye_id
        *  WHERE (messageRecu_id = $recu_id AND messageEnvoye_id = $envoye_id) 
        *  OR (messageEnvoye_id = $recu_id AND messageRecu_id = $envoye_id) 
        */

        $messages = Message::with('user')
            ->where(function ($query) {
                $query->where('messageRecu_id', '=', $this->recu_id)
                    ->where('messageEnvoye_id', '=', $this->envoye_id);
            })
            ->orWhere(function ($query) {
                $query->where('messageRecu_id', '=', $this->envoye_id)
                    ->where('messageEnvoye_id', '=', $this->recu_id);
            })
            ->orderBy('msg_id')
            ->get();

        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if ($message->messageEnvoye_id == $envoye_id) {
                    // il y a un message en fond bleu si les messages sont les votre.

                    $this->sorti .=
                        '
                        <div class="chat outgoing">
                            <div class="details">
                                <p>' . $message->message . '</p>
                            </div>
                        </div>
                        ';
                } else {
                    // il y a un message en fond blanc si les messages ne sont pas les votre avec l'image de l'expéditeur.

                    $this->sorti .=
                        '<div class="chat incoming">
                                <img class="rounded-circle" width="46" height="46" src="' . $message->user->image . '" alt="" />
                                <div class="details">
                                    <p class="my-1">' . $message->message . '</p>
                                </div>
                        </div>';
                }
            }
        }
        return $this->sorti;
    }
}
