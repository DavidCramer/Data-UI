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
     * @param string $slug
     * @param string $group_title
     * @param string $capability
     * @param string $icon
     * @param int    $position
     *
     * @return \Data_UI\Menu_Group
     */
    public function menu_group( $slug, $group_title = null, $capability = null, $icon = null, $position = 100 ){
        if ( ! isset( $this->menu_groups[ $slug ] ) ) {
            $menu_group              = new Menu_Group( $slug );
            $menu_group->group_title = $group_title;
            $menu_group->capability  = $capability;
            $menu_group->icon        = $icon;
            $menu_group->position    = $position;

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
    public function admin_bar_node( $slug, $group_title = null, $capability = null, $icon = null, $position = 100 ){
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
