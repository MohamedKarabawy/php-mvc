<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PHP MVC &mdash; v1.0.0</title>
<link rel="stylesheet" href="<?php echo '/'.basename(BASE_PATH) ?> /public/assets/docs.css">
<link rel="stylesheet" href="<?php echo '/'.basename(BASE_PATH) ?> /public/assets/home.css">
</head>
<body class="home-body">
<div class="home-grid-overlay"></div>

<div class="home-shell">

    <div class="home-topline">
        <span class="dot-live"></span>
        Built from scratch, no dependencies
    </div>

    <h1 class="home-title">PHP <span class="accent">MVC</span></h1>
    <p class="home-version">v1.0.0</p>

    <p class="home-tagline">
        A minimal Model &ndash; View &ndash; Controller framework, written in plain PHP so you can
        read every line of the container, the router and the ORM &mdash; and actually understand
        what a framework does underneath.
    </p>

    <div class="home-actions">
        <a href="<?php echo '/'.basename(BASE_PATH) ?>/docs" class="btn btn-primary">Read the docs &rarr;</a>
        <a href="<?php echo '/'.basename(BASE_PATH) ?>/docs/architecture" class="btn btn-ghost">See how a request flows</a>
    </div>

    <div class="home-showcase">
        <div class="code-window">
            <div class="code-window-bar">
                <span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span>
                <span class="code-filename">public/index.php</span>
            </div>
            <pre><code>require_once dirname(__DIR__) . <span class="tok-str">'/autoloader.php'</span>;

<span class="tok-kw">use</span> Core\Kernel\App;

define(<span class="tok-str">'BASE_PATH'</span>, dirname(__DIR__));

$app = <span class="tok-kw">new</span> App(BASE_PATH);<span class="cursor-blink"></span></code></pre>
        </div>
    </div>

    <div class="home-pillars">
        <div class="pillar">
            <span class="pillar-label">Container</span>
            <p>A reflection-based DI container that auto-wires constructor dependencies with zero configuration.</p>
        </div>
        <div class="pillar">
            <span class="pillar-label">Router</span>
            <p>A flat, explicit <code>Route::get()</code> / <code>Route::post()</code> facade with no hidden matching logic.</p>
        </div>
        <div class="pillar">
            <span class="pillar-label">Model &amp; View</span>
            <p>An active-record base class over PDO, and a Blade-inspired templating layer in a single file.</p>
        </div>
    </div>

    <p class="home-footnote">
        This framework is for learning/teaching purposes only, to make the developer be able to know how MVC works and the underlying of MVC frameworks.
    </p>

</div>
</body>
</html>
