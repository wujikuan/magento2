<?php

namespace Haosuo\Catalog\Model\Config\Source;


class SelectChooseOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                [
                    'value'=>'a',
                    'label'=>__('A')
                ],
                [
                    'value'=>'b',
                    'label'=>__('B')
                ],
                [
                    'value'=>'c',
                    'label'=>__('C')
                ]
            ];
        }
        return $this->_options;
    }

}
