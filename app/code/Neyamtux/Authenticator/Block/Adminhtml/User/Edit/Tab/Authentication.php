<?php
namespace Neyamtux\Authenticator\Block\Adminhtml\User\Edit\Tab;

/**
 * Authentication edit form authentication tab
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
class Authentication extends \Magento\Backend\Block\Widget\Form\Generic
{
    const CURRENT_USER_PASSWORD_FIELD = 'current_password';

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_authSession;

    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    protected $_LocaleLists;

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
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\Locale\ListsInterface $localeLists
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Locale\ListsInterface $localeLists,
        \Neyamtux\Authenticator\Lib\PHPGangsta\GoogleAuthenticator $googleAuthenticator,
        array $data = []
    ) {
        $this->_authSession = $authSession;
        $this->_LocaleLists = $localeLists;
        $this->_googleAuthenticator = $googleAuthenticator;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        /** @var $model \Magento\User\Model\User */
        $model = $this->_coreRegistry->registry('permissions_user');

        // Check for secret key. If doesn't exist, create a new one.
        $this->_googleSecret = $model->getSecretKey();
        if (!$this->_googleSecret) {
            $this->_googleSecret = $this->getSecretCode();
            $model->setSecretKey($this->_googleSecret);
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('user_');

        $authenticationFieldset = $form->addFieldset('authentication_fieldset', ['legend' => __('Authentication Information')]);

        $authenticationFieldset->addField(
            'secret_key',
            'text',
            [
                'name' => 'secret_key',
                'label' => __('Secret Key'),
                'id' => 'secret_key',
                'title' => __('Secret Key'),
                'required' => true
            ]
        );

        $authenticationFieldset->addField(
            'qr_code',
            'note',
            [
                'label'    => __('QR Code'),
                'title'    => __('QR Code'),
                'name'     => 'qr_code',
                'after_element_html' => '<img width="300" src="'
                . $this->getQRCodeUrl()
                . '" id="qr_code_image" />'
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Returns QR secret code
     *
     * @return string
     */
    protected function getSecretCode()
    {
        return $this->_googleAuthenticator->createSecret();
    }

    /**
     * Returns QR code url
     *
     * @return string
     */
    protected function getQRCodeUrl()
    {
        return $this->_googleAuthenticator->getQRCodeGoogleUrl('Neyamtux Authenticator', $this->_googleSecret);
    }
}
