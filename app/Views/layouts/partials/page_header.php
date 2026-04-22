<?php
if (!isset($GLOBALS['show_page_header']) || $GLOBALS['show_page_header'] === false) {
    return;
}
?>

<div class="page__header">
    <?php if (!empty($GLOBALS['back_url'])): ?>
        <a href="<?= htmlspecialchars($GLOBALS['back_url']) ?>" class="page__back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
        </a>
    <?php endif; ?>

    <h1 class="page__title"><?= htmlspecialchars($GLOBALS['page_title'] ?? 'Page') ?></h1>

    <div class="page__buttons">
        <?php if (!empty($GLOBALS['buttons'])): ?>
            <?php foreach ($GLOBALS['buttons'] as $button): ?>
                <button
                    class="btn <?= htmlspecialchars($button['class'] ?? '') ?>"
                    id="<?= htmlspecialchars($button['id'] ?? '') ?>"
                    onclick="<?= htmlspecialchars($button['onclick'] ?? '') ?>">
                    <?= htmlspecialchars($button['text'] ?? '') ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>