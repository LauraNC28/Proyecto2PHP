<h2>Verificación de cuenta</h2>

<?php if (isset($_SESSION['verificado']) && $_SESSION['verificado'] == 'ok'): ?>
    <p>Tu cuenta ha sido verificada correctamente. Ya puedes iniciar sesión.</p>
    <a href="<?= base_url ?>usuario/login">Ir al login</a>
<?php else: ?>
    <p>No se ha podido verificar tu cuenta. El enlace no es válido o ya ha sido usado.</p>
<?php endif; ?>

<?php unset($_SESSION['verificado']); ?>