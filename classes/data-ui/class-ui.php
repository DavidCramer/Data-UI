<?php
/**
 * Core UI Controller.
 * Used to control creating and registering UI objects within a registered namespace.
 */

namespace Data_UI;

/**
 * Class UI
 *
 * @package Data_UI
 */
class UI {

    /**
     * Holds the UI's Menu Groups.
     *
     * @var \Data_UI\Menu_Group[]
     */
    protected $menu_groups = array();

    /**
     * Holds the UI's Admin Bar Nodes.
     *
     * @var \Data_UI\Admin_Bar_Node[]
     */
    protected $admin_bar_nodes = array();

    /**
     * Add a menu page.
     *
     * @param string $slug       The page slug.
     * @param string $menu_title The menu title.
     * @param string $capability The capability the user needs to access the page.
     * @param string $icon       The dashicon or URL to icon file to show in the menu.
     * @param int    $position   The position on the admin menu.
     *
     * @return \Data_UI\Menu_Group
     */
    public function menu_group( $slug, $menu_title = null, $capability = null, $icon = null, $position = 100 ) {
        if ( ! isset( $this->menu_groups[ $slug ] ) ) {
            $menu_group             = new Menu_Group( $slug );
            $menu_group->menu_title = $menu_title;
            $menu_group->capability = $capability;
            $menu_group->icon       = $icon;
            $menu_group->position   = $position;

            $this->menu_groups[ $slug ] = $menu_group;
        }

        return $this->menu_groups[ $slug ];
    }

    /**
     * Add a menu page.
     *
     * @param string $slug
     * @param string $group_title
     * @param string $capability
     * @param string $icon
     * @param int    $position
     *
     * @return \Data_UI\Menu_Group
     */
    public function admin_bar_node( $slug, $group_title = null, $capability = null, $icon = null, $position = 100 ) {
        if ( ! isset( $this->admin_bar_nodes[ $slug ] ) ) {
            $menu_group              = new Admin_Bar_Node( $slug );
            $menu_group->group_title = $group_title;
            $menu_group->capability  = $capability;
            $menu_group->icon        = $icon;
            $menu_group->position    = $position;

            $this->admin_bar_nodes[ $slug ] = $menu_group;
        }

        return $this->admin_bar_nodes[ $slug ];
    }

    public function post_type( $slug ) {
        return new Post_Type( $slug );
    }
}
