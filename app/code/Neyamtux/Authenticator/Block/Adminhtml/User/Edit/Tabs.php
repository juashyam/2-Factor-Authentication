<?php
/**
 * User page left menu
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\Block\Adminhtml\User\Edit;

class Tabs extends \Magento\User\Block\User\Edit\Tabs
{
    /**
     * Authenticator Helper
     *
     * @var \Neyamtux\Authenticator\Helper\Data
     */
     protected $helper;

     /**
      * @param \Magento\Backend\Block\Template\Context $context
      * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
      * @param \Magento\Backend\Model\Auth\Session $authSession
      * @param \Neyamtux\Authenticator\Helper\Data $helper
      * @param array $data
      */
     public function __construct(
         \Magento\Backend\Block\Template\Context $context,
         \Magento\Framework\Json\EncoderInterface $jsonEncoder,
         \Magento\Backend\Model\Auth\Session $authSession,
         \Neyamtux\Authenticator\Helper\Data $helper,
         array $data = []
     ) {
         parent::__construct($context, $jsonEncoder, $authSession, $data);
         $this->helper = $helper;
     }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        // New tab in admin user form
        if ($this->helper->isEnable()) {
            $this->addTabAfter(
                'authentication',
                [
                    'label' => __('Authentication'),
                    'title' => __('Authentication'),
                    'content' =>$this->getLayout()->createBlock('Neyamtux\Authenticator\Block\Adminhtml\User\Edit\Tab\Authentication')->toHtml()
                ],
                'roles_section'
            );            
        }

        return parent::_beforeToHtml();
    }
}
