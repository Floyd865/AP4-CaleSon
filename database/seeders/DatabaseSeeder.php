<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paiements = [
            ['idpaiement' => 1, 'libellepaiement' => 'Carte bancaire'],
            ['idpaiement' => 2, 'libellepaiement' => 'Espèces'],
            ['idpaiement' => 3, 'libellepaiement' => 'Chèque'],
            ['idpaiement' => 4, 'libellepaiement' => 'Virement'],
        ];

        foreach ($paiements as $paiement) {
            DB::table('paiement')->insertOrIgnore($paiement);
        }
    }
}