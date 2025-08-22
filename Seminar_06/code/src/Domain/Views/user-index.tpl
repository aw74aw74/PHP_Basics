<h2>Список пользователей в хранилище</h2>

<a href="/user/create/">Добавить нового пользователя</a>
<br><br>

<ul id="navigation">
    {% for user in users %}
        <li>
            (ID: {{ user.getUserId() }}) {{ user.getUserName() }} {{ user.getUserLastName() }}.
            День рождения: {% if user.getUserBirthday() %}{{ user.getUserBirthday() | date('d.m.Y') }}{% else %}не указан{% endif %}
            <a href="/user/edit/?id={{ user.getUserId() }}">[редактировать]</a>
            <a href="/user/delete/?id={{ user.getUserId() }}" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?');">[удалить]</a>
        </li>
    {% endfor %}
</ul>