<?php
/**
 * User page left menu
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\Block\Adminhtml\User\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Neyamtux\Authenticator\Helper\Data;

class Tabs extends \Magento\User\Block\User\Edit\Tabs
{
    /**
     * Authenticator Helper
     *
     * @var Data
     */
    protected $helper;

    /**
     * @param Context $context
     * @param EncoderInterface $jsonEncoder
     * @param Session $authSession
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
         Context $context,
         EncoderInterface $jsonEncoder,
         Session $authSession,
         Data $helper,
         array $data = []
     ) {
        parent::__construct($context, $jsonEncoder, $authSession, $data);
        $this->helper = $helper;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
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
