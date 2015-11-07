<?php
	include("datos.php");
	$usuario = $_GET["usuario"];
	$cliente = $_GET["cliente"];
	$clave = $_GET["clave"];
	$salon = $_GET["salon"];
	$nombre = $_GET["nombre"];
	$tipo = $_GET["tipo"];
	$fecha1 = $_GET["fecha1"];
	$fecha2 = $_GET["fecha2"];
	$fecha3 = $_GET["fecha3"];
	$dir = $_GET["dir"];
	$inv = $_GET["noInv"];
	$tel = $_GET["tel"];
	try{
		$bd=new PDO($dsnw, $userw, $passw, $optPDO);
		$sql = "select * from cotizaciones where id_cotizacion = $clave";
		$res = $bd->query($sql);
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		if(!isset($res[0]))
		{
			$sql = "insert into cotizaciones (clave, id_empresa, id_usuario, id_cliente, salon, nombre, id_tipo, estatus, fechaevento, fechamontaje, fechadesmont, dirEvento, noinvitados, telefonoContacto)
					values($clave, 1, $usuario, $cliente, '$salon', '$nombre', $tipo, 1, '$fecha1', '$fecha2', '$fecha3', '$dir', $inv, '$tel')";
			$bd->query($sql);
			$r = "La cotizacion se añadio corectamente";
			echo $r;
		}
		else{
			$r = "La cotizacion ya existe";
			echo $r;
		}
	}catch(PDOException $err){
		$r = $err->getMessage();
		echo $r;
	}
?> 