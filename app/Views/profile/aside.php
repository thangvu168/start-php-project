<?php
$name     = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$email    = $user['email'] ?? '';
$username = $user['username'] ?? '';

$sections = [
    [
        'title' => 'Thông tin tài khoản',
        'items' => [
            [
                'label' => 'Tài khoản',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>',
            ],
            [
                'label' => 'Chỉnh sửa',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
            ],
            [
                'label'    => 'Ngôn ngữ',
                'path'     => '#',
                'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20A14.5 14.5 0 0 0 12 2"/><path d="M2 12h20"/></svg>',
                'disabled' => true,
            ],
            [
                'label' => 'Đổi mật khẩu',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
            ],
            [
                'label' => 'Đổi màu hiển thị',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r="2.5"/><circle cx="19" cy="17" r="2.5"/><circle cx="6.5" cy="17" r="2.5"/><path d="M13.5 9C13.5 9 13 15 6.5 14.5M13.5 9C13.5 9 15 14.5 19 14.5"/></svg>',
            ],
            [
                'label' => 'Lịch sử làm việc',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            ],
            [
                'label' => 'Bảo mật hai lớp',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                'disabled' => true,
            ],
        ],
    ],
    [
        'title' => 'Ứng dụng - Bảo mật',
        'items' => [
            [
                'label' => 'Quản lý thiết bị',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
            ],
        ],
    ],
    [
        'title' => 'Tùy chỉnh nâng cao',
        'items' => [
            [
                'label' => 'Lịch sử đăng nhập',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>',
            ],
            [
                'label'    => 'Tùy chỉnh email thông báo',
                'path'     => '#',
                'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
                'disabled' => true,
            ],
            [
                'label' => 'Chỉnh sửa múi giờ',
                'path'  => '#',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            ],
            [
                'label'    => 'Ủy quyền tạm thời',
                'path'     => '#',
                'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>',
                'disabled' => true,
            ],
        ],
    ],
];
?>

<div class="profile-aside-header">
    <span class="profile-aside-name"><?= htmlspecialchars($name) ?></span>
    <div class="profile-aside-meta">
        <span>@<?= htmlspecialchars($username) ?></span>
        <span class="profile-aside-dot"></span>
        <span><?= htmlspecialchars($email) ?></span>
    </div>
</div>

<nav class="menu" id="profileMenu" data-menu-init>
    <?php foreach ($sections as $section): ?>
        <div class="menu__section">
            <div class="menu__section-title"><?= htmlspecialchars($section['title']) ?></div>
            <ul class="menu__list">
                <?php foreach ($section['items'] as $item): ?>
                    <li class="menu__item">
                        <a class="menu__link" href="<?= htmlspecialchars($item['path']) ?>" <?= !empty($item['disabled']) ? ' disabled' : '' ?>>
                            <span class="menu__icon"><?= $item['icon'] ?></span>
                            <span class="menu__label"><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</nav>
