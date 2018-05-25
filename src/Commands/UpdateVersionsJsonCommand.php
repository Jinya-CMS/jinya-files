<?php
/**
 * Created by PhpStorm.
 * User: imanuel
 * Date: 25.05.18
 * Time: 21:53
 */

namespace Jinya\Commands;

use Jinya\Services\Cms\VersionScannerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateVersionsJsonCommand extends ContainerAwareCommand
{
    private const PRODUCT_CMS = 'cms';

    /** @var VersionScannerInterface */
    private $versionScanner;

    /** @var LoggerInterface */
    private $logger;

    /**
     * UpdateVersionsJsonCommand constructor.
     * @param VersionScannerInterface $versionScanner
     * @param LoggerInterface $logger
     */
    public function __construct(VersionScannerInterface $versionScanner, LoggerInterface $logger)
    {
        parent::__construct();
        $this->versionScanner = $versionScanner;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setName('jinya:version:update')
            ->addArgument('product', InputArgument::OPTIONAL, sprintf('Which product to you want to update? Currently available %s', self::PRODUCT_CMS), self::PRODUCT_CMS)
            ->addArgument('mode', InputArgument::OPTIONAL, sprintf('Which versions should be updated, possible values are %s, %s and %s', VersionScannerInterface::VERSION_STABLE, VersionScannerInterface::VERSION_NIGHTLY, VersionScannerInterface::VERSION_ALL), VersionScannerInterface::VERSION_ALL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getArgument('mode');
        if (!in_array($mode, [VersionScannerInterface::VERSION_ALL, VersionScannerInterface::VERSION_STABLE, VersionScannerInterface::VERSION_NIGHTLY])) {
            $this->logger->error("The provided mode $mode is invalid");
        } else {
            $this->versionScanner->updateVersions($mode);
        }
    }
}
