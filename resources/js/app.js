import './bootstrap';
import 'preline';
import 'flowbite';
import "@hotwired/turbo";

Turbo.start();

// Reinitialize scripts on Turbo navigation
document.addEventListener("turbo:load", () => {
    if (typeof window.livewire !== "undefined") {
        window.livewire.rescan();
    }
    if (typeof window.HSStaticMethods !== "undefined") {
        window.HSStaticMethods.autoInit();
    }
    if (typeof window.initFlowbite === "function") {
        window.initFlowbite();
    }
});

// Prevent Turbo from interfering with Livewire
document.addEventListener("turbo:before-render", () => {
    if (typeof window.livewire !== "undefined") {
        window.livewire.stop();
    }
});
