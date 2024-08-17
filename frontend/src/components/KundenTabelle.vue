<template>
    <!-- Kundensuche und Hinzufügen-Button -->
    <v-container class="max-width-container">
        <v-row class="d-flex align-center">
            <v-col cols="12" sm="4">
                <v-text-field
                    v-model="suche"
                    append-icon="mdi-magnify"
                    label="Kunden suchen"
                    @input="onSearch"
                ></v-text-field>
            </v-col>

            <v-spacer></v-spacer>

            <v-col cols="12" sm="4" class="text-right">
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
            
            @update:options="onPaginationUpdate"
        >
  
            <template v-slot:item.actions="{ item }">
                <v-icon small @click="openModal(item)" class="mr-2">mdi-pencil</v-icon>
                <v-icon small @click="deleteCustomer(item.id)" color="red">mdi-delete</v-icon>
            </template>

        </v-data-table-server>
  
        <!-- Kundenmodal -->
        <KundenModal
            v-model="modalSichtbar"
            :kunde="modalKunde"
            @save="saveKunde"
            @close="closeModal"
        />
    </v-container>
</template>
  
<script>
    import KundenModal from './KundenModal.vue';
    import ApiService from "../services/ApiService";

    export default {
        components: {
            KundenModal,
        },
        data() {
            return {
                headers: [
                    { title: 'ID', value: 'id' },
                    { title: 'Vorname', value: 'vorname' },
                    { title: 'Nachname', value: 'nachname' },
                    { title: 'Email', value: 'email' },
                    { title: 'Straße + Hausnummer', value: 'strasse' },
                    { title: 'Postleitzahl', value: 'plz' },
                    { title: 'Ort', value: 'ort' },
                    { title: 'Aktionen', value: 'actions', sortable: false },
                ],

                kunden: [],
                suche: '',
                laden: false,
            
                pagination: {
                page: 1,
                itemsPerPage: 10,
                    itemsPerPageOptions: [
                        {value: 10, title: '10'},
                        {value: 20, title: '20'},
                        {value: 50, title: '50'},
                        {value: 100, title: '100'},
                    ]
                },
                totalItems: 0,

                modalKunde: {
                    id: null,
                    vorname: '',
                    nachname: '',
                    email: '',
                    telefonnummer: '',
                    strasse: '',
                    plz: '',
                    ort: '',
                },
                modalSichtbar: false,
            };
        },
        methods: {
            resetForm() {
                this.$refs.form.reset();
                this.$refs.form.resetValidation();
            },

            getKunden() {
                this.laden = true;
                const { page, itemsPerPage } = this.pagination;
                ApiService.getKunden(page, itemsPerPage, this.suche).then(response => {
                    this.kunden = response.data.data;
                    this.totalItems = response.data.total;
                    this.laden = false;
                });
            },

            onPaginationUpdate(newPagination) {
                this.pagination.page = newPagination.page;
                this.pagination.itemsPerPage = newPagination.itemsPerPage;
                this.getKunden();
            },

            openModal(customer = null) {
                if (customer) {
                    this.modalKunde = { ...customer };
                } else {
                    this.modalKunde = {
                    id: null,
                    vorname: '',
                    nachname: '',
                    email: '',
                    telefonnummer: '',
                    strasse: '',
                    plz: '',
                    ort: ''
                    };
                }
                this.modalSichtbar = true;
            },

            closeModal() {
                this.modalSichtbar = false;
            },

            onSearch() {
                this.pagination.page = 1;
            },

            // Speichert einen Kunden vom Modal
            async saveKunde() {
                try {
                    if (this.modalKunde.id) {
                        // Kunde bearbeiten
                        await ApiService.updateKunde(this.modalKunde.id, this.modalKunde);
                    } 
                    else {
                        // Neuen Kunden hinzufügen
                        await ApiService.addKunde(this.modalKunde);
                    }
                    this.closeModal();

                } catch (error) {
                    console.error(error);
                }
                this.getKunden();
            },

            // Löscht einen Kunden
            deleteKunde(id) {
                if (confirm('Möchten Sie diesen Kunden wirklich löschen?')) {
                    ApiService.deleteKunde(id).then(() => {
                        this.getKunden();
                    });
                }
            },
        },

        watch: {
            search(newSearch) {
                this.pagination.page = 1;
                this.getKunden();
            },
        },
        mounted() {
            this.getKunden();
        },
    };
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
  