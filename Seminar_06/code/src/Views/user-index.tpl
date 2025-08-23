<h1>{{ title }}</h1>

<h3>Добавить нового пользователя</h3>
<form action="/user/add" method="GET">
    <div style="margin-bottom: 10px;">
        <label>Имя: <input type="text" name="name" required></label>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Дата рождения (ДД-ММ-ГГГГ): <input type="text" name="birthday"></label>
    </div>
    <button type="submit">Добавить</button>
</form>

<hr>

<h3>Список пользователей</h3>
<table border="1" cellpadding="5" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Дата рождения</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        {% for user in users %}
            <tr>
                <td>{{ user.getId() }}</td>
                <td>{{ user.getName() }}</td>
                <td>{{ user.getBirthday() ? user.getBirthday()|date('d.m.Y') : 'не указана' }}</td>
                <td>
                    <a href="/user/edit?id={{ user.getId() }}">Редактировать</a>
                    <a href="/user/delete?id={{ user.getId() }}" onclick="return confirm('Вы уверены, что хотите удалить пользователя {{ user.getName() }}?');">Удалить</a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>