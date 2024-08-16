<?php
namespace App\Http\Controllers;

use App\Models\Kunde;
use Illuminate\Http\Request;

class KundeController extends Controller
{
    // Alle Kunden anzeigen
    public function index(Request $request)
    {
        $query = Kunde::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('vorname', 'like', "%{$search}%")
                      ->orWhere('nachname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('telefonnummer', 'like', "%{$search}%")
                      ->orWhere('strasse', 'like', "%{$search}%")
                      ->orWhere('plz', 'like', "%{$search}%")
                      ->orWhere('ort', 'like', "%{$search}%");
            });
        }

        $kunde = $query->paginate(10);

        return response()->json($kunde);
    }

    // Neuen Kunden erstellen
    public function store(Request $request)
    {
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
    public function show($id)
    {
        $kunde = Kunde::findOrFail($id);
        return $kunde;
    }

    // Einen bestimmten Kunden nach ID aktualisieren
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vorname' => 'sometimes|required|string|max:255',
            'nachname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:kunde,email,' . $id,
            'telefonnummer' => 'nullable|string|max:255',
            'strasse' => 'sometimes|required|string|max:255',
            'plz' => 'sometimes|required|string|max:255',
            'ort' => 'sometimes|required|string|max:255',
        ]);

        $kunde = Kunde::findOrFail($id);
        $kunde->update($validatedData);
        return response()->json($kunde, 200);
    }

    // Einen bestimmten Kunden nach ID löschen
    public function destroy($id)
    {
        $kunde = Kunde::findOrFail($id);
        $kunde->delete();
        return response()->json(null, 204);
    }
}
