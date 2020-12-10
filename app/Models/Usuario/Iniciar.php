<?php namespace App\Models\Usuario;

/**
 * Seguridad MamonSoft C.A
 * 
 *
 * @package mamonsoft.app.models.suario
 * @subpackage iniciar
 * @author Carlos Peña
 * @copyright	Derechos Reservados (c) 2020 - 2021, MamonSoft C.A.
 * @link		http://www.mamonsoft.com.ve
 * @since Version 4.0.0
 *
 */

use CodeIgniter\Model;
use App\Models\Usuario\Usuario;


class Iniciar extends Model {

  var $token = null;

  protected $Usuario;

  function __construct() {
    $this -> Usuario = new Usuario();
  }

  function validarCuenta($arg = null) {
    $this -> Usuario -> sobreNombre = $arg['usuario'];
    $this -> Usuario -> clave = $arg['clave'];
    
    
    if ($this -> Usuario -> validar() == TRUE) {
      return  $this -> _entrar($this -> Usuario);
    } else {
      return array();
    }
  }

  private function _entrar($usuario) {

   return array(
        'usuario' => $usuario->login,
        'nombre' => $usuario->nombre .  ' ' . $usuario->apellido,
        'id' => $usuario->id,
        'correo' => $usuario->correo,
        'estatus' => $usuario->estatus,
        'perfil' => $usuario->perfil,
        'roles' => $usuario->listaRoles,
        'ultimaConexion' => ''
    );
  }

  
  function msj(){
    echo 'mensaje';
  }

}
