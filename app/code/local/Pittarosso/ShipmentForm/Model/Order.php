<?php

class Pittarosso_ShipmentForm_Model_Order extends Tbuy_CustomerCare_Model_Order
{
    /**
     * {@inheritdoc}
     */
    public function canShip()
    {
        if ($this->getState() === 'ax' || ($this->getState() === 'sold' && $this->getStatus() !== 'shipped')) {
            return parent::canShip();
        } else {
        	return false;
        }
    }


	/**
	 * Check order state before saving
	 */
	protected function _checkState()
	{
		if (!$this->getId()) {
			return $this;
		}

		$userNotification = $this->hasCustomerNoteNotify() ? $this->getCustomerNoteNotify() : null;

		if (!$this->isCanceled()
			&& !$this->canUnhold()
			&& !$this->canInvoice()
			&& !$this->canShip()) {


			/**
			 * Order can be closed just in case when we have refunded amount.
			 * In case of "0" grand total order checking ForcedCanCreditmemo flag
			 */
			if (floatval($this->getTotalRefunded()) || (!$this->getTotalRefunded()
					&& $this->hasForcedCanCreditmemo())
			) {
				if ($this->getState() !== self::STATE_CLOSED) {
					$this->_setState(self::STATE_CLOSED, true, '', $userNotification);
				}
			}
		}

		if ($this->getState() == self::STATE_NEW && $this->getIsInProcess()) {
			$this->setState(self::STATE_PROCESSING, true, '', $userNotification);
		}
		return $this;
	}
}
