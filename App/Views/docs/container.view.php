@extends('layouts/docs')

@section('content')
<h1>Service Container</h1>
<p class="docs-lede"><code>Core\Container</code> is a minimal dependency-injection container: it stores bindings, builds objects through reflection, and can auto-wire constructor dependencies without any configuration.</p>

<h2>Binding</h2>
<p><code>bind()</code> registers a concrete implementation for an abstract name. If no concrete value is given, the abstract resolves to itself.</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">usage</span></div>
    <pre><code>$container-&gt;bind(Logger::class);

$container-&gt;bind(Mailer::class, SmtpMailer::class);</code></pre>
</div>

<h2>Singletons</h2>
<p><code>singleton()</code> wraps the concrete resolver in a closure so it only gets built once and reused for every subsequent <code>get()</code> call. The kernel uses this for the container itself, the database connection, and the request:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">Core/Kernel/App.php</span></div>
    <pre><code>self::$container-&gt;singleton(Request::class, <span class="tok-kw">function</span> ()
{
    <span class="tok-kw">return</span> <span class="tok-kw">new</span> Request();
});</code></pre>
</div>

<h2>Resolving</h2>
<p><code>get(string $abstract)</code> is the main entry point:</p>
<ul>
    <li>If an instance was already cached for that abstract, it is returned directly.</li>
    <li>If the binding is a closure, it is invoked with the container and, for singletons, cached.</li>
    <li>Otherwise, the abstract (or its bound concrete class name) is passed to <code>build()</code>.</li>
</ul>

<h2>Auto-wiring with reflection</h2>
<p><code>build()</code> instantiates a <code>ReflectionClass</code> for the target, inspects its constructor parameters, and for every typed, non-builtin parameter recursively calls <code>get()</code> again. Scalar parameters fall back to their default value if one is declared, or throw otherwise.</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">Core/Container.php</span></div>
    <pre><code><span class="tok-kw">foreach</span> ($dependencies <span class="tok-kw">as</span> $dependency)
{
    $type = $dependency-&gt;getType();

    <span class="tok-kw">if</span> (!$type || $type-&gt;isBuiltin())
    {
        <span class="tok-kw">if</span> ($dependency-&gt;isDefaultValueAvailable())
        {
            $results[] = $dependency-&gt;getDefaultValue();
            <span class="tok-kw">continue</span>;
        }
        <span class="tok-kw">throw new</span> Exception(<span class="tok-str">"Unresolvable dependency..."</span>);
    }

    $results[] = $this-&gt;get($type-&gt;getName());
}</code></pre>
</div>

<p>This is what allows the router to write <code>$container-&gt;get($controller)</code> for any controller class, without the framework ever needing to know what that controller depends on ahead of time.</p>

<h2>Reference</h2>
<table class="docs-table">
<thead><tr><th>Method</th><th>Description</th></tr></thead>
<tbody>
<tr><td><code>bind($abstract, $concrete = null)</code></td><td>Register a non-cached binding.</td></tr>
<tr><td><code>singleton($abstract, $concrete = null)</code></td><td>Register a binding that is built once and cached.</td></tr>
<tr><td><code>get($abstract)</code></td><td>Resolve an instance, respecting bindings and cached singletons.</td></tr>
<tr><td><code>build($concrete)</code></td><td>Instantiate a class directly through reflection, resolving its constructor.</td></tr>
</tbody>
</table>
@endsection
