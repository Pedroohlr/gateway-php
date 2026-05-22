self.addEventListener('push', function (event) {
    let data = {};
    try {
        data = event.data.json();
    } catch (e) {
        console.error('Erro ao ler payload', e);
    }

    const title = data.title || 'Nova notificação';
    const options = {
        body: data.body || '',
        icon: data.icon || "/uploads/69348a208b0bf.png",
        badge: "/uploads/69348a208b0bf.png",
        data: data.url || "/"
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    event.waitUntil(
        clients.matchAll({ type: "window", includeUncontrolled: true }).then((clientList) => {
            // Se a janela já estiver aberta, foca nela
            for (const client of clientList) {
                if (client.url === event.notification.data && "focus" in client) {
                    return client.focus();
                }
            }

            // Se não estiver aberta, abre uma nova
            if (clients.openWindow) {
                return clients.openWindow(event.notification.data);
            }
        })
    );
});