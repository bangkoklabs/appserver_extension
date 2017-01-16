<?php
namespace api\v1;

use \Document;
use \DateTime;
use \Flight;


class ApiRequestHandler_extension
{

	function getPDFzip($docId)
	{
	  $document = \Document::load(\Document::BY_ID, $docId);

	  $lastChangeset = $document->getChangesets()->last();
	  $created = new DateTime($lastChangeset->createdDate);

	  $tmpPdf = tempnam("/tmp/", $document->name . "_" . $docId . "_") . ".pdf";
	  self::createPDF($document, $tmpPdf);

	  $tmpZip = tempnam("/tmp/", $document->name . "_" . $docId . "_") . ".zip";
	  $cmd = "zip -j \"$tmpZip\" \"$tmpPdf\"";
	  g_Execute($cmd);

	  header("Content-Type: application/zip");
	  header("Content-Length: " . filesize($tmpZip));
	  Flight::lastModified($created->getTimestamp());

	  readfile($tmpZip);
	  unlink($tmpPdf);
	  unlink($tmpZip);
	}

	/*
	  If ever merged to core, delete these functions. They exist in ApiRequestHandler.php
	*/
        private function createPDF(Document $document, $outputFile, $pages = null)
        {
                self::getFacetGenerator($document)->getPDF($outputFile, $pages);
        }

        private function getFacetGenerator(Document $document)
        {
                $appFactory = new \ApplicationFactory();
                $app = $appFactory->createApplication($document->appId);
                $context = new \RequestContext($app, \RequestContext::SOURCE_PENPUSHER);
                $facetGenerator = new \FacetGenerator($document, $app, $context);
                return $facetGenerator;
        }
	/*
	  End delete
	*/
}