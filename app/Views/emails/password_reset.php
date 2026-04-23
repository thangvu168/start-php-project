<div style="font-family: Arial, sans-serif; font-size:14px; color:#111;">
    <p>Xin chào,</p>
    <p>Bạn (hoặc ai đó) đã yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Nhấp vào liên kết bên dưới để đặt lại mật khẩu. Liên kết có hiệu lực trong 1 giờ.</p>
    <p><a href="<?= htmlspecialchars($link) ?>">Đặt lại mật khẩu</a></p>
    <p>Nếu bạn không yêu cầu điều này, hãy bỏ qua email này.</p>
    <p>Trân trọng,<br><?= config('mail.from_name') && htmlspecialchars(config('mail.from_name')) ?></p>
</div>