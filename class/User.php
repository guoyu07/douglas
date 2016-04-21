<?php

namespace douglas;

/*
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */

class User extends Base {

    protected $command = 'signup';

    public function __construct()
    {
        if (!empty($_REQUEST['uop'])) {
            $this->command = $_REQUEST['uop'];
        }
        parent::__construct();
    }

    public function get()
    {
        \Current_User::requireLogin();
        switch ($this->command) {
            case 'signup':
                $this->signup();
                break;

            case 'thankyou':
                $this->title = 'Thank you';
                $this->content = 'Your application is submitted. Your references will be contacted using the supplied addresses.';
                break;
        }
        $this->display();
    }

    public function post()
    {
        switch ($this->command) {
            case 'post_basic_info':
                $this->loadApplicant();
                $this->applicant->postBasic();
                if (!empty($this->applicant->errors)) {
                    $this->applicant->stage = 1;
                    $this->signup();
                } else {
                    $this->applicant->stage = 2;
                    $result = $this->applicant->save();
                    if (\PEAR::isError($result)) {
                        \PHPWS_Error::log($result);
                        exit('Could not save your applicant');
                    }
                    \PHPWS_Core::reroute('index.php?module=douglas&uop=signup&id=' . $this->applicant->id);
                }
                break;


            case 'post_activities':
                $this->loadApplicant();
                $this->applicant->postActivities();
                if (!empty($this->applicant->errors)) {
                    $this->applicant->stage = 2;
                    $this->signup();
                } else {
                    $this->applicant->stage = 3;
                    $result = $this->applicant->save();
                    if (\PEAR::isError($result)) {
                        \PHPWS_Error::log($result);
                        exit('Could not save your applicant');
                    }
                    \PHPWS_Core::reroute('index.php?module=douglas&uop=signup&id=' . $this->applicant->id);
                }
                break;

            case 'post_references':
                $this->loadApplicant();
                $this->applicant->postReferences();
                if (!empty($this->applicant->errors)) {
                    $this->applicant->stage = 3;
                    $this->signup();
                } else {
                    $this->applicant->stage = 4;
                    $result = $this->applicant->save();
                    if (\PEAR::isError($result)) {
                        \PHPWS_Error::log($result);
                        exit('Could not save your applicant');
                    }
                    \PHPWS_Core::reroute('index.php?module=douglas&uop=signup&id=' . $this->applicant->id);
                }
                break;

            case 'post_documents':
                $this->loadApplicant();
                $this->applicant->postDocuments();
                if (!empty($this->applicant->errors)) {
                    $this->applicant->stage = 4;
                    $this->signup();
                } else {
                    $this->applicant->stage = 5;
                    $result = $this->applicant->save();
                    if (\PEAR::isError($result)) {
                        \PHPWS_Error::log($result);
                        exit('Could not save your applicant');
                    }
                    \PHPWS_Core::reroute('index.php?module=douglas&uop=signup&id=' . $this->applicant->id);
                }
                break;

            case 'final_confirmation':
                $this->loadApplicant();
                $this->applicant->completed = 1;
                $this->applicant->save();
                $this->applicant->contactReferences();
                \PHPWS_Core::reroute('index.php?module=douglas&uop=thankyou');
                break;
        }
        $this->display();
    }

    private function signup()
    {
        $due_date = \PHPWS_Settings::get('douglas', 'due_date');
        $unix_time =  strtotime($due_date);
        $current_time = time();
        if ($current_time > $unix_time) {
            $this->content .= 'The Douglas application process is complete. Thank you for your interest.';
            return;
        }
        $this->loadUserApplicant();

        if (isset($_REQUEST['stage'])) {
            $stage = $_REQUEST['stage'];
        } else {
            $stage = $this->applicant->stage;
        }
        $this->title = 'Douglas Miller Honorary Scholarship Applicant - Stage ' . $stage;
        switch ($stage) {
            case '1':
                $this->content .= $this->stageLinks();
                $this->content .= $this->applicant->basicInfo();
                break;

            case '2':
                $this->content .= $this->stageLinks();
                $this->content .= $this->applicant->extraActivities();
                break;

            case '3':
                $this->content .= $this->stageLinks();
                $this->content .= $this->applicant->references();
                break;

            case '4':
                $this->content .= $this->stageLinks();
                $this->content .= $this->applicant->documents();
                break;

            case '5':
                $this->content .= $this->stageLinks();
                $this->content .= $this->completed();
                break;

            default:
                exit($stage);
        }
    }

    private function stageLinks()
    {
        $content = '<div style="padding : 4px; border-bottom : 1px solid black">';
        if (isset($_REQUEST['stage'])) {
            $current_stage = $_REQUEST['stage'];
        } elseif ($this->applicant->completed) {
            $current_stage = 5;
        } else {
            $current_stage = 1;
        }
        for ($i = 1; $i <= $this->applicant->stage; $i++) {
            switch ($i) {
                case 1:
                    $title = 'Contact and Employment';
                    break;
                case 2:
                    $title = 'Activities and Awards';
                    break;
                case 3:
                    $title = 'References';
                    break;
                case 4:
                    $title = 'Essay and Resume';
                    break;

                case 5:
                    $title = 'Completion';
                    break;
            }
            if ($i == $current_stage) {
                $links[] = $i . '. ' . $title;
            } else {
                $links[] = \PHPWS_Text::moduleLink($i . '. ' . $title,
                                'douglas',
                                array('uop' => 'signup', 'stage' => $i));
            }
        }
        $content .= implode(' &gt;&gt; ', $links);
        $content .= '</div>';
        return $content;
    }

    private function completed()
    {
        $form = new \PHPWS_Form('complete');

        $pretpl = $this->applicant->getTags();
        if ($this->applicant->completed) {
            $pretpl['message'] = '<p>Your application is complete and has been submitted. If you need to change any information, you may still do so.</p>';
            if (!empty($_SESSION['douglas_email'])) {
                $pretpl['message'] .= '<p style="background-color: #e3e3e3; padding : 4px;font-weight:bold">It appears that you updated your reference information.'
                        . ' Click the button at the bottom of the page to notify the new reference(s).'
                        . ' Otherwise, your changes have been saved and you may leave this page.</p>';
            $form->addSubmit('submit',
                    'Send my application to my updated reference(s)');
            }
        } else {
            $pretpl['message'] = '<p>Your application is complete. This is your last opportunity to update your information before it is submitted.</p>';
            $pretpl['final_authorization'] = '<p><strong>I hereby authorize the University Recreation Student Employee Douglas Miller
    Honorary Scholarship Committee to verify my grade point average with the
    Appalachian State University Registrar for the purpose of determining
    eligibility. I also certify all information submitted to the Scholarship
    Committee is correct to my best knowledge.</strong></p>';
            $form->addSubmit('submit',
                    'Agree with the above and submit my information for consideration');
        }

        $form->addHidden('module', 'douglas');
        $form->addHidden('id', $this->applicant->id);
        $form->addHidden('uop', 'final_confirmation');
        $tpl = $form->getTemplate();
        $finaltpl = array_merge($tpl, $pretpl);
        return \PHPWS_Template::process($finaltpl, 'douglas',
                        'submit_complete.tpl');
    }

    private function loadUserApplicant()
    {
        $this->loadApplicant();
        if ($this->applicant->id) {
            return true;
        }
        $uid = \Current_User::getId();
        $db = new \PHPWS_DB('douglas_applicants');
        $db->addWhere('user_id', $uid);
        $result = $db->loadObject($this->applicant);
        if (\PEAR::isError($result)) {
            \PHPWS_Error::log($result);
            $this->content = 'An error occurred retrieving your applicant';
            return false;
        }
        if (!$this->applicant->user_id) {
            $this->applicant->user_id = \Current_User::getId();
        }
        return true;
    }

}

?>
