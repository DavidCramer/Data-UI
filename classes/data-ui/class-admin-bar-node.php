<?php

namespace Data_UI;

/**
 * Class Menu Group
 *
 * @package Data_UI
 * @property string          $group_title The group title shown on the menu.
 * @property string          $capability  The capability to access the group.
 * @property string          $icon        The URL or dashicon to use on the menu.
 * @property string|callable $callback    The callback to render the page.
 * @property int             $position    The group position on the menu.
 */
class Admin_Bar_Node extends Page {

    /**
     * Setup hooks.
     */
    public function setup_hooks() {
        add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );
    }

    /**
     * Add menu group to the admin menu.
     *
     * @param \WP_Admin_Bar $wp_admin_bar The admin bar object.
     */
    public function admin_bar_menu( $wp_admin_bar ) {

        $wp_admin_bar->add_node(
            [
                'id'    => $this->slug,
                'title' => $this->group_title,
            ]
        );

        // Add pages.
        foreach ( $this->objects as $page ) {
            $wp_admin_bar->add_node(
                [
                    'id'     => $page->slug,
                    'title'  => $page->menu_title,
                    'parent' => $this->slug,
                    'href'   => admin_url( 'admin.php?page=' . $page->slug ),
                    'meta'   => array(),
                ]
            );
        }
    }

    /**
     * Attach an object.
     *
     * @param $page
     *
     * @return \Data_UI\Page
     */
    public function attach( $page ) {
        if ( $page instanceof Page ) {
            parent::attach( $page );
        }

        return $page;
    }
}
