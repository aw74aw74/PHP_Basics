<h2>{{ title }}</h2>

<form action="/user/save/" method="GET">
    <label for="name">Имя:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <label for="lastname">Фамилия:</label><br>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="birthday">Дата рождения:</label><br>
    <input type="date" id="birthday" name="birthday"><br><br>

    <input type="submit" value="Создать пользователя">
</form>
