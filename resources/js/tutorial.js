/**
 * MrShort.io Dashboard Tutorial
 * Interactive onboarding tutorial using Shepherd.js
 * Updated: December 2024 - 20 Steps with Gamification
 */

import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';

// Only initialize if tutorial should be shown
if (window.showTutorial) {
    document.addEventListener('DOMContentLoaded', initTutorial);
}

function initTutorial() {
    const totalSteps = 20;

    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            cancelIcon: {
                enabled: true
            },
            classes: 'shepherd-theme-custom',
            scrollTo: { behavior: 'smooth', block: 'center' },
            modalOverlayOpeningPadding: 10,
            modalOverlayOpeningRadius: 12,
        }
    });

    // Helper function to create step buttons with Skip option
    function createButtons(stepNumber, isFirst = false, isLast = false) {
        const buttons = [];

        // Skip button (always visible except on last step)
        if (!isLast) {
            buttons.push({
                text: 'Skip Tour',
                action: () => skipTutorial(tour),
                classes: 'shepherd-button-skip'
            });
        }

        // Back button (not on first step)
        if (!isFirst) {
            buttons.push({
                text: '← Back',
                action: tour.back,
                classes: 'shepherd-button-secondary'
            });
        }

        // Next/Finish button
        if (isLast) {
            buttons.push({
                text: 'Start Earning! 🚀',
                action: () => completeTutorial(tour),
                classes: 'shepherd-button-primary'
            });
        } else {
            buttons.push({
                text: `Next (${stepNumber}/${totalSteps}) →`,
                action: tour.next,
                classes: 'shepherd-button-primary'
            });
        }

        return buttons;
    }

    // ========================================
    // PART 1: DASHBOARD ELEMENTS (Steps 1-10)
    // ========================================

    // Step 1: Welcome
    tour.addStep({
        id: 'welcome',
        title: '👋 Welcome to MrShort.io!',
        text: '<p>Welcome to your dashboard! This quick tour will show you how to start earning money by shortening links.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">This will only take about 2 minutes. Let\'s get started!</p>',
        attachTo: {
            element: '[data-tutorial="header"]',
            on: 'bottom'
        },
        buttons: createButtons(1, true, false)
    });

    // Step 2: Gamification Widgets (NEW)
    tour.addStep({
        id: 'gamification-widgets',
        title: '🔥 Your Activity Hub',
        text: '<p>Track your daily activity at a glance:</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li><strong>Login Streak</strong> - Keep your streak alive!</li><li><strong>Daily Challenges</strong> - Complete tasks for rewards</li><li><strong>Weekly Competition</strong> - Compete with others</li></ul>',
        attachTo: {
            element: '[data-tutorial="gamification-widgets"]',
            on: 'bottom'
        },
        buttons: createButtons(2, false, false)
    });

    // Step 3: Quick Shortener
    tour.addStep({
        id: 'shortener',
        title: '✂️ Create Short Links',
        text: '<p>This is your <strong>link shortener</strong>. Paste any URL here and click <strong>\'Shrink Now\'</strong> to create a monetized short link.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Share your short links anywhere - every click earns you money!</p>',
        attachTo: {
            element: '[data-tutorial="shortener"]',
            on: 'bottom'
        },
        buttons: createButtons(3, false, false)
    });

    // Step 4: Date Filter
    tour.addStep({
        id: 'date-filter',
        title: '📅 Filter by Date',
        text: '<p>Use this <strong>date filter</strong> to view your statistics for different time periods.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Options: Today, Last 7 Days, Last 30 Days, Custom Range</p>',
        attachTo: {
            element: '[data-tutorial="date-filter"]',
            on: 'left'
        },
        buttons: createButtons(4, false, false)
    });

    // Step 5: Stats Cards
    tour.addStep({
        id: 'stats',
        title: '📊 Key Metrics',
        text: '<p>Your most important stats:</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li><strong>Total Views</strong> - Click count</li><li><strong>Total Earnings</strong> - Money earned</li><li><strong>Referral Earnings</strong> - From referrals</li><li><strong>Average CPM</strong> - Per 1000 views</li></ul>',
        attachTo: {
            element: '[data-tutorial="stats"]',
            on: 'bottom'
        },
        buttons: createButtons(5, false, false)
    });

    // Step 6: Earnings Chart
    tour.addStep({
        id: 'chart',
        title: '📈 Earnings Chart',
        text: '<p>This <strong>interactive chart</strong> shows your earnings and views over time.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Hover to see detailed data for each day.</p>',
        attachTo: {
            element: '[data-tutorial="chart"]',
            on: 'top'
        },
        buttons: createButtons(6, false, false)
    });

    // Step 7: Performance Overview
    tour.addStep({
        id: 'performance',
        title: '🎯 Goals & Countries',
        text: '<p>Track your progress:</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li><strong>Earnings Goal</strong> - Set monthly targets</li><li><strong>Top Countries</strong> - Where clicks come from</li></ul><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Different countries = different CPM rates!</p>',
        attachTo: {
            element: '[data-tutorial="performance"]',
            on: 'top'
        },
        buttons: createButtons(7, false, false)
    });

    // Step 8: Recent Links
    tour.addStep({
        id: 'recent-links',
        title: '🔗 Recent Links',
        text: '<p>Your <strong>most recent links</strong>. You can:</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li>Copy short links</li><li>View statistics</li><li>Edit or delete</li></ul>',
        attachTo: {
            element: '[data-tutorial="recent-links"]',
            on: 'top'
        },
        buttons: createButtons(8, false, false)
    });

    // Step 9: AI Suggestions
    tour.addStep({
        id: 'suggestions',
        title: '💡 Smart Suggestions',
        text: '<p>Get <strong>AI-powered tips</strong> to maximize your earnings!</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Personalized based on your link performance.</p>',
        attachTo: {
            element: '[data-tutorial="suggestions"]',
            on: 'top'
        },
        buttons: createButtons(9, false, false)
    });

    // Step 10: Payment & Activity
    tour.addStep({
        id: 'payment-activity',
        title: '💰 Payment & Activity',
        text: '<p>At the bottom:</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li><strong>Payment Summary</strong> - Balance, threshold, payout</li><li><strong>Recent Activity</strong> - Announcements</li></ul>',
        attachTo: {
            element: '[data-tutorial="payment-activity"]',
            on: 'top'
        },
        buttons: createButtons(10, false, false)
    });

    // ========================================
    // PART 2: SIDEBAR NAVIGATION (Steps 11-19)
    // ========================================

    // Step 11: Sidebar - Links
    tour.addStep({
        id: 'nav-links',
        title: '🔗 Manage Links',
        text: '<p>Access <strong>all your shortened links</strong> here.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">View stats, edit settings, manage hidden links.</p>',
        attachTo: {
            element: '[data-tutorial="nav-links"]',
            on: 'right'
        },
        buttons: createButtons(11, false, false)
    });

    // Step 12: Sidebar - Withdrawals
    tour.addStep({
        id: 'nav-withdrawals',
        title: '💸 Get Paid',
        text: '<p>Request a withdrawal once you reach the <strong>minimum threshold</strong>.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Methods: PayPal, USDT, Bitcoin & more!</p>',
        attachTo: {
            element: '[data-tutorial="nav-withdrawals"]',
            on: 'right'
        },
        buttons: createButtons(12, false, false)
    });

    // Step 13: Sidebar - Tools
    tour.addStep({
        id: 'nav-tools',
        title: '🛠️ Tools',
        text: '<p>Advanced tools:</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li><strong>Mass Shortener</strong> - Multiple URLs</li><li><strong>Dead Link Checker</strong></li><li><strong>API Access</strong></li></ul>',
        attachTo: {
            element: '[data-tutorial="nav-tools"]',
            on: 'right'
        },
        buttons: createButtons(13, false, false)
    });

    // Step 14: Sidebar - Campaigns
    tour.addStep({
        id: 'nav-campaigns',
        title: '📢 Campaigns',
        text: '<p>Create and manage <strong>advertising campaigns</strong>.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Run ads on MrShort.io network!</p>',
        attachTo: {
            element: '[data-tutorial="nav-campaigns"]',
            on: 'right'
        },
        buttons: createButtons(14, false, false)
    });

    // Step 15: Sidebar - Referrals
    tour.addStep({
        id: 'nav-referrals',
        title: '👥 Referrals',
        text: '<p>Invite friends and <strong>earn a percentage of their earnings forever!</strong></p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">More referrals = more passive income!</p>',
        attachTo: {
            element: '[data-tutorial="nav-referrals"]',
            on: 'right'
        },
        buttons: createButtons(15, false, false)
    });

    // Step 16: Sidebar - Reports
    tour.addStep({
        id: 'nav-reports',
        title: '📊 Reports',
        text: '<p><strong>Comprehensive analytics</strong> about your links and earnings.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">Breakdown by country, device, browser & more!</p>',
        attachTo: {
            element: '[data-tutorial="nav-reports"]',
            on: 'right'
        },
        buttons: createButtons(16, false, false)
    });

    // Step 17: Sidebar - Gamification Hub (EXPANDED)
    tour.addStep({
        id: 'nav-gamification',
        title: '🏆 Gamification Hub',
        text: '<p>Level up your experience!</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li><strong>Daily Spin</strong> - Free daily rewards</li><li><strong>Mystery Boxes</strong> - Open for prizes</li><li><strong>Competition</strong> - Weekly contests</li><li><strong>Battle Pass</strong> - Seasonal rewards</li><li><strong>Teams</strong> - Join or create a team</li><li><strong>VIP Status</strong> - Premium benefits</li><li><strong>Achievements</strong> - Unlock badges</li><li><strong>Leaderboard</strong> - Top earners</li><li><strong>Inventory</strong> - Your items</li></ul>',
        attachTo: {
            element: '[data-tutorial="nav-gamification"]',
            on: 'right'
        },
        buttons: createButtons(17, false, false)
    });

    // Step 18: Sidebar - Contact Support
    tour.addStep({
        id: 'nav-contact',
        title: '💬 Contact Support',
        text: '<p>Need help? <strong>Create a support ticket</strong>.</p><p style="margin-top: 8px; opacity: 0.7; font-size: 0.85rem;">We typically respond within 24 hours!</p>',
        attachTo: {
            element: '[data-tutorial="nav-contact"]',
            on: 'right'
        },
        buttons: createButtons(18, false, false)
    });

    // Step 19: Sidebar - Settings
    tour.addStep({
        id: 'nav-settings',
        title: '⚙️ Settings',
        text: '<p>Configure your account:</p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li><strong>Profile</strong> - Your info</li><li><strong>Payment</strong> - Withdrawal method</li><li><strong>Security</strong> - Enable 2FA</li><li><strong>Theme</strong> - Light/Dark mode</li></ul>',
        attachTo: {
            element: '[data-tutorial="nav-settings"]',
            on: 'right'
        },
        buttons: createButtons(19, false, false)
    });

    // ========================================
    // PART 3: COMPLETION (Step 20)
    // ========================================

    // Step 20: Completion
    tour.addStep({
        id: 'complete',
        title: '🎉 You\'re All Set!',
        text: '<p style="font-size: 1.05rem;">Congratulations! You\'re ready to start earning with MrShort.io!</p><p style="margin-top: 12px;"><strong>Quick Start Tips:</strong></p><ul style="margin: 8px 0; padding-left: 20px; list-style: disc;"><li>Create your first short link</li><li>Share on social media</li><li>Complete daily challenges</li><li>Watch your earnings grow!</li></ul><p style="margin-top: 12px; opacity: 0.7; font-size: 0.85rem;">Need help? Click \'Contact Us\' anytime. Good luck! 🚀</p>',
        buttons: createButtons(20, false, true)
    });

    // Event listeners
    tour.on('cancel', () => skipTutorial(tour));

    // Start the tour
    tour.start();
}

/**
 * Complete the tutorial and save to database
 */
async function completeTutorial(tour) {
    tour.complete();
    await saveTutorialStatus();
}

/**
 * Skip the tutorial and save to database
 */
async function skipTutorial(tour) {
    tour.cancel();
    await saveTutorialStatus();
}

/**
 * Save tutorial completion status via AJAX
 */
async function saveTutorialStatus() {
    try {
        const response = await fetch(window.tutorialCompleteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        });

        if (!response.ok) {
            console.error('Failed to save tutorial status');
        }
    } catch (error) {
        console.error('Error saving tutorial status:', error);
    }
}
