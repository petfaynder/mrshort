<style>
    .info-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        width: 100%;
        max-width: 1000px;
        margin-top: 30px;
    }
    .info-card {
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 30px rgba(0,0,0,0.1);
    }
    .info-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .info-card .icon.red { background-color: #FEF2F2; color: #EF4444; }
    .info-card .icon.blue { background-color: #EFF6FF; color: #3B82F6; }
    .info-card .icon.green { background-color: #F0FDF4; color: #22C55E; }
    .info-card h3 {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 10px 0;
    }
    .info-card p {
        font-size: 14px;
        color: var(--subtle-text);
        line-height: 1.6;
        margin: 0;
    }
    .cta-section {
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 30px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1000px;
        margin-top: 30px;
    }
    .cta-section h2 {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }
    .cta-section p {
        font-size: 16px;
        color: var(--subtle-text);
        margin: 5px 0 0 0;
    }
    .cta-section .cta-button {
        background-color: var(--primary-color);
        color: #fff;
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .cta-section .cta-button:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
    }
</style>

<div class="info-section">
    <div class="info-card">
        <div class="icon red"><i data-feather="user-plus"></i></div>
        <h3>Create an account</h3>
        <p>Creating an account would not take you more than 3 minutes. You only need to provide your email, username and a password.</p>
    </div>
    <div class="info-card">
        <div class="icon blue"><i data-feather="link"></i></div>
        <h3>Shorten and share links</h3>
        <p>After you create an account, you can use one of our powerful tools to shorten links that you want to share.</p>
    </div>
    <div class="info-card">
        <div class="icon green"><i data-feather="dollar-sign"></i></div>
        <h3>Earn money</h3>
        <p>Once you share the links with potential visitors, you get paid for each visit to your links based on our payout rates.</p>
    </div>
</div>

<div class="cta-section">
    <div>
        <h2>Ready to start earning with {{ config('app.name') }}?</h2>
        <p>Register your account and start the journey. It is 100% free!</p>
    </div>
    <a href="{{ route('register') }}" class="cta-button">Sign up</a>
</div>
