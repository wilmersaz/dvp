<template-layouts-app xtitle="Index" xlang="es">
<slot-head>
    <style>
        body {
            background-color: #f0f0f0
        }
        .created-at {
            font-size: 14px;
            color: #a0a0a0;
            font-weight: bold;
        }

    </style>
    </slot-head>
    <div class="m-5 p-5 text-center">
        <strong class="h1 text-primary bg-grey">
            PRUEBA TÉCNICA DOUBLE V PARTNERS
        </strong>

        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-4">
                <div class="container mt-4 mb-4 p-3 d-flex justify-content-center">
                    <div class="card p-4">
                        <div class=" image d-flex flex-column justify-content-center align-items-center">
                            <img src="<?php echo $currentUser[0]["avatar_url"] ?>" height="100" width="100" />
                            <span class="h5 mt-3">
                                <?php echo $currentUser[0]["name"] ?>
                            </span>
                            <div class=" d-flex mt-2">
                                <a href="<?php echo $currentUser[0]["html_url"] ?>" class="btn btn-dark"
                                    target="_blank">Cuenta Github 
                                    <i class="fa fa-github"></i>
                                </a>
                            </div>
                            <div class=" px-2 rounded mt-4 bg-light">
                                <span class="created-at">Registrado el:
                                    <?php echo date('Y-m-d H:i:s', strtotime($currentUser[0]["created_at"])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <slot-js>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </slot-js>
</template-layouts-app>