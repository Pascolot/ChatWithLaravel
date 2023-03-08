<?php

namespace App\Services;

use App\Models\Message;

class MessageChatService
{
    public $user;
    public $envoye_id;
    public $sorti;

    public function messageChatData($user, $envoye_id, $sorti)
    {
        $this->user = $user;
        $this->envoye_id = $envoye_id;
        $this->sorti = $sorti;

        /* 
        *  Cette requête est la même que la requête situe en bas de celle-ci.
        *  SELECT * FROM messages WHERE (messageRecu_id = user['unique_id'] 
        *  OR messageEnvoye_id = user['unique_id']) AND (messageEnvoye_id = $envoye_id
        *  OR messageRecu_id = $envoye_id)  
        */

        $message = Message::where(function ($query) {
            $query->where('messageRecu_id', '=', $this->user['unique_id'])
                ->orWhere('messageEnvoye_id', '=', $this->user['unique_id']);
        })
            ->where(function ($query) {
                $query->where('messageEnvoye_id', '=', $this->envoye_id)
                    ->orWhere('messageRecu_id', '=', $this->envoye_id);
            })
            ->orderBy('msg_id', 'desc')
            ->limit(1)
            ->get();

        if (count($message) > 0) {
            foreach ($message as $msg) {
                $resultat = $msg->message;
            }
        } else {
            $resultat = "aucun message disponible";
        }


        // limiter le nombre du message affiche par 28 caractères 
        //et nous ajoutant trois points si le message est plus de 28 caractères.
        (strlen($resultat) > 28) ? $msg_sorti = substr($resultat, 0, 28) . '...' : $msg_sorti = $resultat;

        // mettre le mot "vous" pour difference le message que vous avez envoyé et que vous avez reçu.
        if (isset($msg->messageEnvoye_id)) {
            ($this->envoye_id == $msg->messageEnvoye_id) ? $vous = "Vous: " : $vous = "";
        } else {
            $vous = null;
        }

        // verifier si l'utilisateur est en ligne ou non
        ($this->user['status'] == "hors ligne") ? $deconnexion = "text-danger" : $deconnexion = "text-success";

        $this->sorti .=
            '<a class="text-decoration-none d-flex justify-content-between align-items-center" href="/chat/' . $this->user['unique_id'] . '">
                        <div class="d-flex">
                            <img class="rounded-circle" width="46" height="46" src="' . $this->user['image'] . '" alt="" />
                            <div class="mx-2">
                                <span class="text-dark">' . $this->user['nom'] . " " . $this->user['prenom'] . '</span>
                                <p class="text-secondary">' . $vous . $msg_sorti . '</p>
                            </div>
                        </div>
                        <div class="">
                            <i class="fas fa-circle ' . $deconnexion . '"></i>
                        </div>
                </a>';
        return $this->sorti;
    }
}
