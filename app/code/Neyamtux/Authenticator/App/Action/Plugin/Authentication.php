<?php
/**
 * Admin login authentication
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\App\Action\Plugin;

use Magento\Framework\Exception\AuthenticationException;

class Authentication extends \Magento\Backend\App\Action\Plugin\Authentication
{
    /**
     * Authenticator Helper
     *
     * @var \Neyamtux\Authenticator\Helper\Data
     */
     protected $helper;

    /**
     * Google Authenticator
     *
     * @var \Neyamtux\Authenticator\Lib\PHPGangsta\GoogleAuthenticator
     */
     protected $googleAuthenticator;

    /**
     * @param \Magento\Backend\Model\Auth $auth
     * @param \Magento\Backend\Model\UrlInterface $url
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\BackendAppList $backendAppList
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Neyamtux\Authenticator\Helper\Data $helper
     * @param \Neyamtux\Authenticator\Lib\PHPGangsta\GoogleAuthenticator $googleAuthenticator
     */
    public function __construct(
        \Magento\Backend\Model\Auth $auth,
        \Magento\Backend\Model\UrlInterface $url,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\BackendAppList $backendAppList,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Neyamtux\Authenticator\Helper\Data $helper,
        \Neyamtux\Authenticator\Lib\PHPGangsta\GoogleAuthenticator $googleAuthenticator
    ) {
        parent::__construct(
            $auth,
            $url,
            $response,
            $actionFlag,
            $messageManager,
            $backendUrl,
            $resultRedirectFactory,
            $backendAppList,
            $formKeyValidator
        );
        $this->helper = $helper;
        $this->googleAuthenticator = $googleAuthenticator;
    }

    /**
     * Performs login, if user submitted login form
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    protected function _performLogin(\Magento\Framework\App\RequestInterface $request)
    {
        $postLogin = $request->getPost('login');
        $outputValue = parent::_performLogin($request);

        // Authenticate QR code
        if ($this->helper->isEnable() && $outputValue) {
            $user = $this->_auth->getUser();
            $secretKey = isset($user['secret_key']) ? $user['secret_key'] : '';
            $qrCode = isset($postLogin['qr_code']) ? $postLogin['qr_code'] : '';

            $outputValue = (int) $this->_authenticateQRCode($secretKey, $qrCode);

            if (!$outputValue) {
                $this->messageManager->addError(__('Invalid Authentication Code.'));
                $this->_auth->getAuthStorage()->processLogout();
            }
        }

        return $outputValue;
    }

    /**
     * Authenticates QR code
     *
     * @return string
     */
    private function _authenticateQRCode($secret, $code, $clockTolerance = 2)
    {
        if (!$secret || !$code) {
            return false;
        }

        return $this->googleAuthenticator->verifyCode($secret, $code, $clockTolerance);
    }
}
