<x-app-layout>
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Welcome Section -->
        <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 1rem; padding: 2rem; margin-bottom: 2rem;">
            <h1 style="font-size: 2.25rem; font-weight: 700; background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">
                Bienvenue, {{ Auth::user()->prenom }} !
            </h1>
            <p style="color: #cbd5e1; font-size: 1.1rem;">
                Vous êtes connecté(e) à votre espace personnel Cale & Sons
            </p>
        </div>

        <!-- Quick Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Stat Card 1 -->
            <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 1rem; padding: 1.5rem; border-left: 3px solid #3b82f6;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        🎫
                    </div>
                    <div>
                        <h3 style="color: #60a5fa; font-size: 0.9rem; margin-bottom: 0.25rem;">Mes Réservations</h3>
                        <p style="color: #e2e8f0; font-size: 1.75rem; font-weight: 700;">0</p>
                    </div>
                </div>
                <a href="{{ route('reservations.index') }}" style="color: #60a5fa; font-size: 0.875rem; text-decoration: none;">Voir mes réservations →</a>
            </div>

            <!-- Stat Card 2 -->
            <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 1rem; padding: 1.5rem; border-left: 3px solid #f97316;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        🎭
                    </div>
                    <div>
                        <h3 style="color: #fb923c; font-size: 0.9rem; margin-bottom: 0.25rem;">Manifestations</h3>
                        <p style="color: #e2e8f0; font-size: 1.75rem; font-weight: 700;">{{ \App\Models\Manifestation::count() }}</p>
                    </div>
                </div>
                <a href="{{ route('manifestations.index') }}" style="color: #fb923c; font-size: 0.875rem; text-decoration: none;">Voir toutes →</a>
            </div>

        <!-- Recent Activity / Info -->
        <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 1rem; padding: 2rem;">
            <h2 style="color: #60a5fa; font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem;">À propos du festival</h2>
            <p style="color: #cbd5e1; line-height: 1.7; margin-bottom: 1rem;">
                Bienvenue sur la plateforme de gestion du Festival Cale & Sons. Vous pouvez consulter toutes les manifestations disponibles, effectuer des réservations et donner votre avis sur les événements auxquels vous avez participé.
            </p>
            <p style="color: #94a3b8; font-size: 0.9rem;">
                Pour toute question, n'hésitez pas à contacter notre équipe.
            </p>
        </div>
    </div>

    <style>
        a[style*="background: rgba(59, 130, 246, 0.1)"]:hover {
            background: rgba(59, 130, 246, 0.15) !important;
            transform: translateY(-3px);
        }
        a[style*="background: rgba(249, 115, 22, 0.1)"]:hover {
            background: rgba(249, 115, 22, 0.15) !important;
            transform: translateY(-3px);
        }
        a[style*="background: rgba(34, 197, 94, 0.1)"]:hover {
            background: rgba(34, 197, 94, 0.15) !important;
            transform: translateY(-3px);
        }
    </style>
</x-app-layout>