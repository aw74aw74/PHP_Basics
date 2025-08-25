<h1>Мой сайт</h1>

<div class="auth-block">
    {% if user %}
        <span>Привет, {{ user.getName() }}!</span> | <a href="/auth/logout" class="btn btn-logout">Выйти</a>
    {% else %}
        <a href="/auth/login" class="btn btn-login">Войти</a>
    {% endif %}
</div>
