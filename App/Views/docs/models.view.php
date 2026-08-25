@extends('layouts/docs')

@section('content')
<h1>Models</h1>
<p class="docs-lede"><code>Core\Model</code> is a small active-record base class. Every model maps to a table, carries its own attributes, and can build a simple <code>WHERE</code>-chained query.</p>

<h2>Defining a model</h2>
<p>If <code>$table</code> is not set explicitly, it defaults to the lowercased class name. <code>User</code> sets it explicitly for clarity:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">App/Models/User.php</span></div>
    <pre><code><span class="tok-kw">namespace</span> App\Models;

<span class="tok-kw">use</span> Core\Model;

<span class="tok-kw">class</span> <span class="tok-cls">User</span> <span class="tok-kw">extends</span> Model
{
    <span class="tok-kw">protected</span> <span class="tok-kw">string</span> $table = <span class="tok-str">'users'</span>;

    <span class="tok-kw">public function</span> posts()
    {
        <span class="tok-kw">return</span> $this-&gt;hasMany(Post::class, <span class="tok-str">'user_id'</span>);
    }
}</code></pre>
</div>

<h2>Reading attributes</h2>
<p>Attributes are stored in an internal array and exposed through <code>__get()</code>, so <code>$user-&gt;name</code> reads from <code>$attributes['name']</code>. If no attribute matches but a method of the same name exists, that method is called instead &mdash; which is how relationship accessors like <code>$user-&gt;posts</code> work as if they were properties.</p>

<h2>Querying</h2>
<table class="docs-table">
<thead><tr><th>Method</th><th>Description</th></tr></thead>
<tbody>
<tr><td><code>::query()</code></td><td>Returns a fresh, empty model instance to start a chain.</td></tr>
<tr><td><code>where($column, $value)</code></td><td>Appends a parameterised <code>column = ?</code> clause. Chainable.</td></tr>
<tr><td><code>get()</code></td><td>Runs the built <code>SELECT</code> and returns an array of hydrated model instances.</td></tr>
<tr><td><code>::find($id)</code></td><td>Fetches a single row by primary key, or <code>null</code>.</td></tr>
</tbody>
</table>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">usage</span></div>
    <pre><code>$active = User::query()-&gt;where(<span class="tok-str">'status'</span>, <span class="tok-str">'active'</span>)-&gt;get();

$user = User::find(4);</code></pre>
</div>

<h2>Relationships</h2>
<p>Four protected helpers cover the common relationship shapes, each building on <code>where()</code> / <code>get()</code> / <code>find()</code>:</p>
<ul>
    <li><code>hasOne($relatedModel, $foreignKey)</code> &mdash; first matching related row, or <code>null</code>.</li>
    <li><code>hasMany($relatedModel, $foreignKey)</code> &mdash; every matching related row.</li>
    <li><code>belongsTo($relatedModel, $foreignKey)</code> &mdash; looks up the related row by its primary key.</li>
    <li><code>belongsToMany($relatedModel, $pivotTable, $foreignPivotKey, $relatedPivotKey)</code> &mdash; joins through a pivot table.</li>
</ul>

<div class="callout callout-note">
    <span class="callout-label">Attributes are plain arrays</span>
    <p>There is no attribute casting, no mass-assignment protection, and no dirty-tracking. Every column returned by the database becomes a readable attribute as-is &mdash; keep that in mind before exposing a model's attributes directly to a view.</p>
</div>
@endsection
