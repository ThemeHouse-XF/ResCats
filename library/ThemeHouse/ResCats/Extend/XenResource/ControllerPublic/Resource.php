<?php

/**
 *
 * @see XenResource_ControllerPublic_Resource
 */
class ThemeHouse_ResCats_Extend_XenResource_ControllerPublic_Resource extends XFCP_ThemeHouse_ResCats_Extend_XenResource_ControllerPublic_Resource
{

    /**
     *
     * @see XenResource_ControllerPublic_Resource::actionIndex()
     */
    public function actionIndex()
    {
        $response = parent::actionIndex();
        
        if ($response instanceof XenForo_ControllerResponse_View) {
            $categoryModel = $this->_getCategoryModel();
            
            $response->params['canAddCategory'] = $categoryModel->canAddCategory();
        }
        
        return $response;
    }

    /**
     *
     * @see XenResource_ControllerPublic_Resource::actionCategory()
     */
    public function actionCategory()
    {
        $response = parent::actionCategory();
        
        if ($response instanceof XenForo_ControllerResponse_View) {
            $categoryModel = $this->_getCategoryModel();
            
            $category = $response->params['category'];
            
            $response->params['canAddCategory'] = $categoryModel->canAddCategory($category);
            $response->params['canEditCategory'] = $categoryModel->canEditCategory($category);
            $response->params['canDeleteCategory'] = $categoryModel->canDeleteCategory($category);
        }
        
        return $response;
    }

    protected function _getCategoryAddEditResponse(array $category, array $parentCategory = null)
    {
        $fieldModel = $this->_getFieldModel();
        $prefixModel = $this->_getPrefixModel();
        
        if (!empty($category['resource_category_id'])) {
            $selectedFields = $fieldModel->getFieldIdsInCategory($category['resource_category_id']);
            
            $categoryPrefixes = array_keys($prefixModel->getPrefixesInCategory($category['resource_category_id']));
        } else {
            $selectedFields = array();
            
            $categoryPrefixes = array();
        }
        
        if (!empty($category['thread_node_id'])) {
            $threadPrefixes = $this->getModelFromCache('XenForo_Model_ThreadPrefix')->getUsablePrefixesInForums(
                $category['thread_node_id']);
        } else {
            $threadPrefixes = array();
        }
        
        $fields = $fieldModel->prepareResourceFields($fieldModel->getResourceFields());
        
        $categoryBreadcrumbs = array();
        if (!empty($category['resource_category_id'])) {
            $categoryBreadcrumbs = $this->_getCategoryModel()->getCategoryBreadcrumb($category);
        } elseif ($parentCategory) {
            $categoryBreadcrumbs = $this->_getCategoryModel()->getCategoryBreadcrumb($parentCategory);
        }
        
        $viewParams = array(
            'category' => $category,
            'parentCategory' => $parentCategory,
            'nodes' => $this->getModelFromCache('XenForo_Model_Node')->getAllNodes(),
            'threadPrefixes' => $threadPrefixes,
            
            'fieldsGrouped' => $fieldModel->groupResourceFields($fields),
            'fieldGroups' => $fieldModel->getResourceFieldGroups(),
            'selectedFields' => $selectedFields,
            
            'prefixGroups' => $prefixModel->getPrefixesByGroups(),
            'prefixOptions' => $prefixModel->getPrefixOptions(),
            'categoryPrefixes' => ($categoryPrefixes ? $categoryPrefixes : array(
                0
            )),
            
            'categoryBreadcrumbs' => $categoryBreadcrumbs
        );
        
        return $this->responseView('ThemeHouse_ResCats_ViewPublic_Category_Add',
            'th_resource_category_add_rescats', $viewParams);
    }

    public function actionCategoryAdd()
    {
        $categoryModel = $this->_getCategoryModel();
        
        $categoryId = $this->_input->filterSingle('resource_category_id', XenForo_Input::UINT);
        if ($categoryId) {
            $parentCategory = $this->_getResourceHelper()->assertCategoryValidAndViewable($categoryId);
        } else {
            $parentCategory = null;
        }
        
        if (!$parentCategory) {
            if (!$categoryModel->canAddCategory(null, $key)) {
                throw $this->getErrorOrNoPermissionResponseException($key);
            }
        } else {
            if (!$categoryModel->canAddCategory($parentCategory, $key)) {
                throw $this->getErrorOrNoPermissionResponseException($key);
            }
        }
        
        $category = array(
            'display_order' => 1,
            'allow_local' => 1,
            'allow_external' => 1,
            'allow_commercial_external' => 1,
            'allow_fileless' => 1,
            'category_description' => ''
        );
        
        return $this->_getCategoryAddEditResponse($category, $parentCategory);
    }

    /**
     * Displays a form to edit an existing category.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionCategoryEdit()
    {
        $categoryModel = $this->_getCategoryModel();
        
        $categoryId = $this->_input->filterSingle('resource_category_id', XenForo_Input::UINT);
        if (!$categoryId) {
            return $this->responseRedirect(XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL, 
                XenForo_Link::buildPublicLink('resource/categories/add'));
        } else {
            $category = $this->_getResourceHelper()->assertCategoryValidAndViewable($categoryId);
        }
        
        if (!$categoryModel->canEditCategory($category, $key)) {
            throw $this->getErrorOrNoPermissionResponseException($key);
        }
        
        return $this->_getCategoryAddEditResponse($category);
    }

    /**
     * Updates an existing category or inserts a new one.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionCategorySave()
    {
        $this->_assertPostOnly();
        
        $categoryModel = $this->_getCategoryModel();
        
        $categoryId = $this->_input->filterSingle('resource_category_id', XenForo_Input::UINT);
        
        if ($categoryId) {
            $category = $this->_getResourceHelper()->assertCategoryValidAndViewable($categoryId);
            
            if (!$categoryModel->canEditCategory($category, $key)) {
                throw $this->getErrorOrNoPermissionResponseException($key);
            }
        } else {
            $category = null;
        }
        
        $parentCategoryId = $this->_input->filterSingle('parent_category_id', XenForo_Input::UINT);
        
        if ($parentCategoryId) {
            $parentCategory = $this->_getResourceHelper()->assertCategoryValidAndViewable($categoryId);
        } else {
            $parentCategory = null;
        }
        
        if (!$category || $parentCategoryId != $category['parent_category_id']) {
            if (!$parentCategory) {
                if (!$categoryModel->canAddCategory(null, $key)) {
                    throw $this->getErrorOrNoPermissionResponseException($key);
                }
            } else {
                if (!$categoryModel->canAddCategory($parentCategory, $key)) {
                    throw $this->getErrorOrNoPermissionResponseException($key);
                }
            }
        }
        
        $dwInput = $this->_input->filter(
            array(
                'category_title' => XenForo_Input::STRING,
                'parent_category_id' => XenForo_Input::UINT,
                'display_order' => XenForo_Input::UINT,
                'allow_local' => XenForo_Input::UINT,
                'allow_external' => XenForo_Input::UINT,
                'allow_commercial_external' => XenForo_Input::UINT,
                'allow_fileless' => XenForo_Input::UINT,
                'thread_node_id' => XenForo_Input::UINT,
                'thread_prefix_id' => XenForo_Input::UINT,
                'always_moderate_create' => XenForo_Input::UINT,
                'always_moderate_update' => XenForo_Input::UINT,
                'require_prefix' => XenForo_Input::BOOLEAN
            ));
        
        $input = $this->_input->filter(
            array(
                'available_fields' => array(
                    XenForo_Input::STRING,
                    'array' => true
                ),
                'available_prefixes' => array(
                    XenForo_Input::UINT,
                    'array' => true
                )
            ));
        
        $description = $this->getHelper('Editor')->getMessageText('category_description', $this->_input);
        
        $description = XenForo_Helper_String::autoLinkBbCode($description, false);
        
        /* @var $formatter ThemeHouse_ResCats_BbCode_Formatter_BbCode_Description */
        $formatter = XenForo_BbCode_Formatter_Base::create('ThemeHouse_ResCats_BbCode_Formatter_BbCode_Description');
        $formatter->configureForDescription();
        
        $parser = XenForo_BbCode_Parser::create($formatter);
        $description= $parser->render($description);
        
        $bbCodeParser = XenForo_BbCode_Parser::create(XenForo_BbCode_Formatter_Base::create('Wysiwyg'));
        $dwInput['category_description'] = $bbCodeParser->render($description);

        $dw = XenForo_DataWriter::create('XenResource_DataWriter_Category');
        if ($categoryId) {
            $dw->setExistingData($categoryId);
        }
        $dw->bulkSet($dwInput);
        $dw->setExtraData(XenResource_DataWriter_Category::DATA_FIELD_IDS, $input['available_fields']);
        $dw->save();
        
        $this->_getPrefixModel()->updatePrefixCategoryAssociationByCategory($dw->get('resource_category_id'), 
            $input['available_prefixes']);
        
        $category = $dw->getMergedData();
        
        if ($dw->isInsert()) {
            return $this->responseRedirect(XenForo_ControllerResponse_Redirect::RESOURCE_CREATED, 
                XenForo_Link::buildPublicLink('resources/categories', $category));
        } else {
            return $this->responseRedirect(XenForo_ControllerResponse_Redirect::RESOURCE_UPDATED, 
                XenForo_Link::buildPublicLink('resources/categories', $category));
        }
    }

    public function actionCategoryDelete()
    {
        $category = $this->_getResourceHelper()->assertCategoryValidAndViewable();
        
        if (!$this->_getCategoryModel()->canDeleteCategory($category, $errorPhraseKey)) {
            throw $this->getErrorOrNoPermissionResponseException($errorPhraseKey);
        }
        
        if ($this->isConfirmedPost()) {
            $dw = XenForo_DataWriter::create('XenResource_DataWriter_Category');
            $dw->setExistingData($category['resource_category_id']);
            $dw->delete();
            
            $reason = $this->_input->filterSingle('reason', XenForo_Input::STRING);
            XenForo_Model_Log::logModeratorAction('resource_category', $category, 'delete', array(
                'reason' => $reason
            ));
            
            if ($category['parent_category_id']) {
                $parentCategory = $this->_getResourceHelper()->assertCategoryValidAndViewable(
                    $category['parent_category_id']);
                return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, 
                    XenForo_Link::buildPublicLink('resources/categories', $parentCategory));
            }
            return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, 
                XenForo_Link::buildPublicLink('resources'));
        } else {
            $viewParams = array(
                'category' => $category,
                'categoryBreadcrumbs' => $this->_getCategoryModel()->getCategoryBreadcrumb($category)
            );
            
            return $this->responseView('ThemeHouse_ResCats_ViewPublic_Category_Delete',
                'th_resource_category_delete_rescats', $viewParams);
        }
    }
}