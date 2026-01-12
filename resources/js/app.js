import './bootstrap';
// flag-icons CSS already imported in app.css - removed duplicate

// Lazy-load tutorial only when needed (saves ~45 KiB initial bundle)
if (window.showTutorial) {
    import('./tutorial').then(m => {
        // Tutorial module handles its own init
    }).catch(err => console.error('Tutorial load failed:', err));
}

// AdBlock detector - only load on pages that need it
if (window.enableAdblockDetection) {
    import('./adblock-detector');
}
