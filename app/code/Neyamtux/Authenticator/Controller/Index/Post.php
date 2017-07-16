<?php
/**
 * Authenticator Demo Post Controller
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\Controller\Index;

class Post extends \Magento\Framework\App\Action\Action
{
    /**
     * Layout Factory
     *
     * @var \Magento\Framework\View\LayoutFactory $layoutFactory
     */
     protected $_layoutFactory = null;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->_layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    /**
     * Varify QR code
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        if (!$post) {
            $this->_redirect('*/*/');
            return;
        }

        $authenticator = $this->_layoutFactory->create()->createBlock('Neyamtux\Authenticator\Block\Authenticator');
        if ($authenticator->authenticateQRCode($post['secret'], $post['code'])) {
            $this->messageManager->addSuccess(
                __('Successfully Authenticated. Cheers...')
            );
        } else {
            $this->messageManager->addError(
                __('Invalide Authentication Code!!!')
            );
        }
        $this->_redirect('authenticator/index');
        return;
    }
}
