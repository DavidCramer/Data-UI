<?php

namespace Data_UI;

use Data_UI\UI\Components\Component\Base;
use Data_UI\UI\Components\Component\Element;

/**
 * Class Menu Group
 *
 * @package Data_UI
 * @property string $menu_title  The title shown on the menu.
 * @property string $capability  The capability to access the group.
 * @property string $icon        The URL or dashicon to use on the menu.
 * @property int    $position    The group position on the menu.
 */
class Menu_Group extends Page {

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array(
        'menu_title' => '',
        'capability' => 'manage_options',
        'icon'       => 'dashicons-table-row-before',
        'position'   => null,
    );

    /**
     * Init the object.
     */
    public function init() {

        $notice          = new Notice();
        $notice->level   = 'warning';
        $notice->message = 'Add pages to menu.';
        $notice->dismiss = true;
        $this->ui->add_component( $notice );
        $this->callback = array( $this, 'render' );
    }

    /**
     * Add menu group to the admin menu.
     */
    public function admin_menu() {
        $this->page_hook = add_menu_page( $this->page_title, $this->menu_title, $this->capability, $this->slug, $this->callback, $this->icon, $this->position );
    }

    /**
     * Add a page to the menu group.
     *
     * @param \Data_UI\Page $page The page object to attach.
     *
     * @return \Data_UI\Page
     */
    public function attach( $page ) {
        if ( $page instanceof Page ) {
            $page->parent = $this->slug;
            if ( is_null( $this->page_title ) ) {
                // Leaving the menu title null, inherits from the first page attached.
                $this->page_title = $page->page_title;
                $page->slug       = $this->slug;
            }
            parent::attach( $page );
        }

        return $page;
    }
}
