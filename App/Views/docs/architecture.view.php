@extends('layouts/docs')

@section('content')
<h1>Architecture</h1>
<p class="docs-lede">A single request travels through five stages before any of your application code runs. Understanding this path is the fastest way to understand the whole framework.</p>

<h2>The request lifecycle</h2>
<ol>
    <li><strong>Autoloading.</strong> <code>autoloader.php</code> registers <code>spl_autoload_register</code>, converting a fully-qualified class name into a file path and requiring it on first use.</li>
    <li><strong>Bootstrapping.</strong> <code>public/index.php</code> defines <code>BASE_PATH</code> and instantiates <code>Core\Kernel\App</code>, passing the project root.</li>
    <li><strong>Environment &amp; container.</strong> The <code>App</code> constructor loads <code>.env</code> through <code>Env::load()</code>, requires <code>helpers.php</code>, and builds a fresh <code>Container</code> registering itself, the <code>Database</code> connection and the <code>Request</code> as singletons.</li>
    <li><strong>Routing.</strong> <code>App</code> requires <code>app/Routes/routes.php</code>, which calls <code>Route::get()</code> / <code>Route::post()</code> for every registered path. Each call resolves a <code>Request</code> from the container and compares it against the current URI.</li>
    <li><strong>Dispatch.</strong> On a match, the router asks the container to <code>build()</code> the target controller (auto-resolving constructor dependencies), calls the requested method, and echoes whatever is returned &mdash; typically a rendered <code>View</code>.</li>
</ol>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">request flow</span></div>
    <pre><code>Browser
  &#8595;
public/index.php
  &#8595;
Core\Kernel\App        (env, helpers, container, routes)
  &#8595;
Core\Router\Route       (match method + path)
  &#8595;
Core\Container          (resolve controller + dependencies)
  &#8595;
App\Controllers\*       (your logic)
  &#8595;
Core\helpers::view()    (render + layout)
  &#8595;
Browser</code></pre>
</div>

<h2>Two "App" directories, on purpose</h2>
<p>The framework namespace <code>Core\*</code> and your application namespace <code>App\*</code> are kept in separate folders so that upgrading the framework never touches your code, and so the autoloader can resolve both with the same simple rule: replace <code>\</code> with <code>/</code> and append <code>.php</code>.</p>

<h2>No magic beyond reflection</h2>
<p>The only non-obvious mechanism in the whole stack is <code>Container::build()</code>, which uses PHP's <code>ReflectionClass</code> to read a class's constructor and resolve each typed, non-builtin parameter recursively from the container. That single method is what lets a controller simply type-hint <code>Request $request</code> in its constructor and receive a working instance, with no manual wiring. See <a href="/docs/container">Service Container</a> for the full breakdown.</p>
@endsection
