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
class CustomPaginatorHelper extends PaginatorHelper {

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

    /**
     * Generates a "previous" link for a set of paged records
     *
     * ### Options:
     *
     * - `tag` The tag wrapping tag you want to use, defaults to 'span'. Set this to false to disable this option
     * - `escape` Whether you want the contents html entity encoded, defaults to true
     * - `model` The model to use, defaults to PaginatorHelper::defaultModel()
     * - `disabledTag` Tag to use instead of A tag when there is no previous page
     *
     * @param string $title Title for the link. Defaults to '<< Previous'.
     * @param array $options Options for pagination link. See #options for list of keys.
     * @param string $disabledTitle Title when the link is disabled.
     * @param array $disabledOptions Options for the disabled pagination link. See #options for list of keys.
     * @return string A "previous" link or $disabledTitle text if the link is disabled.
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::prev
     */
    public function prev($title = '<< Previous', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $_defaults = array(
            'url' => array(), 'step' => 1, 'escape' => true,
            'model' => null, 'tag' => 'span', 'rel' => 'prev'
        );

        $options = array_merge($_defaults, (array) $options);
        $paging = $this->params($options['model']);
        if ($paging['page'] <= 1)
            return '<li class="inactive">' . $title . '</li>';
        return $this->_pagingLink('Prev', $title, $options, $disabledTitle, $disabledOptions);
    }

    /**
     * Generates a "next" link for a set of paged records
     *
     * ### Options:
     *
     * - `tag` The tag wrapping tag you want to use, defaults to 'span'. Set this to false to disable this option
     * - `escape` Whether you want the contents html entity encoded, defaults to true
     * - `model` The model to use, defaults to PaginatorHelper::defaultModel()
     * - `disabledTag` Tag to use instead of A tag when there is no next page
     *
     * @param string $title Title for the link. Defaults to 'Next >>'.
     * @param array $options Options for pagination link. See above for list of keys.
     * @param string $disabledTitle Title when the link is disabled.
     * @param array $disabledOptions Options for the disabled pagination link. See above for list of keys.
     * @return string A "next" link or $disabledTitle text if the link is disabled.
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::next
     */
    public function next($title = 'Next >>', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $_defaults = array(
            'url' => array(), 'step' => 1, 'escape' => true,
            'model' => null, 'tag' => 'span', 'rel' => 'next'
        );
        $options = array_merge($_defaults, (array) $options);
        $paging = $this->params($options['model']);
        if ($paging['page'] >= $paging['pageCount'])
            return '<li class="inactive">' . $title . '</li>';
        return $this->_pagingLink('Next', $title, $options, $disabledTitle, $disabledOptions);
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

    /**
     * Returns a set of numbers for the paged result set
     * uses a modulus to decide how many numbers to show on each side of the current page (default: 8).
     *
     * `$this->Paginator->numbers(array('first' => 2, 'last' => 2));`
     *
     * Using the first and last options you can create links to the beginning and end of the page set.
     *
     * ### Options
     *
     * - `before` Content to be inserted before the numbers
     * - `after` Content to be inserted after the numbers
     * - `model` Model to create numbers for, defaults to PaginatorHelper::defaultModel()
     * - `modulus` how many numbers to include on either side of the current page, defaults to 8.
     * - `separator` Separator content defaults to ' | '
     * - `tag` The tag to wrap links in, defaults to 'span'
     * - `first` Whether you want first links generated, set to an integer to define the number of 'first'
     *    links to generate.
     * - `last` Whether you want last links generated, set to an integer to define the number of 'last'
     *    links to generate.
     * - `ellipsis` Ellipsis content, defaults to '...'
     * - `class` Class for wrapper tag
     * - `currentClass` Class for wrapper tag on current active page, defaults to 'current'
     *
     * @param array $options Options for the numbers, (before, after, model, modulus, separator)
     * @return string numbers string.
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::numbers
     */
    public function numbers($options = array()) {
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
            if ($first && $start > 1) {
                $offset = ($start <= (int) $first) ? $start - 1 : $first;
                if ($offset < $start - 1) {
                    $out .= $this->first($offset, compact('tag', 'separator', 'ellipsis', 'class'));
                } else {
                    $out .= $this->first($offset, compact('tag', 'separator', 'class', 'ellipsis') + array('after' => $separator));
                }
            }

            $out .= $before;
            for ($i = $start; $i < $params['page']; $i++) {
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;
            }

            if ($class) {
                $currentClass .= ' ' . $class;
            }

            $out .= $this->Html->tag($tag, $this->link($params['page'], array('page' => $params['page']), $options), array('class' => $currentClass));
            if ($i != $params['pageCount']) {
                $out .= $separator;
            }
            $start = $params['page'] + 1;
            for ($i = $start; $i < $end; $i++) {
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;
            }

            if ($end != $params['page']) {
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $end), $options), compact('class'));
            }

            $out .= $after;

            if ($last && $end < $params['pageCount']) {
                $offset = ($params['pageCount'] < $end + (int) $last) ? $params['pageCount'] - $end : $last;
                if ($offset <= $last && $params['pageCount'] - $end > $offset) {
                    $out .= $this->last($offset, compact('tag', 'separator', 'ellipsis', 'class'));
                } else {
                    $out .= $this->last($offset, compact('tag', 'separator', 'class', 'ellipsis') + array('before' => $separator));
                }
            }
        } else {
            $out .= $before;

            for ($i = 1; $i <= $params['pageCount']; $i++) {
                if ($i == $params['page']) {
                    if ($class) {
                        $currentClass .= ' ' . $class;
                    }
                    $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), array('class' => $currentClass));
                } else {
                    $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class'));
                }
                if ($i != $params['pageCount']) {
                    $out .= $separator;
                }
            }

            $out .= $after;
        }

        return $out;
    }

}