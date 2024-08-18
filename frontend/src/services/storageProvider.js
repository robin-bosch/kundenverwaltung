/**
 * Kleiner StorageProvider, um Einstellungen in den LocalStorage zu speichern und zu laden
 */
// Key namen für die Speicherung von Einstellungen
// Hier würde sich TypeScript anbieten, um die Typen zu definieren und die Typensicherheit zu erhöhen
const STORAGE_KEYS = {
    ITEMS_PER_PAGE: 'itemsPerPage',
    THEME_CHOICE: 'themeChoice',
};
  
export default {
    // Speichern der Anzahl der anzuzeigenden Elemente pro Seite
    saveItemsPerPage(itemsPerPage) {
        localStorage.setItem(STORAGE_KEYS.ITEMS_PER_PAGE, itemsPerPage);
    },

    // Laden der Anzahl der anzuzeigenden Elemente pro Seite  -> default ist 10
    loadItemsPerPage(defaultValue = 10) {
        const savedItemsPerPage = localStorage.getItem(STORAGE_KEYS.ITEMS_PER_PAGE);
        return savedItemsPerPage ? parseInt(savedItemsPerPage, 10) : defaultValue;
    },

    // Speichern das gewählte Theme
    saveThemeChoice(theme) {
        localStorage.setItem(STORAGE_KEYS.THEME_CHOICE, theme);
    },

    // Laden das gewählte Theme -> default ist 'dark'
    loadThemeChoice(defaultValue = 'dark') {
        return localStorage.getItem(STORAGE_KEYS.THEME_CHOICE) || defaultValue;
    },

    // Löschen aller gespeicherten Einstellungen
    // -> wird aktuell nicht verwendet
    clearAll() {
        localStorage.removeItem(STORAGE_KEYS.ITEMS_PER_PAGE);
        localStorage.removeItem(STORAGE_KEYS.THEME_CHOICE);
    },
};
  