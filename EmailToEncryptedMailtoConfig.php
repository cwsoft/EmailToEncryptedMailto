<?php

namespace ProcessWire;

/**
 * Class EmailToEncryptedMailtoConfig.
 * Module configuration page.
 */
class EmailToEncryptedMailtoConfig extends ModuleConfig {
    /**
     * Default module config values.
     * @return Array with default settings.
     */
    public function getDefaults() {
        return array(
            'pageIdsToSkip' => '',
        );
    }

    /**
     * Function to create the module configuration page in backend.
     * @return $inputFields
     */
    public function getInputfields() {
        $inputFields = parent::getInputfields();

        $f = $this->modules->get('InputfieldText');
        $f->attr('name', 'pageIdsToSkip');
        $f->label = $this->_('Pages to skip from email encryption');
        $f->placeholder = $this->_('Add pageIDs to skip separated by comma. Enter -1 to skip all pages.');
        $inputFields->add($f);

        return $inputFields;
    }
}
