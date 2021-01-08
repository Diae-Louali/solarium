<?php

require_once(__DIR__.'/init.php');
htmlHeader();

// create a client instance
$client = new Solarium\Client($adapter, $eventDispatcher, $config);

// get a select query instance
$query = $client->createSelect();

// get the dismax component and set a boost query
$edismax = $query->getEDisMax();

//(by diae) boost the field relevancy score by x times (all wanted fields need to be defined here)
$edismax->setQueryFields('features^20.0 cat^1.0');

// this query is now a dismax query
$query->setQuery('memory -printer');

// this executes the query and returns the result
$resultset = $client->select($query);

// display the total number of documents found by solr
echo 'NumFound: '.$resultset->getNumFound();

// show documents using the resultset iterator
foreach ($resultset as $document) {

    echo '<hr/><table>';

    // the documents are also iterable, to get all fields
    foreach ($document as $field => $value) {
        // this converts multivalue fields to a comma-separated string
        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
    }

    echo '</table>';
}

htmlFooter();
