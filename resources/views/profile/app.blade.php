<x-app-layout :route="'Aplicativo'">
  <div class="main-content app-content">
    <div class="container-fluid">
      <div class="mb-3 row justify-content-between align-items-start">
        <div style="display:flex;align-item:center;justify-content:flex-start;"
          class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
          <h1 class="mb-0 display-5">Aplicativo</h1>
        </div>
      </div>

      <div class="d-flex justify-content-center align-items-start">
        <a href="#!" class="card-apk-android" onclick="installApp('android')">
          <x-android-icon />
        </a>
        <a href="#!" class="card-apk-ios" onclick="installApp('ios')">
          <x-ios-icon />
        </a>
        <a href="#!" class="card-apk-windows" onclick="installApp('windows')">
          <x-windows-icon />
        </a>
      </div>

      <h6 class="text-center my-3">Clique no icone do sistema operacional correspondente ao seu dispositivo
        para prosseguir
        com a instalação.</h6>
    </div>
  </div>

  <div class="modal fade" id="iosInstallModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3">

        <div class="modal-header">
          <h5 class="modal-title">Adicionar à Tela de Início (iOS)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <p>Siga os passos abaixo para instalar o app no iPhone/iPad:</p>

          <ol class="ps-3">
            <li class="mb-3">
              Toque no botão <strong>Compartilhar</strong> no Safari.<br>
              <img src="/assets/images/icons/pwa-share-ios.svg" width="60px" height="auto"
                class="img-fluid rounded mt-2" alt="Botão compartilhar">
            </li>

            <li class="mb-3">
              Role para baixo e selecione <strong>Adicionar à Tela de Início</strong>.<br>
              <img src="/assets/images/icons/pwa-add-home-ios.svg" width="50px" height="auto"
                class="img-fluid rounded mt-2" alt="Adicionar à tela de início">
            </li>

            <li>
              Toque em <strong>Adicionar</strong> no canto superior direito.
            </li>
          </ol>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>

      </div>
    </div>
  </div>
  <script>
    let deferredPrompt = null;

    window.addEventListener("beforeinstallprompt", (e) => {
      e.preventDefault();
      deferredPrompt = e;
    });

    window.installApp = async function (dispositivo) {
      console.log(dispositivo);

      // navegadores que não suportam instalação via JS (ex.: iOS, Safari Desktop, Firefox)
      if (!window.BeforeInstallPromptEvent) {
        const modal = new bootstrap.Modal(document.getElementById('iosInstallModal'));
        modal.show();
        return;
      }

      // android + windows
      if (dispositivo === 'windows' || dispositivo === 'android') {

        // PWA ainda não está instalável
        if (!deferredPrompt) {
          showToast('info', 'O app ainda não está disponível para instalação.');
          return;
        }

        const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);

        if (isIOS) {
          const modal = new bootstrap.Modal(document.getElementById('iosInstallModal'));
          modal.show();
        } else {
          // dispara prompt de instalação
          deferredPrompt.prompt();

          const result = await deferredPrompt.userChoice;
          console.log("Resultado:", result.outcome);
          if (result.outcome)

            // limpa prompt
            deferredPrompt = null;
          return;

        }
      }

      // iOS (não suporta instalação via JS)
      if (dispositivo === 'ios') {

        // detectar se realmente é iOS
        /*  const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
 
         if (isIOS) { */
        // Abrir modal com tutorial
        const modal = new bootstrap.Modal(document.getElementById('iosInstallModal'));
        modal.show();
        /* } else {
          showToast('info', 'Sistema operacional incompatível.');
        } */

        return;
      }
    };

    document.addEventListener('DOMContentLoaded', function () {
      let ua = navigator.userAgent;
      const match = ua.match(/\((.*?)\)/);
      if (match) {
        console.log({ device: match[1], active: navigator.userActivation })

      }
    })
  </script>
</x-app-layout>