@extends('layouts/docs')

@section('content')
<h1>Database</h1>
<p class="docs-lede"><code>Core\Database</code> is a lazy, static PDO singleton. It reads its connection details from the environment and hands back the same connection for the lifetime of the request.</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">Core/Database.php</span></div>
    <pre><code><span class="tok-kw">public static function</span> connect(): PDO
{
    <span class="tok-kw">if</span> (self::$instance === <span class="tok-kw">null</span>)
    {
        $host = env(<span class="tok-str">'DB_HOST'</span>, <span class="tok-str">'localhost'</span>);
        $dbname = env(<span class="tok-str">'DB_NAME'</span>);
        $user = env(<span class="tok-str">'DB_USER'</span>, <span class="tok-str">'root'</span>);
        $pass = env(<span class="tok-str">'DB_PASS'</span>, <span class="tok-str">''</span>);

        self::$instance = <span class="tok-kw">new</span> PDO(
            <span class="tok-str">"mysql:host={$host};dbname={$dbname};charset=utf8mb4"</span>,
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE =&gt; PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =&gt; PDO::FETCH_ASSOC,
            ]
        );
    }
    <span class="tok-kw">return</span> self::$instance;
}</code></pre>
</div>

<h2>Where it's used</h2>
<ul>
    <li>Bound as a singleton in the container, so <code>$container-&gt;get(Database::class)</code> and any type-hinted dependency resolve to the same connection.</li>
    <li>Called directly by every <code>Model</code> method (<code>get()</code>, <code>find()</code>, the relationship helpers) via <code>Database::connect()</code>.</li>
    <li>Called directly by <code>migrate.php</code> to run raw SQL migration files.</li>
</ul>

<h2>Configuration</h2>
<p>Connection details come entirely from <code>.env</code> through the <a href="/docs/environment"><code>env()</code></a> helper &mdash; <code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code> and <code>DB_PASS</code>. Errors raise real <code>PDOException</code>s, since the connection is created with <code>PDO::ERRMODE_EXCEPTION</code>.</p>

<h2>Writing queries directly</h2>
<p>You are never required to go through <code>Model</code>. Any controller can pull the connection and run parameterised queries by hand:</p>

<div class="code-window">
    <div class="code-window-bar"><span class="code-dot"></span><span class="code-dot"></span><span class="code-dot"></span><span class="code-filename">usage</span></div>
    <pre><code>$pdo = Core\Database::connect();

$stmt = $pdo-&gt;prepare(<span class="tok-str">'SELECT * FROM users WHERE email = ?'</span>);
$stmt-&gt;execute([$email]);

$user = $stmt-&gt;fetch();</code></pre>
</div>
@endsection
