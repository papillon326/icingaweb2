<?php
/* Icinga Web 2 | (c) 2014 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Monitoring\Forms\Config;

use Icinga\Web\Notification;
use Icinga\Forms\ConfigForm;

/**
 * Form for modifying security relevant settings
 */
class SecurityConfigForm extends ConfigForm
{
    /**
     * Initialize this form
     */
    public function init()
    {
        $this->setName('form_config_monitoring_security');
        $this->setSubmitLabel($this->translate('Save Changes'));
    }

    /**
     * @see Form::onSuccess()
     */
    public function onSuccess()
    {
        $this->config->setSection('security', $this->getValues());

        if ($this->save()) {
            Notification::success($this->translate('New security configuration has successfully been stored'));
        } else {
            return false;
        }
    }

    /**
     * @see Form::onRequest()
     */
    public function onRequest()
    {
        $this->populate($this->config->getSection('security')->toArray());
    }

    /**
     * @see Form::createElements()
     */
    public function createElements(array $formData)
    {
        $this->addElement(
            'text',
            'protected_customvars',
            array(
                'allowEmpty'    => true,
                'attribs'       => array('placeholder' => $this->getDefaultProtectedCustomvars()),
                'label'         => $this->translate('Protected Custom Variables'),
                'description'   => $this->translate(
                    'Comma separated case insensitive list of protected custom variables.'
                    . ' Use * as a placeholder for zero or more wildcard characters.'
                    . ' Existence of those custom variables will be shown, but their values will be masked.'
                )
            )
        );

        $this->addElement(
            'text',
            'hidden_customvars',
            array(
                'allowEmpty'    => true,
                'attribs'       => array('placeholder' => $this->getDefaultProtectedCustomvars()),
                'label'         => $this->translate('Hidden Custom Variables'),
                'description'   => $this->translate(
                    'Comma separated case insensitive list of hidden custom variables.'
                    . ' Use * as a placeholder for zero or more wildcard characters.'
                    . ' Existence of those custom variables will not be shown, but remain usable for modules.'
                )
            )
        );
    }

    /**
     * Return the customvars to suggest to protect when none are protected
     *
     * @return string
     */
    public function getDefaultProtectedCustomvars()
    {
        return '*pw*,*pass*,community';
    }
}
