<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return Socialite::driver($provider)->redirect();
        }
        abort(404); // Si le provider n'est pas autorisé
    }


    // Callback du provider
    public function callback (Request $request) {

        $provider = $request->provider;

        if (in_array($provider, $this->providers)) {

        	// Les informations provenant du provider
            $data = Socialite::driver($request->provider)->user();

            # Social login - register
            $email = $data->getEmail(); // L'adresse email
            $name = $data->getName(); // le nom

            # 1. On récupère l'utilisateur à partir de l'adresse email
            $user = User::where("email", $email)->first();

            # 2. Si l'utilisateur existe
            if (isset($user)) {

                // Mise à jour des informations de l'utilisateur
                $user->name = $name;
                $user->save();

            # 3. Si l'utilisateur n'existe pas, on l'enregistre
            } else {
                
                // Enregistrement de l'utilisateur
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt("emilie") // On attribue un mot de passe
                ]);
            }

            # 4. On connecte l'utilisateur
            auth()->login($user);

            # 5. On redirige l'utilisateur vers /home
            if (auth()->check()) return redirect(route('home'));

         }
         abort(404);
    }


    public function register (Request $request) {

        $provider = $request->provider;

        if (in_array($provider, $this->providers)) {

        	// Les informations provenant du provider
            $data = Socialite::driver($request->provider)->user();

            # Social login - register
            $email = $data->getEmail();
            $name = $data->getName();

            # 1. On récupère l'utilisateur à partir de l'adresse email
            $user = User::where("email", $email)->first();

            # 2. Si l'utilisateur existe
            if (isset($user)) {

                // Mise à jour des informations de l'utilisateur
                $user->name = $name;
                $user->save();

            # 3. Si l'utilisateur n'existe pas, on l'enregistre
            } else {
                
                // Enregistrement de l'utilisateur
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt("MOT-DE-PASSE") // On attribue un mot de passe
                ]);
            }

            # 4. On connecte l'utilisateur
            auth()->login($user);

            # 5. On redirige l'utilisateur vers /home
            if (auth()->check()) return redirect(route('home'));

        }
        abort(404);
    }








    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        try{
            //if Authentication is successfull.
            $user = Socialite::driver('facebook')->user();
            /**
             *  Below are fields that are provided by
             *  every provider.
             */
            $facebook_id = $user->getId();
            $name = $user->getName();
            $email = $user->getEmail();
            $avatar = $user->getAvatar();
            //$user->getNickname(); is also available

            // return the user if exists or just create one.
            $user = User::firstOrCreate([
                'facebook_id' => $facebook_id,
                'name'        => $name,
                'email'       => $email,
                'avatar'      => $avatar,
            ]);
            /**
             * Authenticate the user with session.
             * First param is the Authenticatable User object
             * and the second param is boolean for remembering the
             * user.
             */ 
            Auth::login($user,true);
            //Success
            return redirect()->route('home');
        }catch(\Exception $e){
            //Authentication failed
            return redirect()->back()->with('status','authentication failed, please try again!');
        }
    }
}
