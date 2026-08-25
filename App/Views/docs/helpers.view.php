@extends('layouts/docs')

@section('content')
<h1>Helpers</h1>
<p class="docs-lede"><code>Core/helpers.php</code> is loaded once by the kernel and defines the two globally available conveniences every controller and view relies on: <code>env()</code> and <code>view()</code>.</p>

<h2>env()</h2>
<p>Covered in full on the <a href="/docs/environment">Environment</a> page. It reads a variable set by <code>Env::load()</code>, or returns a default.</p>

<h2>The View class</h2>
<p><code>view()</code> is a thin factory around the <code>View</code> class, which implements a tiny Blade-style templating layer in a single file:</p>

<table class="docs-table">
<thead><tr><th>Method</th><th>Description</th></tr></thead>
<tbody>
<tr><td><code>View::make($name)</code></td><td>Static constructor, same as <code>new View($name)</code>.</td></tr>
<tr><td><code>with($key, $value)</code></td><td>Assigns a variable for the view, returns <code>$this</code> for chaining.</td></tr>
<tr><td><code>render()</code></td><td>Extracts assigned data, includes the view file, parses <code>@extends</code> / <code>@section</code>, and includes the layout if one was declared.</td></tr>
<tr><td><code>yield($section)</code></td><td>Called from within a layout file to echo a captured section.</td></tr>
<tr><td><code>__toString()</code></td><td>Calls <code>render()</code> automatically, which is what lets a controller simply <code>return view(...)</code> and have <code>echo</code> render it.</td></tr>
</tbody>
</table>

<h2>Path resolution &amp; safety</h2>
<p>Every view name is resolved against <code>App/Views/</code> and passed through <code>realpath()</code>. The resolved path is then checked to confirm it still starts inside the views directory, which prevents a view name containing <code>../</code> from escaping the views folder:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">Core/helpers.php</span></div>
    <pre><code><span class="tok-kw">private function</span> resolvePath(<span class="tok-kw">string</span> $name): <span class="tok-kw">string</span>
{
    $path = realpath($this-&gt;viewsPath . $name . <span class="tok-str">'.view.php'</span>);

    <span class="tok-kw">if</span> (!$path || !str_starts_with($path, realpath($this-&gt;viewsPath)))
    {
        <span class="tok-kw">throw new</span> Exception(<span class="tok-str">"View file not found or unauthorized."</span>);
    }
    <span class="tok-kw">return</span> $path;
}</code></pre>
</div>

<p>Full templating usage &mdash; <code>@extends</code>, <code>@section</code>, and <code>yield()</code> &mdash; is documented on the <a href="/docs/views">Views &amp; Templating</a> page.</p>
@endsection
