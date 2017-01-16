<?php
require_once(dirname(__FILE__) . '/ApiRequestHandler_extended.php');
require_once(dirname(__FILE__) . '/DocumentRequestHandler_extended.php');

$documentRequestHandler_extension = new \api\v1\DocumentRequestHandler_extension();
Flight::route('GET /documents/', array($documentRequestHandler_extension, 'handleGetDocumentList'), true);
Flight::route('GET /documents/fields', array($documentRequestHandler_extension, 'handleGetDocumentListWithAllFields'), true);
Flight::route('GET /applications/@appId:[0-9]+/documents/', array($documentRequestHandler_extension, 'handleGetDocumentListForApp'), true);
Flight::route('GET /applications/@appId:[0-9]+/documents/fields/', array($documentRequestHandler_extension, 'handleGetDocumentListForAppWithAllFields'), true);
Flight::route('GET /applications/@appId:[0-9]+/documents/fields/@fields/', array($documentRequestHandler_extension, 'handleGetDocumentListForAppWithFields'), true); // $fields pipe (|) separated.
Flight::route('GET /documents/@docId:[0-9]+/status/@status', array($documentRequestHandler_extension, 'handleUpdateDocumentStatus'));

Flight::route('GET /documents/@docId:[0-9]+/pdfzip/', array('\api\v1\ApiRequestHandler_extension', 'getPDFzip'), true);
