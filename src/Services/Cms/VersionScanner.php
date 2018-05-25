<?php
/**
 * Created by PhpStorm.
 * User: imanuel
 * Date: 25.05.18
 * Time: 22:04
 */

namespace Jinya\Services\Cms;

use Jinya\Services\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class VersionScanner implements VersionScannerInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var LoggerInterface */
    private $logger;

    /**
     * VersionScanner constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param LoggerInterface $logger
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, LoggerInterface $logger)
    {
        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
    }

    /**
     * Updates the versions for the given mode
     *
     * @param string $mode
     */
    public function updateVersions(string $mode): void
    {
        if (VersionScannerInterface::VERSION_ALL === $mode) {
            $this->updateVersions(VersionScannerInterface::VERSION_NIGHTLY);
            $this->updateVersions(VersionScannerInterface::VERSION_STABLE);
        } else {
            $this->logger->info("Updating version json files for mode $mode");

            $baseDirectory = getenv('FILES_BASE_DIR');
            $directory = $baseDirectory.DIRECTORY_SEPARATOR.'cms'.DIRECTORY_SEPARATOR.$mode;
            $this->logger->info("Using directory $directory for scan");

            $finder = new Finder();
            $finder->filter(function (\SplFileInfo $file) {
                return 'zip' === $file->getExtension();
            })->files()->sortByName()->in($directory);
            $this->logger->info(sprintf('Found %i files in directory %s', iterator_count($finder), $directory));

            $fs = new Filesystem();
            $archives = [
                'cms' => [],
            ];

            $baseUrl = $this->urlGenerator->generateUrl('cms'.DIRECTORY_SEPARATOR.$mode.DIRECTORY_SEPARATOR);
            $this->logger->info("Base url for generated version file: $baseUrl");

            foreach ($finder as $item) {
                $version = $item->getBasename('.'.$item->getExtension());
                $fileUrl = $baseUrl.$item->getFilename();
                $archives['cms'][$version] = $fileUrl;
                $this->logger->info("Added version $version with url $fileUrl to list of archives");
            }

            $versionsFile = $baseDirectory.DIRECTORY_SEPARATOR.$mode.'.json';

            $this->logger->info("Writing new versions file to $versionsFile");
            $fs->dumpFile($versionsFile, json_encode($archives));

            $this->logger->debug("Wrote file $versionsFile");
        }
    }
}
