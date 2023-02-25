<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function users()
    {
        $envoye_id = session()->get('LoggedUser.unique_id');
        $users = User::where('unique_id', '<>', $envoye_id)->get();
        $sorti = "";

        if (count($users) == 0) {
            $sorti .= "Pas d'utilisateur disponible pour discuter";
        } elseif (count($users) > 0) {
            foreach ($users as $user) {
                // appeler la fonction data
                $sorti =  $this->data($user, $envoye_id, $sorti);
            }
        }
        return $sorti;
    }

    public function chat($id)
    {
        $user_id = $id;
        $user = User::where('unique_id', $user_id)->first();

        return view('pages.chat', [
            'user_id' => $user_id,
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
        $GLOBALS['recu_id'] = $recu_id;
        $GLOBALS['envoye_id'] = $envoye_id;
        $sorti = "";


        /* 
        *  Cette requête est la même que la requête situe en bas de celle-ci.
        *  SELECT * FROM messages as m JOIN users as s ON m.messageEnvoye_id = s.messageEnvoye_id
        *  WHERE (messageRecu_id = $recu_id AND messageEnvoye_id = $envoye_id) 
        *  OR (messageEnvoye_id = $recu_id AND messageRecu_id = $envoye_id) 
        */

        $messages = Message::with('user')
            ->where(function ($rq) {
                $rq->where('messageRecu_id', '=', $GLOBALS['recu_id'])
                    ->where('messageEnvoye_id', '=', $GLOBALS['envoye_id']);
            })
            ->orWhere(function ($rq) {
                $rq->where('messageRecu_id', '=', $GLOBALS['envoye_id'])
                    ->where('messageEnvoye_id', '=', $GLOBALS['recu_id']);
            })
            ->orderBy('msg_id')
            ->get();

        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if ($message->messageEnvoye_id == $envoye_id) {
                    // il y a un message en fond bleu si les messages sont les votre.

                    $sorti .=
                        '
                        <div class="chat outgoing">
                            <div class="details">
                                <p>' . $message->message . '</p>
                            </div>
                        </div>
                        ';
                } else {
                    // il y a un message en fond blanc si les messages ne sont pas les votre avec l'image de l'expéditeur.

                    $sorti .=
                        '<div class="chat incoming">
                                <img class="rounded-circle" width="46" height="46" src="' . $message->user->image . '" alt="" />
                                <div class="details">
                                    <p class="my-1">' . $message->message . '</p>
                                </div>
                        </div>';
                }
            }
        }
        return $sorti;
    }

    public function search(Request $request)
    {
        $termeCherche = $request->cherche;
        $GLOBALS['termeCherche'] = $termeCherche;
        $envoye_id = session()->get('LoggedUser.unique_id');
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
                // appeler la fonction data
                $sorti =  $this->data($user, $envoye_id, $sorti);
            }
        } else {
            $sorti .= "Aucun utilisateur trouvé en relation avec votre recherche";
        }
        return $sorti;
    }

    public function data($user, $envoye_id, $sorti)
    {
        $GLOBALS['user'] = $user;
        $GLOBALS['unique_id'] = $envoye_id;

        /* 
        *  Cette requête est la même que la requête situe en bas de celle-ci.
        *  SELECT * FROM messages WHERE (messageRecu_id = user['unique_id'] 
        *  OR messageEnvoye_id = user['unique_id']) AND (messageEnvoye_id = $envoye_id
        *  OR messageRecu_id = $envoye_id)  
        */

        $message = Message::where(function ($rq) {
            $rq->where('messageRecu_id', '=', $GLOBALS['user']['unique_id'])
                ->orWhere('messageEnvoye_id', '=', $GLOBALS['user']['unique_id']);
        })
            ->where(function ($rq) {
                $rq->where('messageEnvoye_id', '=', $GLOBALS['unique_id'])
                    ->orWhere('messageRecu_id', '=', $GLOBALS['unique_id']);
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
            ($envoye_id == $msg->messageEnvoye_id) ? $vous = "Vous: " : $vous = "";
        } else {
            $vous = null;
        }

        // verifier si l'utilisateur est en ligne ou non
        ($user['status'] == "hors ligne") ? $deconnexion = "text-danger" : $deconnexion = "text-success";

        $sorti .=
            '<a class="text-decoration-none d-flex justify-content-between align-items-center" href="/chat/' . $user['unique_id'] . '">
                        <div class="d-flex">
                            <img class="rounded-circle" width="46" height="46" src="' . $user['image'] . '" alt="" />
                            <div class="mx-2">
                                <span class="text-dark">' . $user['nom'] . " " . $user['prenom'] . '</span>
                                <p class="text-secondary">' . $vous . $msg_sorti . '</p>
                            </div>
                        </div>
                        <div class="">
                            <i class="fas fa-circle ' . $deconnexion . '"></i>
                        </div>
                </a>';
        return $sorti;
    }
}
