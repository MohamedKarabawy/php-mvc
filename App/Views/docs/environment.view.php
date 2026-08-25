@extends('layouts/docs')

@section('content')
<h1>Environment</h1>
<p class="docs-lede"><code>Core\Env</code> reads a <code>.env</code> file and exposes its values through <code>putenv()</code> and <code>$_ENV</code>, so configuration never has to be hard-coded into the framework.</p>

<h2>Loading the file</h2>
<p><code>Env::load()</code> is called once, from the <code>App</code> kernel constructor, before anything else boots:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">Core/Kernel/App.php</span></div>
    <pre><code>\Core\env::load($routesPath . <span class="tok-str">'/.env'</span>);</code></pre>
</div>

<p>Internally, it reads the file line by line, skips blank lines and lines starting with <code>#</code>, splits each remaining line on the first <code>=</code>, trims both sides, and stores the result with <code>putenv()</code> and <code>$_ENV</code>.</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">.env</span></div>
    <pre><code><span class="tok-com"># database credentials</span>
DB_HOST=localhost
DB_NAME=phpmvc
DB_USER=root
DB_PASS=secret</code></pre>
</div>

<h2>Reading values</h2>
<p>The global <code>env()</code> helper, defined in <code>helpers.php</code>, wraps <code>getenv()</code> with a default fallback:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">usage</span></div>
    <pre><code>$host = env(<span class="tok-str">'DB_HOST'</span>, <span class="tok-str">'localhost'</span>);
$name = env(<span class="tok-str">'DB_NAME'</span>);</code></pre>
</div>

<p><code>Database::connect()</code> is the main consumer of this helper, pulling <code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code> and <code>DB_PASS</code> to build its PDO connection string.</p>

<div class="callout callout-note">
    <span class="callout-label">Two env() implementations</span>
    <p><code>helpers.php</code> defines <code>env()</code> from <code>getenv()</code>, while <code>migrate.php</code> defines its own guarded copy reading from <code>$_ENV</code> first. Both are declared behind <code>function_exists()</code> checks so they never collide, but keep the difference in mind if a value is set in one place and not the other.</p>
</div>
@endsection
