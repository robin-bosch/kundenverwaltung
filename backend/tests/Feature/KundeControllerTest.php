<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Kunde;

class KundeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testet, ob alle Kunden mit Paginierung aufgelistet werden können.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_kann_alle_kunden_mit_paginierung_auflisten()
    {
        Kunde::factory()->count(30)->create(); // Erstelle 30 Kunden für den Test

        $response = $this->getJson('/api/kunde?page=1&pageSize=20');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'vorname', 'nachname', 'email', 'telefonnummer', 'strasse', 'plz', 'ort', 'created_at', 'updated_at']
            ],
            'links',
        ]);
        $this->assertCount(20, $response->json('data')); // Stelle sicher, dass nur 20 Kunden zurückgegeben werden
    }

    /**
     * Testet, ob nach Kunden anhand der ID gesucht werden kann.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_kann_nach_kunden_per_id_suchen()
    {
        $kunde = Kunde::factory()->create(['id' => 123]);

        $response = $this->getJson('/api/kunde?search=123');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => 123]);
    }

    /**
     * Testet, ob nach Kunden anhand von Namen oder E-Mail gesucht werden kann.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_kann_nach_kunden_per_name_oder_email_suchen()
    {
        $kunde = Kunde::factory()->create([
            'vorname' => 'John',
            'nachname' => 'Doe',
            'email' => 'john@example.com'
        ]);

        $response = $this->getJson('/api/kunde?search=John');

        $response->assertStatus(200);
        $response->assertJsonFragment(['vorname' => 'John']);

        $response = $this->getJson('/api/kunde?search=example');

        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => 'john@example.com']);
    }

    /**
     * Testet, ob eine leere Ergebnisliste zurückgegeben wird, wenn die Suche keine Übereinstimmungen findet.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_gibt_ein_leeres_ergebnis_zurueck_wenn_keine_uebereinstimmung_gefunden_wird()
    {
        Kunde::factory()->create([
            'vorname' => 'John',
            'nachname' => 'Doe',
            'email' => 'john@example.com'
        ]);

        $response = $this->getJson('/api/kunde?search=NonExistentName');

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
    }

    /**
     * Testet, ob ein neuer Kunde erstellt werden kann.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_kann_einen_neuen_kunden_erstellen()
    {
        $daten = [
            'vorname' => 'John',
            'nachname' => 'Doe',
            'email' => 'john@example.com',
            'telefonnummer' => '1234567890',
            'strasse' => '123 Main St',
            'plz' => '12345',
            'ort' => 'Berlin',
        ];

        $response = $this->postJson('/api/kunde', $daten);

        $response->assertStatus(201);
        $this->assertDatabaseHas('kunde', $daten);
    }

    /**
     * Testet, ob ein Kunde anhand seiner ID angezeigt werden kann.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_kann_einen_kunden_anzeigen()
    {
        $kunde = Kunde::factory()->create();

        $response = $this->getJson("/api/kunde/{$kunde->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $kunde->id]);
    }

    /**
     * Testet, ob ein 404-Fehler zurückgegeben wird, wenn ein Kunde nicht gefunden wird.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_gibt_404_zurueck_wenn_kunde_nicht_gefunden_wird()
    {
        $response = $this->getJson("/api/kunde/99999");

        $response->assertStatus(404);
    }

    /**
     * Testet, ob ein Kunde aktualisiert werden kann.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_kann_einen_kunden_aktualisieren()
    {
        $kunde = Kunde::factory()->create();

        $daten = [
            'vorname' => 'Jane',
            'nachname' => 'Doe',
            'email' => 'jane@example.com',
        ];

        $response = $this->putJson("/api/kunde/{$kunde->id}", $daten);

        $response->assertStatus(200);
        $this->assertDatabaseHas('kunde', array_merge(['id' => $kunde->id], $daten));
    }

    /**
     * Testet, ob ein 404-Fehler zurückgegeben wird, wenn versucht wird, einen nicht existierenden Kunden zu aktualisieren.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_gibt_404_zurueck_wenn_aktualisierung_eines_nicht_existierenden_kunden()
    {
        $daten = [
            'vorname' => 'Jane',
            'nachname' => 'Doe',
            'email' => 'jane@example.com',
        ];

        $response = $this->putJson("/api/kunde/99999", $daten);

        $response->assertStatus(404);
    }

    /**
     * Testet, ob ein Kunde gelöscht werden kann.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_kann_einen_kunden_loeschen()
    {
        $kunde = Kunde::factory()->create();

        $response = $this->deleteJson("/api/kunde/{$kunde->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('kunde', ['id' => $kunde->id]);
    }

    /**
     * Testet, ob ein 404-Fehler zurückgegeben wird, wenn versucht wird, einen nicht existierenden Kunden zu löschen.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_gibt_404_zurueck_wenn_loeschen_eines_nicht_existierenden_kunden()
    {
        $response = $this->deleteJson("/api/kunde/99999");

        $response->assertStatus(404);
    }

    /**
     * Testet, ob die Validierung bei der Kundenerstellung greift.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_validiert_die_kundenerstellung()
    {
        $response = $this->postJson('/api/kunde', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['vorname', 'nachname', 'email', 'strasse', 'plz', 'ort']);
    }

    /**
     * Testet, ob die Validierung bei der Kundenaktualisierung greift.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_validiert_die_kundenaktualisierung()
    {
        $kunde = Kunde::factory()->create();

        $response = $this->putJson("/api/kunde/{$kunde->id}", ['email' => 'not-an-email']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Testet, ob die E-Mail-Adresseneinzigartigkeit bei der Erstellung eines Kunden geprüft wird.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_prueft_eindeutige_email_waehrend_der_erstellung()
    {
        Kunde::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/kunde', [
            'vorname' => 'Jane',
            'nachname' => 'Doe',
            'email' => 'existing@example.com',
            'telefonnummer' => '1234567890',
            'strasse' => '123 Main St',
            'plz' => '12345',
            'ort' => 'Berlin',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Testet, ob die E-Mail-Adresseneinzigartigkeit bei der Aktualisierung eines Kunden geprüft wird.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function es_prueft_eindeutige_email_waehrend_der_aktualisierung()
    {
        Kunde::factory()->create(['email' => 'existing@example.com']);
        $kunde = Kunde::factory()->create(['email' => 'another@example.com']);

        $response = $this->putJson("/api/kunde/{$kunde->id}", ['email' => 'existing@example.com']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
