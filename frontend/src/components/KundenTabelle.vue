<template>
    <!-- Kundensuche und Hinzufügen-Button -->
    <v-container class="max-width-container">
        <v-row class="ma-0">
            <v-col cols="12" sm="6" lg="4" class="px-0">
                <v-text-field
                    v-model="suche"
                    append-icon="mdi-magnify"
                    label="Kunden suchen"
                    hide-details="auto"
                    @input="onSearch"
                ></v-text-field>
            </v-col>

            <v-spacer></v-spacer>

            <v-col cols="12" sm="4" class="d-flex justify-center justify-sm-end align-center px-0">
                <v-btn color="primary" @click="openModal">Kunde hinzufügen</v-btn>
            </v-col>
        </v-row>
  
        <!-- Kundentabelle -->
        <v-data-table-server
            :headers="headers"
            :items="kunden"
            :search="suche"
            :loading="laden"

            :items-per-page="pagination.itemsPerPage"
            :items-per-page-options="pagination.itemsPerPageOptions"
            :items-per-page-text="`Einträge pro Seite`"
            :page.sync="pagination.page"
            :items-length="totalItems"

            no-data-text="Keine Kunden gefunden"

            @update:itemsPerPage="onItemsPerPageUpdate"
            @update:page="onPageUpdate"
        >
  
            <template v-slot:item.actions="{ item }">
                <v-icon small @click="openModal(item)" class="mr-2">mdi-pencil</v-icon>
                <v-icon small @click="confirmDeleteKunde(item.id)" color="red">mdi-delete</v-icon>
            </template>

        </v-data-table-server>
  
        <!-- Kundenmodal -->
        <KundenModal
            v-model="modalSichtbar"
            :kunde="modalKunde"
            @save="saveKunde"
            @close="closeModal"
        />

        <!-- Confirmmodal um das Löschen zu bestätigen -->
        <ConfirmModal
            v-model="confirmModalSichtbar"
            :kundeId="selectedKunde"
            @cancel="confirmModalSichtbar = false"
            @confirm="deleteKunde()"
        />

        <!-- Benachrichtigung -->
        <Benachrichtigung
            v-model="benachrichtigungSichtbar"
            :nachricht="benachrichtigungNachricht"
            :typ="benachrichtigungTyp"
        />
    </v-container>
</template>
  
<script setup>
    import { ref, reactive, watch, onMounted } from 'vue';
    import debounce from 'lodash.debounce';
    import KundenModal from './KundenModal.vue';
    import ApiService from "../services/ApiService";
    import ConfirmModal from './ConfirmModal.vue';
    import Benachrichtigung from './Benachrichtigung.vue';

    // Tabellenüberschriften
    const headers = [
        { title: 'ID', value: 'id' },
        { title: 'Vorname', value: 'vorname' },
        { title: 'Nachname', value: 'nachname' },
        { title: 'Email', value: 'email' },
        { title: 'Straße + Hausnummer', value: 'strasse' },
        { title: 'Postleitzahl', value: 'plz' },
        { title: 'Ort', value: 'ort' },
        { title: 'Aktionen', value: 'actions', sortable: false },
    ];

    // Reactive state
    const kunden = ref([]);
    const suche = ref('');
    const laden = ref(false);

    const pagination = reactive({
        page: 1,
        itemsPerPage: 10,
        itemsPerPageOptions: [
            { value: 10, title: '10' },
            { value: 20, title: '20' },
            { value: 50, title: '50' },
            { value: 100, title: '100' },
        ],
    });
    const totalItems = ref(0);

    // Reactive für das Modal zum Hinzufügen/Bearbeiten eines Kunden
    const modalKunde = reactive({
        id: null,
        vorname: '',
        nachname: '',
        email: '',
        telefonnummer: '',
        strasse: '',
        plz: '',
        ort: '',
    });
    const modalSichtbar = ref(false);

    // Reactive für das ConfirmModal zum Löschen eines Kunden
    const confirmModalSichtbar = ref(false);
    const selectedKunde = ref(null);

    // Reaktiver Zustand für Benachrichtigungen
    const benachrichtigungSichtbar = ref(false);
    const benachrichtigungNachricht = ref('');
    const benachrichtigungTyp = ref('success');

    /**
     * 
     * Benachrichtigungen funktionen
     *  
     */ 
    // Funktion zum Anzeigen der Benachrichtigung
    const zeigeBenachrichtigung = (nachricht, typ = 'success') => {
        benachrichtigungNachricht.value = nachricht;
        benachrichtigungTyp.value = typ;
        benachrichtigungSichtbar.value = true;
    };


    /**
     * 
     * Tabellenevents
     * 
     */
    // Getriggered wenn die Seite aktualisiert wird
    const onPageUpdate = (newPage) => {
        pagination.page = newPage;
        getKunden();
    };

    // Getriggered wenn die Anzahl der Einträge pro Seite aktualisiert wird
    const onItemsPerPageUpdate = (newItemsPerPage) => {
        pagination.itemsPerPage = newItemsPerPage;
        getKunden();
    };

    // Suche nach Kunden, hat eine Verzögerung von 500ms um nicht bei jedem Tastendruck eine Anfrage zu senden
    const onSearch = debounce(() => {
        pagination.page = 1;
        getKunden();
    }, 500);


    /**
     * 
     * Modalsteuerung
     * 
     */
    // Reset Modalkunde
    const resetModalKunde = () => {
        modalKunde.id = null;
        modalKunde.vorname = '';
        modalKunde.nachname = '';
        modalKunde.email = '';
        modalKunde.telefonnummer = '';
        modalKunde.strasse = '';
        modalKunde.plz = '';
        modalKunde.ort = '';
    };

    // Öffne das Kundenmodal zum Hinzufügen/Bearbeiten eines Kunden
    const openModal = (customer = null) => {
        if (customer) {
            Object.assign(modalKunde, customer);
        } 
        modalSichtbar.value = true;
    };

    // Schließe das Kundenmodal
    const closeModal = () => {
        modalSichtbar.value = false;
        resetModalKunde();
    };

    // Öffne das ConfirmModal zum Löschen eines Kunden
    const confirmDeleteKunde = (kundeId) => {
        selectedKunde.value = kundeId;
        confirmModalSichtbar.value = true;
    };

    /**
     * 
     * Kundenfunktionen
     * 
     */

    // Lade Kunden -> Kann auch eine Suche beinhalten oder eine Änderung der Seitengröße
    const getKunden = async () => {
        laden.value = true;
        try {
            const { page, itemsPerPage } = pagination;
            const response = await ApiService.getKunden(page, itemsPerPage, suche.value);
            kunden.value = response.data.data;
            totalItems.value = response.data.total;
        } catch (error) {
            console.error('Fehler beim Abrufen der Kunden:', error);
            zeigeBenachrichtigung('Fehler beim Abrufen der Kunden.', 'error');
        } finally {
            laden.value = false;
        }
    };

    // Speichern eines Kunden
    const saveKunde = async () => {
        try {
            if (modalKunde.id) {
                await ApiService.updateKunde(modalKunde.id, modalKunde);
                zeigeBenachrichtigung('Kunde erfolgreich aktualisiert!', 'success');
            } else {
                await ApiService.addKunde(modalKunde);
                zeigeBenachrichtigung('Kunde erfolgreich hinzugefügt!', 'success');
            }
            closeModal();
            getKunden();
        } catch (error) {
            zeigeBenachrichtigung('Fehler beim Speichern des Kunden.', 'error');
            console.error('Fehler beim Speichern des Kunden:', error);
        }
    };

    // Löschen eines Kunden
    const deleteKunde = async () => {
        try {
            await ApiService.deleteKunde(selectedKunde.value);
            zeigeBenachrichtigung('Kunde erfolgreich gelöscht!', 'success');
            getKunden();
        } catch (error) {
            zeigeBenachrichtigung('Fehler beim Löschen des Kunden.', 'error');
            console.error('Fehler beim Löschen des Kunden:', error);
        } finally {
            confirmModalSichtbar.value = false;
        }
    };

    // Erstes Laden der Kunden
    onMounted(() => {
        getKunden();
    });
</script>

<style scoped>
    .text-right {
        text-align: right;
    }

    /* Maximalgröße der Tabelle */
    .max-width-container {
        max-width: 1900px;
    }
</style>
  