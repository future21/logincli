<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

class Conexion  // se declara una clase para hacer la conexion con la base de datos
{
	var $con;
	function Conexion()
	{

		require_once("configuracion.php");
		// se definen los datos del servidor de base de datos
		//$conection['server']=$conx->db($conection['server']);  //host
		//$conection['user']=$conx->db($conection['user']);         //  usuario
		//$conection['pass']=$conx->db($conection['pass']);             //password
		//$conection['base']=$conx->db($conection['base']);           //base de datos

		// crea la conexion pasandole el servidor , usuario y clave
		$conect= mysql_connect(server,user,pass);

		if ($conect) // si la conexion fue exitosa , selecciona la base
		{
			mysql_select_db(base);
			$this->con=$conect;
		}
	}
	function getConexion() // devuelve la conexion
	{
		return $this->con;
	}
	function Close()  // cierra la conexion
	{
		mysql_close($this->con);
	}

}
class sQuery   // se declara una clase para poder ejecutar las consultas, esta clase llama a la clase anterior
{

	var $coneccion;
	var $consulta;
	var $resultados;
	function sQuery()  // constructor, solo crea una conexion usando la clase "Conexion"
	{
		$this->coneccion= new Conexion();
	}
	function executeQuery($cons)  // metodo que ejecuta una consulta y la guarda en el atributo $pconsulta
	{
		$this->consulta= mysql_query($cons,$this->coneccion->getConexion());
		return $this->consulta;
	}
	function getResults()   // retorna la consulta en forma de result.
	{return $this->consulta;}

	function Close()		// cierra la conexion
	{$this->coneccion->Close();}

	function Clean() // libera la consulta
	{mysql_free_result($this->consulta);}

	function getResultados() // debuelve la cantidad de registros encontrados
	{return mysql_affected_rows($this->coneccion->getConexion()) ;}

	function getAffect() // devuelve las cantidad de filas afectadas
	{return mysql_affected_rows($this->coneccion->getConexion()) ;}

    function fetchAll()
    {
        $rows=array();
		if ($this->consulta)
		{
			while($row=  mysql_fetch_array($this->consulta))
			{
				$rows[]=$row;
			}
		}
        return $rows;
    }

}

class logincli
{
	var $cliente;     //se declaran los atributos de la clase, que son los atributos del cliente
    var $tiempo;
	var $email;
	Var $pass;
    var $salt;
    var $created_at;
	Var $id;

    public static function getClientes()
		{
			$obj_cliente=new sQuery();
			$obj_cliente->executeQuery("select * from logincli"); // ejecuta la consulta para traer al cliente

			return $obj_cliente->fetchAll(); // retorna todos los clientes
		}

	function logincli($nro=0) // declara el constructor, si trae el numero de cliente lo busca , si no, trae todos los clientes
	{
		if ($nro!=0)
		{
			$obj_cliente=new sQuery();
			$result=$obj_cliente->executeQuery("select * from logincli where id = $nro"); // ejecuta la consulta para traer al cliente
			$row=mysql_fetch_array($result);
			$this->id=$row['id'];
			$this->cliente=$row['cliente'];
			$this->tiempo=$row['tiempo'];
			$this->email=$row['email'];
			$this->pass=$row['pass'];
			$this->salt=$row['salt'];
			$this->created_at=$row['created_at'];
		}
	}

		// metodos que devuelven valores
	function getID()
	 { return $this->id;}
	function getCliente()
	 { return $this->cliente;}
	function getTiempo()
	 { return $this->tiempo;}
	function getEmail()
	 { return $this->email;}
	function getPass()
	 { return $this->pass;}
	function getSalt()
	 { return $this->salt;}
	function getCreated_at()
	 { return $this->created_at;}

		// metodos que setean los valores
	function setCliente($val)
	 { $this->cliente=$val;}
	function setTiempo($val)
	 {  $this->tiempo=$val;}
	function setEmail($val)
	 {  $this->email=$val;}
	function setPass($val)
	 {  $this->pass=$val;}
	function setsalt($val)
	 {  $this->salt=$val;}
	function setcreated_at($val)
	 {  $this->created_at=$val;}

    function save()
    {
        if($this->id)
        {$this->updateCliente();}
        else
        {$this->insertCliente();}
    }
	private function updateCliente()	// actualiza el cliente cargado en los atributos
	{
			$subject = "Notificacion de cambio de Datos";    //"Change Password Notification";
			$message = "Bienvenido Usuario, \n\nTus datos han sido cambiados correctamente. \n\nSaludos, \nunocomaseis.com Team.";
			//"Hello User,\n\nYour Password is sucessfully changed.\n\nRegards,\nfuturesoft.es Team.";
			$from = "admin@unocomaseis.com";
			$headers = "From:" . $from;
			$obj_cliente=new sQuery();
			if (isUserExisted($this->email)) {
				$salt = $this->salt;
				//$encrypted_password = $this->encrypted_password;
					$hash = hashSSHA($this->pass);
					$encrypted_password = $hash["encrypted"]; // encrypted password
					$salt = $hash["salt"]; // salt
                    $dat=date_format($this->tiempo, 'Y-m-d');
			        $query="update logincli set cliente='$this->cliente', tiempo='$dat',email='$this->email',pass='$encrypted_password',salt='$salt' where id = '$this->id'";
				send_mail($email,$subject,$message,$headers);
			    $obj_cliente->executeQuery($query); // ejecuta la consulta para traer al cliente
			    return $obj_cliente->getAffect(); // retorna todos los registros afectados

            } else {
              Alert("Email ya existe");
            }

	}
	private function insertCliente()	// inserta el cliente cargado en los atributos
	{
			$subject = "Registro";                         //"Registration";
			$message = "Bienvenido $fname,\n\nTe has registrado correctamente en nuestro servicio.\n\nSaludos, \nAdministrador unocomaseis.com";
			//"Hello $fname,\n\nYou have sucessfully registered to our service.\n\nRegards,\nAdmin.";
			$from = "admin@unocomaseis.com";
			$headers = "From:" . $from;

			if (isUserExisted($this->email)) {
                Alert("Usuario ya existe");
            } else if(!validEmail($this->email)) {
                Alert("Email inválido");
            } else {
			$hash = hashSSHA($this->pass);
			$encrypted_password = $hash["encrypted"]; // encrypted password
			$salt = $hash["salt"]; // salt

			$obj_cliente=new sQuery();
			$query="insert into logincli (id, cliente, tiempo, email,pass,salt,created_at) values (NULL, '$this->cliente', '$this->tiempo','$this->email','$this->pass','$salt',NOW() )";
			//send_mail($email,$subject,$message,$headers);

			$obj_cliente->executeQuery($query); // ejecuta la consulta para traer al cliente
			return $obj_cliente->getAffect(); // retorna todos los registros afectados
            }
	}
	function delete()	// elimina el cliente
	{
			$obj_cliente=new sQuery();
			$query="delete from logincli where id=$this->id";
			$obj_cliente->executeQuery($query); // ejecuta la consulta para  borrar el cliente
			return $obj_cliente->getAffect(); // retorna todos los registros afectados

	}

}

function cleanString($string)
{
    $string=trim($string);
    $string=mysql_escape_string($string);
	$string=htmlspecialchars($string);

    return $string;
}

function getUserByEmailAndPassword($email, $password, $encr=0) {
    $obj_cliente=new sQuery();
	$result = $obj_cliente->executeQuery("SELECT * FROM logincli WHERE email = '$email'");
	// check for result
	$no_of_rows = mysql_num_rows($result);
	if ($no_of_rows > 0) {
        $result = mysql_fetch_array($result);
		$salt = $result['salt'];
		$encrypted_password = $result['encrypted_password'];
		if ($encr==0) {
		    $hash = checkhashSSHA($salt, $password);
			//$result = $hash
			// check for password equality
        } else {
            $hash = $password;
        }
		if ($encrypted_password == $hash) {
		    // user authentication details are correct
			return $result;
		}
	} else {
	    // user not found
		return false;
	}
}

function isUserExisted($email) {
			$obj_cliente=new sQuery();
			$result = $obj_cliente->executeQuery("SELECT email from logincli WHERE email = '$email'"); // ejecuta la consulta para traer al cliente

            //$result = mysql_query("SELECT email from logincli WHERE email = '$email'");
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0) {
				// user existed
				return true;
			} else {
				// user not existed
				return false;
			}
}

function forgotPassword($forgotpassword, $newpassword, $salt) {
			$obj_cliente=new sQuery();
			$result = $obj_cliente->executeQuery("UPDATE logincli SET encrypted_password = '$newpassword',salt = '$salt' WHERE email = '$forgotpassword'"); // ejecuta la consulta para traer al cliente

			//$result = mysql_query("UPDATE ofe_users SET encrypted_password = '$newpassword',salt = '$salt' WHERE email = '$forgotpassword'");

			if ($result) {
				return true;
			} else {
				return false;
			}

}


function random_string() {
			$character_set_array = array();
			$character_set_array[] = array('count' => 7, 'characters' => 'abcdefghijklmnopqrstuvwxyz');
			$character_set_array[] = array('count' => 1, 'characters' => '0123456789');
			$temp_array = array();
			foreach ($character_set_array as $character_set) {
				for ($i = 0; $i < $character_set['count']; $i++) {
					$temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
				}
			}
			shuffle($temp_array);
			return implode('', $temp_array);
}

function hashSSHA($password) {

	    $salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
}

	/**
	* Decrypting password
	* returns hash string
	*/
function checkhashSSHA($salt, $password) {

	    $hash = base64_encode(sha1($password . $salt, true) . $salt);

		return $hash;
}

function validEmail($email) {
		   $isValid = true;
		   $atIndex = strrpos($email, "@");
		   if (is_bool($atIndex) && !$atIndex) {
			  $isValid = false;
		   } else {
			  $domain = substr($email, $atIndex+1);
			  $local = substr($email, 0, $atIndex);
			  $localLen = strlen($local);
			  $domainLen = strlen($domain);
			  if ($localLen < 1 || $localLen > 64) {
				 // local part length exceeded
				 $isValid = false;
			  } else if ($domainLen < 1 || $domainLen > 255) {
				 // domain part length exceeded
				 $isValid = false;
			  } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
				 // local part starts or ends with '.'
				 $isValid = false;
			  } else if (preg_match('/\\.\\./', $local)) {
				 // local part has two consecutive dots
				 $isValid = false;
			  } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
				 // character not valid in domain part
				 $isValid = false;
			  } else if (preg_match('/\\.\\./', $domain)) {
				 // domain part has two consecutive dots
				 $isValid = false;
			  } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
				 // character not valid in local part unless
				 // local part is quoted
				 if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
					$isValid = false;
				 }
			  }
			  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
				 // domain not found in DNS
				 $isValid = false;
			  }
		   }
		   return $isValid;
}

function multidimensional_search($parents, $searched) {
  if (empty($searched) || empty($parents)) {
    return false;
  }

  foreach ($parents as $key => $value) {
    $exists = true;
    foreach ($searched as $skey => $svalue) {
      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
    }
    if($exists){ return $svalue; }
  }

  return false;
}

function alr($msg) {
    echo '<script type="text/javascript">alert("'. $msg .'")</script>';
}

function largo($movil) {
    $obj=new sQuery();

	$result = $obj->executeQuery("SELECT * FROM abr_abreviados WHERE n_largo = '$movil'");
	// check for result
	$no_of_rows = mysql_num_rows($result);
	//echo $movil;
	if ($no_of_rows > 0) {
		$result = mysql_fetch_array($result);
		return $result;
	} else {
		return false;
	}
}

function corto($movil) {
    $obj=new sQuery();

	$result = $obj->executeQuery("SELECT * FROM abr_abreviados WHERE n_corto = '$movil'");
	// check for result
	$no_of_rows = mysql_num_rows($result);
	//echo $movil;
	if ($no_of_rows > 0) {
		$result = mysql_fetch_array($result);
		return $result;
	} else {
		return false;
	}
}

function send_mail($email,$subject,$message,$headers) {
	//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
	require("class.phpmailer.php");

	$mail             = new PHPMailer();

	//$body             = file_get_contents('contents.html');
	$body			  = $message;
	//$body             = eregi_replace("[\]",'',$body);

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "send.one.com"; // SMTP server
	//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
    //$mail->SMTPSecure = "ssl";
	//$mail->Host       = "mail.yourdomain.com"; // sets the SMTP server
	$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "send@macronukes.com"; // SMTP account username
	$mail->Password   = "jgp2165";        // SMTP account password

	$mail->SetFrom('send@macronukes.com', 'admin@unocomaseis.com');

	//$mail->AddReplyTo("name@yourdomain.com","First Last");

	$mail->Subject    = $subject;

	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($body);

	$address = $email;
	$mail->AddAddress($address, $address);

	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	if(!$mail->Send()) {
	  alr("Mailer Error: " . $mail->ErrorInfo);
	} else {
	  alr("Mensaje enviado!");
	}

}


?>

