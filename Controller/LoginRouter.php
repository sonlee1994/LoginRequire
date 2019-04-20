<?php

namespace CustomerLogin\LoginRequire\Controller;

use CustomerLogin\LoginRequire\Api\Controller\LoginCheckInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RouterInterface;

class LoginRouter implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;
    /**
     * @var LoginCheck
     */
    private $loginCheck;

    /**
     * LoginRouter constructor.
     *
     * @param ActionFactory $actionFactory
     * @param LoginCheckInterface $loginCheck
     * @throws \InvalidArgumentException
     */
    public function __construct(
        ActionFactory $actionFactory,
        LoginCheckInterface $loginCheck
    )
    {
        $this->actionFactory = $actionFactory;
        $this->loginCheck = $loginCheck;
    }

    /**
     * {@inheritdoc}
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if ($this->loginCheck->execute()) {
            $request->setDispatched(true);
            return $this->actionFactory->create(\Magento\Framework\App\Action\Redirect::class);
        }
    }
}
