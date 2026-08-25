<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? 'PHP MVC' ?> &mdash; PHP MVC Docs</title>
<link rel="stylesheet" href="<?php echo '/'.basename(BASE_PATH) ?> /public/assets/docs.css">
</head>
<body>
<div class="docs-shell">

    <aside class="docs-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-mark">Mv</div>
            <div class="sidebar-brand-text">
                <span class="sidebar-brand-name">PHP MVC</span>
                <span class="sidebar-brand-version">v1.0.0</span>
            </div>
        </div>

        <?php foreach ($navGroups as $group) : ?>
        <div class="sidebar-group">
            <div class="sidebar-group-title"><?= $group['label'] ?></div>
            <ul class="sidebar-links">
                <?php foreach ($group['items'] as $item) : ?>
                <li>
                    <a href="<?= $item['href'] ?>" class="<?= (isset($active) && $active === $item['key']) ? 'is-active' : '' ?>">
                        <?= $item['label'] ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>
    </aside>

    <main class="docs-main">
        <div class="docs-breadcrumb">PHP MVC <span>/</span> <?= $chapter ?? 'Docs' ?> <span>/</span> <?= $title ?? '' ?></div>

        <div class="docs-content">
            <div class="callout callout-note">
                <span class="callout-label">Note</span>
                <p>This framework is designed exclusively for learning and teaching purposes, providing developers with a practical understanding of how the MVC architecture works and the underlying concepts and mechanisms behind modern MVC frameworks.</p>
            </div>

            <?php $this->yield('content'); ?>
        </div>

        <?php if (isset($prev) || isset($next)) : ?>
        <div class="docs-pager">
            <?php if (isset($prev)) : ?>
            <a href="<?= $prev['href'] ?>" class="pager-prev">
                <span class="pager-dir">&larr; Previous</span>
                <span class="pager-title"><?= $prev['label'] ?></span>
            </a>
            <?php else : ?>
            <span></span>
            <?php endif; ?>

            <?php if (isset($next)) : ?>
            <a href="<?= $next['href'] ?>" class="pager-next">
                <span class="pager-dir">Next &rarr;</span>
                <span class="pager-title"><?= $next['label'] ?></span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>

</div>
</body>
</html>
