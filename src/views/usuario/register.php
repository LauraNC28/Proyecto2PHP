<h1>Registrarse</h1>

<?php if (isset($_SESSION['register']) && $_SESSION['register'] == 'pending') : ?>
    <strong class="success-alert">¡Te has registrado correctamente!</strong>
<?php elseif (isset($_SESSION['register']) && $_SESSION['register'] == 'failed') : ?>
    <strong class="error-alert">¡Ha habido un error al registrarte!</strong>
<?php elseif (isset($_SESSION['register']) && $_SESSION['register'] == 'email_used') : ?>
    <strong class="error-alert">El correo ya está registrado. Usa uno diferente.</strong>
<?php endif; ?>
<?php Utils::deleteSession('register'); ?>

<form action="<?= base_url ?>usuario/save" method="post" id="registroForm" novalidate>
    <label for="name">Nombre: </label>
    <input type="text" name="name" id="name" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" required>
    <?php echo isset($_SESSION['errors']) ? Utils::showError($_SESSION['errors'], 'name') : ''; ?>

    <label for="surname">Apellidos: </label>
    <input type="text" name="surname" id="surname" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" required>
    <?php echo isset($_SESSION['errors']) ? Utils::showError($_SESSION['errors'], 'surname') : ''; ?>

    <label for="email">Email: </label>
    <input type="email" name="email" id="email" required>
    <?php echo isset($_SESSION['errors']) ? Utils::showError($_SESSION['errors'], 'email') : ''; ?>

    <label for="password">Contraseña: </label>
    <input type="password" name="password" id="password" minlength="6" required>
    <small>La contraseña debe tener al menos 6 caracteres.</small>
    <?php echo isset($_SESSION['errors']) ? Utils::showError($_SESSION['errors'], 'password') : ''; ?>

    <?php if (isset($_SESSION['admin'])) : ?>
        <label for="rol">Rol:</label>
        <select name="rol">
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>
        <input type="submit" value="Crear usuario">
    <?php else : ?>
        <input type="submit" value="Registrarse">
    <?php endif; ?>

    <?php Utils::deleteSession('errors') ?>
</form>

<script>
// Validación JavaScript
document.getElementById('registroForm').addEventListener('submit', function (e) {
    const nombre = document.getElementById('name').value.trim();
    const apellidos = document.getElementById('surname').value.trim();
    const password = document.getElementById('password').value;

    const nombreValido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombre);
    const apellidosValido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(apellidos);

    if (!nombreValido || !apellidosValido) {
        alert("Nombre y apellidos no deben contener números ni caracteres especiales.");
        e.preventDefault();
        return false;
    }

    if (password.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        e.preventDefault();
        return false;
    }
});
</script>