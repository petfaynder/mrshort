/**
 * Enhanced Anti-AdBlock Detection Module v2
 * Uses network request blocking detection - most reliable method
 */
(function () {
    'use strict';

    const AdBlockDetector = {
        detected: false,
        callbacks: [],
        testsCompleted: 0,
        totalTests: 3,

        init: function () {
            console.log('[AdBlock Detector] Starting detection...');

            // Method 1: Try to load ads.js script
            this.testScript('/ads.js', 'script_ads');

            // Method 2: Try to load Google AdSense-like path
            this.testScript('/pagead/js/adsbygoogle.js', 'script_adsense');

            // Method 3: Fetch request to ad-like URL
            this.testFetch();

            // Method 4: DOM element check (fallback)
            this.testElement();
        },

        /**
         * Test if a script can be loaded
         */
        testScript: function (src, method) {
            const script = document.createElement('script');
            script.async = true;
            script.src = src + '?t=' + Date.now();

            script.onload = () => {
                console.log('[AdBlock Detector] Script loaded:', src);
                this.testsCompleted++;
                this.checkAllTests();
            };

            script.onerror = () => {
                console.log('[AdBlock Detector] Script blocked:', src);
                this.triggerDetected(method);
            };

            // Only append after DOM is ready
            const appendScript = () => {
                document.head.appendChild(script);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', appendScript);
            } else {
                appendScript();
            }
        },

        /**
         * Test using fetch API
         */
        testFetch: function () {
            // Create a fake ad URL path
            fetch('/ads.js?check=1&t=' + Date.now(), {
                method: 'HEAD',
                cache: 'no-store'
            })
                .then(response => {
                    if (response.ok) {
                        console.log('[AdBlock Detector] Fetch successful');
                        this.testsCompleted++;
                        this.checkAllTests();
                    } else {
                        this.triggerDetected('fetch_error');
                    }
                })
                .catch(err => {
                    console.log('[AdBlock Detector] Fetch blocked:', err);
                    this.triggerDetected('fetch_blocked');
                });
        },

        /**
         * Test using DOM element - adblocks hide elements with ad class names
         */
        testElement: function () {
            // Wait for DOM
            const runTest = () => {
                // Create a visible test element
                const bait = document.createElement('div');
                bait.id = 'ad-test-bait';
                bait.className = 'ad-banner advertisement adsbox ad-placeholder ad_banner';
                bait.style.cssText = 'width: 300px !important; height: 250px !important; position: absolute !important; left: -9999px !important; visibility: visible !important; display: block !important;';
                bait.innerHTML = '&nbsp;';
                document.body.appendChild(bait);

                // Check after a delay
                setTimeout(() => {
                    const el = document.getElementById('ad-test-bait');
                    if (!el) {
                        this.triggerDetected('element_removed');
                        return;
                    }

                    const style = window.getComputedStyle(el);
                    const hidden =
                        style.display === 'none' ||
                        style.visibility === 'hidden' ||
                        style.opacity === '0' ||
                        el.offsetHeight === 0 ||
                        el.clientHeight === 0 ||
                        el.offsetParent === null;

                    if (hidden) {
                        this.triggerDetected('element_hidden');
                    } else {
                        console.log('[AdBlock Detector] Element visible');
                        this.testsCompleted++;
                        this.checkAllTests();
                    }

                    // Clean up
                    if (el) el.remove();
                }, 200);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', runTest);
            } else {
                runTest();
            }
        },

        /**
         * Check if all tests completed without detection
         */
        checkAllTests: function () {
            // If we haven't detected and got positive results from tests
            // Don't do anything - no adblock detected
            if (this.testsCompleted >= 2 && !this.detected) {
                console.log('[AdBlock Detector] No adblock detected');
            }
        },

        /**
         * Trigger detected state
         */
        triggerDetected: function (method) {
            if (this.detected) return;

            this.detected = true;
            console.log('[AdBlock Detector] AdBlock DETECTED via:', method);

            // Small delay to ensure DOM is ready
            setTimeout(() => {
                this.callbacks.forEach(callback => {
                    try {
                        callback();
                    } catch (e) {
                        console.error('[AdBlock Detector] Callback error:', e);
                    }
                });
            }, 100);
        },

        /**
         * Register callback for when adblock is detected
         */
        onDetected: function (callback) {
            if (typeof callback === 'function') {
                this.callbacks.push(callback);
                // If already detected, fire immediately
                if (this.detected) {
                    setTimeout(callback, 0);
                }
            }
        },

        /**
         * Check if adblock was detected
         */
        isDetected: function () {
            return this.detected;
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => AdBlockDetector.init());
    } else {
        AdBlockDetector.init();
    }

    // Expose globally
    window.AdBlockDetector = AdBlockDetector;
})();

// Additional check: verify global ad variables after scripts should have loaded
window.addEventListener('load', function () {
    setTimeout(function () {
        // Check if our bait scripts set their variables
        if (!window.adsLoaded && !window.AdBlockDetector.isDetected()) {
            console.log('[AdBlock Detector] Variable check failed - adsLoaded not set');
            window.AdBlockDetector.triggerDetected('variable_check');
        }
        if (!window.googleAdsLoaded && !window.AdBlockDetector.isDetected()) {
            console.log('[AdBlock Detector] Variable check failed - googleAdsLoaded not set');
            window.AdBlockDetector.triggerDetected('google_variable_check');
        }
    }, 1000);
});
