<?php
set_time_limit(300);
// Conexión a la base de datos
$host = "15.235.114.116"; 
$usuario = "fomentol_practica";  
$clave = "COFODEP2025**";         
$base_de_datos = "fomentol_tarjetavecino"; 

$conexion = new mysqli($host, $usuario, $clave, $base_de_datos);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$nombres_masculinos = [];

$nombres_masculinos = array_merge($nombres_masculinos, [
    "matias", "cristobal", "vicente", "agustin", "benjamin", "maximo", "lucas", "emiliano", "julian", "franco",
    "renato", "bastian", "alonso", "bruno", "thiago", "martin", "joel", "emilio", "fabian", "gonzalo",
    "ignacio", "sebastian", "damian", "esteban", "felipe", "fernando", "francisco", "gabriel", "hector", "javier",
    "jorge", "josue", "julio", "leonardo", "luis", "manuel", "marcos", "mario", "miguel", "nicolas",
    "oscar", "pablo", "pedro", "rafael", "ramiro", "raul", "ricardo", "roberto", "rodrigo", "santiago",
    "sergio", "tomas", "victor", "eduardo", "enrique", "gustavo", "hernan", "isidro", "jaime", "jesus",
    "mauricio", "ramon", "salvador", "samuel", "simon", "tobias", "valentin", "baltazar", "camilo", "dario",
    "luciano", "mariano", "patricio", "valentin", "ariel", "baltazar", "camilo", "dario", "emilio", "fabian",
    "gonzalo", "ignacio", "julian", "leandro", "luciano", "mariano", "matias", "maximiliano", "nicolas", "patricio",
    "renato", "sebastian", "valentin", "vicente", "benjamin", "cristobal", "emiliano", "franco", "joel", "lucas",
    "maximo", "thiago", "bruno", "alonso", "bastian", "renato", "julian", "emilio", "fabian", "gonzalo",
    "ignacio", "sebastian", "damian", "esteban", "felipe", "fernando", "francisco", "gabriel", "hector", "javier",
    "jorge", "josue", "julio", "leonardo", "luis", "manuel", "marcos", "mario", "miguel", "nicolas",
    "oscar", "pablo", "pedro", "rafael", "ramiro", "raul", "ricardo", "roberto", "rodrigo", "santiago",
    "sergio", "tomas", "victor", "eduardo", "enrique", "gustavo", "hernan", "isidro", "jaime", "jesus",
    "mauricio", "ramon", "salvador", "samuel", "simon", "tobias", "valentin", "vicente", "alonso", "ariel",
    "baltazar", "camilo", "dario", "emilio", "fabian", "gonzalo", "ignacio", "julian", "leandro", "luciano",
    "mariano", "matias", "maximiliano", "nicolas", "patricio", "renato", "sebastian", "valentin", "vicente", "benjamin",
    "cristobal", "emiliano", "franco", "joel", "lucas", "maximo", "thiago", "bruno", "alonso", "bastian",
    "renato", "julian", "emilio", "fabian", "gonzalo", "ignacio", "sebastian", "damian", "esteban", "felipe",
    "fernando", "francisco", "gabriel", "hector", "javier", "jorge", "josue", "julio", "leonardo", "luis",
    "manuel", "marcos", "mario", "miguel", "nicolas", "oscar", "pablo", "pedro", "rafael", "ramiro",
    "raul", "ricardo", "roberto", "rodrigo", "santiago", "sergio", "tomas", "victor", "eduardo", "enrique",
    "gustavo", "hernan", "isidro", "jaime", "jesus", "mauricio", "ramon", "salvador", "samuel", "simon",
    "tobias", "valentin", "vicente", "alonso", "ariel", "baltazar", "camilo", "dario", "emilio", "fabian",
    "gonzalo", "ignacio", "julian", "leandro", "luciano", "mariano", "matias", "maximiliano", "nicolas", "patricio",
    "renato", "sebastian", "valentin", "vicente", "benjamin", "cristobal", "emiliano", "franco", "joel", "lucas",
    "maximo", "thiago", "bruno", "alonso", "bastian", "renato", "julian", "emilio", "fabian", "gonzalo",
    "ignacio", "sebastian", "damian", "esteban", "felipe", "fernando", "francisco", "gabriel", "hector", "javier",
    "jorge", "josue", "julio", "leonardo", "luis", "manuel", "marcos", "mario", "miguel", "nicolas",
    "oscar", "pablo", "pedro", "rafael", "ramiro", "raul", "ricardo", "roberto", "rodrigo", "santiago",
    "sergio", "tomas", "victor", "eduardo", "enrique", "gustavo", "hernan", "isidro", "jaime", "jesus",
    "mauricio", "ramon", "salvador", "samuel", "simon", "tobias", "valentin", "vicente", "alonso", "ariel",
    "baltazar", "camilo", "dario", "emilio", "fabian", "gonzalo", "ignacio", "julian", "leandro", "luciano",
    "mariano", "matias", "maximiliano", "nicolas", "patricio", "renato", "sebastian", "valentin", "vicente", "benjamin",
    "cristobal", "emiliano", "franco", "joel", "lucas", "maximo", "thiago", "bruno", "alonso", "bastian",
    "renato", "julian", "emilio", "fabian", "gonzalo", "ignacio", "sebastian", "damian", "esteban", "felipe",
    "fernando", "francisco", "gabriel", "hector", "javier", "jorge", "josue", "julio", "leonardo", "luis",
    "manuel", "marcos", "mario", "miguel", "nicolas", "oscar", "pablo", "pedro", "rafael", "ramiro",
    "raul", "ricardo", "roberto", "rodrigo", "santiago", "sergio", "tomas", "victor", "eduardo", "enrique",
    "gustavo", "hernan", "isidro", "jaime", "jesus", "mauricio", "ramon", "salvador", "samuel", "simon",
    "tobias", "valentin", "vicente", "alonso", "ariel", "baltazar", "camilo", "dario", "emilio", "fabian",
    "gonzalo", "ignacio", "julian", "leandro", "luciano", "mariano", "matias", "maximiliano", "nicolas", "patricio",
    "renato", "sebastian", "valentin", "vicente", "benjamin", "cristobal", "emiliano", "franco", "joel", "lucas",
    "maximo", "thiago", "bruno", "alonso", "bastian", "renato", "julian", "emilio", "fabian", "gonzalo",
    "ignacio", "sebastian", "damian", "esteban", "felipe", "fernando", "francisco", "gabriel", "hector", "javier",
    "jorge", "josue", "julio", "leonardo", "luis", "manuel", "marcos", "mario", "miguel", "nicolas",
    "oscar", "pablo", "pedro", "rafael","gerson","ernesto","marco","jairo","francisco","marcelo","nelson","edgar","edgard",
    "rodolfo","jorge","jeorge","leonardo","jonathan","jhonatan","daniel","waldo","carlos","claudio","benedicto",
    "rishard","jean","ruben","eduardo","edson","renato","raul","franco","franko","pablo","enrique","alvaro","david",
    "maicol","daniel adolfo","mario","erick","ivan","clemente","arturo","sebastian","jaime","jose", "guillermo", "CESAR", "HUGO", "RICHARD", "ANTONIO", "JUAN", "nicolás", "ARMADOR", "angelo",
    "JOSE", "GUILLERMO", "JUAN", "ABRAHAM", "DILLMAN", "SIGFRIDO", "CHRISTIAN", "JUAN", "andres", "JUAN",
    "ALEJANDRO", "BENITO", "DANILO", "ADRIÁN", "ANTONIO", "ALADINO", "ALEXIS", "hugo", "raúl", "ALEJANDRO",
    "GERMAN", "WILMER", "vladimir", "CESAR", "MAC", "josé", "RAÚL","NICOLAZ","diego","fabio","exequiel","Lukas","lucas","dylan","aaron","nibaldo","franklin","wilfred","enzo","cristian",
    "reynaldo","alejo","alejandro","kevin","joaquin","elias","alex","axel","freddy","giovanni","marizio","isaac","orlando","eric","erik","muriel","samuel","benjamin","vinzenso","anton",
    "rivaldo","leo","tadeo","roman","santiago","gabo","gonza","patricio","pepe","michael","milton","nelson","waldo","frederick","juan carlos","sergio","christian","rogers","andre",
    "danilo","antonio","agustin","aladino","hugo","manuel","jesus","tito","braulio","alan","guillermo","raul","cesar","ramon","yerko","victor","rene","justo","walter","paolo","miguel","hector",
    "aladino","edson"]);

$sql = "SELECT ctrtec_id, ctrtec_nombre FROM ctrtecnicos WHERE genero IS NULL";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $id = $fila["ctrtec_id"];
        $nombre = explode(" ", $fila["ctrtec_nombre"])[0]; 

      
        if (in_array(strtolower($nombre), array_map('strtolower', $nombres_masculinos))) {
            $genero = "M";
        } else {
            $genero = "";
        }

        $update_sql = "UPDATE ctrtecnicos SET genero = '$genero' WHERE ctrtec_id = $id";
        $conexion->query($update_sql);
    }
    echo "Actualización de género completada.";
} else {
    echo "No hay nombres sin género en la base de datos.";
}

$conexion->close();
?>