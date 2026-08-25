@extends('layouts/docs')

@section('content')
<h1>Requests</h1>
<p class="docs-lede"><code>Core\Http\Request</code> wraps the current HTTP method, path and input data behind a single object, registered as a singleton in the container.</p>

<h2>What it captures</h2>
<p>On construction it reads <code>$_SERVER['REQUEST_METHOD']</code>, parses the path from <code>$_SERVER['REQUEST_URI']</code>, and sanitises either <code>$_GET</code> or <code>$_POST</code> &mdash; whichever matches the current method &mdash; into an internal <code>$body</code> array using <code>filter_input()</code> with <code>FILTER_SANITIZE_SPECIAL_CHARS</code>.</p>

<h2>Reference</h2>
<table class="docs-table">
<thead><tr><th>Method</th><th>Returns</th></tr></thead>
<tbody>
<tr><td><code>getMethod()</code></td><td>The uppercased HTTP method, e.g. <code>GET</code>.</td></tr>
<tr><td><code>getPath()</code></td><td>The request path only, query string excluded.</td></tr>
<tr><td><code>all()</code></td><td>The full sanitised input array for the current method.</td></tr>
<tr><td><code>get($key, $default = null)</code></td><td>A single sanitised input value, or the default if it is missing.</td></tr>
</tbody>
</table>

<h2>Using it in a controller</h2>
<p>Because <code>Request</code> is bound as a singleton, any controller can simply type-hint it in its constructor and the container resolves the same instance used for routing:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">App/Controllers/ContactController.php</span></div>
    <pre><code><span class="tok-kw">namespace</span> App\Controllers;

<span class="tok-kw">use</span> Core\Http\Request;

<span class="tok-kw">class</span> <span class="tok-cls">ContactController</span> <span class="tok-kw">extends</span> Controller
{
    <span class="tok-kw">private</span> Request $request;

    <span class="tok-kw">public function</span> __construct(Request $request)
    {
        $this-&gt;request = $request;
    }

    <span class="tok-kw">public function</span> index()
    {
        $email = $this-&gt;request-&gt;get(<span class="tok-str">'email'</span>, <span class="tok-str">''</span>);

        <span class="tok-kw">return</span> view(<span class="tok-str">'contact'</span>)-&gt;with(<span class="tok-str">'email'</span>, $email);
    }
}</code></pre>
</div>

<div class="callout callout-note">
    <span class="callout-label">Sanitisation, not validation</span>
    <p><code>FILTER_SANITIZE_SPECIAL_CHARS</code> escapes HTML-relevant characters, it does not validate the shape or type of the input. Values coming through <code>all()</code> or <code>get()</code> should still be validated for length, format and business rules before use.</p>
</div>
@endsection
