@extends('layouts/docs')

@section('content')
<h1>Installation</h1>
<p class="docs-lede">There is no package manager involved. PHP MVC is a set of plain PHP files you copy into a project
    and point your web server at.</p>

<h2>Requirements</h2>
<ul>
    <li>PHP 8.0 or newer, with the <code>pdo_mysql</code> extension enabled.</li>
    <li>A MySQL-compatible database.</li>
    <li>Any web server capable of routing every request to a single front controller (Apache with
        <code>mod_rewrite</code>, or PHP's built-in server).</li>
</ul>

<h2>Project layout</h2>
<p>The framework expects the following directory layout relative to the project root:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span
            class="code-dot"></span><span class="code-filename">directory tree</span></div>
    <pre><code>project-root/
&#9500;&#9472; Core/
&#9474;   &#9500;&#9472; Http/Request.php
&#9474;   &#9500;&#9472; Kernel/App.php
&#9474;   &#9500;&#9472; Router/Route.php
&#9474;   &#9500;&#9472; Container.php
&#9474;   &#9500;&#9472; Database.php
&#9474;   &#9500;&#9472; Env.php
&#9474;   &#9500;&#9472; Model.php
&#9474;   &#9500;&#9472; helpers.php
&#9474;   &#9492;&#9472; migrate.php
&#9500;&#9472; app/
&#9474;   &#9492;&#9472; Routes/routes.php
&#9500;&#9472; App/
&#9474;   &#9500;&#9472; Controllers/
&#9474;   &#9500;&#9472; Models/
&#9474;   &#9492;&#9472; Views/
&#9500;&#9472; App/Migrations/
&#9474;   &#9500;&#9472; up/
&#9474;   &#9492;&#9472; down/
&#9500;&#9472; public/
&#9474;   &#9492;&#9472; index.php
&#9492;&#9472; .env
&#9500;&#9472; autoloader.php</code></pre>
</div>

<div class="callout">
    <span class="callout-label">About the casing</span>
    <p>The framework itself lives under <code>Core/</code>, while your application code lives under <code>App/</code>
        for controllers, models and views, but <code>app/Routes/</code> (lowercase) for the routes file. This mirrors
        the exact paths the kernel and the view engine resolve against &mdash; keep the casing as-is on case-sensitive
        file systems.</p>
</div>

<h2>1. Configure the environment</h2>
<p>Create a <code>.env</code> file at the project root:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span
            class="code-dot"></span><span class="code-filename">.env</span></div>
    <pre><code>DB_HOST=localhost
DB_NAME=phpmvc
DB_USER=root
DB_PASS=</code></pre>
</div>

<h2>2. Point the web server at <code>public/</code></h2>
<p>Every request must be funnelled into <code>public/index.php</code>, which boots the kernel:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span
            class="code-dot"></span><span class="code-filename">public/index.php</span></div>
    <pre><code>require_once dirname(__DIR__) . <span class="tok-str">'/autoloader.php'</span>;

<span class="tok-kw">use</span> Core\Kernel\App;

define(<span class="tok-str">'BASE_PATH'</span>, dirname(__DIR__));

$app = <span class="tok-kw">new</span> App(BASE_PATH);</code></pre>
</div>

<p>For local development, PHP's built-in server is enough:</p>

<div class="callout">
    Note: The project is currently designed to run through Apache or Nginx rather than PHP's built-in development
    server. Place the project directory inside your web server's document root, such as htdocs or www, and make sure the
    public directory is configured as the web root.

    Once the server is running, open your project URL in the browser to access the landing page, then visit
    /php-mvc/docs to explore the full in-app documentation.
</div>

<h2>3. Run the migrations</h2>
<p>Create the database referenced in <code>DB_NAME</code>, then run:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span
            class="code-dot"></span><span class="code-filename">terminal</span></div>
    <pre><code>php run migrate</code></pre>
</div>

<p>See <a href="/docs/migrations">Migrations</a> for the full command reference.</p>
@endsection