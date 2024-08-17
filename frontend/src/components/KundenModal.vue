<template>
    <v-dialog v-model="visible" max-width="600px">
        <v-card class="pa-4">
            <!-- Titel mit Close button -->
            <v-card-title class="d-flex">
                <span class="text-h5 text-weight-bold text-center">Kunde {{ kunde.id ? 'bearbeiten' : 'hinzufügen' }}</span>
    
                <v-spacer></v-spacer>

                <v-btn icon @click="$emit('close')">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            
            <!-- Formular -->
            <v-card-text>
                <v-form ref="form" v-model="formValid">
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="kunde.vorname" 
                                    label="Vorname*" 
                                    :rules="[v => !!v || 'Vorname ist erforderlich']" 
                                    required
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="kunde.nachname" 
                                    label="Nachname*" 
                                    :rules="[v => !!v || 'Nachname ist erforderlich']" 
                                    required
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="kunde.email" 
                                    label="Email*" 
                                    :rules="[v => !!v || 'Email ist erforderlich', v => /.+@.+\..+/.test(v) || 'E-Mail muss gültig sein']" 
                                    required
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="kunde.telefonnummer" 
                                    label="Telefonnummer"
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="kunde.strasse" 
                                    label="Strasse und Hausnummer*" 
                                    :rules="[v => !!v || 'Strasse und Hausnummer sind erforderlich']" 
                                    required
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="kunde.plz" 
                                    label="PLZ*" 
                                    :rules="[v => !!v || 'PLZ ist erforderlich']" 
                                    required
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="kunde.ort" 
                                    label="Ort*" 
                                    :rules="[v => !!v || 'Ort ist erforderlich']" 
                                    required
                                >
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>

            <!-- Abbrechen und Speichern/Hinzufügen Button -->
            <v-card-actions>
                <v-btn color="red" variant="flat" @click="$emit('close')">Abbrechen</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary" variant="flat" @click="saveKunde">{{ kunde.id ? 'Speichern' : 'Hinzufügen' }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
  
  <script>
    export default {
        props: {
            value: {
                type: Boolean,
                default: false
            },
            kunde: {
                type: Object,
                default: () => ({
                    id: null,
                    vorname: '',
                    nachname: '',
                    email: '',
                    telefonnummer: '',
                    strasse: '',
                    plz: '',
                    ort: ''
                })
            }
        },
        data() {
            return {
                visible: this.value,
                formValid: false
            };
        },
        watch: {
            value(val) {
                this.visible = val;
            },
            visible(val) {
                this.$emit('input', val);
            }
        },
        methods: {
            // Validierung und Speichern des Kunden durch ein Signal
            saveKunde() {
                if (this.$refs.form.validate()) {
                    this.$emit('save', this.kunde);
                }
            }
        }
    };
</script>

<style scoped>
</style>
  
  