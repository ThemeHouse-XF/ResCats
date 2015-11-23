<?php

/**
 *
 * @see XenResource_Model_Category
 */
class ThemeHouse_ResCats_Extend_XenResource_Model_Category extends XFCP_ThemeHouse_ResCats_Extend_XenResource_Model_Category
{

    /**
     * Determines if a user can add a category inside the given resource
     * category.
     *
     * @param array $category
     * @param string $errorPhraseKey
     * @param array $viewingUser
     * @param array|null $categoryPermissions
     *
     * @return boolean
     */
    public function canAddCategory(array $category = null, &$errorPhraseKey = '', array $viewingUser = null, 
        array $categoryPermissions = null)
    {
        if ($category) {
            $this->standardizeViewingUserReferenceForCategory($category, $viewingUser, $categoryPermissions);
        } else {
            $this->standardizeViewingUserReference($viewingUser);
        }
        
        if ($category) {
            return XenForo_Permission::hasContentPermission($categoryPermissions, 'addCategory');
        }
        
        return XenForo_Permission::hasPermission($viewingUser['permissions'], 'resource', 'addCategory');
    }

    /**
     * Determines if a user can edit the given resource category.
     *
     * @param array $category
     * @param string $errorPhraseKey
     * @param array $viewingUser
     * @param array|null $categoryPermissions
     *
     * @return boolean
     */
    public function canEditCategory(array $category, &$errorPhraseKey = '', array $viewingUser = null, 
        array $categoryPermissions = null)
    {
        $this->standardizeViewingUserReferenceForCategory($category, $viewingUser, $categoryPermissions);
        
        return XenForo_Permission::hasContentPermission($categoryPermissions, 'editCategory');
    }

    /**
     * Determines if a user can delete the given resource category.
     *
     * @param array $category
     * @param string $errorPhraseKey
     * @param array $viewingUser
     * @param array|null $categoryPermissions
     *
     * @return boolean
     */
    public function canDeleteCategory(array $category, &$errorPhraseKey = '', array $viewingUser = null,
        array $categoryPermissions = null)
    {
        $this->standardizeViewingUserReferenceForCategory($category, $viewingUser, $categoryPermissions);
    
        return XenForo_Permission::hasContentPermission($categoryPermissions, 'deleteCategory');
    }
}