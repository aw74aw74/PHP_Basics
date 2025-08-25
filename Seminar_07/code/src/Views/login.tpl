<h2>Вход</h2>

{% if error %}
    <p style="color: red;">{{ error }}</p>
{% endif %}

<form method="post">
    <div>
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label>
            <input type="checkbox" name="remember_me"> Запомнить меня
        </label>
    </div>
    <div>
        <button type="submit">Войти</button>
    </div>
</form>
