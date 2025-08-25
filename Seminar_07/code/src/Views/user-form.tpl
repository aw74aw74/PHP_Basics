{% if user_form is defined and user_form.id %}
    <h2>Редактировать пользователя</h2>
    <form action="/user/update" method="post">
        <input type="hidden" name="id" value="{{ user_form.id }}">
{% else %}
    <h2>Добавить пользователя</h2>
    <form action="/user/add" method="post">
{% endif %}

    <div>
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" value="{{ user_form.name | default('') }}" required>
    </div>
    <div>
        <label for="birthday">Дата рождения (дд-мм-гггг):</label>
        <input type="text" id="birthday" name="birthday" value="{{ user_form.birthday ? user_form.birthday|date('d-m-Y') : '' }}">
    </div>
    <div>
        <button type="submit" class="btn btn-save">Сохранить</button>
        <a href="/user" class="btn btn-cancel" style="margin-left: 10px;">Отмена</a>
    </div>
</form>
