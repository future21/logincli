<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once("rpcl/rpcl.inc.php");

//require_once("../api_php/main.php");
//require_once("smarty/libs/SmartyBC.class.php");
//Includes
use_unit("forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
include_once ("clase.php");// incluyo las clases a ser usadas
//require_once("api_php/include/DB_Functions.php");
redirect("main.php");

//Class definition
class Unit1 extends Page
{

    public $imgCancel = null;
    public $btnAceptar = null;
    public $lblTitle = null;
    public $lblPassword = null;
    public $edtPassword = null;
    public $edtUsuario = null;
    public $lblUsuario = null;
    public $imgOK = null;
    public $imagen = null;
    public $btnPass = null;

    function btnAceptarClick($sender, $params)
    {
      global $view;
      $view= new stdClass(); // creo una clase standard para contener la vista
      $view->disableLayout=false;// marca si usa o no el layout , si no lo usa imprime directamente el template

      $user = getUserByEmailAndPassword($this->edtUsuario->Text, $this->edtPassword->Text, 0);
      if ($user != false) {
    	  $response["Correcto"] = "Usuario encontrado";
    	  $response["nombre"] = $user["nombre"];
    	  $response["apellidos"] = $user["apellido"];
    	  $response["email"] = $user["email"];
    	  $response["password"] = $user["encrypted_password"];
          //echo $response["nombre"];
          $this->imagen->ImageSource = $this->imgOK->ImageSource;
          $this->imgOK->Visible = False;
          $this->imgCancel->Visible = False;
          redirect("main.php?op=1&email=".$user["email"]."&password=".urlencode($user["encrypted_password"]));
      }  else {
          //echo "Usuario Incorrecto";
          $this->imagen->ImageSource = $this->imgCancel->ImageSource;
          $this->imgOK->Visible = False;
          $this->imgCancel->Visible = False;
      }

      /*
      if (($this->edtUsuario->Text == 'admin') && ($this->edtPassword->Text == '1234'))
      {
        $this->imagen->ImageSource = $this->imgOK->ImageSource;
        $this->imgOK->Visible = False;
        $this->imgCancel->Visible = False;
      } else
      {
        $this->imagen->ImageSource = $this->imgCancel->ImageSource;
        $this->imgOK->Visible = False;
        $this->imgCancel->Visible = False;
      }
      */
    }

    function Unit1Show($sender, $params)
    {
      $this->imgCancel->Left = $this->imgOK->Left;
    }
    function btnPassClick($sender, $params)
    {
			$forgotpassword = $this->edtUsuario->Text;
            //$id=intval($_POST['id']);
            //$client=new Usuarios($id);

			$randomcode = random_string();


			$hash = hashSSHA($randomcode);
			$encrypted_password = $hash["encrypted"]; // encrypted password
			$salt = $hash["salt"];
			$subject = "Recuperacion de password";                      //"Password Recovery";
			$message = "Bienvenido Usuario,\n\nTu nuevo Password se ha generado correctamente. Tu nuevo password es $randomcode . Entra con tu nuevo password y accede en el panel de usuario.\n\nSaludos, \nunocomaseis.com Team.";
			//"Hello User,\n\nYour Password is sucessfully changed. Your new Password is $randomcode . Login with your new Password and change it in the User Panel.\n\nRegards,\nfuturesoft.es Team.";
			$from = "admin@unocomaseis.com";
			$headers = "From:" . $from;
			if (isUserExisted($forgotpassword)) {

				$user = forgotPassword($forgotpassword, $encrypted_password, $salt);
				if ($user) {
					$response["success"] = 1;
					send_mail($forgotpassword,$subject,$message,$headers);
					$response["Correcto"] = "Password creado. Revise su email. "; //.$randomcode;             //"JSON Error occured in Registartion";
                    //------------------------------->>> Borrar linea
					//echo json_encode($response);
                    //echo "<scriptlanguage=javascript>alert('Password creado. Revise su email')</script>";
                    //echo $randomcode;
                    //alr("Password creado. Revise su email");
                    //------------------------------->>>
				} else {
					$response["error"] = 1;
					//echo json_encode($response);
                    //echo "<scriptlanguage=javascript>alert('Error')</script>";
                    alr("Error");
				}

				// user is already existed - error response

			} else {

				$response["error"] = 2;
				$response["error_msg"] = "Usuario no existe";                      //"User not exist";
				//echo json_encode($response);
                //echo '<script type="text/javascript">alert("Usuario no existe.")</script>';
                alr("Usuario no existe");

			}

    }

}

global $application;

global $Unit1;

//Creates the form
$Unit1=new Unit1($application);

//Read from resource file
$Unit1->loadResource(__FILE__);


//Shows the form
$Unit1->show();

?>