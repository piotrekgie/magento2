<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\QuoteWebapi\Model\Webapi;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Quote\Api\CartManagementInterface;

/**
 * Replaces a "%cart_id%" value with the current authenticated customer's cart
 */
class ParamOverriderCartId implements ParamOverriderInterface
{
    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    public function __construct(
        UserContextInterface $userContext,
        CartManagementInterface $cartManagement
    ) {
        $this->userContext = $userContext;
        $this->cartManagement = $cartManagement;
    }

    public function getOverridenValue() {
        if ($this->userContext->getUserType() === UserContextInterface::USER_TYPE_CUSTOMER) {
            $customerId = $this->userContext->getUserId();

            /** @var \Magento\Quote\Api\Data\CartInterface */
            $cart = $this->cartManagement->getCartForCustomer($customerId);
            if ($cart) {
                return $cart->getId();
            }
        }
        return null;
    }
}
