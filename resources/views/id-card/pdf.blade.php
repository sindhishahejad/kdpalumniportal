<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Official Alumni Smart ID Card</title>
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
            background-color: #1C3661;
            color: #ffffff;
            text-align: center;
            padding: 12px 15px;
            border-bottom: 2px solid #f59e0b;
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
            background-color: #8b0000;
            color: #ffffff;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }
        .body-container {
            width: 100%;
            border-collapse: collapse;
            padding: 0;
        }
        .body-container td {
            vertical-align: top;
            padding: 12px;
        }
        .photo-box {
            width: 110px;
            height: 140px;
            border: 2px solid #9ca3af;
            background-color: #f3f4f6;
            text-align: center;
            vertical-align: middle;
        }
        .photo-box img {
            width: 110px;
            height: 140px;
            object-fit: cover;
        }
        .initials {
            font-size: 32px;
            font-weight: bold;
            color: #1C3661;
            line-height: 140px;
            text-transform: uppercase;
        }
        .details-area {
            padding-left: 10px;
        }
        .name-heading {
            font-size: 15px;
            font-weight: 900;
            color: #000000;
            text-transform: uppercase;
            margin: 0 0 2px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #000000;
        }
        .membership-text {
            font-size: 10px;
            font-weight: bold;
            color: #047857;
            margin: 3px 0 8px 0;
        }
        .field-row {
            font-size: 11px;
            margin-bottom: 4px;
        }
        .field-label {
            width: 75px;
            font-weight: bold;
            color: #4b5563;
            display: inline-block;
            font-size: 10px;
            text-transform: uppercase;
        }
        .field-val {
            font-weight: bold;
            color: #1C3661;
        }
        .field-val-normal {
            font-weight: bold;
            color: #111827;
        }
        .qr-box {
            width: 90px;
            text-align: center;
            vertical-align: middle;
            border-left: 1px dashed #d1d5db;
            padding: 10px;
        }
        .card-footer {
            background-color: #f9fafb;
            border-top: 1px solid #d1d5db;
            padding: 8px 15px;
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
            font-size: 9px;
            font-weight: bold;
            color: #4b5563;
        }
    </style>
</head>
<body>

    @php
        $hasGd = extension_loaded('gd');
    @endphp

    <div class="id-card">
        <!-- HEADER -->
        <div class="header">
            <h1>ALUMNI ASSOCIATION OF K. D. POLYTECHNIC</h1>
            <p>PATAN, GUJARAT - 384265</p>
            <div class="banner">
                OFFICIAL SMART ALUMNI CARD
            </div>
        </div>

        <!-- BODY CONTENT -->
        <table class="body-container">
            <tr>
                <!-- Photo Column -->
                <td style="width: 115px; text-align: center;">
                    <div class="photo-box">
                        @php
                            $photoPath = $user->photo_path ?? ($user->profile->photo_path ?? null);
                            $photoFile = $photoPath ? public_path('storage/' . $photoPath) : null;
                        @endphp
                        @if($hasGd && $photoFile && file_exists($photoFile))
                            <img src="{{ $photoFile }}" alt="Profile Photo">
                        @else
                            <div class="initials">
                                {{ collect(explode(' ', $user->name))->map(fn($seg) => mb_substr($seg, 0, 1))->join('') }}
                            </div>
                        @endif
                    </div>
                </td>

                <!-- Details Column -->
                <td class="details-area">
                    <h2 class="name-heading">{{ $user->name }}</h2>
                    <p class="membership-text">• Lifetime Member (Verified)</p>

                    <div class="field-row">
                        <span class="field-label">Alumni ID</span>
                        <span class="field-val">: KDP-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    @if($user->entry_no)
                        <div class="field-row">
                            <span class="field-label">Roll/Entry</span>
                            <span class="field-val-normal">: {{ $user->entry_no }}</span>
                        </div>
                    @endif

                    <div class="field-row">
                        <span class="field-label">Dept</span>
                        <span class="field-val-normal">: {{ $user->department ?? optional($user->profile)->department ?? 'Computer Engineering' }}</span>
                    </div>

                    @php
                        $gradYear = $user->graduation_year ?? optional($user->profile)->graduation_year;
                    @endphp
                    @if($gradYear)
                        <div class="field-row">
                            <span class="field-label">Class of</span>
                            <span class="field-val-normal">: {{ $gradYear }}</span>
                        </div>
                    @endif

                    @if($user->blood_group)
                        <div class="field-row">
                            <span class="field-label">Blood Grp</span>
                            <span class="field-val-normal" style="color: #b91c1c;">: {{ $user->blood_group }}</span>
                        </div>
                    @endif
                </td>

                <!-- QR Code Column -->
                <td class="qr-box">
                    @if(!empty($qrBase64) && $hasGd)
                        <img src="{{ $qrBase64 }}" style="width: 80px; height: 80px; display: block; margin: 0 auto;">
                    @else
                        <div style="width: 80px; height: 80px; background: #e5e7eb; border: 1px solid #9ca3af; margin: 0 auto; line-height: 80px; font-size: 8px; font-weight: bold; color: #4b5563;">
                            VERIFIED QR
                        </div>
                    @endif
                    <div style="font-size: 8px; font-weight: bold; color: #047857; text-transform: uppercase; margin-top: 4px;">
                        Scan to Verify
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
                    <td style="width: 160px; vertical-align: bottom; text-align: right;">
                        <div style="font-family: 'Times New Roman', Times, serif; font-style: italic; font-size: 15px; font-weight: bold; color: #1C3661;">
                            Principal / President
                        </div>
                        <div style="border-top: 1px solid #000000; font-size: 9px; font-weight: bold; text-transform: uppercase; color: #6b7280; margin-top: 2px;">
                            K.D. POLYTECHNIC, PATAN
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
