<style>
    :root { color-scheme: light; }
    * { box-sizing: border-box; }
    body {
        align-items: center;
        background: radial-gradient(circle at top left, #e8efff 0, #f5f7fb 45%, #edf4ff 100%);
        color: #172033;
        display: flex;
        font-family: Inter, "Segoe UI", sans-serif;
        justify-content: center;
        margin: 0;
        min-height: 100vh;
        padding: 24px;
    }
    .auth-card {
        background: #fff;
        border: 1px solid #e4e9f2;
        border-radius: 18px;
        box-shadow: 0 24px 70px rgba(28, 44, 82, .12);
        max-width: 460px;
        padding: 34px;
        width: 100%;
    }
    .auth-brand { align-items: center; display: flex; gap: 12px; margin-bottom: 30px; }
    .auth-brand img { height: 44px; object-fit: contain; width: 44px; }
    .auth-brand strong { display: block; font-size: 15px; }
    .auth-brand span { color: #667085; display: block; font-size: 12px; margin-top: 3px; }
    .auth-card h1 { font-size: 24px; letter-spacing: -.4px; margin: 0 0 8px; }
    .auth-subtitle { color: #667085; font-size: 14px; line-height: 1.6; margin: 0 0 24px; }
    .auth-field { margin-bottom: 16px; }
    .auth-field label { display: block; font-size: 13px; font-weight: 650; margin-bottom: 7px; }
    .auth-field input, .auth-field select {
        background: #fff; border: 1px solid #d6dce8; border-radius: 9px; color: #172033;
        font: inherit; font-size: 14px; min-height: 44px; padding: 10px 12px; width: 100%;
    }
    .auth-field input:focus, .auth-field select:focus { border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67, 97, 238, .12); outline: 0; }
    .auth-button {
        background: #4361ee; border: 0; border-radius: 9px; color: #fff; cursor: pointer;
        font: inherit; font-size: 14px; font-weight: 700; margin-top: 8px; min-height: 46px; width: 100%;
    }
    .auth-button:hover { background: #3450d4; }
    .auth-link { color: #4361ee; font-size: 13px; font-weight: 650; text-decoration: none; }
    .auth-footer { color: #667085; font-size: 13px; margin: 22px 0 0; text-align: center; }
    .auth-alert { border-radius: 9px; font-size: 13px; line-height: 1.5; margin: 0 0 16px; padding: 11px 13px; }
    .auth-alert.error { background: #fff1f0; border: 1px solid #ffc9c3; color: #a4261d; }
    .auth-alert.success { background: #edfbf4; border: 1px solid #b7e7ca; color: #176b3a; }
    .auth-alert ul { margin: 0; padding-left: 18px; }
    @media (max-width: 520px) { .auth-card { padding: 26px 22px; } }
</style>
