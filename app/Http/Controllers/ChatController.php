<?php

namespace App\Http\Controllers;

use App\Actions\CreateMessageAction;
use App\Models\User;
use App\Services\GetChatService;
use App\Services\MessageChatService;
use App\Services\SearchUserService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public $getChatService;
    public $messageChatService;

    public function __construct(GetChatService $getChatService, MessageChatService $messageChatService)
    {
        $this->getChatService = $getChatService;
        $this->messageChatService = $messageChatService;
    }

    public function users()
    {
        $senderId = auth()->user()->unique_id;
        $users = User::where('unique_id', '<>', $senderId)->get();
        $output = "";

        if (count($users) == 0) {
            $output .= "Pas d'utilisateur disponible pour discuter";
        } elseif (count($users) > 0) {
            foreach ($users as $user) {
                $output =  $this->messageChatService->messageChatData($user, $senderId, $output);
            }
        }

        return $output;
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

    public function insertChat(Request $request, CreateMessageAction $createMessageAction)
    {
        $receiverId = $request->messageRecu_id;
        $senderId = $request->messageEnvoye_id;
        $message = $request->message;
        $createMessageAction->handle($receiverId, $senderId, $message);
    }

    public function getChat(Request $request)
    {
        $receiverId = $request->messageRecu_id;
        $senderId = $request->messageEnvoye_id;
        $output = "";

        return $this->getChatService->getMessageChatData($receiverId, $senderId, $output);
    }

    public function search(Request $request, SearchUserService $searchUserService)
    {
        $searchTerm = $request->cherche;
        $senderId = auth()->user()->unique_id;
        $output = "";

        return $searchUserService->searching($searchTerm, $senderId, $output);
    }
}
