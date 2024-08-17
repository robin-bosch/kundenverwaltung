<template>
    <v-dialog v-model="sichtbar" max-width="500px">
        <v-card>
            <v-card-title class="headline">Bestätigung</v-card-title>
            <v-card-text>Sind Sie sicher, dass Sie den Kunden mit der ID {{ kundeId }} löschen möchten?</v-card-text>
    
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" variant="flat" @click="cancel">Abbrechen</v-btn>
                <v-btn color="red darken-1" variant="flat" @click="confirm">Löschen</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
  
<script setup>
    import { ref, watch } from 'vue';
    
    // Definiere Props
    const props = defineProps({
        kundeId: {
            type: Number,
            default: false,
            required: true
        },
    });
  
    // Reactive für die Sichtbarkeit des Modals
    const sichtbar = ref(props.value);
  
     
  
    // Beobachte ob sich der Sichtbarkeits-Prop ändert
    watch(() => props.value, (newVal) => {
        sichtbar.value = newVal;
    });

    // Emit events
    const emit = defineEmits(['cancel', 'confirm']);
  
    // Events zum Abbrechen und Bestätigen
    const cancel = () => {
        emit('cancel');
    };
    
    const confirm = () => {
        emit('confirm');
    };
  </script>
  
  