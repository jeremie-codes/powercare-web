<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'nom' => 'Ménage à domicile',
            'image' => 'services/menage-domicile.jpg',
            'description' => 'Nettoyage et entretien complet de votre domicile.',
            'prix_base' => 25.00,
        ]);

        Service::create([
            'nom' => 'Babysitting',
            'image' => 'services/babysitting.jpg',
            'description' => 'Garde ponctuelle ou régulière de vos enfants.',
            'prix_base' => 15.00,
        ]);

        Service::create([
            'nom' => 'Repassage',
            'image' => 'services/repassage.jpg',
            'description' => 'Repassage soigné de vos vêtements à domicile.',
            'prix_base' => 20.00,
        ]);
    }
}
