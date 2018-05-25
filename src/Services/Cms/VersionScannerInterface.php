<?php
/**
 * Created by PhpStorm.
 * User: imanuel
 * Date: 25.05.18
 * Time: 22:01
 */

namespace Jinya\Services\Cms;

interface VersionScannerInterface
{
    public const VERSION_NIGHTLY = 'nightly';

    public const VERSION_STABLE = 'stable';

    public const VERSION_ALL = 'all';

    /**
     * Updates the versions for the given mode
     *
     * @param string $mode
     */
    public function updateVersions(string $mode): void;
}
