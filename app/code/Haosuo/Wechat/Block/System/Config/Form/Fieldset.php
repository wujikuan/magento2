<?php

namespace Haosuo\Wechat\Block\System\Config\Form;

class Fieldset extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    protected function _getAdditionalElementTypes()
    {
        return [
            'modal_debug' => '\Magento\Config\Block\System\Config\Form\Field\Toggle',
        ];
    }
}
