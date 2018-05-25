<?php
/**
 * Created by PhpStorm.
 * User: imanuel
 * Date: 25.05.18
 * Time: 22:21
 */

namespace Jinya\Services;


use Underscore\Types\Strings;

class UrlGenerator implements UrlGeneratorInterface
{

    /**
     * Generates a url from the given filename
     *
     * @param string $filename
     * @return string
     */
    public function generateUrl(string $filename): string
    {
        $target = Strings::replace($filename, '\\', '/');
        if (Strings::startsWith($target, '/')) {
            $target = '/' . $target;
        }

        return getenv('FILES_BASE_URL') . $target;
    }
}