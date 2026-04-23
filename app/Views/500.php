<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Lỗi máy chủ</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body>
    <div style="display:flex;align-items:center;justify-content:center;padding:60px 16px;">
        <div style="max-width:720px;width:100%;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(18,22,26,0.08);padding:36px;text-align:center;">
            <div style="font-size:54px;line-height:1;margin-bottom:8px;">⚠️</div>
            <h1 style="margin:4px 0 12px;font-size:28px;">500 — Lỗi máy chủ</h1>
            <p style="color:#555;margin:0 0 20px;"><?= htmlspecialchars($message ?? 'Đã xảy ra lỗi bên trong hệ thống. Vui lòng thử lại sau.') ?></p>

            <div style="display:flex;gap:12px;justify-content:center;margin-top:18px;">
                <div style="display:flex;gap:12px;justify-content:center;margin-top:18px;">
                    <button id="btnBackHome" class="btn">
                        Trang chủ
                    </button>
                    <button id="btnBack" class="btn btn-primary">
                        Quay lại
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#btnBackHome').click(function() {
                window.location.href = '/';
            });

            $('#btnBack').click(function() {
                history.back();
            });
        });
    </script>
</body>

</html>