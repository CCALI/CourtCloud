<?php
/**
 * Transforms a file into other formats
 * Relies upon PyODConverter and LibreOffice
 * to handle the heavy lifting.
 * Takes as input files saved to CourtCloud and
 * outputs HTML and PDF.
 *
 * TODO:
 * - make this an OwnCloud App so that is uses
 *   the OC API to get docs.
 */

 $opinion = $argv[1]; // start with a path to the opinion
 $opin_parts = pathinfo($opinion); //get parts of opinion path
 $opin_file = $opin_parts['filename']; //get filename part to use in conversion targets
 $converter = "/vol/code/transform/DocumentConverter.py"; //path to converter
 $flrpath = "/vol/data/flrdemo/"; //path to FLR storage
 $opin_html = $flrpath.$opin_file.".html";
 $opin_pdf = $flrpath.$opin_file.".pdf";
 
 //convert to HTML
 exec("python $converter $opinion $opin_html");
 // let's cleanup HTML
 $tidyconfig = array('output-xhtml' => TRUE,
                     'wrap' => 200,
                     'clean' => TRUE,
                     'preserve-entities' => TRUE,
                     'char-encoding' => 'utf8',
                     'input-encoding' => 'utf8',
                     'output-encoding' => 'utf8',);
 $tidyhtmldoc = tidy_repair_file($opin_html, $tidyconfig);
 file_put_contents($opin_html, $tidyhtmldoc);

 //
 exec("python $converter $opinion $opin_pdf");
 
 echo "OK!";
 
 
 
 
?>