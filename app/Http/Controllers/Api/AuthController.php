<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->orWhere('phone', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides',
                'data' => null
            ], 401);
        }

        // Génération d’un token si tu utilises Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Récupération du profil lié (Agent ou Client)
        $profile = $user->role === 'agent' ? $user->agent : $user->client;

        return response()->json([
            'success' => true,
            'message' => 'Connexion reussie',
            'data' => [
                'token' => $token,
                'user' => $user,
                'profile' => $profile,
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|string|email',
            'password' => 'required|string',
            'type' => 'nullable|string',
            'entreprise_nom' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        if (!$request->email && !$request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez renseigner un email ou un numéro de téléphone.',
            ], 422);
        }

        $userExistant = User::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if ($userExistant) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur existant',
                'data' => null
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        $client = Client::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'entreprise_nom' => $request->entreprise_nom,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'data' => [
                'token' => $token,
                'user' => User::find($user->id),
                'profile' => $client,
            ]
        ]);
    }

    /**
     * 🔹 Supprime le compte utilisateur
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié.',
                ], 401);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Compte supprimé avec succès.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du compte.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Déconnexion (logout)
     * Supprime le token actuel de l'utilisateur (utile pour mobile)
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                // Les tokens Sanctum transitoires ne peuvent pas être supprimés.
                $token = $user->currentAccessToken();
                if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) {
                    $token->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Déconnexion réussie.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Aucun utilisateur connecté.',
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
