<?php
/**
 * Notice class for displaying notices.
 */

namespace Data_UI;

use \Data_UI\UI\Components\Component;

/**
 * Class Screen
 *
 * @package Data_UI
 * @property string $level            The notice level. Success, warning, info, neutral etc.
 * @property bool   $dismiss          Flag if the notice is dismissible.
 * @property string $message          The notice message.
 */
class Notice extends Screen {

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array(
        'level'   => 'neutral',
        'dismiss' => false,
        'message' => null,
    );

    /**
     * Init the object.
     */
    public function admin_init() {
        $notice          = new Component\Notice();
        $notice->level   = $this->level;
        $notice->dismiss = $this->dismiss;
        $notice->message = $this->message;
        $this->ui->add_component( $notice );
    }

}
