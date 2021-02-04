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
 * @property string $slug          The objects slug.
 */
class UI {

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array();

    /**
     * Holds the attached objects.
     *
     * @var UI_Object[]
     */
    protected $objects = array();
    /**
     * Holds the registered objects under type.
     *
     * @var array
     */
    protected $registry = array();

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
     * UI Object constructor.
     *
     * @param string $slug The object slug.
     */
    public function __construct( $slug = null ) {
        $this->slug = $slug;
    }

    /**
     * Register an object.
     *
     * @param string $slug   The slug to register the object under.
     * @param object $object The object to register.
     * @param string $type   The type of object to register.
     */
    protected function register( $slug, $object, $type ) {
        $this->attach( $object );
        $this->registry[ $type ][ $slug ] = $object;
    }

    /**
     * Add an admin menu group.
     *
     * @param string $slug       The menu slug.
     * @param string $menu_title The menu title.
     * @param string $capability The capability the user needs to access the page.
     * @param string $icon       The dashicon or URL to icon file to show in the menu.
     * @param int    $position   The position on the admin menu.
     *
     * @return \Data_UI\Menu_Group
     */
    public function menu_group( $slug, $menu_title = null, $capability = 'read', $icon = null, $position = 100 ) {
        if ( ! isset( $this->registry['menu_group'][ $slug ] ) ) {
            $menu_group             = new Menu_Group( $slug );
            $menu_group->menu_title = $menu_title;
            $menu_group->capability = $capability;
            $menu_group->icon       = $icon;
            $menu_group->position   = $position;
            $this->register( $slug, $menu_group, 'menu_group' );
        }

        return $this->registry['menu_group'][ $slug ];
    }

    /**
     * Add an admin page.
     *
     * @param string $slug       The page slug.
     * @param string $page_title The menu title.
     * @param string $menu_title The menu title.
     * @param string $capability The capability the user needs to access the page.
     * @param string $icon       The dashicon or URL to icon file to show in the menu.
     * @param int    $position   The position on the admin menu.
     *
     * @return \Data_UI\Page
     */
    public function page( $slug, $page_title, $menu_title = null, $capability = 'read', $icon = null, $position = 100 ) {
        if ( ! isset( $this->registry['pages'][ $slug ] ) ) {
            $page             = new Page( $slug );
            $page->page_title = $page_title;
            $page->menu_title = $menu_title;
            $page->capability = $capability;
            $page->icon       = $icon;
            $page->position   = $position;
            $this->register( $slug, $page, 'page' );
        }

        return $this->registry['page'][ $slug ];
    }

    /**
     * Add a settings page.
     *
     * @param string $slug       The page slug.
     * @param string $page_title The menu title.
     * @param string $menu_title The menu title.
     * @param string $capability The capability the user needs to access the page.
     * @param string $icon       The dashicon or URL to icon file to show in the menu.
     * @param int    $position   The position on the admin menu.
     *
     * @return \Data_UI\Settings_Page
     */
    public function settings_page( $slug, $page_title, $menu_title = null, $capability = 'read', $icon = null, $position = 100 ) {
        if ( ! isset( $this->registry['settings_page'][ $slug ] ) ) {
            $page             = new Settings_Page( $slug );
            $page->page_title = $page_title;
            $page->menu_title = $menu_title;
            $page->capability = $capability;
            $page->icon       = $icon;
            $page->position   = $position;
            $this->register( $slug, $page, 'settings_page' );
        }

        return $this->registry['settings_page'][ $slug ];
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
     * @return \Data_UI\Admin_Bar_Node
     */
    public function admin_bar_node( $slug, $group_title = null, $capability = 'read', $icon = null, $position = 100 ) {
        if ( ! isset( $this->registry['bar_nodes'][ $slug ] ) ) {
            $node              = new Admin_Bar_Node( $slug );
            $node->group_title = $group_title;
            $node->capability  = $capability;
            $node->icon        = $icon;
            $node->position    = $position;
            $this->register( $slug, $node, 'bar_nodes' );
        }

        return $this->registry['bar_nodes'][ $slug ];
    }

    /**
     * Attach an object.
     *
     * @param UI $object The object to attach.
     *
     * @return bool
     */
    public function attach( $object ) {
        $attached = false;
        if ( ! in_array( $object, $this->objects, true ) ) {
            $this->objects[] = $object;
            $attached        = true;
        }

        return $attached;
    }
}
