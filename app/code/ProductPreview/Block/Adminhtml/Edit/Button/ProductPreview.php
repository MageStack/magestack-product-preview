<?php

namespace MageStack\ProductPreview\Block\Adminhtml\Edit\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Catalog\Model\Product;

class ProductPreview extends Generic {
    /**
     * Url Builder
     *
     * @var Context
     */
    protected $context;

    /**
     * Registry
     *
     * @var Registry
     */
    protected $registry;

    /*
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    /*
     * @var \Magento\Framework\UrlInterface
     */
    protected $_productModel;
    /*
     *@var \Magento\Framework\App\Helper\Context
     */
    protected $_contextHelper;
    /*
     *@var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManagerInterface;
    /**
     * Generic constructor
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Product $product,
        ObjectManagerInterface $objectManagerInterface,
        \Magento\Framework\App\Helper\Context $contextHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
    ) {
        $this->context = $context;
        $this->_productModel = $product;
        $this->_objectManager = $objectManagerInterface;
        $this->registry = $registry;
        $this->_contextHelper = $contextHelper;
        $this->_urlBuilder = $contextHelper->getUrlBuilder();
        $this->_storeManagerInterface = $storeManagerInterface;
    }
    /*
     * getting data for preview button
     * return array
     */
    public function getButtonData()
    {
        $product  = $this->_productModel->load($this->getProduct()->getId());
        $storeId = $this->_storeManagerInterface->getDefaultStoreView()->getId();
        $storeCode = $this->_storeManagerInterface->getDefaultStoreView()->getCode();
        $productUrl = $this->_urlBuilder->getUrl( 'catalog/product/view', [ '_scope' => $storeId, 'id' => $product->getId(), '_nosid' => true , '_query' => ['___store' => $storeCode]]);
        if($this->_isProductNew()) return;
        return [
            'label' => __('Preview'),
            'on_click' => "window.open('{$productUrl}','_blank'); return false;",
            'sort_order' => 1
        ];
    }

    /**
     * Check whether new product is being created
     *
     * @return bool
     */
    protected function _isProductNew()
    {
        $product = $this->getProduct();
        return !$product || !$product->getId();
    }
}