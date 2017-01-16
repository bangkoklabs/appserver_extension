<?php

namespace api\v1;
use \Document;
use \DBFactory;
use \UserService;

class DocumentRepository {

	public static function getDocumentList ($withFields=false, $appId=null, $fieldsToInclude=array(), $documentStatus=null) {
                $dbFactory = new DBFactory();
		$db = $dbFactory->createDB();

		$user = $_SERVER['PHP_AUTH_USER'];
		$userService = new UserService($user);

		$query = "SELECT Doc.id as doc_id FROM Doc, Changeset WHERE Changeset.doc_id = Doc.id";
		if(!$userService->hasRole("viewersuperuser")) {
			$query .= " AND Doc.id IN (SELECT DISTINCT doc_id FROM Changeset WHERE submitter_email = '" . $user . "')";
		}

		if($appId !== null) {
			$query .= " AND Doc.app_id = " . $appId;
		}

		if($documentStatus != null)
			$query .= " AND Doc.status = '" . $documentStatus . "' ";

		$query .= " GROUP BY Doc.id ORDER BY Doc.id";
		$db->query($query);
		//g_Log($query);
		$docIds = array();
		while ($row = $db->fetchRow()) {
			$docIds[] = $row["doc_id"];
		}
		
		$response = array();
		if($withFields == true) {
			foreach($docIds as $docId) {
				$response[] = array("doc_id"=> $docId, "fields" => self::getDocumentFields($docId, $fieldsToInclude));
			}
		} else {
			foreach($docIds as $docId) {
				$response[] = array("doc_id" => $docId);
			}
		}

		return $response;
	}

	public static function getDocumentFields($docId, $fieldsToInclude) {
		$document = Document::Load(Document::BY_ID, $docId);
		$pages = $document->pages;
		$fieldValues = array();
		foreach($pages as $page) {
			$fields = $page->fields;
			foreach($fields as $field) {
				if(empty($fieldsToInclude) || in_array($field->key, $fieldsToInclude)) {
					// $fieldValues[$field->key] = $field->value;
					$fieldValues[$field->key]['value'] = $field->value;
					$fieldValues[$field->key]['type'] = $field->type;
					$fieldValues[$field->key]['checkedValue'] = $field->checkedValue;
				}
			}
		}
		return $fieldValues;
	}
}
