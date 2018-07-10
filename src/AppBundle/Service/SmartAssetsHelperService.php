<?php

namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class SmartAssetsHelperService.
 *
 * @category Service
 */
class SmartAssetsHelperService
{
    const HTTP_PROTOCOL = 'https://';
    const PHP_CLI_API = 'cli';

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var AssetsHelper
     */
    private $ah;

    /**
     * @var string mailer URL base
     */
    private $mub;

    /**
     * Methods.
     */

    /**
     * SmartAssetsHelperService constructor.
     *
     * @param KernelInterface $kernel
     * @param AssetsHelper    $ah
     * @param string          $mub
     */
    public function __construct(KernelInterface $kernel, AssetsHelper $ah, $mub)
    {
        $this->kernel = $kernel;
        $this->ah = $ah;
        $this->mub = $mub;
    }

    /**
     * Determine if this PHP script is executed under a CLI context.
     *
     * @return bool
     */
    public function isCliContext()
    {
        return self::PHP_CLI_API === php_sapi_name();
    }

    /**
     * If is CLI context returns absolute file path, otherwise returns absolute URL path.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getAbsoluteAssetPathByContext($assetPath)
    {
        $package = new UrlPackage(self::HTTP_PROTOCOL.$this->mub.'/', new EmptyVersionStrategy());
        $result = $package->getUrl($assetPath);

        if ($this->isCliContext()) {
            $result = $this->kernel->getRootDir().DIRECTORY_SEPARATOR.$assetPath;
        }

        return $result;
    }

    /**
     * If is CLI context returns relative file path, otherwise returns relative URL path.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getRelativeAssetPathByContext($assetPath)
    {
        $result = $this->ah->getUrl($assetPath);

        if ($this->isCliContext()) {
            $result = $this->kernel->getRootDir().DIRECTORY_SEPARATOR.$assetPath;
        }

        return $result;
    }
}
