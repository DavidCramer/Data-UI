<?php
/**
 * Screen extension of UI_Object. This extension is responsible for working with output or rendered UI_Objects. I.E pages or fields.
 */

namespace Data_UI;

/**
 * Class Screen
 *
 * @package Data_UI
 */
class Screen extends UI_Object {

    /**
     * The UI component controller object.
     *
     * @var \Data_UI\UI\UI
     */
    protected $ui;

    /**
     * UI Object constructor.
     *
     * @param string $slug The object slug.
     */
    public function __construct( $slug = null ) {
        $this->ui = new UI\UI( $slug );
        parent::__construct( $slug );
    }

    /**
     * Render the page component as a callback in admin_menu (add_submenu_page).
     */
    public function render() {
        $this->ui->render();
    }
}
