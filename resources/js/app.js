import './bootstrap';
import 'preline';
import 'flowbite';

document.addEventListener('livewire:navigated', () => { 
    window.HSStaticMethods.autoInit();
});
document.addEventListener("livewire:navigated", () => {
    if (typeof window.initFlowbite === "function") {
            window.initFlowbite(); 
        }
    });

