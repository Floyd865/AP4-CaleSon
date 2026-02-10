<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Manifestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    /**
     * Afficher le formulaire d'ajout d'avis
     */
    public function create($type, $id, $date = null)
    {
        // Vérifier que l'utilisateur a réservé
        if (!Avis::userHasReservation($id, Auth::id(), $type)) {
            return redirect()->back()->with('error', 'Vous devez avoir réservé cette manifestation pour donner un avis.');
        }

        // Vérifier si l'utilisateur a déjà donné un avis
        if (Avis::userHasReviewed($id, Auth::id(), $type)) {
            return redirect()->back()->with('error', 'Vous avez déjà donné un avis pour cette manifestation.');
        }

        // Récupérer la manifestation
        if ($type === 'atelier' && $date) {
            $manifestation = Manifestation::findAtelierByIdAndDate($id, $date);
        } else {
            $manifestation = Manifestation::findByTypeAndId($type, $id);
        }

        if (!$manifestation) {
            abort(404);
        }

        return view('avis.create', [
            'manifestation' => $manifestation,
            'type' => $type,
            'date' => $date
        ]);
    }

    /**
     * Enregistrer un nouvel avis
     */
    public function store(Request $request, $type, $id)
    {
        // Vérifier que l'utilisateur a réservé
        if (!Avis::userHasReservation($id, Auth::id(), $type)) {
            return redirect()->back()->with('error', 'Vous devez avoir réservé cette manifestation pour donner un avis.');
        }

        // Vérifier si l'utilisateur a déjà donné un avis
        if (Avis::userHasReviewed($id, Auth::id(), $type)) {
            return redirect()->back()->with('error', 'Vous avez déjà donné un avis pour cette manifestation.');
        }

        // Validation
        $validated = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000'
        ]);

        $columnName = match($type) {
        'concert' => 'idmanif_concert',
        'conference' => 'idmanif_conference',
        'atelier' => 'idmanif_atelier',
        'exposition' => 'idmanif_exposition',
        };

        // Créer l'avis
        Avis::create([
            $columnName => $id,
            'idspec' => Auth::id(),
            'note' => $validated['note'],
            'commentaire' => $validated['commentaire'],
            'dateavis' => now()
        ]);

        // Rediriger selon le type
        if ($type === 'atelier' && $request->date) {
            return redirect()->route('manifestations.show.atelier', [
                'id' => $id,
                'date' => $request->date
            ])->with('success', 'Votre avis a été enregistré avec succès !');
        } else {
            return redirect()->route('manifestations.show', [
                'type' => $type,
                'id' => $id
            ])->with('success', 'Votre avis a été enregistré avec succès !');
        }
    }

    /**
     * Afficher le formulaire de modification d'avis
     */
    public function edit($idavis)
    {
        $avis = Avis::where('idavis', $idavis)
            ->where('idspec', Auth::id())
            ->first();

        if (!$avis) {
            abort(404, 'Avis non trouvé ou vous n\'êtes pas autorisé à le modifier.');
        }

        $manifestation = Manifestation::where('idmanif', $avis->idmanif)->first();

        return view('avis.edit', [
            'avis' => $avis,
            'manifestation' => $manifestation
        ]);
    }

    /**
     * Mettre à jour un avis
     */
    public function update(Request $request, $idavis)
    {
        $avis = Avis::where('idavis', $idavis)
            ->where('idspec', Auth::id())
            ->first();

        if (!$avis) {
            abort(404, 'Avis non trouvé ou vous n\'êtes pas autorisé à le modifier.');
        }

        // Validation
        $validated = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000'
        ]);

        // Mettre à jour
        $avis->update([
            'note' => $validated['note'],
            'commentaire' => $validated['commentaire']
        ]);

        return redirect()->back()->with('success', 'Votre avis a été modifié avec succès !');
    }

    /**
     * Supprimer un avis
     */
    public function destroy($idavis)
    {
        $avis = Avis::where('idavis', $idavis)
            ->where('idspec', Auth::id())
            ->first();

        if (!$avis) {
            abort(404, 'Avis non trouvé ou vous n\'êtes pas autorisé à le supprimer.');
        }

        $avis->delete();

        return redirect()->back()->with('success', 'Votre avis a été supprimé avec succès !');
    }
}
