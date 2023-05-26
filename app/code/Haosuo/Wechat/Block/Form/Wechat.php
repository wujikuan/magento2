<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Haosuo\Wechat\Block\Form;

use Magento\Payment\Block\Form;

class Wechat extends Form
{
    /**
     * Wechat template
     *
     * @var string
     */
    protected $_template = 'Haosuo_Wechat::form/wechat.phtml';
}
