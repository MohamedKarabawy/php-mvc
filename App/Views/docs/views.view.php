@extends('layouts/docs')

@section('content')

<h1>Views &amp; Templating</h1>

<p class="docs-lede">
    The <code>View</code> class in <code>helpers.php</code> renders plain
    <code>.view.php</code> files and supports a tiny, regex-based subset of Blade:
    <code>&#64;extends</code> and <code>&#64;section</code> /
    <code>&#64;endsection</code>.
</p>

<h2>A plain view</h2>

<p>
    With no layout, a view is just a PHP file that gets <code>include</code>d
    with its assigned variables extracted into scope:
</p>

<div class="code-window">
    <div class="code-window-bar">
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-filename">App/Views/home.view.php</span>
    </div>

    <pre><code>&lt;h1&gt;Homepage&lt;/h1&gt;</code></pre>
</div>

<div class="code-window">
    <div class="code-window-bar">
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-filename">App/Controllers/HomeController.php</span>
    </div>

    <pre><code><span class="tok-kw">public function</span> index()
{
    <span class="tok-kw">return</span> view(<span class="tok-str">'home'</span>);
}</code></pre>
</div>

<h2>Passing data</h2>

<p>
    <code>with()</code> is chainable and every key becomes a variable inside the
    view via <code>extract()</code>:
</p>

<div class="code-window">
    <div class="code-window-bar">
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-filename">usage</span>
    </div>

    <pre><code><span class="tok-kw">return</span> view(<span class="tok-str">'posts/show'</span>)
    -&gt;with(<span class="tok-str">'post'</span>, $post)
    -&gt;with(<span class="tok-str">'comments'</span>, $comments);</code></pre>
</div>

<div class="code-window">
    <div class="code-window-bar">
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-filename">App/Views/posts/show.view.php</span>
    </div>

    <pre><code>&lt;h1&gt;&lt;?= $post-&gt;title ?&gt;&lt;/h1&gt;</code></pre>
</div>

<h2>Layouts</h2>

<p>
    A view can declare a layout with
    <code>&#64;extends('layout-name')</code>
    and wrap its body in
    <code>&#64;section('content') ... &#64;endsection</code>.
    These are matched with regular expressions against the view's already-rendered
    output, so they must appear as literal text &mdash; not inside a PHP tag:
</p>

<div class="code-window">
    <div class="code-window-bar">
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-filename">App/Views/posts/show.view.php</span>
    </div>

    <pre><code>&#64;extends(<span class="tok-str">'layouts/site'</span>)

&#64;section(<span class="tok-str">'content'</span>)

&lt;h1&gt;&lt;?= $post-&gt;title ?&gt;&lt;/h1&gt;

&#64;endsection</code></pre>
</div>

<p>
    The layout file pulls the captured section back in with
    <code>$this-&gt;yield()</code>:
</p>

<div class="code-window">
    <div class="code-window-bar">
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-dot"></span>
        <span class="code-filename">App/Views/layouts/site.view.php</span>
    </div>

    <pre><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;body&gt;
    &lt;?php $this-&gt;yield(<span class="tok-str">'content'</span>); ?&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
</div>

<h2>How rendering actually happens</h2>

<ol>
    <li>
        The view file is included inside an output buffer; any real
        <code>&lt;?= ?&gt;</code> or <code>&lt;?php ?&gt;</code> tags in it execute normally.
    </li>
    <li>
        The buffered HTML is scanned with regular expressions for
        <code>&#64;extends(...)</code> and any
        <code>&#64;section(...) ... &#64;endsection</code> blocks, and those blocks are stored.
    </li>
    <li>
        If a layout was declared, the layout file is included in a second output buffer,
        where it can call <code>$this-&gt;yield('content')</code> to echo the stored section back out.
    </li>
</ol>

<div class="callout callout-note">
    <span class="callout-label">This documentation site is an example</span>

    <p>
        Every page in this documentation is itself a <code>.view.php</code> file
        extending <code>layouts/docs</code>, rendered by exactly this mechanism
        &mdash; open <code>App/Views/docs/</code> in the framework source to see it in practice.
    </p>
</div>

@endsection