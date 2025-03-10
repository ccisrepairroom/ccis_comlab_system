import './bootstrap';
import 'preline';
import 'flowbite';
import "@hotwired/turbo";

// Optimize Turbo navigation performance
document.addEventListener("turbo:load", () => {
    window.livewire?.rescan();
    window.HSStaticMethods?.autoInit();
    window.initFlowbite?.();
});

// Prevent Turbo from interfering with Livewire
document.addEventListener("turbo:before-render", () => {
    window.livewire?.stop();
});
