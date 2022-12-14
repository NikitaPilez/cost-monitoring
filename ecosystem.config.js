module.exports = {
    apps: [
        {
            name: 'websocket',
            interpreter: '/usr/bin/php8.1',
            script: './artisan',
            args: 'websockets:serve',
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '256M',
            kill_timeout: 20000,
            restart_delay: 5000,
            max_restarts: 15,
        },
    ],
};
