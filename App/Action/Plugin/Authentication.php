<?php
/**
 * Admin login authentication
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\App\Action\Plugin;

use Magento\Backend\App\BackendAppList;
use Magento\Backend\Model\Auth;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Message\ManagerInterface;
use Neyamtux\Authenticator\Helper\Data;
use Neyamtux\Authenticator\Lib\PHPGangsta\GoogleAuthenticator;

class Authentication extends \Magento\Backend\App\Action\Plugin\Authentication
{
    /**
     * Authenticator Helper
     *
     * @var Data
     */
    protected $helper;

    /**
     * Google Authenticator
     *
     * @var GoogleAuthenticator
     */
    protected $googleAuthenticator;

    /**
     * @param Auth $auth
     * @param UrlInterface $url
     * @param ResponseInterface $response
     * @param ActionFlag $actionFlag
     * @param ManagerInterface $messageManager
     * @param UrlInterface $backendUrl
     * @param RedirectFactory $resultRedirectFactory
     * @param BackendAppList $backendAppList
     * @param Validator $formKeyValidator
     * @param Data $helper
     * @param GoogleAuthenticator $googleAuthenticator
     */
    public function __construct(
        Auth $auth,
        UrlInterface $url,
        ResponseInterface $response,
        ActionFlag $actionFlag,
        ManagerInterface $messageManager,
        UrlInterface $backendUrl,
        RedirectFactory $resultRedirectFactory,
        BackendAppList $backendAppList,
        Validator $formKeyValidator,
        Data $helper,
        GoogleAuthenticator $googleAuthenticator
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
     * @param RequestInterface $request
     * @return bool
     */
    protected function _performLogin(RequestInterface $request)
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
     * @param $secret
     * @param $code
     * @param int $clockTolerance
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
