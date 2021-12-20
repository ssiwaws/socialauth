<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // Mes providers autorisés
    protected $providers = [ "google", "github", "facebook" ];

    # La vue pour les liens vers les providers
    public function loginRegister () {
    	return view("auth.login");
    }

    # redirection vers le provider
    public function redirect (Request $request) {

        $provider = $request->provider;

        // On vérifie si le provider est autorisé
        if (in_array($provider, $this->providers)) {
            return Socialite::driver($provider)->redirect(); // On redirige vers le provider
        }
        abort(404); // Si le provider n'est pas autorisé
    }

    // Callback du provider
    public function callback (Request $request) {

        $provider = $request->provider;

        if (in_array($provider, $this->providers)) {

        	// Les informations provenant du provider
        	$data = Socialite::driver($request->provider)->user();

            // Les informations de l'utilisateur
            $user = $data->user;

            // voir les informations de l'utilisateur
            // dd($user);

            // token
            $token = $data->token;

            // Les informations de l'utilisateur
            $id = $data->getId();
            $nickname = $data->getNickname();
            $name = $data->getName();
            $email = $data->getEmail();
            $avatar = $data->getAvatar();
        }
        abort(404);
    }
}
