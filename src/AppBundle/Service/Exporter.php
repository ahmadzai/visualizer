<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 9/9/2018
 * Time: 9:29 AM
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class Exporter
{
    public static function exportCSV($data) {

        $rows = array();
        foreach ($data as $datum) {

            $rows[] = implode(',', $datum);
        }

        $content = implode("\n", $rows);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

}