<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_paiement', function (Blueprint $table) {
            $table->id('idtransaction');
            $table->unsignedBigInteger('idinscription');
            $table->unsignedInteger('idpaiement'); // Référence vers la table paiement
            $table->decimal('montant', 10, 2);
            $table->timestamp('date_transaction');
            $table->enum('statut', ['en_attente', 'valide', 'echoue', 'rembourse'])->default('en_attente');
            $table->string('reference_transaction', 100)->unique();
            $table->text('details_carte')->nullable(); // Stockage sécurisé des 4 derniers chiffres
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index('idinscription');
            $table->index('statut');
            $table->index('date_transaction');
        });
        
        // Ajouter la colonne idtransaction à la table billet
        Schema::table('billet', function (Blueprint $table) {
            $table->unsignedBigInteger('idtransaction')->nullable()->after('numticket');
            $table->decimal('prix_paye', 8, 2)->nullable()->after('idtransaction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billet', function (Blueprint $table) {
            $table->dropColumn(['idtransaction', 'prix_paye']);
        });
        
        Schema::dropIfExists('transaction_paiement');
    }
};