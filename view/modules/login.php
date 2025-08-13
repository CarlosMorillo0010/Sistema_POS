<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 login_left" style="display: flex;">
        	<!-- <img class="logo_tipo" src="view/img/template/logotipo.png"> -->
        </div>
        <div class="col-md-4 login_right">
            <!-- .login-box -->
            <div class="login-box">
                <div class="login-box-body">

                		<div class="logo_right">
        					<!-- <img class="logo_movil" src="view/img/template/logotipo.png"> -->
        				</div>

                		<p class="login-box-msg">Ingresar al sistema</p>

	                    <form class="formulario__login" id="formulario__login" method="post">

	                        <div class="form-group" id="grupo__usuario">
	                        	<div class="input-group">
		                            <label for="usuarioLogin" class="formulario__label"><h5>Cedula de identidad:</h5></label>
		                            <div class="formulario__grupo-input">
		                                <input type="text" id="usuarioLogin" name="usuario"
		                                       class="form-control formulario__input" placeholder="Ingresar cedula de identidad"
		                                       pattern="[0-9]+">
		                            </div>
		                            <p class="formulario__input-error">La cedula de identidad solo puede llevar números.</p>
		                        </div>
	                        </div>

	                        <div class="form-group formulario__grupo" id="grupo__password">
	                        	<div class="input-group">
		                            <label for="password" class="formulario__label"><h5>Contraseña:</h5></label>
		                            <div class="formulario__grupo-input">
		                                <input type="password" id="password" name="password"
		                                       class="form-control formulario__input" placeholder="Ingresar contraseña"
		                                       minlength="5" maxlength="8">
		                            </div>
		                            <p class="formulario__input-error">La contraseña tiene que ser de 5 a 8 dígitos.</p>
		                        </div>
	                        </div>
	                        <div class="row text-center">
	                            <div class="col-xs-12">
	                                <button type="submit" id="enviar" class="btn btn-primary btn-login">
	                                    Ingresar
	                                </button>
	                            </div>
	                        </div>
	                        <div class="formulario__grupo" id="formulario__mensaje">
	                            <p class="alert alert-success text-center formulario__mensaje-exito" role="alert"
	                               id="formulario__mensaje-exito"><strong>Ha iniciado sesión</strong></p>
	                            <p class="alert alert-danger text-center formulario__mensaje-error" role="alert"
	                               id="formulario__mensaje-error"><strong>Tiene que llenar todos los campos</strong></p>
	                        </div>
	                	
	                        <?php
		                        $login = new ControllerUsers();
		                        $login->ctrIngresarUsuario();
	                        ?>
	                    </form>
					</div>
                </div>
                <!-- /.login-box-body -->
            </div>
            <!-- /.login-box -->
        </div>
    </div>
</div>