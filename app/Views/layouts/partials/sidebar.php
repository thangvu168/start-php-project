<div class="sidebar">
    <div class="sidebar__avatar">
        <img src="<?= htmlspecialchars($_SESSION['avatar'] ?? '/assets/image/default_avatar.webp') ?>" alt="Avatar">
    </div>

    <!-- Menu -->
    <div class="sidebar__menu">

        <a href="/" class="sidebar__item">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                        <path d="M6.133 21C4.955 21 4 20.02 4 18.81v-8.802c0-.665.295-1.295.8-1.71l5.867-4.818a2.09 2.09 0 0 1 2.666 0l5.866 4.818c.506.415.801 1.045.801 1.71v8.802c0 1.21-.955 2.19-2.133 2.19z" />
                        <path d="M9.5 21v-5.5a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2V21" />
                    </g>
                </svg>
            </div>
            <div class="text">Trang chủ</div>
        </a>

        <a href="/profile" class="sidebar__item">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="6" r="4" />
                        <path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" />
                    </g>
                </svg>
            </div>
            <div class="text">Hồ sơ</div>
        </a>
    </div>

    <!-- Logout -->
    <div id="btnOpenModalLogout" class="sidebar__logout" id="sidebar__logout">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="currentColor" d="m16.56 5.44l-1.45 1.45A5.97 5.97 0 0 1 18 12a6 6 0 0 1-6 6a6 6 0 0 1-6-6c0-2.17 1.16-4.06 2.88-5.12L7.44 5.44A7.96 7.96 0 0 0 4 12a8 8 0 0 0 8 8a8 8 0 0 0 8-8c0-2.72-1.36-5.12-3.44-6.56M13 3h-2v10h2" />
        </svg>
        Đăng xuất
    </div>
</div>

<div id="modalConfirmLogout" class="modal hidden">
    <div class="modal__overlay" data-close=true></div>

    <div class="modal__content">
        <div class="modal__header">
            <strong class="modal__title">Xác nhận đăng xuất</strong>
            <button type="button" class="modal__close" data-close="true">&times;</button>
        </div>
        <div class="modal__body">
            <p>Bạn có chắc chắn muốn đăng xuất không?</p>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn" data-close="true">Đóng</button>
            <button type="button" class="btn btn-primary" id="btnConfirmLogout">Đăng xuất</button>
        </div>
    </div>
</div>