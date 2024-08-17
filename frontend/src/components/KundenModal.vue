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
                    <v-container class="pa-0 pa-sm-2">
                        <v-row >
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
  
<script setup>
    import { ref, watch } from 'vue';

    const props = defineProps({
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
    });

    // Reactives
    const formValid = ref(false);
    const visible = ref(false);

    // Emits
    const emit = defineEmits(['input', 'save']);

    // Beobachte ob sich der Sichtbarkeits-Prop ändert
    watch(() => props.value, (val) => {
        visible.value = val;
    });

    // Beobachte ob sich das Formular ändert
    watch(visible, (val) => {
        emit('input', val);
    });

    // Validierung und speichern des Formulars
    const saveKunde = () => {
        if (formValid.value) {
            emit('save', props.kunde);
        }
    };
</script>

<style scoped>
</style>
  
  