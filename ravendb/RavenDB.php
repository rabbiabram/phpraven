<?php
class RavenDocumentOperation {
	const Rename = 'Rename';
	const Set = 'Set';
	const Add = 'add';
	const Copy = 'Copy';
}

class Transaction {

}

class TransactionsPool {

}


class RavenDocument {
	private $__id = null;
	private $__lastModified = null;
	private $__ravenLastModified = null;
	private $__etag = null;

	private $__data = null;
	private $__isStored = null;

	public function __construct($documentJSON) {
		$this->__parse($documentJSON);
	}

	public function toJSON() {
		return json_encode($this->__data);
	}

	public function sendTo(RavenDB $RavenDBInstance) {

	}

	public function Set($field, $value) {

	}

	public function add($field, $value) {

	}

	public function Rename($field, $value) {

	}

	public function Copy($field, $valie) {

	}

	private function __parseMetadata($metadata) {
		var_dump($metadata);
		$this->__id = $metadata["@id"];
		$this->__lastModified = $metadata["Last-Modified"];
		$this->__ravenLastModified = $metadata["Raven-Last-Modified"];
		$this->__etag = $metadata["@etag"];
	}

	private function __parse($document) {
		$data = array();
		var_dump($document);
		foreach ($document as $key => $value) {
			if ($key == '@metadata') {
				$this->__isStored = true;
				$this->__parseMetadata($value);
				continue;
			}
			$data[$key] = $value;

		}
		$this->__data = (object) $data;
	}

}
class RavenDB {

	const GET = 'GET';
	const POST = 'POST';
	const PUT = 'PUT';
	const PATCH = 'PATCH';

	private $__host = null;
	private $__port = null;

	public function __construct($host="127.0.0.1", $port=8080) {
		$this->__host = $host;
		$this->__port = $port;
	}

	static public function getInstance($host, $port) {
		return new RavenDB($host, $port);
	}

	public function call($command, $data=array()) {
		return $this->__call_api($command, $data);
	}

	private function __call_api($endpoint, $data = array(), $type=RavenDB::GET) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://{$this->__host}:{$this->__port}/{$endpoint}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;

	}


	private function __prepareDocumentsList($resultJSON) {
		$json = json_decode($resultJSON, true);
		$documents = array();
		foreach ($json as $key => $value) {
			$documents[] = new RavenDocument($value);
		}
		return $documents;
	}
	public function getDocumentsList() {
		return $this->__prepareDocumentsList($this->__call_api('docs'));
	}

	public function putDocument() {
		return $this->__call_api('docs', $data, RavenDB::PUT);
	}
}

$r = new RavenDB();
$d = $r->getDocumentsList();
var_dump($d);