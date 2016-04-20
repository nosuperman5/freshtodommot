<?php
/**
 * iKantam LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the iKantam EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://magento.ikantam.com/store/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * ExtendedFaq Module to newer versions in the future.
 *
 * @category   Ikantam
 * @package    Ikantam_ExtendedFaq
 * @author     iKantam Team <support@ikantam.com>
 * @copyright  Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license    http://magento.ikantam.com/store/license-agreement  iKantam EULA
 */

class Ikantam_ExtendedFaq_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLED          = 'ikantam_extendedfaq/options/enabled';
    const XML_PATH_URL              = 'ikantam_extendedfaq/options/url';
    const XML_PATH_URL_TITLE        = 'ikantam_extendedfaq/options/url_title';
    const XML_PATH_META_TITLE       = 'ikantam_extendedfaq/options/meta_title';
    const XML_PATH_META_KEYWORDS    = 'ikantam_extendedfaq/options/meta_keywords';
    const XML_PATH_META_DESCRIPTION = 'ikantam_extendedfaq/options/meta_description';

	public function isEnabled()
    {
        return (bool) Mage::getStoreConfig(self::XML_PATH_ENABLED);
    }
    
    public function getSearchUrl()
    {
    	return Mage::getUrl($this->getUrl() . '/search');
    }
    
    public function getUrl()
    {
    	$url = Mage::getStoreConfig(self::XML_PATH_URL);
    	if (empty($url)) {
    		return 'faq';
    	}
        return $url;
    }
    
    public function getUrlTitle()
    {
    	$urlTitle = Mage::getStoreConfig(self::XML_PATH_URL_TITLE);
    	if (empty($urlTitle)) {
    		return 'FAQ';
    	}
        return $urlTitle;
    }
    
    public function getFooterLinkUrl()
    {
    	$url = Mage::getStoreConfig(self::XML_PATH_URL);
    	if (empty($url)) {
    		return Mage::getBaseUrl('link') . 'faq';
    	}
        return Mage::getUrl($url);
    }

	public function getMetaTitle()
    {
    	$metaTitle = Mage::getStoreConfig(self::XML_PATH_META_TITLE);
    	if (empty($metaTitle)) {
    		return 'FAQ';
    	}
        return $metaTitle;
    }
    
    public function getMetaKeywords()
    {
        return Mage::getStoreConfig(self::XML_PATH_META_KEYWORDS);
    }
	
	public function getMetaDescription()
    {
        return Mage::getStoreConfig(self::XML_PATH_META_DESCRIPTION);
    }
}
