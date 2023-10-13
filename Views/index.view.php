<template-layouts-app xtitle="Index" xlang="es">
    <div class="m-5 p-5 text-center">
        <?php if(!empty($error) ): ?>
            <div class="alert alert-danger">
            <?php echo $error ?? '' ?>
            </div>
        <?php endif; ?>
        <strong class="h1 text-primary bg-grey">
            PRUEBA TÉCNICA DOUBLE V PARTNERS
        </strong>

        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-4">
            <form action="consulta.php" method="GET" role="form">
                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input class="form-control" type="text" id="username" name="username" value="<?php echo $username ?? '' ?>" required minlength="4" oninput="dvp(this.value)">
                    <?php if(!empty($error2) ): ?>
                    <span class="text-danger"><?php echo $error2 ?? '' ?></span>
                    <?php endif; ?>
                </div>
                <input type="submit" class="btn btn-success mt-3" value="Consultar" />
                <input type="reset" class="btn btn-danger mt-3" id="reset">
            </form>
        </div>
        </div>
        <div id="chartdiv"></div>
        <?php if(!empty($users) ): ?>
        <div class="table-responsive mt-3">
            <table class="theadcolor table table-striped table-hover table-checkable dataTable no-footer dtr-inline">
                <thead class="table-dark">
                    <th>Avatar</th>
                    <th>Id</th>
                    <th>Usuario</th>
                    <th>Ruta</th>
                </thead>
                <tbody>
                    <?php foreach ($users as $key => $value): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $value['avatar_url'] ?>" alt="" style="max-width: 100px;">
                        </td>
                        <td>
                            <?php echo $value["id"] ?>
                        </td>
                        <td>
                            <?php echo $value["login"] ?>
                        </td>
                        <td>
                            <a href="component.php?login=<?php echo $value["login"] ?? '' ?>" target="_blank" style="text-decoration: none;">🔗</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p>Mostranto <?php echo $filterCount ?> de <?php echo $total ?> registros</p>
        <div class="btn-group">
            <a href="<?php  echo BASE_URL.'/Modulos/crud.php?action=index&page='.$previousPage ?>" class="btn btn-sm btn-secondary <?php echo $previousBtn ?>">
                Anterior
            </a>
            <button class="btn btn-sm btn-light"><?php echo $currentPage ?>
            </button>
            <a href="<?php  echo BASE_URL.'/Modulos/crud.php?action=index&page='.$nextPage ?>" class="btn btn-sm btn-secondary <?php echo $nextBtn ?>">
                Siguiente
            </a>
        </div>
        <?php endif; ?>
    </div>
    <slot-js>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js">
        </script>
        <script>
            function dvp(val) {
                let value = val.toLowerCase();
                if (value.includes("doublevpartners")) {
                    document.getElementById('reset').click();
                }
            }
        </script>
    </slot-js>
</template-layouts-app>