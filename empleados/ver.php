<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Modificar un empleado</title>
</head>

<body>
    <div class="container">
        <?php
        require __DIR__ . '/../comunes/auxiliar.php';
        require __DIR__ . '/auxiliar.php';

        barra();

        $errores = [];

        if (!isset($_GET['id'])) {
            aviso('El empleado que intentas ver no existe.', 'danger');
            header('Location: index.php');
            return;
        }

        $id = trim($_GET['id']);

        $pdo = conectar();
        $sent = $pdo->prepare('SELECT *
                                FROM empleados
                                WHERE id = :id');
        $sent->execute(['id' => $id]);
        
        if (($args = $sent->fetch(PDO::FETCH_ASSOC)) === false) {
            aviso('Error al modificar fila.', 'danger');
            header('Location: index.php');
            return;
        }

        foreach (PAR as $k => $v) : ?>
            <?php if (isset(PAR[$k]['def'])) : ?>
                <div class="form-group">
                    <label for="<?= $k ?>"><?= PAR[$k]['etiqueta'] ?></label>
                    <?php if (isset(PAR[$k]['relacion'])) : ?>
                        <?php
                        $tabla = PAR[$k]['relacion']['tabla'];
                        $visualizar = PAR[$k]['relacion']['visualizar'];
                        $ajena = PAR[$k]['relacion']['ajena'];
                        $sent = $pdo->query("SELECT $ajena, $visualizar
                                               FROM $tabla"); ?>

                        <label for=""></label>
                        <select id="<?= $k ?>" name="<?= $k ?>" class="form-control">

                        <label></label>
                            <?php foreach ($sent as $fila) : ?>

                                <option value="<?= h($fila[0]) ?>" <?= selected($fila[0], $args['departamento_id']) ?>>
                                    <?= h($fila[1]) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    <?php else : ?>
                        <input type="text" class="form-control <?= valido($k, $errores) ?>" id="<?= $k ?>" name="<?= $k ?>" value="<?= h($args[$k]) ?>" readonly>
                    <?php endif ?>
                    <?= mensajeError($k, $errores) ?>
                </div>
        <?php endif;
        endforeach ?>

        <div class="row mt-3">
            <div class="col">
                <form action="" method="post">
                    <?= token_csrf() ?>
                    <a href="index.php" class="btn btn-info" role="button">
                        Volver
                    </a>

                </form>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </div>
</body>

</html>