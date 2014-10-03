<?php

/**
 * Pagination Helper class file.
 *
 * Generates pagination links
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Helper
 * @since         CakePHP(tm) v 1.2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppHelper', 'View/Helper');
App::import('Helper', 'Paginator');

/**
 * Pagination Helper class for easy generation of pagination links.
 *
 * PaginationHelper encloses all methods needed when working with pagination.
 *
 * @package       Cake.View.Helper
 * @property      HtmlHelper $Html
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html
 */
class PaginatorWithArrayHelper extends PaginatorHelper {

    /**
     * Protected method for generating prev/next links
     *
     * @param string $which
     * @param string $title
     * @param array $options
     * @param string $disabledTitle
     * @param array $disabledOptions
     * @return string
     */
    protected function _pagingLink($which, $title = null, $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $check = 'has' . $which;
        $_defaults = array(
            'url' => array(), 'step' => 1, 'escape' => true,
            'model' => null, 'tag' => 'span', 'class' => strtolower($which)
        );
        $options = array_merge($_defaults, (array) $options);

        $paging = $this->params($options['model']);
        if (empty($disabledOptions)) {
            $disabledOptions = $options;
        }

        if (!$this->{$check}($options['model']) && (!empty($disabledTitle) || !empty($disabledOptions))) {
            if (!empty($disabledTitle) && $disabledTitle !== true) {
                $title = $disabledTitle;
            }
            $options = array_merge($_defaults, (array) $disabledOptions);
        } elseif (!$this->{$check}($options['model'])) {
            return null;
        }

        foreach (array_keys($_defaults) as $key) {
            ${$key} = $options[$key];
            unset($options[$key]);
        }

        $url = array_merge(array('page' => $paging['page'] + ($which == 'Prev' ? $step * -1 : $step)), $url);
        if ($this->{$check}($model)) {
            return $this->Html->tag($tag, $this->link($title, $url, array_merge($options, compact('escape', 'model'))), compact('class'));
        } else {
            $options = array_merge($options, compact('escape', 'class'));
            $options['class'] = 'active ';
            return $this->Html->tag($tag, '<a href="#" class="active hide" >' . $title . '</a>', $options);
        }
    }

    public function counter($options = array()) {
        $options = array('tag' => 'li', 'currentClass' => 'active', 'separator' => '');
        if ($options === true) {
            $options = array(
                'before' => ' | ', 'after' => ' | ', 'first' => 'first', 'last' => 'last'
            );
        }
        $defaults = array(
            'tag' => 'span', 'before' => null, 'after' => null, 'model' => $this->defaultModel(), 'class' => null,
            'modulus' => '9', 'separator' => ' | ', 'first' => null, 'last' => null, 'ellipsis' => '...', 'currentClass' => 'current'
        );
        $options += $defaults;

        $params = (array) $this->params($options['model']) + array('page' => 1);
        unset($options['model']);

        if ($params['pageCount'] <= 1) {
            return false;
        }

        extract($options);
        unset($options['tag'], $options['before'], $options['after'], $options['model'], $options['modulus'], $options['separator'], $options['first'], $options['last'], $options['ellipsis'], $options['class'], $options['currentClass']
        );
        $out = '';
        if ($modulus && $params['pageCount'] > $modulus) {
            $half = intval($modulus / 2);
            $end = $params['page'] + $half;

            if ($end > $params['pageCount']) {
                $end = $params['pageCount'];
            }
            $start = $params['page'] - ($modulus - ($end - $params['page']));
            if ($start <= 1) {
                $start = 1;
                $end = $params['page'] + ($modulus - $params['page']) + 1;
            }
            $params['start'] = $start;
            $params['end'] = $end;
        } else {
            $params['start'] = 1;
            $params['end'] = $params['pageCount'];
        }
        return $params;
    }

    public function numbers($options = array()) {
        if ($options === true) {
            $options = array(
                'before' => ' | ', 'after' => ' | ', 'first' => 'first', 'last' => 'last'
            );
        }

        $defaults = array(
            'tag' => 'span', 'before' => null, 'after' => null, 'model' => $this->defaultModel(), 'class' => null,
            'modulus' => '9', 'separator' => ' | ', 'first' => null, 'last' => null, 'ellipsis' => '...', 'currentClass' => ''
        );

        $options += $defaults;

        $params = (array) $this->params->paging + array('page' => 1);
        unset($options['model']);

        if ($params['pageCount'] <= 1) {
            return false;
        }

        extract($options);

        $linkOptions = array();
        if (!empty($class)) {
            $linkOptions['class'] = $class;
        }

        $out = '';

        $controller = $this->request->params['controller'];
        $action = $this->request->params['action'];

        if ($modulus && $params['pageCount'] > $modulus) {

            $half = intval($modulus / 2);
            $end = $params['page'] + $half;

            if ($end > $params['pageCount']) {
                $end = $params['pageCount'];
            }
            $start = $params['page'] - ($modulus - ($end - $params['page']));
            if ($start <= 1) {
                $start = 1;
                $end = $params['page'] + ($modulus - $params['page']) + 1;
            }

            $out .= $before;

            for ($i = $start; $i < $params['page']; $i++) {
                $out .= $this->Html->tag($tag, $this->Html->link($i, array('page' => $i), $linkOptions)) . $separator;
            }

            $out .= $this->Html->tag($tag, $this->Html->link($i, array('page' => $i)), array('class' => $currentClass));

            if ($i != $params['pageCount']) {
                $out .= $separator;
            }
            $start = $params['page'] + 1;
            for ($i = $start; $i < $end; $i++) {
                $out .= $this->Html->tag($tag, $this->Html->link($i, array('page' => $i), $linkOptions), compact('class')) . $separator;
            }

            if ($end != $params['page']) {
                $out .= $this->Html->tag($tag, $this->Html->link($i, array('page' => $end), $linkOptions), compact('class'));
            }

            $out .= $after;
        } else {
            $out .= $before;

            for ($i = 1; $i <= $params['pageCount']; $i++) {
                if ($i == $params['page']) {
                    if ($class) {
                        $currentClass .= ' ' . $class;
                    }
                    $out .= $this->Html->tag($tag, $this->Html->link($i, array('page' => $i)), array('class' => $currentClass));
                } else {
                    $out .= $this->Html->tag($tag, $this->Html->link($i, array('page' => $i), $linkOptions)) . $separator;
                }
                if ($i != $params['pageCount']) {
                    $out .= $separator;
                }
            }

            $out .= $after;
        }

        return $out;
    }

    /**
     * Generates a "next" link for a set of paged records
     *
     * ### Options:
     *
     * - `tag` The tag wrapping tag you want to use, defaults to 'span'. Set this to false to disable this option
     * - `disabledTag` Tag to use instead of A tag when there is no next page
     *
     * @param string $title Title for the link. Defaults to 'Next >>'.
     * @param array $options Options for pagination link. See above for list of keys.
     * @param string $disabledTitle Title when the link is disabled.
     * @param array $disabledOptions Options for the disabled pagination link. See above for list of keys.
     * @return string A "next" link or $disabledTitle text if the link is disabled.
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::next
     */
    public function next($title = '>', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $page = $this->request->params['paging']['page'];
        $pageCount = $this->request->params['paging']['pageCount'];
        if ($page >= $pageCount)
            return $this->_hasNextPrevPage('next', $title = '>', $options, $disabledTitle, $disabledOptions);
        else
            return $this->_hasNextPrevPage('next', $title = '>', $options, $disabledTitle, $disabledOptions);
    }

    /**
     * Generates a sorting link. Sets named parameters for the sort and direction. Handles
     * direction switching automatically.
     *
     * @param string $key The name of the key that the recordset should be sorted.
     * @param string $title Title for the link. If $title is null $key will be used
     * 		for the title and will be generated by inflection.
     * @param array $options Options for sorting link. See above for list of keys.
     * @return string A link sorting default by 'asc'. If the resultset is sorted 'asc' by the specified
     *  key the returned link will sort by 'desc'.
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::sort
     */
    public function prev($title = '<', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $page = $this->request->params['paging']['page'];
        if ($page <= 1)
            return $this->_hasNextPrevPage('prev', $title = '<', $options, $disabledTitle, $disabledOptions);
        else
            return $this->_hasNextPrevPage('prev', $title = '<', $options, $disabledTitle, $disabledOptions);
    }

    /**
     * build a previous or next page link
     *
     * @flg: string 'prev' or 'next'
     */
    private function _hasNextPrevPage($flg, $title = '<<', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $out = '';
        if (!isset($this->request->params['paging'])) {
            return $out;
        }

        $controller = $this->request->params['controller'];
        $action = $this->request->params['action'];
        $nextPage = $this->request->params['paging']['page'];
        $pageCount = $this->request->params['paging']['pageCount'];
        if ($nextPage > $pageCount)
            $nextPage = $pageCount;
        $tag = 'span';
        if (isset($options['tag'])) {
            $tag = $options['tag'];
        }

        $enabledOptions = array();
        if (isset($options['class'])) {
            unset($options['tag']);
            $enabledOptions = $options;
        }

        // get next page or previous page according to the flag
        switch ($flg) {
            case 'prev':
                $showPage = max($nextPage - 1, 1);
                $option = 'prevPage';
                break;
            case 'next':
                $showPage = min($nextPage + 1, $pageCount);
                $option = 'nextPage';
                break;
        }
        $url = Router::url('/', true) . $controller . '/' . $action;
        if (isset($this->request->params['parameter']) && !empty($this->request->params['parameter'])) {
            $url .= '/' . $this->request->params['parameter'];
        }

        if ($this->request->params['paging'][$option]) {
            $out .= $this->Html->tag($tag, $this->Html->link($title, array('page' => $showPage), $enabledOptions), $disabledOptions);
        } else {
            $out .= '<li class="inactive">' . $title . '</li>';
            //$this->Html->tag($tag, $this->Html->link($title, array('page' => $showPage), $enabledOptions), $disabledOptions);
        }
        return $out;
    }

}