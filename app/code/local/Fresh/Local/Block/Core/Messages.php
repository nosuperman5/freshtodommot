<?php

class Fresh_Local_Block_Core_Messages extends Mage_Core_Block_Messages
{
    /*public function getHtml($type=null)
    {
        $html = '<' . $this->_messagesFirstLevelTagName . ' id="admin_messages">';
        foreach ($this->getMessages($type) as $message) {
            $html.= '<' . $this->_messagesSecondLevelTagName . ' class="'.$message->getType().'-msg">'
                . ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText()
                . '</' . $this->_messagesSecondLevelTagName . '>';
        }
        $html .= '</' . $this->_messagesFirstLevelTagName . '>';
        return $html;
    }*/

    public function getGroupedHtml()
    {
        $types = array(
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING,
            Mage_Core_Model_Message::NOTICE,
            Mage_Core_Model_Message::SUCCESS
        );
        $html = '';
        foreach ($types as $type) {
            if ( $messages = $this->getMessages($type) ) {
                if ( !$html ) {
                    $html .= '<div class="messages">';
                }
                $html .= '<div class="alert alert-' . $type . '" role="alert">';
                $html .= '<ul>';

                foreach ( $messages as $message ) {
                    $html.= '<li>';
                    //$html.= '<' . $this->_messagesContentWrapperTagName . '>';
                    $html.= ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText();
                    //$html.= '</' . $this->_messagesContentWrapperTagName . '>';
                    $html.= '</li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
            }
        }
        if ( $html) {
            $html .= '</div>';
        }
        return $html;
    }
}
