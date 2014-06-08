
<?php
class MultiProcessBatchServiceBase {
	
	protected $_classPath;
	protected $_maxProcess = 1;
	protected $_sleepMicroSeconds = 300000;

	public function __construct( $class_path ){
		$this->setClassPath( $class_path );
	}

	public function runProcess(){
		try{
			for( $i=0; $i<$this->getMaxProcess(); $i++ ){
				$this->exec( $this->getExecCmd() );
			}
			$this->waitProcess();
		}catch ( Exception $e){
			$this->_logger->error( $e );
		}
	}

	public function exec( $cmd ){
		exec( $cmd );
	}

	public function getExecCmd( $param = null ){
		$cmd = 'php ';
		$cmd .= $this->_classPath . ' ';
		if( $param ){
			$cmd .= $param . ' ';
		}
		$cmd .= '> /dev/null 2>&1 &';
		return $cmd;
	}

	public function waitProcess(){
		while( true ){
			if( $this->getCurrentProcessCount() <= 0 ){
				break;
			}
			usleep( $this->_sleepMicroSeconds );
		}
	}

	public function getCurrentProcessCount(){
		$output = array();
		exec( "ps ax | grep \"" . $this->getClassPath() . "\" | grep -v grep", $output );
		return count( $output );
	}

	/**
	 * @return mixed
	 */
	protected  function getClassPath()
	{
		return $this->_classPath;
	}

	/**
	 * @param mixed $class_path
	 */
	public function setClassPath( $class_path )
	{
		$this->_classPath = $class_path;
	}

	/**
	 * @return int
	 */
	public function getMaxProcess()
	{
		return $this->_maxProcess;
	}

	/**
	 * @param int $max_process
	 */
	public function setMaxProcess( $max_process )
	{
		$this->_maxProcess = $max_process;
	}

}
