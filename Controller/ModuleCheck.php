<?php

namespace CustomerLogin\LoginRequire\Controller;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ModuleCheck
 *
 * @package CustomerLogin\LoginRequire\Controller
 */
class ModuleCheck
{
    /*
     * Configuration
     */
    const MODULE_CONFIG_ENABLED = 'customer/CustomerLogin_LoginRequire/enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ModuleCheck constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return !!$this->scopeConfig->getValue(
            self::MODULE_CONFIG_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
