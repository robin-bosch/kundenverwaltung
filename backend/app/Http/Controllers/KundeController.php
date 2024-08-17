<?php
namespace App\Http\Controllers;

use App\Models\Kunde;
use Illuminate\Http\Request;

class KundeController extends Controller
{
    // Alle Kunden anzeigen
    public function index(Request $request) {
        $query = Kunde::query();
    
        // Suche nach Kunden
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');

            // Suche nur nach ID, wenn die Suche eine Zahl ist
            if (is_numeric($search)) {
                $query->where('id', 'LIKE', '%' . $search . '%');

            // Suche nach Vorname, Nachname oder E-Mail
            } else {
                $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(vorname) LIKE ?', ['%' . strtolower($search) . '%'])
                          ->orWhereRaw('LOWER(nachname) LIKE ?', ['%' . strtolower($search) . '%'])
                          ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            }
        }
    
        //Seitengröße festlegen und die aktuelle Seite ermitteln
        $pageSize = $request->input('pageSize', 20);
        $page = $request->input('page', 1);
    
        // Liste mit Kunden nach Seitenkriterien einteilen
        $kundenListe = $query->paginate($pageSize, ['*'], 'page', $page);
    
        return response()->json($kundenListe);
    }

    // Neuen Kunden erstellen
    public function store(Request $request) {

        $validatedData = $request->validate([
            'vorname' => 'required|string|max:255',
            'nachname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:kunde',
            'telefonnummer' => 'nullable|string|max:255',
            'strasse' => 'required|string|max:255',
            'plz' => 'required|string|max:255',
            'ort' => 'required|string|max:255',
        ]);

        $kunde = Kunde::create($validatedData);
        return response()->json($kunde, 201);
    }

    // Einen bestimmten Kunden nach ID anzeigen
    public function show($id) {
        $kunde = Kunde::findOrFail($id);
        return $kunde;
    }

    // Einen bestimmten Kunden nach ID aktualisieren
    public function update(Request $request, $id) {

        $validatedData = $request->validate([
            'vorname' => 'sometimes|required|string|max:255',
            'nachname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:kunde,email,' . $id,
            'telefonnummer' => 'nullable|string|max:255',
            'strasse' => 'sometimes|required|string|max:255',
            'plz' => 'sometimes|required|string|max:255',
            'ort' => 'sometimes|required|string|max:255',
        ]);

        // Suche Kunde in Datenbank und updaten
        $kunde = Kunde::findOrFail($id);
        $kunde->update($validatedData);

        return response()->json($kunde, 200);
    }

    // Einen bestimmten Kunden nach ID löschen
    public function destroy($id) {
        $kunde = Kunde::findOrFail($id);
        $kunde->delete();
        return response()->json(null, 204);
    }
}
