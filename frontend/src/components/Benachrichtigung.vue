<template>
    <v-snackbar
        v-model="sichtbar"
        :color="typ"
        timeout="3000"
        location="bottom right"
        elevation="6"
    >
        <v-container class="d-flex pa-2" fluid>
            <v-icon class="mr-2">{{ icon }}</v-icon>
            <span class="me-auto">{{ nachricht }}</span>
            <v-icon small @click="sichtbar = false" >mdi-close</v-icon>
        </v-container>
    </v-snackbar>
  </template>
  
  <script setup>
    import { ref, watch, computed } from 'vue';
    
    // Definiere Props
    const props = defineProps({
        nachricht: {
            type: String,
            required: true,
        },
        typ: {
            type: String,
            default: 'success',
        },
        modelValue: {
            type: Boolean,
            default: false,
        }
    });
    
    // Emit events
    const emit = defineEmits(['update:modelValue']);
    
    // Sichtbarkeit der Benachrichtigung
    const sichtbar = ref(props.modelValue);
  
    // Beobachte ob sich der Sichtbarkeits-Prop ändert
    watch(() => props.modelValue, (newValue) => {
        sichtbar.value = newValue;
    });
  
    // Emitte das update:modelValue Event, wenn sich die Sichtbarkeit ändert
    watch(sichtbar, (newValue) => {
        emit('update:modelValue', newValue);
    });
  
    // Icon für die Benachrichtigung
    const icon = computed(() => {
        switch (props.typ) {
        case 'success':
            return 'mdi-check-circle';
        case 'error':
            return 'mdi-alert-circle';
        case 'warning':
            return 'mdi-alert';
        default:
            return 'mdi-information';
        }
    });
  
</script>
  
<style scoped>
</style>
  