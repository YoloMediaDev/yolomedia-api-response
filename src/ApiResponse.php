<?php
	/**
	 * User: gerson
	 * Date: 3/03/18
	 * Time: 13:39
	 */
	
	namespace YoloMedia;
	
	
	class ApiResponse {
		public $code;
		public $msg;
		public $details;
		public $errors = [];
		public $httpStatus;
		public $headers = [];
		public $data;
		
		public static $SUCCESS = 'success';
		public static $ERROR = 'error';
		public static $NOT_FOUND = 'not_found';
		public static $NEED_PARAMS = 'need_params';
		public static $EDITED = 'edited';
		public static $CREATED = 'created';
		public static $DELETED = 'deleted';
		public static $BAD_REQUEST = 'bad_request';
		
		private $tpls = [
			'success'     => [ 'code' => 'success', 'msg' => 'Petición correcta', 'httpStatus' => 200 ],
			'error'       => [ 'code' => 'error', 'msg' => 'Ocurrió un problema al realizar tu petición', 'httpStatus' => 500 ],
			'not_found'   => [ 'code' => 'error', 'msg' => 'No pudimos encontrar este item, por favor prueba nuevamente', 'httpStatus' => 404 ],
			'need_params' => [ 'code' => 'error', 'msg' => 'Algúnos parametros necesario no fueron recibidos', 'httpStatus' => 400 ],
			'edited'      => [ 'code' => 'success', 'msg' => 'Los cambios fueron guardados correctamente', 'httpStatus' => 200 ],
			'created'     => [ 'code' => 'success', 'msg' => 'El registro fué creado', 'httpStatus' => 201 ],
			'deleted'     => [ 'code' => 'success', 'msg' => 'El registro fué eliminado', 'httpStatus' => 200 ],
			'bad_request' => [ 'code' => 'bad_request', 'msg' => 'Uno o más datos enviados no cumplen los requisitos', 'httpStatus' => 400 ]
		];
		
		public function __construct( $data = null, $msg = null, $code = null, $httpStatus = null, $details = null ) {
			
			$this->details    = $details;
			$this->msg        = $msg ? $msg : $this->tpls['success']['msg'];
			$this->code       = $code ? $code : $this->tpls['success']['code'];
			$this->httpStatus = $httpStatus ? $httpStatus : $this->tpls['success']['httpStatus'];
			$this->data       = $data;
		}
		
		public function getStatusData() {
			$response = [
				'status' => [
					'code'    => $this->code,
					'msg'     => $this->msg,
					'details' => $this->details,
					'errors'  => $this->errors
				]
			];
			
			return $response;
		}
		
		public function getResponseData()
		{
			$response = [
				'data' => $this->data,
				'status' => [
					'code'    => $this->code,
					'msg'     => $this->msg,
					'details' => $this->details,
					'errors'  => $this->errors
				]
			];
			
			return $response;
		}
		
		public function loadTemplate( string $template, $msg = null, $details = null ) {
			
			$this->details = $details;
			if ( isset( $this->tpls[ $template ] ) ) {
				$this->msg        = $msg ? $msg : $this->tpls[ $template ]['msg'];
				$this->code       = $this->tpls[ $template ]['code'];
				$this->httpStatus = $this->tpls[ $template ]['httpStatus'];
			}
		}
		
		public function httpResponse( $template = null, $msg = null, $details = null, $data = null ) {
			if ( $template ) {
				$this->loadTemplate( $template, $msg, $details );
			}
			if($data)
				$this->data = $data;
			
			if(function_exists('response'))
			{
				return response( $this->getResponseData(), $this->httpStatus, $this->headers );
			}else{
				return $this->getResponseData();
			}
		}
		
		static function make( $data = null, $msg = null, $code = null, $httpStatus = null, $details = null ) {
			$status = new ApiResponse( $data, $msg, $code, $httpStatus, $details );
			return $status;
		}
		
		public function registerError( $errorMsg ) {
			array_push( $this->errors, $errorMsg );
		}
	}