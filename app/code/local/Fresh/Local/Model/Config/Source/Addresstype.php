<?php
class Fresh_Local_Model_Config_Source_Addresstype extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /*public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('fresh_local')->__('Residential')),
            array('value' => 0, 'label'=>Mage::helper('fresh_local')->__('Non-residential')),
        );
    }

    public function toArray()
    {
        return array(
            0 => Mage::helper('fresh_local')->__('Residential'),
            1 => Mage::helper('fresh_local')->__('Non-residential'),
        );
    }*/
	
	public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = array(
                array(
					'value' => 1,
					'label'=>Mage::helper('fresh_local')->__('Residential')
                ),
                array(
					'value' => 2,
					'label'=>Mage::helper('fresh_local')->__('Non-residential')
                )
            );
        }
        return $this->_options;
    }
}