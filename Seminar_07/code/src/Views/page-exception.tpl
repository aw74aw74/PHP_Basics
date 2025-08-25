<h1>{{ title }}</h1>
<p>Произошло исключение:</p>
<div class="alert alert-danger">
    <p><strong>Сообщение:</strong> {{ exception_message }}</p>
</div>

<h3>Трассировка стека:</h3>
<pre><code>{{ exception_trace }}</code></pre>
