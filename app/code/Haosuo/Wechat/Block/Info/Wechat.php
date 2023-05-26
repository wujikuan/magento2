<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Haosuo\Wechat\Block\Info;

use Magento\Payment\Block\Info;

class Wechat extends Info
{

    /**
     * @var string
     */
    protected $_template = 'Haosuo_Wechat::info/wechat.phtml';
}
