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
		
		public static $SUCCESS = 'success';
		public static $ERROR = 'error';
		public static $NOT_FOUND = 'not_found';
		public static $NEED_PARAMS = 'need_params';
		public static $EDITED = 'edited';
		public static $CREATED = 'created';
		public static $DELETED = 'deleted';
		
		private $tpls = [
			'success'   => [ 'code' => 'success', 'msg' => 'Petición correcta', 'httpStatus' => 200 ],
			'error'   => [ 'code' => 'error', 'msg' => 'Ocurrió un problema al realizar tu petición', 'httpStatus' => 500 ],
			'not_found'   => [ 'code' => 'error', 'msg' => 'No pudimos encontrar este item, por favor prueba nuevamente', 'httpStatus' => 404 ],
			'need_params' => [ 'code' => 'error', 'msg' => 'Algúnos parametros necesario no fueron recibidos', 'httpStatus' => 400 ],
			'edited' => [ 'code' => 'success', 'msg' => 'Los cambios fueron guardados correctamente', 'httpStatus' => 200 ],
			'created' => [ 'code' => 'success', 'msg' => 'El registro fué creado', 'httpStatus' => 201 ],
			'deleted' => [ 'code' => 'success', 'msg' => 'El registro fué eliminado', 'httpStatus' => 200]
		];
		
		public function __construct( $msg = null, $code = null, $httpStatus = null, $details = null ) {
			
			$this->details    = $details;
			$this->msg        = $msg?$msg:$this->tpls['success']['msg'];
			$this->code       = $code?$code:$this->tpls['success']['code'];
			$this->httpStatus = $httpStatus?$httpStatus:$this->tpls['success']['httpStatus'];
		}
		
		public function toResource($data = null) {
			$response = [
				'status' => [
					'code'    => $this->code,
					'msg'     => $this->msg,
					'details' => $this->details,
					'errors' => $this->errors
				]
			];
			if($data)
				$response['data'] = $data;
			
			return $response;
		}
		
		public function setType( string $type, $msg = null, $details = null ) {
			
			$this->details = $details;
			if ( isset( $this->tpls[ $type ] ) ) {
				$this->msg        = $msg?$msg:$this->tpls[$type]['msg'];
				$this->code       = $this->tpls[$type]['code'];
				$this->httpStatus = $this->tpls[$type]['httpStatus'];
			}
		}
		
		public function httpResponse( $template = null, $msg = null, $details = null, $data = null) {
			if($template)
				$this->setType($template, $msg, $details);
			return response( $this->toResource($data), $this->httpStatus, $this->headers );
		}
		
		static function make( $msg = null, $code = null, $httpStatus = null, $details = null, $data = null ) {
			$status = new ApiStatus( $msg, $code, $httpStatus, $details );
			return $status->toResource($data);
		}
		
		public function registerError($errorMsg)
		{
			array_push($this->errors, $errorMsg);
		}
	}