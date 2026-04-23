<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Trang không tìm thấy</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body>
    <div style="display:flex;align-items:center;justify-content:center;padding:60px 16px;">
        <div style="max-width:600px;width:100%;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(18,22,26,0.08);padding:36px;text-align:center;">
            <div style="font-size:54px;line-height:1;margin-bottom:8px;">🚫</div>
            <h1 style="margin:4px 0 12px;font-size:28px;">404 — Trang không tìm thấy</h1>
            <p style="color:#555;margin:0 0 20px;"><?= htmlspecialchars($message ?? 'Trang bạn yêu cầu không tồn tại hoặc đã bị di chuyển.') ?></p>

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