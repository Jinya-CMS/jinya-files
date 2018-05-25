<?php
/**
 * Created by PhpStorm.
 * User: imanuel
 * Date: 25.05.18
 * Time: 22:15
 */

namespace Jinya\Services;


interface UrlGeneratorInterface
{
    /**
     * Generates a url from the given filename
     *
     * @param string $filename
     * @return string
     */
    public function generateUrl(string $filename): string;
}