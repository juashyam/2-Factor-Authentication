<?php
/**
 * Authenticator Helper
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    const XML_PATH_AUTHENTICATOR_ENABLE = 'authenticator/general/enable';

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns System Config value
     *
     * @param string System Config XML
     * @param int Store Id
     *
     * @return string
     */
    private function getConfigValue($field, $storeId = null)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(
            $field,
            $storeScope,
            $storeId
        );
    }

    /**
     * Check if authenticator is enable
     *
     * @param int Store Id
     *
     * @return string
     */
    public function isEnable($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_AUTHENTICATOR_ENABLE, $storeId);
    }
}
