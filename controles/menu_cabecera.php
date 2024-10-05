<?php
verifico();
$con = new conexion();
$con->conectar();
$fotos = pg_query("SELECT usu_foto FROM usuarios where usu_cod=$_SESSION[id]");
while ($foto = pg_fetch_row($fotos)) {
    $foto2 = $foto[0];
}
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="../controles/inicio.php" class="site_title"><i class="fa fa-home"></i> <span>ELECTRO</span></a>
        </div>
        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="<?php echo "$foto2"; ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Bienvenido/a</span>
                <h2><?php echo $_SESSION['fun_nom'] ?></h2>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /menu profile quick info -->
        <br />
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3 class="page-header" style="color: #ffffff;">
                    Electrodomesticos SA
                </h3>
                <ul class="nav side-menu">
                    <li><a href="../controles/inicio.php"><i class="fa fa-windows fa-fw"></i><strong>Inicio</strong></a></li>
                    <div class="menu_section">
                        <h3 class="page-header" style="color: #ffffff;">
                            REFERENCIALES
                        </h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-shopping-cart fa-fw "></i> Referencial Compras <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu"><a href="../usuarios/usuario.php">Usuario</a></li>
                                    <li class="sub_menu"><a href="../perfiles/perfil.php">Perfiles</a></li>
                                    <li class="sub_menu"><a href="../personas/persona.php">Personas</a></li>
                                    <li class="sub_menu"><a href="../funcionarios/funcionario.php">Funcionario</a></li>
                                    <li class="sub_menu"><a href="../clientes/cliente.php">Cliente</a></li>
                                    <li class="sub_menu"><a href="../proveedores/proveedor.php">Proveedor</a></li>
                                    <li><a href="../cargos/cargo.php">Cargo</a>
                                    <li><a href="../profesiones/profesion.php">Profesion</a>
                                    <li><a href="../generos/genero.php">Genero</a>
                                    <li><a href="../estados_civiles/estado_civil.php">Estado Civil</a>
                                    <li><a href="../tipo_personas/tipo_persona.php">Tipo Persona</a>
                                    <li><a href="../especialidades/especialidad.php">Especialidad</a></li>
                                    <li><a href="../ciudades/ciudad.php">Ciudad</a>
                                    <li><a href="../nacionalidades/nacionalidad.php">Paises</a>
                                    <li><a href="../depositos/deposito.php">Deposito</a>
                                    <li><a href="../proveedor_timbrados/proveedor_timbrado.php">Proveedor Timbrado</a>
                                    <li><a href="../empresas/empresa.php">Empresa</a>
                                    <li><a href="../items/item.php">Items</a>
                                    <li><a href="../marcas/marca.php">Marcas</a>
                                    <li><a href="../marcas_items/marca_item.php">Marcas - Items</a>
                                    <li><a href="../motivos_ajustes/motivo_ajuste.php">Motivo Ajuste</a>
                                    <li><a href="../sucursales/sucursales.php">Sucursal</a>
                                    <li><a href="../tipo_ajustes/tipo_ajuste.php">Tipo Ajuste</a>
                                    <li><a href="../tipo_impuestos/tipo_impuesto.php">Tipo Impuesto</a>
                                    <li><a href="../tipo_items/tipo_item.php">Tipo de Item</a>
                                    <li><a href="../vehiculos/vehiculo.php">Vehiculo</a>
                                    <li><a href="../vehiculos_modelos/vehiculo_modelo.php">Vehiculo Modelos</a>
                                    <li><a href="../vehiculos_marcas/vehiculo_marca.php">Vehiculo Marcas</a></li>
                                </ul>
                            </li>

                            <li><a><i class="fa fa-home fa-fw"></i>Referencial Ventas<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="../cajas/caja.php">Caja</a>
                                    <li><a href="../timbrados/timbrado.php">Timbrados</a></li>
                                    <li><a href="../cajas_timbrados/caja_timbrado.php">Cajas - Timbrados</a></li>
                                    <li><a href="../forma_cobros/forma_cobro.php">Forma Cobro</a>
                                    <li><a href="../entidades_adheridas/entidad_adherida.php">Entidad Adherida</a>
                                    <li><a href="../entidades_emisoras/entidad_emisora.php">Entidad Emisora</a>
                                    <li><a href="../marca_tarjetas/marca_tarjeta.php">Marca Tarjeta</a>
                                </ul>
                            </li>

                            <li><a><i class="fa fa-usd fa-fw"></i>Referencial Tesoreria<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="../bancos/banco.php">Bancos</a>
                                    <li><a href="../rubros/rubro.php">Rubros</a>
                                    <li><a href="../conceptos/concepto.php">concepto credito/debito bancarios</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="menu_section">
                        <h3 class="page-header" style="color: #ffffff;">
                            MOVIMIENTOS
                        </h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-shopping-cart fa-fw "></i> Gestion Compras <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                            </li>
                            <li class="sub_menu"><a href="../pedido_compras/pedido_compra.php">Pedido de Compras</a></li>
                            <li class="sub_menu"><a href="../presupuestos_proveedores/presupuesto_proveedor.php">Presupuesto Proveedor</a></li>
                            <li class="sub_menu"><a href="../ordenes_compra/orden_compra.php">Ordenes de Compras</a></li>
                            <li class="sub_menu"><a href="../compras/compra.php">Compras</a></li>
                            <li class="sub_menu"><a href="../ajustes/ajuste.php">Ajustes</a></li>
                            <li class="sub_menu"><a href="../transferencias/transferencia.php">Notas de Remision</a></li>
                            <li class="sub_menu"><a href="../notas_compras/nota_compra.php">Nota de Compras</a></li>
                        </ul>

                        <li><a><i class="fa fa-money "></i>Gestion Ventas<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li class="sub_menu"><a href="../pedido_ventas/pedido_venta.php">Pedido de Venta</a></li>
                                <li class="sub_menu"><a href="../ventas/venta.php">Ventas</a></li>
                                <li class="sub_menu"><a href="../aperturas_cierres/apertura_cierre.php">Apertura / Cierre / Arqueo </a></li>
                                <li class="sub_menu"><a href="../cobros/cobro.php">Cobros</a></li>
                                <li class="sub_menu"><a href="../notas_ventas/nota_venta.php">Notas Ventas</a></li>
                                <li class="sub_menu"><a href="../notas_remisiones/nota_remision.php">Notas Remsiones</a></li>
                            </ul>
                        </li>

                        <li><a><i class="fa fa-money "></i>Gestion Tesoreria<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li class="sub_menu"><a href="../facturas_varias/factura_varias.php">Facturas Varias</a></li>
                                <li class="sub_menu"><a href="../ordenes_pagos/orden_pago.php">Ordenes de pagos</a></li>
                                <li class="sub_menu"><a href="../impresiones_cheques/impresion_cheque.php">Impresiones de Cheques</a></li>
                                <!-- <li class="sub_menu"><a href="../notas_remisiones3/nota_remision2.php">Procesos Especiales</a></li> -->
                                <li class="sub_menu"><a href="../asignaciones_fondos_fijos/asignacion_fondo_fijo.php">Asignaciones Fondo Fijos</a></li>
                                <li class="sub_menu"><a href="../rendiciones_fondos_fijos/rendicion_fondo_fijo.php">Rendiciones Fondo Fijo</a></li>
                                <li class="sub_menu"><a href="../reposiciones_fondos_fijos/reposicion_fondo_fijo.php">Reposiciones Fondo Fijos</a></li>
                                <li class="sub_menu"><a href="../conciliaciones_bancarias/conciliacion_bancaria.php">Conciliaciones Bancarias</a></li>
                                <li class="sub_menu"><a href="../otros_creditos_debitos/otro_credito_debito.php">Otros Debitos y Creditos</a></li>
                                <li class="sub_menu"><a href="../registros_depositos/registro_deposito.php">Depositos Bancarios</a></li>
                                <li class="sub_menu"><a href="../liquidaciones_tarjetas/liquidacion_tarjeta.php">Liquidacion de Tarjetas</a></li>
                            </ul>
                        </li>
                </ul>
            </div>
            </ul>
            <div class="menu_section">
                <h3 class="page-header" style="color: #ffffff;">
                    REPORTES
                </h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-line-chart"></i> LISTADOS <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="../reportes_pedidos/reporte_pedido_compra.php">Pedido Compras</a></li>
                            <li><a href="../reportes_ordenes_compras/reporte_orden_compra.php">Ordenes Compras</a></li>
                            <li><a href="../reportes_compras/reporte_compra.php">Compras</a></li>
                            <li><a href="../reportes_pedidos_ventas/reporte_pedido_venta.php">Reporte de Pedido Ventas</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo "$foto2"; ?>" alt=""><?php echo $_SESSION['fun_nom'] ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="../clases/cerrar_sesion.php"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>