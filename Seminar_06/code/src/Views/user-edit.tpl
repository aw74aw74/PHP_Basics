<h1>{{ title }}: {{ user.getName() }}</h1>

<form action="/user/update" method="POST">
    <input type="hidden" name="id" value="{{ user.getId() }}">
    <div style="margin-bottom: 10px;">
        <label>Имя: <input type="text" name="name" value="{{ user.getName() }}" required></label>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Дата рождения (ДД-ММ-ГГГГ): <input type="text" name="birthday" value="{{ user.getBirthday() ? user.getBirthday()|date('d-m-Y') : '' }}"></label>
    </div>
    <button type="submit">Сохранить</button>
    <a href="/user">Отмена</a>
</form>
