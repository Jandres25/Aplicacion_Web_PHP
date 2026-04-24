<?php
$pageHeaderTitle = isset($pageHeaderTitle) ? trim((string)$pageHeaderTitle) : '';
if ($pageHeaderTitle === '') {
    return;
}

$pageHeaderIcon = isset($pageHeaderIcon) && trim((string)$pageHeaderIcon) !== ''
    ? trim((string)$pageHeaderIcon)
    : 'fas fa-layer-group';

$pageBreadcrumbs = (isset($pageBreadcrumbs) && is_array($pageBreadcrumbs)) ? $pageBreadcrumbs : [];
?>

<section class="module-page-header mt-4 mb-3" aria-labelledby="module-page-title">
    <div class="row module-page-header__inner align-items-center g-2">
        <div class="col-12 col-md-6 module-page-header__title-wrap">
            <h1 class="module-page-header__title mb-0" id="module-page-title">
                <i class="<?= htmlspecialchars($pageHeaderIcon, ENT_QUOTES, 'UTF-8'); ?> me-2" aria-hidden="true"></i>
                <?= htmlspecialchars($pageHeaderTitle, ENT_QUOTES, 'UTF-8'); ?>
            </h1>
        </div>

        <?php if (!empty($pageBreadcrumbs)) : ?>
            <nav class="col-12 col-md-6 module-page-header__breadcrumb-nav" aria-label="breadcrumb">
                <ol class="breadcrumb module-page-header__breadcrumb mb-0 justify-content-md-end">
                    <?php foreach ($pageBreadcrumbs as $item) : ?>
                        <?php
                        $label = isset($item['label']) ? trim((string)$item['label']) : '';
                        if ($label === '') {
                            continue;
                        }

                        $href = isset($item['href']) ? trim((string)$item['href']) : '';
                        $icon = isset($item['icon']) ? trim((string)$item['icon']) : '';
                        $isActive = !empty($item['active']) || $href === '';
                        ?>
                        <li class="breadcrumb-item<?= $isActive ? ' active' : ''; ?>"<?= $isActive ? ' aria-current="page"' : ''; ?>>
                            <?php if (!$isActive) : ?>
                                <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php if ($icon !== '') : ?>
                                        <i class="<?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?> me-1" aria-hidden="true"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            <?php else : ?>
                                <?php if ($icon !== '') : ?>
                                    <i class="<?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?> me-1" aria-hidden="true"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        <?php endif; ?>
    </div>
</section>
