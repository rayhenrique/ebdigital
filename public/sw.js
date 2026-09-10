self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

// Sem interceptação de fetch — o SW existe apenas para suporte PWA (instalação na tela inicial).
// Todas as requisições seguem diretamente para o servidor sem interferência.
