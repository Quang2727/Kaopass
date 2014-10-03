<?php
App::uses('FormHelper', 'View/Helper');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class MyFormHelper extends FormHelper {

    /**
     * Override FormHelper default options
     *
     * @param void
     * @return void
     * @author Mai Nhut Tan
     * @since 2013/09/24
     */
    public function create($model = null, $options = array()) {

        $options = array_merge(array(
            'class' => 'form-horizontal margin-none',
            'inputDefaults' => array(
                'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                'div' => array('class' => 'control-group'),
                'label' => array('class' => 'control-label'),
                'between' => '<div class="controls">',
                'required' => false,
                'after' => '</div>',
                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
                'hiddenField' => false
            )),
        $options);

        return parent::create($model, $options);
    }
}
