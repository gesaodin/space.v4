<?php namespace App\Models\Comun;

/**
 * 
 *
 * @package pace\application\modules\panel\model\comun
 * @subpackage comun
 * @author Carlos Peña
 * @copyright Derechos Reservados (c) 2015 - 2016, MamonSoft C.A.
 * @link http://www.mamonsoft.com.ve
 * @since version 1.0
 *
 */
use CodeIgniter\Model;



class DBpace extends Model {
	
	var $dbs = NULL;
	
	var $err = NULL;
	/**
	*	Constructor de la Calse
	*
	*/
	function __construct(){
		$this->iniciarPace();
	}

	/**
	*	Establecer Conexión a la Base de datos PACE
	*/
	private function iniciarPace(){
		if (! isset ( $this->dbs )) {
			$this->dbs = \Config\Database::connect('default', false);
		}
		return $this->dbs;
	}

	/**
	* Permite Capturar Error y otros
	*
	* @param string
	* @return array
	*/
	function consultar($consulta){
		$this->err = array(
				'message' => 'Bien',
				'query' => $consulta,
				'cant' => 0
				);
				
		if ( ! (@$rs = $this->dbs->query($consulta))){
			$this->err = $this->dbs->error();
			//$this->err['query'] = $consulta;		
			$this->err['code'] = 1;
			$this->err['cant'] = 0;
			//En el caso de un error se genera $err['message']
		}else{

			$this->err['code'] = 0;
			$this->err['rs'] = array();

			if(is_object($rs)){
				
				$this->err['rs'] = $rs->getResult();
				$this->err['cant'] =  count($rs->getResult()); //Pendiente por evaluar para postgres
			}
		}
		// echo "<pre>";
		// print_r((object)$this->err);
		// echo "</pre>";
		return (object)$this->err;
	}
	

	/**
	* Permite Insertar Datos por arreglos
	*
	* @param string
	* @return array
	*/
	function insertarArreglo($tabla, $datos){
		$this->dbs->insert($tabla, $datos);
	}


	/**
	* Permite Actualizar Datos por arreglos
	*
	* @param string
	* @return array
	*/
	function actualizarArreglo($tabla = '', $datos = array(), $donde = array()){
		$this->dbs->where($donde);
		$this->dbs->update($tabla, $datos);
	}
	function __destruct(){
		unset($this->dbs);
	}


}