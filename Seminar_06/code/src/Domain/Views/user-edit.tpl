<h2>{{ title }}</h2>

<form action="/user/update/" method="GET">
    <input type="hidden" name="id" value="{{ user.getUserId() }}">

    <label for="name">Имя:</label><br>
    <input type="text" id="name" name="name" value="{{ user.getUserName() }}"><br><br>

    <label for="lastname">Фамилия:</label><br>
    <input type="text" id="lastname" name="lastname" value="{{ user.getUserLastName() }}"><br><br>

    <label for="birthday">Дата рождения:</label><br>
    <input type="date" id="birthday" name="birthday" value="{% if user.getUserBirthday() %}{{ user.getUserBirthday() | date('Y-m-d') }}{% endif %}"><br><br>

    <input type="submit" value="Сохранить изменения">
</form>
