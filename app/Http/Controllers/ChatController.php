<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Services\GetChatService;
use App\Services\MessageChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public $messageChatService;
    public $getChatService;

    public function __construct(MessageChatService $messageChatService, GetChatService $getChatService)
    {
        $this->messageChatService = $messageChatService;
        $this->getChatService = $getChatService;
    }

    public function users()
    {
        $envoye_id = auth()->user()->unique_id;
        $users = User::where('unique_id', '<>', $envoye_id)->get();
        $sorti = "";

        if (count($users) == 0) {
            $sorti .= "Pas d'utilisateur disponible pour discuter";
        } elseif (count($users) > 0) {
            foreach ($users as $user) {
                $sorti =  $this->messageChatService->messageChatData($user, $envoye_id, $sorti);
            }
        }
        return $sorti;
    }

    public function chat($uniqueId)
    {
        $userUniqueId = $uniqueId;
        $user = User::firstWhere('unique_id', '=', $userUniqueId);

        return view('pages.chat', [
            'userUniqueId' => $userUniqueId,
            'user' => $user,
        ]);
    }

    public function insertChat(Request $request)
    {
        $recu_id = $request->messageRecu_id;
        $envoye_id = $request->messageEnvoye_id;
        $message = $request->message;

        if (!empty($message)) {
            Message::create([
                'messageRecu_id' => $recu_id,
                'messageEnvoye_id' => $envoye_id,
                'message' => $message,
            ]);
            return;
        }
        return;
    }

    public function getChat(Request $request)
    {
        $recu_id = $request->messageRecu_id;
        $envoye_id = $request->messageEnvoye_id;
        $sorti = "";
        return $this->getChatService->getMessageChatData($recu_id, $envoye_id, $sorti);
    }

    public function search(Request $request)
    {
        $termeCherche = $request->cherche;
        $GLOBALS['termeCherche'] = $termeCherche;
        $envoye_id = auth()->user()->unique_id;
        $sorti = "";

        /**
         *  SELECT * FROM users WHERE NOT unique_id = envoye_id AND
         *  (LIKE nom = '%termeCherche%' OR LIKE prenom = '%termeCherche%');  
         **/
        $users = User::where('unique_id', '<>', $envoye_id)
            ->where(function ($rq) {
                $rq->where('nom', 'like', '%' . $GLOBALS['termeCherche'] . '%')
                    ->orWhere('prenom', 'like', '%' . $GLOBALS['termeCherche'] . '%');
            })
            ->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $sorti =  $this->messageChatService->messageChatData($user, $envoye_id, $sorti);
            }
        } else {
            $sorti .= "Aucun utilisateur trouvé en relation avec votre recherche";
        }
        return $sorti;
    }
}
