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

class Ikantam_ExtendedFaq_Model_Resource_Category_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('extendedfaq/category');
    }
    
    /**
     * Retreive option hash
     *
     * @return array
     */
    public function toOptionHash()
    {
        return parent::_toOptionHash('id', 'title');
    }
}
