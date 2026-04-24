<?php
$currentPath = strtok($_SERVER['REQUEST_URI'], '?');

function menuIsActive(string $href, string $currentPath): bool
{
    if ($href === '/') return $currentPath === '/';
    return str_starts_with($currentPath, $href);
}

$menu = [
    [
        'label' => 'Hồ sơ',
        'href'  => '/profile',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="6" r="4"/><path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z"/></svg>',
    ],
];
?>

<div class="sidebar">
    <div class="sidebar__avatar">
        <img src="<?= htmlspecialchars(($_SESSION['avatar'] ?? '') ?: '/assets/image/default_avatar.jpg') ?>" alt="Avatar">
    </div>

    <nav class="sidebar__menu">
        <?php foreach ($menu as $item):
            $active = menuIsActive($item['href'], $currentPath) ? ' active' : '';
        ?>
            <a href="<?= htmlspecialchars($item['href']) ?>" class="menu__item<?= $active ?>">
                <span class="menu__icon"><?= $item['icon'] ?></span>
                <span class="menu__label"><?= htmlspecialchars($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div id="btnOpenModalLogout" class="sidebar__logout">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
        <span>Đăng xuất</span>
    </div>
</div>

<div id="modalConfirmLogout" class="modal hidden">
    <div class="modal__overlay" data-close="true"></div>
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
