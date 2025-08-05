<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['subject'] }}</title>
    <style type="text/css">
        :root {
            --primary-color: #7910a9;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #374151;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: var(--primary-color);
            padding: 30px;
            text-align: center;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            color: white;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .logo-text {
            font-weight: 600;
            font-size: 1.4rem;
        }

        .content {
            padding: 40px 30px;
        }

        h1 {
            color: #1f2937;
            margin: 0 0 25px 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .message {
            background-color: var(--light-bg);
            padding: 20px;
            border-radius: 6px;
            border-left: 3px solid var(--primary-color);
            margin: 20px 0;
        }

        .cta-button {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin: 20px 0;
            transition: background-color 0.2s;
        }

        .cta-button:hover {
            background-color: #1d4ed8;
        }

        .footer {
            background-color: var(--light-bg);
            padding: 20px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .company-info {
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .company-name {
            font-weight: 600;
            color: #1f2937;
        }

        .unsubscribe {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            font-size: 0.8rem;
            color: #6b7280;
        }

        .unsubscribe a {
            color: var(--primary-color);
            text-decoration: none;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .content,
            .header,
            .footer {
                padding: 150px;
            }
        }
    </style>
</head>
@php
    $setting = get_setting();
@endphp

<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <div class="logo-icon">
                    <img src="{{ my_asset($setting->logo) }}" height="25" alt="Logo">
                </div>
                <div class="logo-text">{{ $setting->name }}</div>
            </div>
        </div>

        <div class="content">
            <h1>{{ $data['subject'] }}</h1>

            @if (isset($data['name']) && $data['name'] != '')
                <h6>Hi {{ $data['name'] ?? '' }},</h6>
            @endif
            <div class="message">
                {!! $data['message'] !!}
            </div>

            @if (isset($data['link']) && isset($data['link_text']))
                <div style="text-align: center;">
                    <a href="{{ $data['link'] }}" class="cta-button">{{ $data['link_text'] }}</a>
                </div>
            @endif

            <p>Best regards,<br>
                <strong>The {{ $setting->name }} Team</strong>
            </p>
        </div>

        <div class="footer">
            <div class="company-info company-name">{{ $setting->name }}</div>
            <div class="company-info">{{ $setting->phone }} |
                <a href="mailto:{{ $setting->support_email }}"
                    style="color: #6b7280;">{{ $setting->support_email }}</a>
            </div>
            <div class="company-info">© {{ date('Y') }} {{ $setting->title }}. All rights reserved.</div>

            <div class="unsubscribe">
                You're receiving this because you're a user at {{ $setting->name }}.<br>
                <a href="#">Unsubscribe</a> | <a href="#">Privacy Policy</a>
            </div>
        </div>
    </div>
</body>

</html>
