import './bootstrap';
import 'preline';
import 'flowbite';

document.addEventListener('livewire:navigated', () => { 
    if (typeof window.HSStaticMethods?.autoInit === "function") {
        window.HSStaticMethods.autoInit();
    }
    
    if (typeof window.initFlowbite === "function") {
        window.initFlowbite(); 
    }
});

