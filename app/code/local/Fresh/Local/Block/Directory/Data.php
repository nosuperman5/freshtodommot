<?php
class Fresh_Local_Block_Directory_Data extends Mage_Directory_Block_Data
{
	//public function getCountryCodeHtmlSelect($defValue=null, $name='country_code', $id='country_code', $title='Country Code')
    public function getCountryCodeHtmlSelect($defValue=null, $name='country_id', $id='country', $title='Country')
	{
		
		Varien_Profiler::start('TEST: '.__METHOD__);
        if (is_null($defValue)) {
            $defValue = $this->getCountryId();
        }
        $cacheKey = 'DIRECTORY_COUNTRY_SELECT_STORE_'.Mage::app()->getStore()->getCode();
        if (Mage::app()->useCache('config') && $cache = Mage::app()->loadCache($cacheKey)) {
            $options = unserialize($cache);
        } else {
            //$options = $this->getCountryCollection()->toOptionArray();
			$options = Mage::helper('fresh_local')->getCountryCodeOptions();
            if (Mage::app()->useCache('config')) {
                Mage::app()->saveCache(serialize($options), $cacheKey, array('config'));
            }
        }
		//print_r($options); die();
        $html = $this->getLayout()->createBlock('core/html_select')
            ->setName($name)
            ->setId($id)
            ->setTitle(Mage::helper('directory')->__($title))
            ->setClass('form-control validate-select')
            ->setValue($defValue)
            ->setOptions($options)
            ->getHtml();

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $html;
    }

}