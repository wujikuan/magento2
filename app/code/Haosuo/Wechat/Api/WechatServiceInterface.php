<?php
namespace Haosuo\Wechat\Api;

use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;


/**
 * Interface providing token generation for Admins
 *
 * @api
 * @since 100.0.2
 */
interface WechatServiceInterface
{
    /**
     * Create access token for admin given the admin credentials.
     *
     * @return string Token created
     * @throws InputException For invalid input
     * @throws AuthenticationException
     * @throws LocalizedException
     */
    public function getOrderPlaceRedirectUrl();

    /**
     * Create access token for admin given the admin credentials.
     *
     * @return string Token created
     * @throws InputException For invalid input
     * @throws AuthenticationException
     * @throws LocalizedException
     */
    public function getOrderPaymentStatus();

}
