<?php
/**
 * Authenticator Block to generate and authenticate QR Code for Demo
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\Block;

class Authenticator extends \Magento\Framework\View\Element\Template
{
    const SESSION_KEY = 'google_authentication';

    /**
     * Google Authenticator
     *
     * @var /Neyamtux\Authenticator\Lib\PHPGangsta $_googleAuthenticator
     */
     protected $_googleAuthenticator = null;

     /**
      * Google Secret
      *
      * @var string $_googleSecret
      */
     protected $_googleSecret = null;

     /**
      * Catalog Session
      *
      * @var \Magento\Catalog\Model\Session $session
      */
      protected $_session = null;

    /**
     * @param Template\Context $context
     * @param GoogleAuthenticator $googleAuthenticator
     * @param \Magento\Catalog\Model\Session $session
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Neyamtux\Authenticator\Lib\PHPGangsta\GoogleAuthenticator $googleAuthenticator,
        \Magento\Catalog\Model\Session $session,
        array $data = [])
    {
        $this->_googleAuthenticator = $googleAuthenticator;
        $this->_session = $session;
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;

        if ($secret = $this->getSessionData(self::SESSION_KEY)) {
            $this->_googleSecret = $secret;
        } else {
            $this->_googleSecret = $this->_googleAuthenticator->createSecret();
            $this->setSessionData(self::SESSION_KEY, $this->_googleSecret);
        }
    }

    /**
     * Returns QR secret code
     *
     * @return string
     */
    public function getSecretCode()
    {
        return $this->_googleSecret;
    }

    /**
     * Sets session for secret key
     *
     * @return string
     */
    public function setSessionData($key, $value)
    {
        return $this->_session->setData($key, $value);
    }

    /**
     * Gets session for secret key
     *
     * @return string
     */
    public function getSessionData($key, $remove = false)
    {
        return $this->_session->getData($key, $remove);
    }

    /**
     * Returns action url for authentication form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('authenticator/index/post', ['_secure' => true]);
    }

    /**
     * Returns QR code url
     *
     * @return string
     */
    public function getQRCodeUrl()
    {
        return $this->_googleAuthenticator->getQRCodeGoogleUrl('Neyamtux Authenticator', $this->_googleSecret);
    }

    /**
     * Authenticates QR code
     *
     * @return string
     */
    public function authenticateQRCode($secret, $code)
    {
        if (!$secret || !$code) {
            return false;
        }

        return $this->_googleAuthenticator->verifyCode($secret, $code);
    }
}
