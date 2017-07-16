<?php
/**
 * Authenticator Demo Controller
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
