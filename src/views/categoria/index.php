<h1>Gestionar categorías</h1>
<a href="<?=base_url?>categoria/crear" class="button button-small">Crear Categoria</a>
<table>
    <tr>
        <th>ID</th>
        <th>NOMBRE</th>
        <th></th>

    </tr>
    <?php while ($cat = $categorias->fetch_object()) : ?>

        <tr>
            <td><?= $cat->id ?></td>
            <td><?= $cat->nombre ?></td>
            <td><a href="<?=base_url?>categoria/eliminar&id=<?= $cat->id ?>" class="button button-red button-small">Eliminar</a></td>

        </tr>
    <?php endwhile; ?>
</table>
<?php if (isset($_SESSION['categoria_eliminada'])): ?>
    <div class="alert">
        <?= $_SESSION['categoria_eliminada']; ?>
    </div>
    <?php unset($_SESSION['categoria_eliminada']); ?>
<?php endif; ?>