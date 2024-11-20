import axios from 'axios';
window.axios = axios;

// Función global para fetch con CSRF
async function fetchWithCsrf(url, options = {}) {
    // Obtener el token CSRF si no está configurado
    await fetch('/sanctum/csrf-cookie', {
        method: 'GET',
        credentials: 'include', // Permite enviar cookies
    });

    // Obtener el token XSRF desde las cookies
    const xsrfToken = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1];

    if (!options.headers) {
        options.headers = {};
    }

    if (xsrfToken) {
        options.headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken);
    }

    options.credentials = 'include'; // Permitir cookies
    return fetch(url, options);
}

// Exponer la función globalmente
window.fetchWithCsrf = fetchWithCsrf;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
