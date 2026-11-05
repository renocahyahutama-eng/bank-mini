<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 30px 15px;
            color: #1e293b;
        }
        .container {
            max-width: 540px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 28px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 8px 0 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .body {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #0f172a;
        }
        .text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .otp-box {
            background: #eff6ff;
            border: 2px dashed #3b82f6;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 24px;
        }
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            color: #1e40af;
            margin-bottom: 8px;
        }
        .otp-code {
            font-size: 34px;
            font-weight: 800;
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 8px;
            color: #1d4ed8;
        }
        .otp-expiry {
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
        }
        .alert-warning {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 12.5px;
            color: #92400e;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 28px;">🏦</div>
            <h1>Bank Mini SMKN 1 Bantul</h1>
            <p>Layanan Perbankan Siswa Berbasis Digital</p>
        </div>
        <div class="body">
            <div class="greeting">Halo, {{ $nasabah->student_name }}!</div>
            <div class="text">
                Kami menerima permintaan reset kata sandi (password) untuk akun E-Banking Anda dengan Nomor Rekening <strong>{{ $nasabah->account_number }}</strong>.
                Gunakan kode One-Time Password (OTP) di bawah ini untuk melanjutkan:
            </div>

            <div class="otp-box">
                <div class="otp-label">Kode Verifikasi OTP Anda</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">⏱ Berlaku selama 10 menit</div>
            </div>

            <div class="alert-warning">
                <strong>Penting:</strong> Jangan berikan kode OTP ini kepada siapa pun, termasuk petugas Bank Mini sekolah. Petugas tidak pernah meminta kode OTP nasabah.
            </div>

            <div class="text" style="font-size: 13px; color: #64748b; margin-bottom: 0;">
                Jika Anda tidak merasa meminta reset password ini, abaikan email ini. Akun Anda tetap aman.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bank Mini SMKN 1 Bantul &bull; Jl. Sabdodadi, Bantul, D.I. Yogyakarta<br>
            Email otomatis, mohon tidak membalas langsung ke alamat ini.
        </div>
    </div>
</body>
</html>

