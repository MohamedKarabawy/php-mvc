@extends('layouts/docs')

@section('content')
<h1>Controllers</h1>
<p class="docs-lede">Controllers live under <code>App/Controllers</code>, extend the shared <code>App\Controllers\Controller</code> base class, and are resolved through the container for every dispatched route.</p>

<h2>The base class</h2>
<p><code>Controller</code> itself is intentionally empty &mdash; it exists purely as a shared type that future cross-cutting methods, traits or properties can be added to later:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">App/Controllers/Controller.php</span></div>
    <pre><code><span class="tok-kw">namespace</span> App\Controllers;

<span class="tok-kw">abstract class</span> <span class="tok-cls">Controller</span>
{
}</code></pre>
</div>

<h2>A minimal controller</h2>
<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">App/Controllers/HomeController.php</span></div>
    <pre><code><span class="tok-kw">namespace</span> App\Controllers;

<span class="tok-kw">class</span> <span class="tok-cls">HomeController</span> <span class="tok-kw">extends</span> Controller
{
    <span class="tok-kw">public function</span> index()
    {
        <span class="tok-kw">return</span> view(<span class="tok-str">'home'</span>);
    }
}</code></pre>
</div>

<h2>Constructor injection</h2>
<p>Because the router resolves every controller through <code>$container-&gt;get($controller)</code>, a controller's constructor can type-hint any class the container knows how to build &mdash; a bound singleton like <code>Request</code>, or any other class with a resolvable constructor:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">App/Controllers/PostsController.php</span></div>
    <pre><code><span class="tok-kw">namespace</span> App\Controllers;

<span class="tok-kw">use</span> Core\Http\Request;
<span class="tok-kw">use</span> App\Models\Post;

<span class="tok-kw">class</span> <span class="tok-cls">PostsController</span> <span class="tok-kw">extends</span> Controller
{
    <span class="tok-kw">public function</span> __construct(<span class="tok-kw">private</span> Request $request)
    {
    }

    <span class="tok-kw">public function</span> index()
    {
        $posts = Post::query()-&gt;get();

        <span class="tok-kw">return</span> view(<span class="tok-str">'posts/index'</span>)-&gt;with(<span class="tok-str">'posts'</span>, $posts);
    }
}</code></pre>
</div>

<h2>Registering it</h2>
<p>Every controller needs a matching entry in <code>routes.php</code>:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">app/Routes/routes.php</span></div>
    <pre><code>Route::get(<span class="tok-str">'/posts'</span>, [PostsController::class, <span class="tok-str">'index'</span>]);</code></pre>
</div>
@endsection
