<?php
error_reporting(E_ALL);
/**
 * This sql need to run to create table at respected Data Base
 * --
-- Table structure for table `downloader`
--

DROP TABLE IF EXISTS `downloader`;
CREATE TABLE IF NOT EXISTS `downloader` (
  `id` int(11) NOT NULL,
  `secure_link` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL,
  `downloaded` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `downloader`
--
ALTER TABLE `downloader`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `downloader`
--
ALTER TABLE `downloader`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
 */
/**
 * This class will generate secure time frame download link
 * to user let download any file [hidden path] from server and also keep track 
 * @author dhiraj
 *
 */
class createSecureDownloadLink{

	private $link;
	private $string = '';
	private $connParam = array();
	private $expiry = NULL;
	private $conn = NULL;
	private $fakeFileName;
	private $realFileName;
	private $downloadFolder;

	/**
	 * 
	 * @param unknown $link
	 * @param unknown $connParam
	 * @param unknown $expiryDay
	 * @param string $realFile
	 * @param string $fakeFile
	 */
	public function __construct($link, $connParam, $expiryDay = 7, $realFile = 'a.txt', $fakeFile = 'real.txt', $downloadFolder = 'downloadFolder'){
		$this->link = $link;
		$this->connParam = array(
				'host' => $connParam['host'],
				'user' => $connParam['user'],
				'pass' => $connParam['pass'],
				'db'   => $connParam['db']
		);
		$this->string = chunk_split(substr(md5(time().rand(10000,99999)), 0, 20), 6, ''); 
		$this->expiry = date('Y-m-d h:i:s', strtotime("+$expiryDay day"));
		$this->realFileName = $realFile;
		$this->fakeFileName = $fakeFile;
		$this->downloadFolder = $downloadFolder;
	}
	
	/**
	 * This will connect the db
	 */
	private function _dbConnection(){
		// Create connection
		$this->conn = new mysqli($this->connParam['host'], $this->connParam['user'], $this->connParam['pass'], $this->connParam['db']);
		// Check connection
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		}
		//echo 'DB Connected'; exit;
		return true;
	}
	
	/**
	 * This will generate link
	 */
	public function createLink(){
		$this->_dbConnection();
		
		$query = "insert into downloader (secure_link, expiry) values(?,?)";
		$stmt = $this->conn->prepare($query);
		
		$stmt->bind_param('ss', $this->string, $this->expiry);
		
		if ($stmt->execute() === TRUE) {
			echo "New record created successfully. Need to send the following link to download <a href='".$this->link . $this->string."'>".$this->link . $this->string.'</a><br>';
		} else {
			echo "Error: " . $sql . "<br>" . $this->conn->error;
		}
		
		$this->conn->close();
		
		return true;
	}

	/**
	 * 
	 */
	public function forceDownload(){
		//echo 'here';
		$this->_dbConnection();
		 
		$sid = $_GET['id'];
		$result = null;
		
		$query = "select id from downloader where downloaded = 0 and expiry >= CURDATE() and secure_link = ?";
		$stmt = $this->conn->prepare($query);
		if($stmt === false) {
			trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->conn->errno . ' ' . $this->conn->error, E_USER_ERROR);
		}
				
		$stmt->bind_param('s', $sid);
		$stmt->execute();
		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->close();
	    //echo $id; exit;
		if($id > 0){		
		
			// Update database
			$sql = "update downloader set downloaded = 1 where id = ?";
			$stmt = $this->conn->prepare($sql);
			if($stmt === false) {
				trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->conn->errno . ' ' . $this->conn->error, E_USER_ERROR);
			}
			$stmt->bind_param('s', $id);
			$stmt->execute();
			
			
			$file = $this->downloadFolder . "/". $this->realFileName;
			$fp = fopen($file, 'rb');
		
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=$this->fakeFileName");
			header("Content-Length: " . filesize($file));
			fpassthru($fp);
					
			
		}else{
			echo 'You are not allowed to download';
			exit;
		}
		
		$stmt->close();
				
		$this->conn->close();
	}

}
?>