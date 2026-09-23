<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = Service::all();

        $agents = [
            [
                'name' => 'Aline Mukendi',
                'email' => 'aline.agent@gmail.com',
                'phone' => '0810000001',
                'avatar' => 'https://i.pravatar.cc/300?img=47',
                'service' => 'Ménage à domicile',
                'experience' => 5,
                'disponibilite' => 'temps plein',
            ],
            [
                'name' => 'Patrick Kabeya',
                'email' => 'patrick.agent@gmail.com',
                'phone' => '0810000002',
                'avatar' => 'https://i.pravatar.cc/300?img=12',
                'service' => 'Babysitting',
                'experience' => 3,
                'disponibilite' => 'temps partiel',
            ],
            [
                'name' => 'Grâce Ilunga',
                'email' => 'grace.agent@gmail.com',
                'phone' => '0810000003',
                'avatar' => 'https://i.pravatar.cc/300?img=32',
                'service' => 'Repassage',
                'experience' => 4,
                'disponibilite' => 'occasionnel',
            ],
        ];

        foreach ($agents as $agentData) {
            $service = $services->firstWhere('nom', $agentData['service']);

            if (!$service) {
                continue;
            }

            $user = User::create([
                'name' => $agentData['name'],
                'email' => $agentData['email'],
                'phone' => $agentData['phone'],
                'password' => Hash::make('password'),
                'role' => 'agent',
                'avatar' => $agentData['avatar'],
            ]);

            Agent::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'experience' => $agentData['experience'],
                'disponibilite' => $agentData['disponibilite'],
                'adresse' => 'Kinshasa, RDC',
                'statut' => 'disponible',
                'rating' => 0,
                'is_badges' => true,
            ]);
        }
    }
}
