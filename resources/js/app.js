import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Explicitly initialize window.Echo to eliminate undefined errors
try {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
        wsPort: 8081,
        wssPort: 8081,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
    });
    console.log('✅ Echo successfully initialized on window!');
} catch (error) {
    console.error('❌ Error initializing Echo:', error);
}