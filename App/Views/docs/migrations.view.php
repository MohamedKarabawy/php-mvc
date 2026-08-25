@extends('layouts/docs')

@section('content')
<h1>Migrations</h1>
<p class="docs-lede">Migrations here are plain <code>.sql</code> files, run through a small CLI script &mdash; <code>Core/migrate.php</code> &mdash; rather than a fluent schema builder.</p>

<h2>File layout</h2>
<p>Two folders hold the SQL that creates and drops your schema:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">directory tree</span></div>
    <pre><code>App/Migrations/
&#9500;&#9472; up/
&#9474;   &#9492;&#9472; 001_create_users.sql
&#9492;&#9472; down/
    &#9492;&#9472; 001_drop_users.sql</code></pre>
</div>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">001_create_users.sql</span></div>
    <pre><code>CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE
);</code></pre>
</div>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">001_drop_users.sql</span></div>
    <pre><code>SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE users;

SET FOREIGN_KEY_CHECKS = 1;</code></pre>
</div>

<h2>Running migrations</h2>
<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">terminal</span></div>
    <pre><code>php Core/migrate.php migrate
php Core/migrate.php drop</code></pre>
</div>

<p>The script connects with <code>Database::connect()</code>, reads every <code>.sql</code> file in the relevant folder with <code>glob()</code>, and executes each one with <code>PDO::exec()</code>, printing a per-file result line.</p>

<h2>How failures are reported</h2>
<table class="docs-table">
<thead><tr><th>Condition</th><th>Output</th></tr></thead>
<tbody>
<tr><td>Table already exists during <code>migrate</code></td><td><code>FAILURE: Cannot migrate ... Table already exists in the database!</code></td></tr>
<tr><td>Table missing during <code>drop</code></td><td><code>FAILURE: Cannot drop ... Table does not exist in the database!</code></td></tr>
<tr><td>Any other <code>PDOException</code></td><td><code>ERROR executing &lt;file&gt;: &lt;message&gt;</code></td></tr>
<tr><td>Success</td><td><code>SUCCESS: Executed &lt;file&gt; (Table created / dropped)</code></td></tr>
</tbody>
</table>

<div class="callout callout-note">
    <span class="callout-label">No ordering or tracking table</span>
    <p>Files run in whatever order <code>glob()</code> returns them, and there is no migrations table recording what has already run &mdash; the numeric filename prefix (<code>001_</code>, <code>002_</code>&hellip;) is a convention for readability, not something the script enforces. Re-running <code>migrate</code> against an already-migrated database is safe only because the sample migration uses <code>CREATE TABLE IF NOT EXISTS</code>.</p>
</div>
@endsection
