<?php
/*
 * HiPay fullservice Magento2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright      Copyright (c) 2016 - HiPay
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 *
 */
namespace HiPay\FullserviceMagento\Block\Adminhtml\SplitPayment\Edit;

/**
 * Adminhtml split payment edit form block
 *
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
	
	/**
	 * 
	 * @var \HiPay\FullserviceMagento\Model\System\Config\Source\SplitPayment\Status  $spStatus
	 */
    protected $spStatus;
	
	/**
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\Data\FormFactory $formFactory
	 * @param array $data
	 */
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Framework\Registry $registry,
			\Magento\Framework\Data\FormFactory $formFactory,
			\HiPay\FullserviceMagento\Model\System\Config\Source\SplitPayment\Status  $spStatus,
			array $data = []
			) {

				parent::__construct($context,$registry,$formFactory, $data);
				$this->spStatus = $spStatus;
	}
	
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
    	
    	/*
    	 * Checking if user have permissions to save information
    	 */
    	if ($this->_isAllowedAction('HiPay_FullserviceMagento::split_save')) {
    		$isElementDisabled = false;
    	} else {
    		$isElementDisabled = true;
    	}
    	
    	/** @var \Magento\Framework\Data\Form $form */
    	$form = $this->_formFactory->create(
    			['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
    			);
    
    	$form->setHtmlIdPrefix('splitpayment_');
    
    	$model = $this->_coreRegistry->registry('split_payment');
    
    	$fieldset = $form->addFieldset(
    			'splitpayment_fieldset',
    			['legend' => __('Split Payment'), 'class' => 'fieldset-wide']
    			);
    
    	$fieldset->addField(
    			'date_to_pay',
    			'date',
    			[
    					'name' => 'date_to_pay',
    					'label' => __('Date to pay'),
    					'title' => __('Date to pay'),
    					'required'=>true,
    					'disabled' => $isElementDisabled
    			]
    			);
    	

    	$fieldset->addField(
    			'amount_to_pay',
    			'text',
    			[
    					'name' => 'amount_to_pay',
    					'label' => __('Amount to pay'),
    					'title' => __('Amount to pay'),
    					'required'=>true,
    					'disabled' => $isElementDisabled,
    					'class'=>'validate-zero-or-greater'
    			]
    			);
    	
    	$options = $this->spStatus->toOptionArray();
    	$fieldset->addField(
    			'status',
    			'select',
    			[
    					'name' => 'status',
    					'label' => __('Status'),
    					'title' => __('Status'),
    					'values'=>$options,
    					'required'=>true,
    					'disabled' => true
    			]
    			);
    	
    
    	$this->_eventManager->dispatch('adminhtml_hipay_splitpayment_edit_prepare_form', ['form' => $form]);

    	if ($model->getSplitPaymentId() !== null) {
    		// If edit add id
    		$form->addField('split_payment_id', 'hidden', ['name' => 'split_payment_id', 'value' => $model->getSplitPaymentId()]);
    	}
    	
    	$form->setValues($model->getData());
    	$form->setUseContainer(true);
    	$this->setForm($form);
    
    	return parent::_prepareForm();
    }
    
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
    	return $this->_authorization->isAllowed($resourceId);
    }

}
