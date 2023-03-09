<?php

namespace App\Services;

use App\Models\Message;

class MessageChatService
{
    public $user;
    public $senderId;
    public $output;

    public function messageChatData($user, $senderId, $output)
    {
        $this->user = $user;
        $this->senderId = $senderId;
        $this->output = $output;

        /* 
        *  Cette requête est la même que la requête situe en bas de celle-ci.
        *  "SELECT * FROM messages WHERE (messageRecu_id = user['unique_id'] 
        *  OR messageEnvoye_id = user['unique_id']) AND (messageEnvoye_id = $envoye_id
        *  OR messageRecu_id = $envoye_id)" 
        */

        $message = Message::where(function ($query) {
            $query->where('messageRecu_id', '=', $this->user['unique_id'])
                ->orWhere('messageEnvoye_id', '=', $this->user['unique_id']);
        })
            ->where(function ($query) {
                $query->where('messageEnvoye_id', '=', $this->senderId)
                    ->orWhere('messageRecu_id', '=', $this->senderId);
            })
            ->orderBy('msg_id', 'desc')
            ->limit(1)
            ->get();

        if (count($message) > 0) {
            foreach ($message as $msg) {
                $result = $msg->message;
            }
        } else {
            $result = "aucun message disponible";
        }


        // limiter le nombre du message affiche par 28 caractères 
        //et nous ajoutant trois points si le message est plus de 28 caractères.
        (strlen($result) > 28) ? $msg_output = substr($result, 0, 28) . '...' : $msg_output = $result;

        // mettre le mot "vous" pour difference le message que vous avez envoyé et que vous avez reçu.
        if (isset($msg->messageEnvoye_id)) {
            ($this->senderId == $msg->messageEnvoye_id) ? $you = "Vous: " : $you = "";
        } else {
            $you = null;
        }

        // verifier si l'utilisateur est en ligne ou non
        ($this->user['status'] == "hors ligne") ? $offline = "text-danger" : $offline = "text-success";

        $this->output .=
            '<a class="text-decoration-none d-flex justify-content-between align-items-center" href="/chat/' . $this->user['unique_id'] . '">
                        <div class="d-flex">
                            <img class="rounded-circle" width="46" height="46" src="' . $this->user['image'] . '" alt="" />
                            <div class="mx-2">
                                <span class="text-dark">' . $this->user['nom'] . " " . $this->user['prenom'] . '</span>
                                <p class="text-secondary">' . $you . $msg_output . '</p>
                            </div>
                        </div>
                        <div class="">
                            <i class="fas fa-circle ' . $offline . '"></i>
                        </div>
                </a>';

        return $this->output;
    }
}
