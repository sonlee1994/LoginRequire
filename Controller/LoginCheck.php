<?php

namespace CustomerLogin\LoginRequire\Controller;

use CustomerLogin\LoginRequire\Api\Controller\LoginCheckInterface;
use CustomerLogin\LoginRequire\Model\Session;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Store\Model\StoreManagerInterface;

class LoginCheck extends Action implements LoginCheckInterface
{
    const TARGET_URL = 'customer/account/login';
    /**
     * @var CustomerSession
     */
    private $customerSession;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var ModuleCheck
     */
    private $moduleCheck;
    /**
     * @var ResponseHttp
     */
    private $response;

    /**
     * Creates a new {@link \CustomerLogin\LoginRequire\Controller\LoginCheck}.
     *
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param Session $session
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param ModuleCheck $moduleCheck
     * @param ResponseHttp $response
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        Session $session,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        ModuleCheck $moduleCheck,
        ResponseHttp $response
    )
    {
        $this->customerSession = $customerSession;
        $this->session = $session;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->moduleCheck = $moduleCheck;
        $this->response = $response;
        parent::__construct($context);
    }

    /**
     * Manages redirect
     *
     * @return bool TRUE if redirection is applied, else FALSE
     */
    public function execute()
    {
        if ($this->moduleCheck->isModuleEnabled() === false) {
            return false;
        }

        // if user is logged in, every thing is fine
        if ($this->customerSession instanceof \Magento\Customer\Model\Session &&
            $this->customerSession->isLoggedIn()) {
            return false;
        }

        $url = $this->_url->getCurrentUrl();
        $urlParts = \parse_url($url);
        $path = $urlParts['path'];
        $targetUrl = self::TARGET_URL;

        // current path is already pointing to target url, no redirect needed
        if ($targetUrl === $path) {
            return false;
        }

        // Add any GET query parameters back to the path after making our url checks.
        if (isset($urlParts['query']) && !empty($urlParts['query'])) {
            $path .= '?' . $urlParts['query'];
        }

        if (!$this->isAjaxRequest()) {
            $this->session->setAfterLoginReferer($path);
        }

        $this->response->setNoCacheHeaders();
        $this->response->setRedirect($this->getRedirectUrl($targetUrl));
        $this->response->sendResponse();
        return true;
    }

    /**
     * Check if a request is AJAX request
     *
     * @return bool
     */
    private function isAjaxRequest()
    {
        if ($this->_request instanceof \Magento\Framework\App\Request\Http) {
            return $this->_request->isAjax();
        }
        if ($this->_request->getParam('ajax') || $this->_request->getParam('isAjax')) {
            return true;
        }
        return false;
    }

    /**
     * @param string $targetUrl
     * @return string
     */
    private function getRedirectUrl($targetUrl)
    {
        return \sprintf(
            '%s%s',
            $this->getBaseUrl(),
            $targetUrl
        );
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, true);
    }
}
