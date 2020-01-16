<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Borrar departamento</title>
</head>

<body>
    <div class="container">
        <?php
        require __DIR__ . '/../comunes/auxiliar.php';
        require __DIR__ . '/auxiliar.php';


        function borrarDepEmp($pdo, $tabla, $id)
        {
            $sent = $pdo->prepare("DELETE
                                            FROM empleados
                                            WHERE departamento_id = :id");
            $sent->execute(['id' => $id]);

            $sent = $pdo->prepare("DELETE
                                            FROM $tabla
                                            WHERE id = :id");
            $sent->execute(['id' => $id]);

            header('Location: /departamentos/index.php');
        }

        barra();

        if (!isset($_COOKIE['aceptar'])) {
            alert('Este sitio usa cookies. <a href="/comunes/cookies.php">Estoy de acuerdo</a>', 'info');
        }

        $pag = recogerNumPag();
        $orden = recogerOrden();
        $pdo = conectar();

        if (es_GET()) {
            alert();
            if (isset($_GET['id'])) {
                $id = trim($_GET['id']);
            } else {
                aviso('Departamento no indicado.', 'danger');
                header('Location: /departamentos/index.php');
                return;
            }
        } else {
            if (isset($_POST['id'])) {
                $id = trim($_POST['id']);

                borrarDepEmp($pdo, 'departamentos', $id);
            }
        }

        $errores = [];
        $args = comprobarParametros(PAR, REQ_GET, $errores);

        $sent = $pdo->prepare('SELECT e.*
                                FROM empleados e
                                LEFT JOIN departamentos d
                                ON e.departamento_id = d.id
                                WHERE d.id = :id');
        $sent->execute(['id' => $id]);

        $count = $pdo->prepare('SELECT count(e.*)
                                FROM empleados e
                                LEFT JOIN departamentos d
                                ON e.departamento_id = d.id
                                WHERE d.id = :id');
        $count->execute(['id' => $id]);
        $stmt = $count->fetchColumn();

        $emp_id = [];

        for ($i = 0; $i < $stmt; $i++) {
            $args = $sent->fetch(PDO::FETCH_ASSOC);
            if ($args === false) {
                aviso('Error al modificar fila.', 'danger');
                header('Location: index.php');
                return;
            } else {
                array_push($emp_id, $args['id']);

                dibujarTabla2($sent, $errores, $args);
            }
        }

        function dibujarTabla2($sent, $errores, $args)
        {
        ?>
            <div class="row mt-4">
                <div class="col-8 offset-2">
                    <table class="table">
                        <thead>
                            <?php foreach ($args as $k => $v) : ?>
                                <th scope="col">
                                    <?php if ($k === 'id') :
                                    else : ?>
                                        <?= $k ?>
                                    <?php endif ?>
                                </th>
                            <?php endforeach ?>
                        </thead>
                        <tbody>

                            <tr scope="row">
                                <?php foreach ($args as $k => $v) : ?>
                                    <?php if (isset($args[$k])) : ?>
                                        <?php if ($k === 'id') :
                                        else : ?>
                                            <td><?= $v ?></td>
                                        <?php endif ?>
                                    <?php else : ?>

                                    <?php endif ?>
                                <?php endforeach ?>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col text-center">
                <h2>¿Está seguro de que desea borrar el departamento?</h2>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button class="btn btn-danger" type="submit">
                        Sí
                    </button>
                    <a href="/departamentos/index.php" class="btn btn-info" role="button">No</a>
                </form>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>