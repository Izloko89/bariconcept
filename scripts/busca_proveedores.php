<?php session_start();
header("Content-type: application/json");
$empresaid=$_SESSION["id_empresa"];
$term=$_GET["term"];
include("datos.php");

	$total1 =0 ;
try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	//sacar los campos para acerlo más autoámtico
	$campos=array();
	$res=$bd->query("DESCRIBE proveedores;");
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $a=>$c){
		$campos[$a]=$c["Field"];
	}
	
	$res=$bd->query("SELECT * FROM proveedores WHERE id_empresa=$empresaid AND nombre LIKE '%$term%' OR clave = '$term';");
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $i=>$v){
		$r[$i]["label"]=$v["nombre"];
		$r[$i]["form"]="#f_clientes";
		foreach($campos as $campo){
			$r[$i][$campo]=$v[$campo];
		}
	}
		$sql="SELECT * FROM proveedores_movimientos WHERE id_proveedor=$term AND movimiento = 'pago';";
	$res=$bd->query($sql);
	
	$tabla='<center><table class=table><tr>
		<td style="padding-left: 20px;padding-right: 20px;">Fecha</td>
		<td style="padding-left: 20px;padding-right: 20px;">Evento</td>
		<td style="padding-left: 20px;padding-right: 20px;">Egreso</td>
		<td style="padding-left: 20px;padding-right: 50px;">Saldo</td>
	</tr>';
	$id=1;
	$total=0;
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $d){
		$total = $total - $d["cantidad"];
		$tabla.='<tr>';
		$tabla.="<td>".$d["fecha"].'</td>';
		$tabla.="<td>".$d["movimiento"].'</td>';
		$tabla.='<td>'.$d["cantidad"] .'</td>';
		if($total<0){
			$tabla.='<td><font color="red">' . $total . '</font></td>';
		} else{
			$tabla.="<td>".$total."</td>";
		}
		$tabla.='</tr>';
		$id++;
		//$total+=$d["cantidad"];
	}
		$sql="SELECT * FROM proveedores_movimientos WHERE id_proveedor=$term AND movimiento = 'renta';";
		$sql1="SELECT * FROM proveedores_movimientos WHERE id_proveedor=$term AND movimiento = 'compra';";
	$res=$bd->query($sql);
	$res1=$bd->query($sql1);
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $d){
		$total1 += $d["cantidad"];
	}
	foreach($res1->fetchAll(PDO::FETCH_ASSOC) as $d){
		$total1 += $d["cantidad"];
	}
	$ttl = 0;
	$ttl = $total1 - $total;
	//$tabla.='<tr><td></td><td style="text-align:right;">Total=</td><td>'.$ttl.'</td></tr>';
	$tabla.="</table></center>";
	$r["tabla"] = $tabla;
}catch(PDOException $err){
	echo $err->getMessage();
}

echo json_encode($r);
?>