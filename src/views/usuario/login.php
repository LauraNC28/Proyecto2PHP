<h1>Iniciar Sesión</h1>

<?php if (isset($_SESSION['error_login'])) : ?>
    <strong class="error-alert"><?= $_SESSION['error_login'] ?></strong>
<?php endif; ?>
<?php Utils::deleteSession('error_login'); ?>

<form action="<?= base_url ?>usuario/login" method="post">
    <label for="email">Email: </label>
    <input type="email" name="email" required>

    <label for="password">Contraseña: </label>
    <input type="password" name="password" required>

    <input type="submit" value="Iniciar sesión">
</form>
