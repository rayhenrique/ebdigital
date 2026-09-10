self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  // Não interceptar requisições de navegação (HTML) — deixa o browser/Livewire gerenciar
  if (event.request.mode === 'navigate') {
    return;
  }

  // Para outros recursos, tenta a rede e, se falhar, retorna resposta de erro gracioso
  event.respondWith(
    fetch(event.request).catch(() => {
      return new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
    })
  );
});
