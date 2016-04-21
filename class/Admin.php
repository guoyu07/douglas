<?php

namespace douglas;

/*
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */

class Admin extends Base {

    public function get()
    {
        if ($_GET['aop']) {
            $command = $_GET['aop'];
        } else {
            $command = 'app_listing';
        }
        switch ($command) {
            case 'app_listing':
                $this->appListing();
                break;

            case 'print':
                $this->loadApplicant();
                $this->printPDF();
                break;

            case 'settings':
                $this->settingsForm();
                break;

            case 'email_references':
                $this->emailReferences();
                break;

            case 'delete':
                if (!\Current_User::isDeity() || !\Current_User::authorized('douglas')) {
                    throw new \MethodNotAllowedException;
                }
                $this->delete();
                \PHPWS_Core::goBack();
                break;

            case 'get_comments':
                $this->getComments($_GET['id']);
                exit();
        }

        $this->display();
    }

   private function getComments($id)
   {
       $id = (int)$id;

       $db = \Database::newDB();
       $t1 = $db->addTable('douglas_applicants');
       $t1->addFieldConditional('id', $id);
       $t1->addField('admin_comments');
       $result = $db->selectOneRow();
       echo $result['admin_comments'];
       exit();
   }

    /**
     * Permanently deletes an application
     */
    private function delete()
    {
        $id = (int) $_GET['id'];
        $db = \Database::newDB();
        $t = $db->addTable('douglas_applicants');
        $t->addFieldConditional('id', $id);
        $row = $db->selectOneRow();
        if (is_file($row['resume'])) {
            unlink($row['resume']);
        }
        if (is_file($row['essay'])) {
            unlink($row['essay']);
        }
        $db->delete();
    }

    private function emailReferences()
    {
        $this->loadApplicant();
        if ($this->applicant->stage != '5') {
            $this->title = 'Error';
            $this->content = 'Applicant has not finished their application.
                <a href="index.php?module=douglas&aop=app_listing">Return to admin menu</a>';
            return;
        }
        $this->applicant->contactReferences();
        \PHPWS_Core::reroute('index.php?module=douglas&aop=app_listing');
    }

    private function settingsForm()
    {
        javascript('datepicker');
        $form = new \PHPWS_Form('settings');
        $form->addHidden('module', 'douglas');
        $form->addHidden('aop', 'post_settings');
        $form->addText('contact_email',
                \PHPWS_Settings::get('douglas', 'contact_email'));
        $form->setLabel('contact_email', 'Contact email');
        $form->addText('due_date', \PHPWS_Settings::get('douglas', 'due_date'));
        $form->setLabel('due_date', 'Due date');
        $form->addCssClass('due_date', 'datepicker');
        $form->addSubmit('Save settings');

        $form->addCheck('cc_on_reference_email', 1);
        $form->setLabel('cc_on_reference_email',
                'Email Contact email address on reference emails?');
        $form->setMatch('cc_on_reference_email',
                \PHPWS_Settings::get('douglas', 'cc_on_reference_email'));

        if (\Current_User::isDeity()) {
            $form->addText('wkpdf', \PHPWS_Settings::get('douglas', 'wkpdf'));
            $form->setSize('wkpdf', 40);
            $form->setLabel('wkpdf', 'Location of wkhtmltopdf binary');
        }

        $tpl = $form->getTemplate();
        $tpl['menu'] = $this->menu('settings');

        $this->content = \PHPWS_Template::process($tpl, 'douglas',
                        'settings.tpl');
    }

    public function post()
    {
        if (!\Current_User::authorized('douglas')) {
            throw new \MethodNotAllowedException;
        }
        $command = $_POST['aop'];

        switch ($command) {
            case 'post_settings':
                $email = $_POST['contact_email'];
                \PHPWS_Settings::set('douglas', 'contact_email', $email);
                \PHPWS_Settings::set('douglas', 'due_date',
                        strip_tags($_POST['due_date']));
                \PHPWS_Settings::set('douglas', 'cc_on_reference_email',
                        (int) isset($_POST['cc_on_reference_email']));

                if (isset($_POST['wkpdf'])) {
                    $wkpdf = $_POST['wkpdf'];
                    if (preg_match('@[^\w\-/]@', $wkpdf)) {
                        throw new \MethodNotAllowedException;
                    }
                    \PHPWS_Settings::set('douglas', 'wkpdf', $wkpdf);
                }

                \PHPWS_Settings::save('douglas');
                \PHPWS_Core::reroute('index.php?module=douglas&aop=settings');
                break;

            case 'post_comments':
                $this->postComments();
                break;
        }
    }

    private function postComments()
    {
        $id = (int)$_POST['id'];
        $comments = $_POST['comments'];
        $applicant = new Applicant($id);
        $applicant->setAdminComments($comments);
        $applicant->save();
        exit();
    }

    private function menu($active)
    {
        $tpl['active1'] = ($active == 'app_listing') ? ' class="active"' : null;
        $tpl['active2'] = ($active == 'settings') ? ' class="active"' : null;
        $tpl['app_listing'] = \PHPWS_Text::secureLink('Listing', 'douglas',
                        array('aop' => 'app_listing'), null, 'Listing');
        $tpl['settings'] = \PHPWS_Text::secureLink('Settings', 'douglas',
                        array('aop' => 'settings'));
        $template = new \Template($tpl);
        $template->setModuleTemplate('douglas', 'admin_menu.html');
        return $template->get();
    }

    private function appListing()
    {
        javascript('jquery');
        $page_tpl['menu'] = $this->menu('app_listing');
        $page_tpl['authkey'] = \Current_User::getAuthKey();

        \PHPWS_Core::initCoreClasS('DBPager.php');

        \PHPWS_Core::initModClass('douglas', 'Applicant.php');
        $pager = new \DBPager('douglas_applicants', 'douglas\Applicant');
        $pager->setModule('douglas');
        $pager->setTemplate('pager.tpl');
        $pager->addRowTags('getTags');
        $pager->addSortHeader('last_name', 'Name');
        $pager->addSortHeader('created_date', 'Submitted date');
        $pager->addPageTags($page_tpl);

        $this->content = $pager->get();
    }

    public function printPDF()
    {
        $this->applicant->printPDF();
    }

}

?>
