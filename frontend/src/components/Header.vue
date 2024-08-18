<template>
    <v-app-bar app color="primary" class="px-1 px-sm-3 px-md-5">
        <v-toolbar-title>Kundenverwaltung</v-toolbar-title>
        
        <v-spacer></v-spacer>

        <v-btn icon @click="toggleTheme">
            <v-icon>{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waxing-crescent' }}</v-icon>
        </v-btn>
    </v-app-bar>
</template>
  
<script setup>
    import { useTheme } from 'vuetify'
    import storageProvider from '../services/storageProvider';

    const theme = useTheme();

    // Toggled das Theme und speichert die Auswahl
    function toggleTheme() {
        theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
        storageProvider.saveThemeChoice(theme.global.name.value);
    }
</script>

<style scoped>
</style>