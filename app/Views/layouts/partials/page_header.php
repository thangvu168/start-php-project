<?php if (empty($page_header)): ?>
<?php return; ?>
<?php endif; ?>

<div class="page__header">
    <?php if (!empty($page_header['back_url'])): ?>
        <a href="<?= htmlspecialchars($page_header['back_url']) ?>" class="page__back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
        </a>
    <?php endif; ?>

    <h1 class="page__title"><?= htmlspecialchars($page_header['title'] ?? 'Page') ?></h1>

    <div class="page__buttons">
        <?php foreach ($page_header['buttons'] ?? [] as $button): ?>
            <button
                class="btn <?= htmlspecialchars($button['class'] ?? '') ?>"
                id="<?= htmlspecialchars($button['id'] ?? '') ?>">
                <?= htmlspecialchars($button['text'] ?? '') ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>