@extends('layouts/docs')

@section('content')
<h1>Routing</h1>
<p class="docs-lede"><code>Core\Router\Route</code> is a static facade with two methods, <code>get()</code> and <code>post()</code>, each matching a path against the current request and dispatching a callback or a controller action.</p>

<h2>Registering routes</h2>
<p>Routes are registered in <code>app/Routes/routes.php</code>, which the kernel requires once during boot. A callback is either a closure, or a two-item array of <code>[ControllerClass::class, 'method']</code>:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">app/Routes/routes.php</span></div>
    <pre><code><span class="tok-kw">use</span> Core\Router\Route;
<span class="tok-kw">use</span> App\Controllers\HomeController;

Route::get(<span class="tok-str">'/'</span>, [HomeController::class, <span class="tok-str">'index'</span>]);

Route::get(<span class="tok-str">'/about'</span>, <span class="tok-kw">function</span> ()
{
    <span class="tok-kw">return</span> <span class="tok-str">'About page'</span>;
});</code></pre>
</div>

<h2>How a match is decided</h2>
<p>Each call resolves the shared <code>Request</code> from the container and first checks the HTTP method. It then takes <code>$_SERVER['REQUEST_URI']</code>, strips the query string, and compares it against the registered route string.</p>
<ol>
    <li>The method must match &mdash; <code>get()</code> only ever matches <code>GET</code>, <code>post()</code> only <code>POST</code>.</li>
    <li>Matching path against route is done as a plain string comparison, so the path you register should mirror the path you expect the browser to request exactly (leading slash included).</li>
    <li>On a match, the callback runs, its return value is echoed, and execution stops with <code>exit</code> &mdash; so only the first matching route in the file ever runs.</li>
</ol>

<div class="callout callout-note">
    <span class="callout-label">Flat routes only</span>
    <p>There is no parameter placeholder syntax (no <code>/user/{id}</code>) and no values are passed into the controller method &mdash; <code>$controllerInstance-&gt;$method()</code> is always called with zero arguments. Each distinct URL needs its own <code>Route::get()</code> / <code>Route::post()</code> call and its own controller method, matching the flat style used throughout <code>routes.php</code>. If you need a value from the URL, read it from the <code>Request</code> object (query string or POST body) inside the controller instead of from the path.</p>
</div>

<h2>Dispatching a controller action</h2>
<p>When the callback is a <code>[Controller::class, 'method']</code> pair, the router resolves the controller through the container &mdash; so the controller's constructor can type-hint any bound service, such as <code>Request</code> &mdash; and calls the method:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">Core/Router/Route.php</span></div>
    <pre><code>[$controller, $method] = $callback;

$controllerInstance = $container-&gt;get($controller);

echo $controllerInstance-&gt;$method();

exit;</code></pre>
</div>
@endsection
