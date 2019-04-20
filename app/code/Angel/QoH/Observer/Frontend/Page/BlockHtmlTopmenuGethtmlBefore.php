<?php


namespace Angel\QoH\Observer\Frontend\Page;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\UrlInterface;

class BlockHtmlTopmenuGethtmlBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        UrlInterface $urlBuilder
    ){
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $data = [
            'name'      => __('Queen of Hearts'),
            'id'        => 'qoh_menu_item',
            'url'       => $this->urlBuilder->getUrl('qoh'),
            'is_active' => false
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);

        $data = [
            'name'      => __('Current Raffle'),
            'id'        => 'qoh_menu_item_current',
            'url'       => $this->urlBuilder->getUrl('qoh'),
            'is_active' => false
        ];
        $processing = new Node($data, 'id', $tree, $node);
        $node->addChild($processing);

        $data = [
            'name'      => __('Finished'),
            'id'        => 'qoh_menu_item_finished',
            'url'       => $this->urlBuilder->getUrl('qoh/finished'),
            'is_active' => false
        ];
        $finished = new Node($data, 'id', $tree, $node);
        $node->addChild($finished);
    }
}
