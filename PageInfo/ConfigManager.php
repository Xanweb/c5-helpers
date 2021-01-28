<?php

namespace Xanweb\Helper\PageInfo;

use Concrete\Core\Entity\Block\BlockType\BlockType;
use Doctrine\ORM\EntityManagerInterface;
use Xanweb\Common\Traits\ApplicationTrait;
use Xanweb\Common\Traits\SingletonTrait;
use Xanweb\Helper\PageInfo\Exception\UndefinedConfigException;
use Xanweb\Helper\PageInfo\Fetcher\AttributePropertyFetcher;
use Xanweb\Helper\PageInfo\Fetcher\BlockPropertyFetcher;
use Xanweb\Helper\PageInfo\Fetcher\PagePropertyFetcher;

class ConfigManager
{
    use ApplicationTrait;
    use SingletonTrait;

    public const DEFAULT = 'default';
    public const BASIC = 'basic';
    public const ADVANCED = 'advanced';

    /**
     * @var Config[]
     */
    private $configs = [];

    /**
     * Register Config.
     *
     * @param string $configKey
     * @param Config $config
     */
    public function register(string $configKey, Config $config): void
    {
        $this->configs[$configKey] = $config;
    }

    /**
     * Check if config exists.
     *
     * @param string $configKey
     *
     * @return bool
     */
    public function has(string $configKey): bool
    {
        return isset($this->configs[$configKey]) || in_array($configKey, [self::DEFAULT, self::BASIC, self::ADVANCED]);
    }

    /**
     * @param string $configKey
     *
     * @return Config|null
     */
    public function getConfig(string $configKey): ?Config
    {
        if (isset($this->configs[$configKey])) {
            return $this->configs[$configKey];
        }

        // Register predefined configs
        switch ($configKey) {
            case self::DEFAULT:
                $this->registerDefaultConfig();
                break;
            case self::BASIC:
                $this->registerBasicConfig();
                break;
            case self::ADVANCED:
                $this->registerAdvancedConfig();
                break;
            default:
                throw new UndefinedConfigException(t('Can\'t get config with key `%s`.', $configKey));
        }

        return $this->getConfig($configKey);
    }

    protected function registerDefaultConfig(): void
    {
        $config = $this->app(Config::class);
        $config->registerPageNameFetcher(new PagePropertyFetcher(PagePropertyFetcher::PAGE_NAME));
        $config->registerPageDescriptionFetcher(new PagePropertyFetcher(PagePropertyFetcher::PAGE_DESCRIPTION));
        $config->registerThumbnailFetcher(new AttributePropertyFetcher('thumbnail'));

        $this->register(self::DEFAULT, $config);
    }

    protected function registerBasicConfig(): void
    {
        $config = $this->app(Config::class);
        $config->registerPageNameFetcher(new PagePropertyFetcher(PagePropertyFetcher::PAGE_NAME));
        $config->registerPageDescriptionFetcher(new PagePropertyFetcher(PagePropertyFetcher::PAGE_DESCRIPTION));
        $config->registerThumbnailFetcher(new AttributePropertyFetcher('thumbnail'));

        $repo = $this->app(EntityManagerInterface::class)->getRepository(BlockType::class);
        $btXanImage = $repo->findOneBy(['btHandle' => 'xan_image']);
        $config->registerThumbnailFetcher(new BlockPropertyFetcher(
            is_object($btXanImage) ? 'xan_image' : 'image', function ($bController) {
                return $bController->getFileObject();
            })
        );

        $this->register(self::BASIC, $config);
    }

    protected function registerAdvancedConfig(): void
    {
        $config = $this->app(Config::class);
        $repo = $this->app(EntityManagerInterface::class)->getRepository(BlockType::class);
        $pageHeadingBlock = $repo->findOneBy(['btHandle' => 'page_heading']);
        if (is_object($pageHeadingBlock)) {
            $config->registerPageNameFetcher(new BlockPropertyFetcher(
                'page_heading', function ($bController) {
                    return $bController->getPageHeading();
                })
            );

            $config->registerPageDescriptionFetcher(new BlockPropertyFetcher(
                'page_heading', function ($bController) {
                    return $bController->getTeaserText();
                })
            );
        } else {
            $config->registerPageNameFetcher(new BlockPropertyFetcher(
                'page_title', function ($bController) {
                    return $bController->getTitleText();
                })
            );
        }

        $config->registerPageNameFetcher(new PagePropertyFetcher(PagePropertyFetcher::PAGE_NAME));
        $config->registerPageDescriptionFetcher(new PagePropertyFetcher(PagePropertyFetcher::PAGE_DESCRIPTION));
        $config->registerThumbnailFetcher(new AttributePropertyFetcher('thumbnail'));

        $btXanImage = $repo->findOneBy(['btHandle' => 'xan_image']);
        $config->registerThumbnailFetcher(new BlockPropertyFetcher(
            is_object($btXanImage) ? 'xan_image' : 'image', function ($bController) {
                return $bController->getFileObject();
            })
        );

        $this->register(self::ADVANCED, $config);
    }
}
