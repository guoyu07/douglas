<?php

namespace douglas;

/*
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */

class Applicant {

    public $id;
    public $user_id;
    public $last_name;
    public $first_name;
    public $created_date;
    public $asu_box;
    public $banner_id;
    public $asu_username;
    public $phone;
    public $class_year;
    public $gpa;
    public $major;
    public $current_jobs;

    /**
     * Name of primary supervisor. Note: the wording on this was changed from
     * "professional" to "primary."
     * @var string
     */
    public $professional_super;
    public $professional_super_email;
    public $peer_super;
    public $employment_length;
    public $contributions;
    public $organization1;
    public $organization2;
    public $organization3;
    public $office1;
    public $office2;
    public $office3;
    public $accomplish1;
    public $accomplish2;
    public $accomplish3;
    public $award1;
    public $award2;
    public $award3;
    public $received1;
    public $received2;
    public $received3;
    public $description1;
    public $description2;
    public $description3;
    public $reference_email1;
    public $reference_email2;
    public $reference_email3;
    public $reference1;
    public $reference2;
    public $reference3;
    public $resume;
    public $essay;
    public $stage = 1;
    public $errors;
    public $completed = 0;
    public $admin_comments = '';

    public function __construct($id = 0)
    {
        $this->id = (int) $id;
        if ($this->id) {
            $this->init();
        }

        if (!$this->created_date) {
            $this->created_date = time();
        }
    }

    public function init()
    {
        $db = new \PHPWS_DB('douglas_applicants');
        return $db->loadObject($this);
    }

    public function basicInfo()
    {
        \Layout::addStyle('douglas');
        $form = new \PHPWS_Form('basic_application');
        $form->addHidden('module', 'douglas');
        if ($this->id) {
            $form->addHidden('id', $this->id);
        }
        $form->addHidden('uop', 'post_basic_info');
        $form->addHidden('stage', '1');

        $form->addText('first_name', $this->first_name);
        $form->setLabel('first_name', 'First name');
        $form->setRequired('first_name');

        $form->addText('last_name', $this->last_name);
        $form->setLabel('last_name', 'Last name');
        $form->setRequired('last_name');

        $form->addText('asu_box', $this->asu_box);
        $form->setLabel('asu_box', 'ASU box number');
        $form->setSize('asu_box', 10);
        $form->setRequired('asu_box');

        $form->addText('asu_username', $this->asu_username);
        $form->setLabel('asu_username', 'ASU username');
        $form->setSize('asu_username', 15);
        $form->setRequired('asu_username');

        $form->addText('banner_id', $this->banner_id);
        $form->setLabel('banner_id', 'Banner ID');
        $form->setRequired('banner_id');

        $form->addText('major', $this->major);
        $form->setLabel('major', 'Major');
        $form->setRequired('major');

        $form->addText('gpa', $this->gpa);
        $form->setLabel('gpa', 'GPA');
        $form->setSize('gpa', '5');
        $form->setRequired('gpa');

        $form->addText('phone', $this->getPhone());
        $form->setLabel('phone', 'Phone number');
        $form->setSize('phone', '15');
        $form->setRequired('phone');

        /**
         * @var $classes array Pulled from inc/classes.php
         */
        $classes = null;
        include PHPWS_SOURCE_DIR . 'mod/douglas/inc/classes.php';
        $form->addSelect('class_year', $classes);
        $form->setLabel('class_year', 'Current year');

        $form->addTextArea('contributions', $this->contributions);
        $form->setLabel('contributions',
                'Notable contributions to University Recreation');
        $form->setCols('contributions', 70);

        $form->addText('current_jobs', $this->current_jobs);
        $form->setLabel('current_jobs', 'Current UREC Job(s)');
        $form->setSize('current_jobs', 90);
        $form->setRequired('current_jobs');

        $form->addText('employment_length', $this->employment_length);
        $form->setLabel('employment_length', 'Length of Employment');
        $form->setRequired('employment_length');

        $form->addText('peer_super', $this->peer_super);
        $form->setLabel('peer_super', 'Peer Supervisor name(if applicable)');
        $form->setSize('peer_super', 30);

        $form->addText('professional_super', $this->professional_super);
        $form->setLabel('professional_super', 'Primary supervisor name');
        $form->setRequired('professional_super');
        $form->setSize('professional_super', 30);

        $form->addText('professional_super_email',
                $this->professional_super_email);
        $form->setLabel('professional_super_email', 'Primary supervisor email');
        $form->setRequired('professional_super_email');
        $form->setSize('professional_super_email', 20);

        $form->addSubmit('Continue...');
        $tpl = $form->getTemplate();
        if (!empty($this->errors)) {
            $tpl['ERROR'] = implode('<br />', $this->errors);
        }
        return \PHPWS_Template::process($tpl, 'douglas', 'basic.tpl');
    }

    public function extraActivities()
    {
        $form = new \PHPWS_Form('extra-activites');
        $form->addHidden('module', 'douglas');
        $form->addHidden('uop', 'post_activities');
        $form->addHidden('id', $this->id);
        $form->addHidden('stage', '2');
        $form->addText('organization1', $this->organization1);
        $form->setSize('organization1', 20);
        $form->setLabel('organization1', 'Organization');
        $form->setRequired('organization1');

        $form->addText('organization2', $this->organization2);
        $form->setSize('organization2', 20);
        $form->addText('organization3', $this->organization3);
        $form->setSize('organization3', 20);

        $form->addTextArea('office1', $this->office1);
        $form->setCols('office1', 30);
        $form->setRows('office1', 8);
        $form->setLabel('office1', 'Offices held - year held');
        $form->setRequired('office1');

        $form->addTextArea('office2', $this->office2);
        $form->setCols('office2', 30);
        $form->setRows('office2', 8);
        $form->addTextArea('office3', $this->office3);
        $form->setCols('office3', 30);
        $form->setRows('office3', 8);

        $form->addTextArea('accomplish1', $this->accomplish1);
        $form->setCols('accomplish1', 30);
        $form->setRows('accomplish1', 8);
        $form->setLabel('accomplish1', 'Accomplishments');
        $form->setRequired('accomplish1');

        $form->addTextArea('accomplish2', $this->accomplish2);
        $form->setCols('accomplish2', 30);
        $form->setRows('accomplish2', 8);
        $form->addTextArea('accomplish3', $this->accomplish3);
        $form->setCols('accomplish3', 30);
        $form->setRows('accomplish3', 8);

        $form->addText('award1', $this->award1);
        $form->setSize('award1', 20);
        $form->addText('award2', $this->award2);
        $form->setSize('award2', 20);
        $form->addText('award3', $this->award3);
        $form->setSize('award3', 20);

        $form->addText('received1', $this->received1);
        $form->addText('received2', $this->received2);
        $form->addText('received3', $this->received3);

        $form->addTextArea('description1', $this->description1);
        $form->setCols('description1', 33);
        $form->setRows('description1', 6);
        $form->addTextArea('description2', $this->description2);
        $form->setCols('description2', 33);
        $form->setRows('description2', 6);
        $form->addTextArea('description3', $this->description3);
        $form->setCols('description3', 33);
        $form->setRows('description3', 6);

        $form->addSubmit('Continue...');
        $tpl = $form->getTemplate();

        if (!empty($this->errors)) {
            foreach ($this->errors as $k => $v) {
                $tpl[$k] = $v;
            }
        }

        return \PHPWS_Template::process($tpl, 'douglas', 'extra.tpl');
    }

    public function references()
    {
        $form = new \PHPWS_Form('references');
        $form->addHidden('module', 'douglas');
        $form->addHidden('id', $this->id);
        $form->addHidden('uop', 'post_references');
        $form->addHidden('stage', '3');
        $form->addText('reference_email1', $this->reference_email1);
        $form->setLabel('reference_email1', 'Reference email 1');
        $form->setSize('reference_email1', '30');
        $form->setRequired('reference_email1');

        $form->addText('reference_email2', $this->reference_email2);
        $form->setLabel('reference_email2', 'Reference email 2');
        $form->setSize('reference_email2', '30');

        $form->addText('reference_email3', $this->reference_email3);
        $form->setLabel('reference_email3', 'Reference email 3');
        $form->setSize('reference_email3', '30');

        $form->addSubmit('Continue...');
        $tpl = $form->getTemplate();

        if (!empty($this->errors)) {
            $tpl['ERROR'] = implode('<br />', $this->errors);
        }

        return \PHPWS_Template::process($tpl, 'douglas', 'references.tpl');
    }

    public function documents()
    {
        $form = new \PHPWS_Form('references');
        $form->addHidden('module', 'douglas');
        $form->addHidden('uop', 'post_documents');
        $form->addHidden('id', $this->id);
        $form->addHidden('stage', '4');
        $form->addFile('essay');
        $form->addFile('resume');
        $form->addSubmit('Finish');

        if (!empty($this->essay)) {
            $form->addTplTag('ESSAY_LABEL',
                    sprintf('<a href="%s" target="_blank">Your current essay is here.</a> If you upload another, it will replace this one.',
                            $this->essay));
        } else {
            $form->setLabel('essay',
                    'Please upload an essay containing your reasons for applying');
        }

        if (!empty($this->resume)) {
            $form->addTplTag('RESUME_LABEL',
                    sprintf('<a href="%s" target="_blank">Your current resume is here.</a> If you upload another, it will replace this one.',
                            $this->resume));
        } else {
            $form->setLabel('resume', 'Please upload a resume');
        }
        $tpl = $form->getTemplate();

        if (!empty($this->errors)) {
            foreach ($this->errors as $tag => $message) {
                $tpl[$tag . '_error'] = $message;
            }
        }
        return \PHPWS_Template::process($tpl, 'douglas', 'documents.tpl');
    }

    public function setPhone($phone)
    {
        $this->phone = preg_replace('/\D/', '', trim($phone));
        $phone_len = strlen($this->phone);
        if ($phone_len != 7 && $phone_len != 10) {
            $this->errors['phone'] = 'Phone number must be seven or ten numbers in length';
        }
    }

    public function getPhone()
    {
        if (strlen($this->phone) > 7) {
            return sprintf('%s-%s-%s', substr($this->phone, 0, 3),
                    substr($this->phone, 3, 3), substr($this->phone, 6));
        } elseif (!empty($this->phone)) {
            return sprintf('%s-%s', substr($this->phone, 0, 3),
                    substr($this->phone, 3));
        } else {
            return null;
        }
    }

    public function postBasic()
    {
        $this->setFirstName($_POST['first_name']);
        $this->setLastName($_POST['last_name']);
        $this->setASUBox($_POST['asu_box']);
        $this->setBannerId($_POST['banner_id']);
        $this->setASUUsername($_POST['asu_username']);
        $this->setPhone($_POST['phone']);
        $this->setClassYear($_POST['class_year']);
        $this->setMajor($_POST['major']);
        $this->setGPA($_POST['gpa']);
        $this->setCurrentJobs($_POST['current_jobs']);
        $this->setEmploymentLength($_POST['employment_length']);
        $this->setProfessionalSuper($_POST['professional_super']);
        $this->setProfessionalSuperEmail($_POST['professional_super_email']);
        $this->setPeerSuper($_POST['peer_super']);
        $this->setContributions($_POST['contributions']);
    }

    public function postDocuments()
    {
        if (!empty($_FILES['essay']['tmp_name'])) {
            $essay = & $_FILES['essay'];
            if (!\PHPWS_File::checkMimeType($essay['tmp_name'], 'pdf')) {
                $this->errors['pdf'] = 'Uploaded files must be in PDF format.';
            } else {
                $destination_directory = 'files/douglas/';
                $file_destination = time() . '_' . $this->user_id . '_essay.pdf';
                if (\PHPWS_File::fileCopy($essay['tmp_name'],
                                $destination_directory, $file_destination, true,
                                false)) {
                    if (isset($this->essay)) {
                        @unlink($this->essay);
                    }
                    $this->essay = $destination_directory . $file_destination;
                    $this->save();
                }
            }
        } elseif (empty($this->essay)) {
            $this->errors['essay'] = 'Please upload a PDF copy of your application essay.';
        }

        if (!empty($_FILES['resume']['tmp_name'])) {
            $resume = & $_FILES['resume'];
            if (!\PHPWS_File::checkMimeType($resume['tmp_name'], 'pdf')) {
                $this->errors['pdf'] = 'Uploaded files must be in PDF format.';
            } else {
                $destination_directory = 'files/douglas/';
                $file_destination = time() . '_' . $this->user_id . '_resume.pdf';
                if (\PHPWS_File::fileCopy($resume['tmp_name'],
                                $destination_directory, $file_destination, true,
                                false)) {
                    if (isset($this->resume)) {
                        @unlink($this->resume);
                    }
                    $this->resume = $destination_directory . $file_destination;
                    $this->save();
                }
            }
        } elseif (empty($this->resume)) {
            $this->errors['resume'] = 'Please upload a PDF copy of your resume.';
        }
    }

    public function postActivities()
    {
        $this->setOrganization(1, $_POST['organization1']);
        $this->setOffice(1, $_POST['office1']);
        $this->setAccomplish(1, $_POST['accomplish1']);
        if ((!empty($this->organization1) || !empty($this->office1) || !empty($this->accomplish1)) &&
                (empty($this->organization1) || empty($this->office1) || empty($this->accomplish1))) {
            $this->errors['activities'] = 'Organization, offices held, and accomplishments must be completely filled out or left blank';
        }

        $this->setOrganization(2, $_POST['organization2']);
        $this->setOffice(2, $_POST['office2']);
        $this->setAccomplish(2, $_POST['accomplish2']);
        if ((!empty($this->organization2) || !empty($this->office2) || !empty($this->accomplish2)) &&
                (empty($this->organization2) || empty($this->office2) || empty($this->accomplish2))) {
            $this->errors['activities'] = 'Organization, offices held, and accomplishments must be completely filled out or left blank';
        }

        $this->setOrganization(3, $_POST['organization3']);
        $this->setOffice(3, $_POST['office3']);
        $this->setAccomplish(3, $_POST['accomplish3']);
        if ((!empty($this->organization3) || !empty($this->office3) || !empty($this->accomplish3)) &&
                (empty($this->organization3) || empty($this->office3) || empty($this->accomplish3))) {
            $this->errors['activities'] = 'Organization, offices held, and accomplishments must be completely filled out or left blank';
        }

        $this->setAward(1, $_POST['award1']);
        $this->setReceived(1, $_POST['received1']);
        $this->setDescription(1, $_POST['description1']);

        if ((!empty($this->award1) || !empty($this->received1) || !empty($this->description1)) &&
                (empty($this->award1) || empty($this->received1) || empty($this->description1))) {
            $this->errors['award'] = 'Award, received date, and description must be completely filled out or left blank';
        }

        $this->setAward(2, $_POST['award2']);
        $this->setReceived(2, $_POST['received2']);
        $this->setDescription(2, $_POST['description2']);
        if ((!empty($this->award2) || !empty($this->received2) || !empty($this->description2)) &&
                (empty($this->award2) || empty($this->received2) || empty($this->description2))) {
            $this->errors['award'] = 'Award, received date, and description must be completely filled out or left blank';
        }

        $this->setAward(3, $_POST['award3']);
        $this->setReceived(3, $_POST['received3']);
        $this->setDescription(3, $_POST['description3']);
        if ((!empty($this->award3) || !empty($this->received3) || !empty($this->description3)) &&
                (empty($this->award3) || empty($this->received3) || empty($this->description3))) {
            $this->errors['award'] = 'Award, received date, and description must be completely filled out or left blank';
        }
    }

    public function postReferences()
    {
        if (empty($_POST['reference_email1'])) {
            $this->errors['email'] = 'You must enter at least one reference';
        }

        // If completed, we store the current reference email in case they change it
        if ($this->completed) {
            $oldref1 = $this->reference_email1;
            $oldref2 = $this->reference_email2;
            $oldref3 = $this->reference_email3;
        }

        $this->setReferenceEmail(1, $_POST['reference_email1']);
        $this->setReferenceEmail(2, $_POST['reference_email2']);
        $this->setReferenceEmail(3, $_POST['reference_email3']);

        if (!empty($this->reference_email1) &&
                ($this->reference_email2 == $this->reference_email1 ||
                $this->reference_email3 == $this->reference_email1)) {
            $this->errors['email'] = 'Reference email 1 is a duplicate.';
        }
        if (!empty($this->reference_email2) &&
                ($this->reference_email2 == $this->reference_email3 ||
                $this->reference_email2 == $this->reference_email1)) {
            $this->errors['email'] = 'Reference email 2 is a duplicate.';
        }
        if (!empty($this->reference_email3) &&
                ($this->reference_email3 == $this->reference_email1 ||
                $this->reference_email3 == $this->reference_email2)) {

            $this->errors['email'] = 'Reference email 3 is a duplicate.';
        }

        /**
         * We check if they changed a reference. If so, we session it so they
         * can email them.
         */
        if ($this->completed) {
            if ($oldref1 != $this->reference_email1) {
                $_SESSION['douglas_email'] = $this->reference_email1;
            } else {
                if (isset($_SESSION['douglas_email'][1])) {
                    unset($_SESSION['douglas_email'][1]);
                }
            }

            if ($oldref2 != $this->reference_email2) {
                $_SESSION['douglas_email'][2] = $this->reference_email2;
            } else {
                if (isset($_SESSION['douglas_email'][2])) {
                    unset($_SESSION['douglas_email'][2]);
                }
            }

            if ($oldref3 != $this->reference_email3) {
                $_SESSION['douglas_email'][3] = $this->reference_email3;
            } else {
                if (isset($_SESSION['douglas_email'][3])) {
                    unset($_SESSION['douglas_email'][3]);
                }
            }
        }
    }

    public function setReferenceEmail($num, $reference_email)
    {
        $val = 'reference_email' . $num;
        if (empty($reference_email)) {
            $this->$val = null;
            return;
        }

        $this->$val = trim(strip_tags($reference_email));
        if (!\PHPWS_Text::isValidInput($this->$val, 'email')) {
            $this->errors['email'] = 'One or more email addresses are not formatted correctly.';
        }
    }

    public function setOrganization($num, $organization)
    {
        $val = 'organization' . $num;
        $this->$val = trim(strip_tags($organization));
    }

    public function setOffice($num, $office)
    {
        $val = 'office' . $num;
        $this->$val = trim(strip_tags($office));
    }

    public function setAccomplish($num, $accomplish)
    {
        $val = 'accomplish' . $num;
        $this->$val = trim(strip_tags($accomplish));
    }

    public function setAdminComments($comments)
    {
        $this->admin_comments = strip_tags(trim($comments));
    }

    public function setAward($num, $award)
    {
        $val = 'award' . $num;
        $this->$val = trim(strip_tags($award));
    }

    public function setReceived($num, $received)
    {
        $val = 'received' . $num;
        $this->$val = trim(strip_tags($received));
    }

    public function setDescription($num, $description)
    {
        $val = 'description' . $num;
        $this->$val = trim(strip_tags($description));
    }

    public function setFirstName($name)
    {
        $this->first_name = strip_tags(trim($name));
        if (empty($this->first_name)) {
            $this->errors['first_name'] = 'First name may not be empty.';
        }
    }

    public function setLastName($name)
    {
        $this->last_name = strip_tags(trim($name));
        if (empty($this->last_name)) {
            $this->errors['last_name'] = 'Last name may not be empty.';
        }
    }

    public function setASUBox($box)
    {
        $this->asu_box = strip_tags(trim($box));
        if (empty($this->asu_box)) {
            $this->errors['asu_box'] = 'ASU Box may not be empty.';
        } elseif (!is_numeric($this->asu_box)) {
            $this->errors['asu_box'] = 'ASU Box not formatted correctly.';
        }
    }

    public function setBannerId($id)
    {
        $this->banner_id = strip_tags(trim($id));
        if (empty($this->banner_id)) {
            $this->errors['banner_id'] = 'Banner ID may not be empty.';
        } elseif (!is_numeric($this->banner_id)) {
            $this->errors['banner_id'] = 'Banner ID not formatted correctly.';
        }
    }

    public function setASUUsername($username)
    {
        $this->asu_username = strip_tags(trim($username));
        if (empty($this->asu_username)) {
            $this->errors['asu_username'] = 'ASU user name may not be empty.';
        } elseif (preg_match('/\W/', $this->asu_username)) {
            $this->errors['asu_username'] = 'ASU user name not formatted correctly.';
        }
    }

    public function setClassYear($year)
    {
        $this->class_year = strip_tags(trim($year));
    }

    public function setMajor($major)
    {
        $this->major = strip_tags(trim($major));
        if (empty($this->major)) {
            $this->errors['major'] = 'Major may not be empty.';
        }
    }

    public function setGPA($gpa)
    {
        $this->gpa = strip_tags(trim($gpa));
        if (empty($this->gpa)) {
            $this->errors['gpa'] = 'GPA may not be empty.';
        }
    }

    public function setCurrentJobs($jobs)
    {
        $this->current_jobs = strip_tags(trim($jobs));
        if (empty($this->current_jobs)) {
            $this->errors['current_jobs'] = 'Current jobs may not be empty.';
        }
    }

    public function setEmploymentLength($length)
    {
        $this->employment_length = strip_tags(trim($length));
        if (empty($this->employment_length)) {
            $this->errors['employment_length'] = 'Employment length may not be empty.';
        }
    }

    public function setProfessionalSuper($supervisor)
    {
        $this->professional_super = strip_tags(trim($supervisor));
        if (empty($this->professional_super)) {
            $this->errors['professional_name'] = 'Primary supervisor name must not be empty.';
        }
    }

    public function setProfessionalSuperEmail($supervisor_email)
    {
        $this->professional_super_email = strip_tags(trim($supervisor_email));
        if (empty($this->professional_super_email)) {
            $this->errors['professional_email'] = 'Primary supervisor email must not be empty.';
        }
    }

    public function setPeerSuper($supervisor)
    {
        $this->peer_super = strip_tags(trim($supervisor));
    }

    public function setContributions($contribution)
    {
        $this->contributions = strip_tags(trim($contribution));
    }

    public function save()
    {
        $db = new \PHPWS_DB('douglas_applicants');
        if (empty($this->user_id)) {
            $this->user_id = \Current_User::getId();
        }
        return $db->saveObject($this);
    }

    public function getResume()
    {
        if ($this->resume) {
            return sprintf('<a href="%s" target="_index">Resume for %s %s</a>',
                    $this->resume, $this->first_name, $this->last_name);
        } else {
            return null;
        }
    }

    public function getEssay()
    {
        if ($this->essay) {
            return sprintf('<a href="%s" target="_index">Essay for %s %s</a>',
                    $this->essay, $this->first_name, $this->last_name);
        } else {
            return null;
        }
    }

    public function getTags()
    {
        $tpl['id'] = $this->id;
        $tpl['user_id'] = $this->user_id;
        $tpl['last_name'] = $this->last_name;
        $tpl['first_name'] = $this->first_name;
        $tpl['created_date'] = strftime('%B %d, %Y', $this->created_date);
        $tpl['asu_box'] = $this->asu_box;
        $tpl['banner_id'] = $this->banner_id;
        $tpl['asu_username'] = $this->asu_username;
        $tpl['email'] = '<a href="mailto:' . $this->asu_username . '@appstate.edu">' . $this->asu_username . '@appstate.edu</a>';
        $tpl['phone'] = $this->getPhone();
        $tpl['class_year'] = $this->class_year;
        $tpl['gpa'] = $this->gpa;
        $tpl['major'] = $this->major;
        $tpl['current_jobs'] = $this->current_jobs;
        $tpl['professional_super'] = $this->professional_super;
        $tpl['professional_super_email'] = $this->professional_super_email;
        $tpl['peer_super'] = $this->peer_super;
        $tpl['employment_length'] = $this->employment_length;
        $tpl['contributions'] = $this->contributions;
        if ($this->organization1) {
            $tpl['organization1'] = $this->organization1;
            $tpl['office1'] = $this->office1;
            $tpl['accomplish1'] = $this->accomplish1;
        } else {
            $tpl['organization1'] = '<i>Left empty</i>';
        }

        if ($this->organization2) {
            $tpl['organization2'] = $this->organization2;
            $tpl['office2'] = $this->office2;
            $tpl['accomplish2'] = $this->accomplish2;
        } else {
            $tpl['organization2'] = '<i>Left empty</i>';
        }

        if ($this->organization3) {
            $tpl['organization3'] = $this->organization3;
            $tpl['office3'] = $this->office3;
            $tpl['accomplish3'] = $this->accomplish3;
        } else {
            $tpl['organization3'] = '<i>Left empty</i>';
        }


        if (!empty($this->award1)) {
            $tpl['award1'] = $this->award1;
            $tpl['received1'] = $this->received1;
            $tpl['description1'] = $this->description1;
        } else {
            $tpl['award1'] = '<i>Left empty</i>';
        }

        if (!empty($this->award2)) {
            $tpl['award2'] = $this->award2;
            $tpl['received2'] = $this->received2;
            $tpl['description2'] = $this->description2;
        } else {
            $tpl['award2'] = '<i>Left empty</i>';
        }

        if (!empty($this->award3)) {
            $tpl['award3'] = $this->award3;
            $tpl['received3'] = $this->received3;
            $tpl['description3'] = $this->description3;
        } else {
            $tpl['award3'] = '<i>Left empty</i>';
        }

        $tpl['reference_email1'] = $this->reference_email1;
        $tpl['reference_email2'] = $this->reference_email2;
        $tpl['reference_email3'] = $this->reference_email3;
        $tpl['reference1'] = $this->reference1;
        $tpl['reference2'] = $this->reference2;
        $tpl['reference3'] = $this->reference3;
        $tpl['resume'] = $this->getResume();
        $tpl['essay'] = $this->getEssay();
        $tpl['stage'] = $this->stage;
        $tpl['complete'] = $this->stage == '5' ? '<span style="color : green">Yes</span>' : '<span style="color : red">No</span>';
        $tpl['errors'] = $this->errors;
        $tpl['print'] = sprintf('<a href="%s" target="_blank">' . \Icon::show('print') . '</a>',
                \PHPWS_Text::linkAddress('douglas',
                        array('aop' => 'print', 'id' => $this->id), true));

        if ($this->stage == '5') {
            $tpl['email_references'] = sprintf('<a href="%s">' . \Icon::show('email') . '</a>',
                    \PHPWS_Text::linkAddress('douglas',
                            array('aop' => 'email_references',
                        'id' => $this->id), true));
        }

        if (\Current_User::isDeity()) {
            $js['question'] = 'Are you sure you want to permanently delete this applicant?';
            $js['link'] = \Icon::show('delete');
            $js['address'] = \PHPWS_Text::linkAddress('douglas',
                            array('aop' => 'delete', 'id' => $this->id), true);
            $tpl['delete'] = javascript('confirm', $js);
        }
        return $tpl;
    }

    public function contactReferences()
    {
        // If this exists we ONLY send to the new reference
        if (!empty($_SESSION['douglas_email'])) {
            if (is_array($_SESSION['douglas_email'])) {
                foreach ($_SESSION['douglas_email'] as $new_reference) {
                    $to[] = $new_reference;
                }
                unset($_SESSION['douglas_email']);
            } else {
                // Bad douglas email session
                unset($_SESSION['douglas_email']);
                return;
            }
        } else {
            if ($this->reference_email1) {
                $to[] = $this->reference_email1;
            }
            if ($this->reference_email2) {
                $to[] = $this->reference_email2;
            }
            if ($this->reference_email3) {
                $to[] = $this->reference_email3;
            }
        }

        $subject = 'Reference request';
        $from = \PHPWS_Settings::get('douglas', 'contact_email');
        $due_date = \PHPWS_Settings::get('douglas', 'due_date');

        $file_name = $this->printPDF(true);


        // email references
        $refmessage = $this->referenceEmailMessage();
        foreach ($to as $refemail) {
            $this->mail_file($refemail, $subject, $refmessage, $from, $file_name);
        }

        // email primary (professional) supervisor
        $profmessage = $this->professionalEmailMessage();
        $this->mail_file($this->professional_super_email, $subject,
                $profmessage, $from, $file_name);

        @unlink($file_name);
    }

    private function referenceEmailMessage()
    {
        $val['from'] = \PHPWS_Settings::get('douglas', 'contact_email');
        $val['due_date'] = \PHPWS_Settings::get('douglas', 'due_date');
        $val['first_name'] = $this->first_name;
        $val['last_name'] = $this->last_name;
        $template = new \Template($val);
        $template->setModuleTemplate('douglas', 'emails/reference_email.html');
        return $template->get();
    }

    private function professionalEmailMessage()
    {
        $val['from'] = \PHPWS_Settings::get('douglas', 'contact_email');
        $val['due_date'] = \PHPWS_Settings::get('douglas', 'due_date');
        $val['first_name'] = $this->first_name;
        $val['last_name'] = $this->last_name;
        $template = new \Template($val);
        $template->setModuleTemplate('douglas', 'emails/professional_email.html');
        return $template->get();
    }

    /**
     * source http://www.barattalo.it/2010/01/10/sending-emails-with-attachment-and-html-with-php/
     * @param type $to
     * @param type $subject
     * @param type $messagehtml
     * @param type $from
     * @param type $fileatt
     * @param type $replyto
     * @return type
     */
    public function mail_file($to, $subject, $messagehtml, $from, $fileatt, $replyto = "")
    {
        // handles mime type for better receiving
        $ext = strrchr($fileatt, '.');
        $ftype = "";
        if ($ext == ".doc")
            $ftype = "application/msword";
        if ($ext == ".jpg")
            $ftype = "image/jpeg";
        if ($ext == ".gif")
            $ftype = "image/gif";
        if ($ext == ".zip")
            $ftype = "application/zip";
        if ($ext == ".pdf")
            $ftype = "application/pdf";
        if ($ftype == "")
            $ftype = "application/octet-stream";

        // read file into $data var
        $file = fopen($fileatt, "rb");
        $data = fread($file, filesize($fileatt));
        fclose($file);

        // split the file into chunks for attaching
        $content = chunk_split(base64_encode($data));
        $uid = md5(uniqid(time()));

        // build the headers for attachment and html
        $h = "From: $from\n";
        if (\PHPWS_Settings::get('douglas', 'cc_on_reference_email')) {
            $h .= "Cc: $from \n";
        }

        if ($replyto)
            $h .= "Reply-To: " . $replyto . "\n";
        $h .= "MIME-Version: 1.0\n";
        $h .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\n\n";
        $h .= "This is a multi-part message in MIME format.\n";
        $h .= "--" . $uid . "\n";
        $h .= "Content-type:text/html; charset=iso-8859-1\n";
        $h .= "Content-Transfer-Encoding: 7bit\n\n";
        $h .= $messagehtml . "\n\n";
        $h .= "--" . $uid . "\n";
        $h .= "Content-Type: " . $ftype . "; name=\"" . basename($fileatt) . "\"\n";
        $h .= "Content-Transfer-Encoding: base64\n";
        $h .= "Content-Disposition: attachment; filename=\"" . basename($fileatt) . "\"\n\n";
        $h .= $content . "\n\n";
        $h .= "--" . $uid . "--";

        // send mail
        return mail($to, $subject, strip_tags($messagehtml), $h);
    }

    public function printPDF($return_file = false)
    {
        $vars = $this->getTags();

        $content = \PHPWS_Template::process($vars, 'douglas', 'print.tpl');
        \PHPWS_Core::initModClass('douglas', 'WKPDF.php');
        $path = \PHPWS_Settings::get('douglas', 'wkpdf');
        if (empty($path) || !is_file($path)) {
            $path = null;
        }
        $pdf = new \WKPDF($path);
        $pdf->set_html($content);
        $pdf->render();
        try {
            if ($this->essay || $this->resume) {
                $output_file = '/tmp/' . time() . '_tmp.pdf';
                $pdf->output(\WKPDF::$PDF_SAVEFILE, $output_file);
                $join_file = '/tmp/' . time() . '_join.pdf';
                if ($this->essay && $this->resume) {
                    $this->joinPDF($join_file, $output_file, $this->essay,
                            $this->resume);
                } elseif ($this->essay) {
                    $this->joinPDF($join_file, $output_file, $this->essay);
                } elseif ($this->resume) {
                    $this->joinPDF($join_file, $output_file, $this->resume);
                }
                if ($return_file) {
                    return $join_file;
                } else {
                    header('Content-Type: application/pdf');
                    header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
                    header('Pragma: public');
                    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                    header('Content-Disposition: inline; filename="' . basename($join_file) . '";');
                    readfile($join_file);
                }
            } else {
                if ($return_file) {
                    $filename = 'files/tempdf' . rand() . '.pdf';
                    if (!$pdf->output(\WKPDF::$PDF_SAVEFILE, $filename)) {
                        exit('could not save');
                    }
                    return $filename;
                } else {
                    $pdf->output(\WKPDF::$PDF_EMBEDDED, '');
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        exit();
    }

    public function joinPDF($save_path)
    {
        $result = \func_get_args();
        unset($result[0]);
        $join_files = implode(' ', $result);

        $join_string = sprintf('gs -q -sPAPERSIZE=letter -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=%s %s',
                $save_path, $join_files);
        system($join_string);
    }

}

?>
