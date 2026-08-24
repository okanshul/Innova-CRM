<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
    <style>
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6fb;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f6fb;
            padding: 40px 15px;
            box-sizing: border-box;
        }
        .email-container {
            max-width: 540px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #06060f 0%, #1e1b4b 100%);
            padding: 32px 30px;
            text-align: center;
        }
        .brand-logo {
            max-height: 48px;
            max-width: 200px;
            object-fit: contain;
        }
        .brand-text {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .email-body {
            padding: 36px 32px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 15px;
            color: #475569;
            margin-bottom: 28px;
        }
        .otp-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px dashed #6366f1;
            border-radius: 12px;
            padding: 24px 20px;
            text-align: center;
            margin-bottom: 28px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #4f46e5;
            margin: 8px 0;
            display: inline-block;
        }
        .otp-expiry {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }
        .notice-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #92400e;
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
        .email-footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                @if ($systemLogo)
                    <img src="{{ asset($systemLogo) }}" alt="{{ $appName }}" class="brand-logo">
                @else
                    <h1 class="brand-text">{{ $appName }}</h1>
                @endif
            </div>

            <div class="email-body">
                <h2 class="greeting">Password Reset Request</h2>
                <p class="intro-text">
                    We received a request to reset the password for your account on <strong>{{ $appName }}</strong>. Use the 6-digit verification code below to complete your password reset:
                </p>

                <div class="otp-card">
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="otp-expiry">This code will expire in <strong>{{ $expiresInMinutes }} minutes</strong>.</div>
                </div>

                <div class="notice-box">
                    <strong>Security Notice:</strong> If you did not request a password reset, please ignore this email or contact system administration if you have concerns. Never share this code with anyone.
                </div>
            </div>

            <div class="email-footer">
                <p>&copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
                <p>Automated security notification from {{ $appName }} system.</p>
            </div>
        </div>
    </div>
</body>
</html>
