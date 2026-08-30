<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Official Alumni ID Card</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .id-card {
            width: 540px;
            background: #ffffff;
            border: 2px solid #9ca3af;
            margin: 0 auto;
        }
        .header {
            background-color: #2e53a3;
            color: #ffffff;
            text-align: center;
            padding: 12px 15px;
            border-bottom: 2px solid #f97316;
        }
        .header h1 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 11px;
            font-weight: bold;
            color: #e5e7eb;
        }
        .banner {
            background-color: #ffffff;
            color: #2e53a3;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            padding: 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            margin-top: 8px;
        }
        .body-container {
            width: 100%;
            border-collapse: collapse;
            padding: 0;
        }
        .body-container td {
            vertical-align: top;
            padding: 15px;
        }
        .photo-box {
            width: 120px;
            height: 150px;
            border: 2px solid #9ca3af;
            background-color: #f3f4f6;
            text-align: center;
            vertical-align: middle;
        }
        .photo-box img {
            width: 120px;
            height: 150px;
            object-fit: cover;
        }
        .initials {
            font-size: 32px;
            font-weight: bold;
            color: #2e53a3;
            line-height: 150px;
            text-transform: uppercase;
        }
        .details-area {
            padding-left: 15px;
        }
        .name-heading {
            font-size: 16px;
            font-weight: 900;
            color: #000000;
            text-transform: uppercase;
            margin: 0 0 2px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #000000;
        }
        .membership-text {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            margin: 4px 0 10px 0;
        }
        .field-row {
            font-size: 11px;
            margin-bottom: 5px;
        }
        .field-label {
            width: 85px;
            font-weight: bold;
            color: #000000;
            display: inline-block;
        }
        .field-val {
            font-weight: bold;
            color: #2e53a3;
        }
        .field-val-normal {
            font-weight: normal;
            color: #111827;
        }
        .card-footer {
            background-color: #ffffff;
            border-top: 2px solid #d1d5db;
            padding: 10px 15px;
            width: 100%;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: bottom;
            padding: 0;
        }
        .member-since {
            font-size: 10px;
            font-weight: bold;
            color: #000000;
        }
    </style>
</head>
<body>

    <div class="id-card">
        <!-- HEADER -->
        <div class="header">
            <h1>ALUMNI ASSOCIATION OF K. D. POLYTECHNIC</h1>
            <p>PATAN, GUJARAT - 384265</p>
            <div class="banner">
                ALUMNI MEMBERSHIP CARD
            </div>
        </div>

        <!-- BODY CONTENT -->
        <table class="body-container">
            <tr>
                <td style="width: 130px; text-align: center;">
                    <div class="photo-box">
                        @php
                            $photoPath = $user->photo_path ?? ($user->profile->photo_path ?? null);
                        @endphp
                        @if($photoPath)
                            <img src="{{ filter_var($photoPath, FILTER_VALIDATE_URL) ? $photoPath : public_path('storage/' . $photoPath) }}" alt="Profile Photo">
                        @else
                            <div class="initials">
                                {{ collect(explode(' ', $user->name))->map(fn($seg) => mb_substr($seg, 0, 1))->join('') }}
                            </div>
                        @endif
                    </div>
                </td>
                <td class="details-area">
                    <h2 class="name-heading">{{ $user->name }}</h2>
                    <p class="membership-text">Lifetime Member</p>

                    <div class="field-row">
                        <span class="field-label">Alumni ID</span>
                        <span class="field-val">: KDP-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Department</span>
                        <span class="field-val-normal">: {{ $user->profile->department ?? $user->department ?? 'Computer Engineering' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Contact No.</span>
                        <span class="field-val-normal">: {{ $user->profile->phone ?? $user->phone ?? '9265105831' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Blood Grp.</span>
                        <span class="field-val-normal">: {{ $user->blood_group ?? 'N/A' }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <!-- CARD FOOTER -->
        <div class="card-footer">
            <table class="footer-table">
                <tr>
                    <td style="vertical-align: bottom;">
                        <span class="member-since">Member Since : {{ $user->created_at->format('d/m/Y') }}</span>
                    </td>
                    <!-- SIGNATURE BLOCK - PERFECTLY ALIGNED -->
                    <td style="width: 170px; vertical-align: bottom; padding: 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="text-align: right; padding: 0 0 1px 0; line-height: 1;">
                                    <div style="font-family: 'Times New Roman', Times, serif; font-style: italic; font-size: 18px; font-weight: bold; color: #1e3a8a;">
                                        A. S. Patel
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-top: 1px solid #000000; text-align: right; padding: 3px 0 0 0; line-height: 1;">
                                    <div style="font-size: 10px; font-weight: 900; text-transform: uppercase; color: #000000;">
                                        PRESIDENT
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>