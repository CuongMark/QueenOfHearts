<?php


namespace Angel\QoH\Api\Data;

interface PrizeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Prize list.
     * @return \Angel\QoH\Api\Data\PrizeInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Angel\QoH\Api\Data\PrizeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
