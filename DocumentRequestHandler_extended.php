<?php

namespace api\v1;

use \Document;
use \Flight;

require_once(dirname(__FILE__) . "/DocumentRepository.php");

class DocumentRequestHandler_extension
{

	public function handleUpdateDocumentStatus ($docId, $status) {
                $doc = Document::load(Document::BY_ID, $docId);
		$status = strtoupper($status);

		if(!($status == Document::STATE_NEW || $status == Document::STATE_OPEN || $status == Document::STATE_CLOSED || $status == Document::STATE_INCOMPLETE || $status == Document::STATE_COMPLETED))
		  throw new Exception("Invalid status");
		$doc->save($status);
	}

	public function handleGetDocumentList ($route) {
                Flight::json(DocumentRepository::getDocumentList(false, null, array(), $this->getStatusFromUrl($route)));
	}

	public function handleGetDocumentListWithAllFields ($route) {
                Flight::json(DocumentRepository::getDocumentList(true, null, array(), $this->getStatusFromUrl($route)));
	}

	public function handleGetDocumentListForApp ($appId, $route) {
                Flight::json(DocumentRepository::getDocumentList(false, $appId, array(), $this->getStatusFromUrl($route)));
	}
	
	public function handleGetDocumentListForAppWithAllFields ($appId, $route) {
		Flight::json(DocumentRepository::getDocumentList(true, $appId, array(), $this->getStatusFromUrl($route)));
	}

	public function handleGetDocumentListForAppWithFields ($appId, $fieldsToIncludeString, $route) {
		Flight::json(DocumentRepository::getDocumentList(true, $appId, explode("|", $fieldsToIncludeString), $this->getStatusFromUrl($route)));
	}

	private function getStatusFromUrl($route) {
		$documentStatus = null;
		$urlPieces = explode("?", $route->splat);
		if(count($urlPieces) == 2) {
			$getPieces = explode("=", $urlPieces[1]);
			if(count($getPieces == 2) && $getPieces[0] == "status") {
				$documentStatus = $getPieces[1];
			}
		}
		return $documentStatus;
	}
}
