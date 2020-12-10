<?php
namespace App\Controllers;

/**
 * IpsfaNet
 * 
 *
 * @package Login
 * @author Carlos Peña
 * @copyright	Derechos Reservados (c) 2014 - 2015, MamonSoft C.A.
 * @link		http://www.mamonsoft.com.ve
 * @since Version 1.0
 *
 */

use App\Models\Usuario\Iniciar;
use CodeIgniter\Controller;

use CodeIgniter\Debug\Toolbar\Collectors\BaseCollector;

date_default_timezone_set ( 'America/Caracas' );
define ('__CONTROLADOR', 'Login');

class Login extends Controller {
	protected $session; 
	protected $Iniciar;

	function __construct(){
		//parent::__construct();
		

		//library('session');
		$this->session = \Config\Services::session();
		$this->Iniciar = new Iniciar();
		
		helper('url');
		
	}
	
	function index($msj = null) {
		return $this->ingresar();
	}

	/**
	 *Menu
	 */
  	function ingresar() {
		
		if(isset($_SESSION['usuario'])){
			return $this->inicio();	
		}else{
			return view("login.php");
		}
		
	}


	public function inicio(){
		
		return redirect()->to('/Panel'); 
	}
	/* 
	| ------------------------------------------------------------
	|	Control de Acciones
	| ------------------------------------------------------------
	*/

	/**
	 * Validar y sincronizar el usuario de conexión
	 *
	 * @access  public
	 * @return mixed
	 */	
	public function validarUsuario(){	

		if(isset($_POST['usuario']) && $_POST['usuario'] != ""){
			$valores["usuario"] = $_POST['usuario'];
			$valores["clave"] = $_POST['clave'];
			$resultado = $this->Iniciar->validarCuenta($valores);
			
			$this->session->set('usuario', $resultado);
			
			if ( count($resultado) > 0){
				$this->session->get('usuario');
				$this->inicio();
			}else{
				return $this->salir();
			}
		}else{
			return $this->salir();
		}
	
	}




	/**
	* Establecer politicas para la recuperacion de clave
	*
	* @access public
	* @return mixed
	*/	
	public function recuperar($msj = ''){
		$data['msj'] = $msj;
		view('login/afiliacion/frmRecuperar', $data);	
	}

	/**
	* Registar y asignar tipo al usuario
	*
	* @access public
	* @return mixed
	*/	
	public function registrarUsuario(){
		$this -> load -> model("usuario/usuario","usuario");
		$usuario = new $this -> usuario;
		$usuario->cedula = $_SESSION['cedula'];
		$usuario->tipo = 1;
		$usuario->nombre = $_SESSION['nombreRango'];
		$usuario->sobreNombre = $_POST['usuario'];
		$usuario->correo = $_POST['correo'];
		$usuario->clave = $_POST['clave'];
		$usuario->respuesta = $_SESSION['APIkey'];
		$usuario->perfil = $_SESSION['situacion'];
		if($usuario->existe() == -1){
			$usuario->registrar();
			$this->load->model('comun/Dbipsfa');
			$arr = array(
		      'cedu' => $usuario->cedula,
		      'obse' => 'Creación de Usuario',
		      'fech' => 'now()',
		      'app' => 'Login',
		      'tipo' => 0
			);

    		$this->Dbipsfa->insertarArreglo('traza', $arr);
			$_SESSION['correo'] = $_POST['correo'];
			$_SESSION['estatus'] = 0;
    		$this->enviarCorreoCertificacion();
			view('login/afiliacion/frmOk');
		}else{
			$msj = "El usuario se encuentra registrado, intente recuperar la contraseña";
			$this->identificacion($msj);
		}
	}




	/**
	* Permite validar la ultima conexion
	*
	* @access public
	* @return mixed
	*/
	public function ultimaConexion(){
		print_r($_SESSION);
	}

  	public function salir(){
  		session_destroy();
  		return view('login.php');
  	}




  	

	function __destruct(){
	}
}