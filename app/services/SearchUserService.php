<?php

namespace App\Services;

use App\Models\User;
use App\Services\MessageChatService;

class SearchUserService
{
    public $serchTerm;
    public $messageChatService;

    public function __construct(MessageChatService $messageChatService)
    {
        $this->messageChatService = $messageChatService;
    }

    public function searching($serchTerm, $senderId, $output)
    {
        $this->serchTerm = $serchTerm;

        /**
         *  SELECT * FROM users WHERE NOT unique_id = envoye_id AND
         *  (LIKE nom = '%termeCherche%' OR LIKE prenom = '%termeCherche%');  
         **/

        $users = User::where('unique_id', '<>', $senderId)
            ->where(function ($query) {
                $query->where('nom', 'like', '%' . $this->serchTerm . '%')
                    ->orWhere('prenom', 'like', '%' . $this->serchTerm . '%');
            })
            ->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $output =  $this->messageChatService->messageChatData($user, $senderId, $output);
            }
        } else {
            $output .= "Aucun utilisateur trouvé en relation avec votre recherche";
        }

        return $output;
    }
}
