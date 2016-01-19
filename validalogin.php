<?php


//Recogemos las variables del usuario y del password
$usuario = $_REQUEST['usuario'];
$password = $_REQUEST['password'];


//Nos conectamos a la base de datos
include 'config.php';
$conexion = new mysqli($host, $username, $password, $db_name);
if ($conexion->connect_errno) {
    die("Error de conexion: $conexion->connect_error");
}

//evitamos un sql-injection
$usuario = $conexion->real_escape_string($usuario);
$pass = $conexion->real_escape_string($pass);

$sql = "SELECT * "
        . "FROM user "
        . "WHERE  username='$usuario' AND password=SHA2('$pass')";

$result = $conexion->query($sql);
$validado = FALSE;
while ($fila = $result->fetch_assoc()){
    //hay un usuario que cumple
    $validado = TRUE;
    $email=$fila['email'];
    $id_user = $fila['clave_usuario'];
}
if($validado){
    echo "Bienvenido al cuestionario<br>";
// Ahora toca buscar las preguntas para ponerlas en pantalla dentro de un form
    
   $sql = "SELECT *"
           . "FROM preguntas ";
   $result = $conexion->query($sql);
   echo '<form action="calificar.php" method="post">';
   while ($fila = $result->fetch_assoc()){
    //cogmeos la pregunta y las opciones
       $idp = $fila['id_pregunta'];
       $pregunta = $fila['pregunta'];
       $op1 = $fila['opcion1'];
       $op2 = $fila['opcion2'];
       $op3 = $fila['opcion3'];
       $op4 = $fila['opcion4'];
    //las presentamos
       echo '<b>',$pregunta,'</b><br>';
       echo "<input type='radio' name='$idp value='1'> $op1<br>";
       echo "<input type='radio' name='$idp value='2'> $op2<br>";
       echo "<input type='radio' name='$idp value='3'> $op3<br>";
       echo "<input type='radio' name='$idp value='4'> $op4<br>";
       echo "<hr>";
   }
   echo '<input type="submit"value="corregir">';
   echo '</form>';
}
else {echo "Usuario o contraseña incorrecta";}
$conexion->close();