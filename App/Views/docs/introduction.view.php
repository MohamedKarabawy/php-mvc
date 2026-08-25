@extends('layouts/docs')

@section('content')
<h1>Introduction</h1>
<p class="docs-lede">PHP MVC is a minimal, hand-built Model &ndash; View &ndash; Controller framework written in plain PHP, with no external dependencies. It exists to show, line by line, what a framework like Laravel or Symfony is actually doing underneath its own abstractions.</p>

<p>Every request that hits the application passes through the same small set of files: an autoloader, a kernel, a service container, a router, and a handful of base classes for controllers and models. There is no magic here that you cannot open and read in a few minutes.</p>

<h2>What's included</h2>
<ul>
    <li>A PSR-4-style <code>autoloader</code> that maps namespaces to file paths.</li>
    <li>An <code>App</code> kernel that boots the environment, the container and the router.</li>
    <li>A small dependency-injection <code>Container</code> with constructor auto-wiring.</li>
    <li>A <code>Route</code> facade supporting <code>GET</code> and <code>POST</code> registrations.</li>
    <li>A <code>Request</code> object wrapping <code>$_GET</code> / <code>$_POST</code> with basic sanitisation.</li>
    <li>An <code>Env</code> loader for <code>.env</code> files, plus a global <code>env()</code> helper.</li>
    <li>A PDO-backed <code>Database</code> singleton and an active-record style <code>Model</code> base class.</li>
    <li>A tiny Blade-inspired <code>View</code> engine supporting <code>@extends</code> and <code>@section</code>.</li>
    <li>A stand-alone <code>migrate.php</code> CLI runner for plain SQL migration files.</li>
</ul>

<h2>Who this is for</h2>
<p>This documentation walks through every one of those pieces in isolation, and then shows how they compose together to answer a single HTTP request &mdash; from <code>index.php</code> down to the HTML that gets echoed back to the browser.</p>

<div class="code-window">
    <div class="code-window-bar">
        <span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span>
        <span class="code-filename">app/Routes/routes.php</span>
    </div>
    <pre><code><span class="tok-kw">use</span> Core\Router\Route;
<span class="tok-kw">use</span> App\Controllers\HomeController;

Route::get(<span class="tok-str">'/'</span>, [HomeController::class, <span class="tok-str">'index'</span>]);</code></pre>
</div>

<p>That is the entire public surface a developer needs to learn before shipping a page. Everything else in these docs explains what happens behind that one line.</p>
@endsection
