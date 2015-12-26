<?php
class Learn_Cartcomment_Model_Cart extends Mage_Checkout_Model_Cart 
{
    public function updateItems($data)
    {
        Mage::dispatchEvent('checkout_cart_update_items_before', array('cart'=>$this, 'info'=>$data));

        /* @var $messageFactory Mage_Core_Model_Message */
        $messageFactory = Mage::getSingleton('core/message');
        $session = $this->getCheckoutSession();
        $qtyRecalculatedFlag = false;
        foreach ($data as $itemId => $itemInfo) {
            $item = $this->getQuote()->getItemById($itemId);
            if (!$item) {
                continue;
            }

            if (!empty($itemInfo['remove']) || (isset($itemInfo['qty']) && $itemInfo['qty']=='0')) {
                $this->removeItem($itemId);
                continue;
            }

            $qty = isset($itemInfo['qty']) ? (float) $itemInfo['qty'] : false;
            if ($qty > 0) {
                $item->setQty($qty);

                $itemInQuote = $this->getQuote()->getItemById($item->getId());

                if (!$itemInQuote && $item->getHasError()) {
                    Mage::throwException($item->getMessage());
                }

                if (isset($itemInfo['before_suggest_qty']) && ($itemInfo['before_suggest_qty'] != $qty)) {
                    $qtyRecalculatedFlag = true;
                    $message = $messageFactory->notice(Mage::helper('checkout')->__('Quantity was recalculated from %d to %d', $itemInfo['before_suggest_qty'], $qty));
                    $session->addQuoteItemMessage($item->getId(), $message);
                }
            }
            
            /*- Cart Comments -*/
            if(!empty($itemInfo['comments'])) {
                $item->setItemcomment($itemInfo['comments']);
            }
            /*- Cart Comments -*/
        }

        if ($qtyRecalculatedFlag) {
            $session->addNotice(
                Mage::helper('checkout')->__('Some products quantities were recalculated because of quantity increment mismatch')
            );
        }

        Mage::dispatchEvent('checkout_cart_update_items_after', array('cart'=>$this, 'info'=>$data));
        return $this;
    }
}