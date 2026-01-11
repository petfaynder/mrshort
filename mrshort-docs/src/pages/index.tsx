import clsx from 'clsx';
import Link from '@docusaurus/Link';
import useDocusaurusContext from '@docusaurus/useDocusaurusContext';
import Layout from '@theme/Layout';
import Heading from '@theme/Heading';

import styles from './index.module.css';

function HeroBanner() {
    const { siteConfig } = useDocusaurusContext();
    return (
        <header className={styles.heroBanner}>
            <div className="container">
                <Heading as="h1" className={styles.heroTitle}>
                    MRShort Documentation
                </Heading>
                <p className={styles.heroSubtitle}>
                    Learn how to shorten links, earn money, and maximize your earnings with our comprehensive guides.
                </p>
                <div className={styles.heroButtons}>
                    <Link className="button button--primary button--lg" to="/docs/getting-started">
                        🚀 Get Started
                    </Link>
                    <Link className="button button--secondary button--lg" to="/docs/faq">
                        ❓ FAQ
                    </Link>
                </div>
            </div>
        </header>
    );
}

type FeatureCardProps = {
    title: string;
    emoji: string;
    description: string;
    links: { label: string; to: string }[];
};

function FeatureCard({ title, emoji, description, links }: FeatureCardProps) {
    return (
        <div className={styles.featureCard}>
            <div className={styles.featureHeader}>
                <span className={styles.featureEmoji}>{emoji}</span>
                <h3>{title}</h3>
            </div>
            <p className={styles.featureDescription}>{description}</p>
            <ul className={styles.featureLinks}>
                {links.map((link, idx) => (
                    <li key={idx}>
                        <Link to={link.to}>{link.label}</Link>
                    </li>
                ))}
            </ul>
        </div>
    );
}

const FeatureList: FeatureCardProps[] = [
    {
        title: 'Getting Started',
        emoji: '🚀',
        description: 'New to MRShort? Start here to learn the basics of link shortening and earning.',
        links: [
            { label: 'Create Your First Link', to: '/docs/getting-started' },
            { label: 'Understanding the Dashboard', to: '/docs/dashboard' },
            { label: 'Quick Start Guide', to: '/docs/quick-start' },
        ],
    },
    {
        title: 'Monetization',
        emoji: '💰',
        description: 'Learn how to maximize your earnings and withdraw your balance.',
        links: [
            { label: 'CPM Rates Explained', to: '/docs/earnings' },
            { label: 'Withdrawal Methods', to: '/docs/withdrawals' },
            { label: 'Referral Program', to: '/docs/referrals' },
        ],
    },
    {
        title: 'Features & Tools',
        emoji: '🛠️',
        description: 'Discover all the powerful features MRShort offers.',
        links: [
            { label: 'Mass Link Shortener', to: '/docs/tools/mass-shortener' },
            { label: 'API Integration', to: '/docs/api' },
            { label: 'Analytics & Reports', to: '/docs/analytics' },
        ],
    },
    {
        title: 'Gamification',
        emoji: '🎮',
        description: 'Earn extra rewards through our gamification features.',
        links: [
            { label: 'Daily Spin & Rewards', to: '/docs/gamification' },
            { label: 'Achievements System', to: '/docs/gamification/achievements' },
            { label: 'VIP Levels', to: '/docs/gamification/vip' },
        ],
    },
    {
        title: 'Account & Security',
        emoji: '🔐',
        description: 'Manage your account settings and keep your account secure.',
        links: [
            { label: 'Account Settings', to: '/docs/account/settings' },
            { label: 'Two-Factor Authentication', to: '/docs/account/2fa' },
            { label: 'Payment Settings', to: '/docs/account/payments' },
        ],
    },
    {
        title: 'Advertising',
        emoji: '📢',
        description: 'Promote your content with MRShort advertising campaigns.',
        links: [
            { label: 'Create a Campaign', to: '/docs/advertising/create-campaign' },
            { label: 'Targeting Options', to: '/docs/advertising/targeting' },
            { label: 'Campaign Analytics', to: '/docs/advertising/analytics' },
        ],
    },
];

function FeaturesSection() {
    return (
        <section className={styles.featuresSection}>
            <div className="container">
                <div className={styles.featuresGrid}>
                    {FeatureList.map((props, idx) => (
                        <FeatureCard key={idx} {...props} />
                    ))}
                </div>
            </div>
        </section>
    );
}

function QuickLinksSection() {
    return (
        <section className={styles.quickLinksSection}>
            <div className="container">
                <Heading as="h2" className={styles.sectionTitle}>
                    Quick Resources
                </Heading>
                <div className={styles.quickLinksGrid}>
                    <Link className={styles.quickLink} to="/docs/faq">
                        <span className={styles.quickLinkIcon}>❓</span>
                        <div>
                            <strong>FAQ</strong>
                            <p>Common questions answered</p>
                        </div>
                    </Link>
                    <Link className={styles.quickLink} to="/docs/troubleshooting">
                        <span className={styles.quickLinkIcon}>🔧</span>
                        <div>
                            <strong>Troubleshooting</strong>
                            <p>Solve common issues</p>
                        </div>
                    </Link>
                    <a className={styles.quickLink} href="https://mrshort.io/user/contact" target="_blank">
                        <span className={styles.quickLinkIcon}>📧</span>
                        <div>
                            <strong>Contact Support</strong>
                            <p>Get help from our team</p>
                        </div>
                    </a>
                    <a className={styles.quickLink} href="https://mrshort.io/feedback" target="_blank">
                        <span className={styles.quickLinkIcon}>💡</span>
                        <div>
                            <strong>Feature Requests</strong>
                            <p>Suggest new features</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    );
}

export default function Home(): JSX.Element {
    const { siteConfig } = useDocusaurusContext();
    return (
        <Layout
            title="Home"
            description="MRShort Help Center - Learn how to shorten links and earn money">
            <HeroBanner />
            <main>
                <FeaturesSection />
                <QuickLinksSection />
            </main>
        </Layout>
    );
}
