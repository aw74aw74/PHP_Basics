<h1>{{ title }}</h1>

<hr>

<a href="/user/add" class="btn btn-add">Добавить нового пользователя</a>

<h2>Список пользователей</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Дата рождения</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        {% for user_item in users %}
            <tr>
                <td>{{ user_item.id }}</td>
                <td>{{ user_item.name }}</td>
                <td>{{ user_item.birthday ? user_item.birthday|date('d-m-Y') : 'Не указана' }}</td>
                <td>
                    <a href="/user/edit?id={{ user_item.id }}" class="btn btn-edit">Редактировать</a>
                    <form action="/user/delete" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="{{ user_item.id }}">
                        <button type="submit" class="btn-delete" onclick="return confirm('Вы уверены?');">Удалить</button>
                    </form>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>