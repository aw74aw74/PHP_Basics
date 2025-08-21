<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ title }}</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

    <header>
        {% include 'header.tpl' %}
    </header>

    <aside class="sidebar">
        {% include 'sidebar.tpl' %}
    </aside>

    <main class="content">
        {% include content_template_name %}
    </main>

    <footer>
        {% include 'footer.tpl' %}
    </footer>

</body>
</html>