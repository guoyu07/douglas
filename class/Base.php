<?php

namespace douglas;

/*
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */

abstract class Base {

    /**
     * Title of content block displayed above content block
     * @var string
     */
    protected $title;
    /**
     * Content displayed to user
     * @var string
     */
    protected $content;
    /**
     * Small content box shown at top of page.
     * @var string
     */
    protected $message;
    /**
     * Applicant object
     * @var \douglas\Applicant
     */
    protected $applicant;
    protected $command;

    public function __construct()
    {
        if (!empty($_POST)) {
            $this->post();
        } else {
            $this->get();
        }
    }

    public function display()
    {
        $tpl['title'] = $this->title;
        $tpl['content'] = $this->content;
        $tpl['message'] = $this->message;

        \Layout::add(\PHPWS_Template::process($tpl, 'douglas', 'main.tpl'));
    }

    protected function loadApplicant($id=0)
    {
        \PHPWS_Core::initModClass('douglas', 'Applicant.php');
        if (!$id && isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
        }
        if (empty($this->applicant)) {
            $this->applicant = new Applicant($id);
        }
    }

}

?>