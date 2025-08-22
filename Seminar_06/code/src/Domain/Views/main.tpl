<!DOCTYPE html>
<html>
    <head>
        <title>{{ title }}</title>
    </head>
    <body>
        <nav>
            <a href="/">Главная</a>
            <a href="/user">Пользователи</a>
        </nav>
        <hr>

        {% include content_template_name %}
    </body>
</html>