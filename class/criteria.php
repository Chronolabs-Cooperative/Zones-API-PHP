<?php
/**
 * Email Account Propogation REST Services API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         emails-api
 * @since           1.1.11
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         1.1.11
 * @description		A REST API for the creation and management of emails/forwarders and domain name parks for email
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/Emails-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 * 
 */

defined('API_ROOT_PATH') || exit('Restricted access');

/**
 * A criteria (grammar?) for a database query.
 *
 * Abstract base class should never be instantiated directly.
 *
 * @abstract
 */
class CriteriaElement
{
    /**
     * Sort order
     *
     * @var string
     */
    public $order = 'ASC';

    /**
     *
     * @var string
     */
    public $sort = '';

    /**
     * Number of records to retrieve
     *
     * @var int
     */
    public $limit = 0;

    /**
     * Offset of first record
     *
     * @var int
     */
    public $start = 0;

    /**
     *
     * @var string
     */
    public $groupby = '';

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Render the criteria element
     * @return string
     */
    public function render()
    {
    }

    /**
     *
     * @param string $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     *
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     *
     * @param string $order
     */
    public function setOrder($order)
    {
        if ('DESC' === strtoupper($order)) {
            $this->order = 'DESC';
        }
    }

    /**
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param int $limit
     */
    public function setLimit($limit = 0)
    {
        $this->limit = (int)$limit;
    }

    /**
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     *
     * @param int $start
     */
    public function setStart($start = 0)
    {
        $this->start = (int)$start;
    }

    /**
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     *
     * @param string $group
     */
    public function setGroupBy($group)
    {
        $this->groupby = $group;
    }

    /**
     *
     * @return string
     */
    public function getGroupby()
    {
        return $this->groupby ? " GROUP BY {$this->groupby}" : '';
    }
    /**
     * *#@-
     */
}

/**
 * Collection of multiple {@link CriteriaElement}s
 *
 */
class CriteriaCompo extends CriteriaElement
{
    /**
     * The elements of the collection
     *
     * @var array Array of {@link CriteriaElement} objects
     */
    public $criteriaElements = array();

    /**
     * Conditions
     *
     * @var array
     */
    public $conditions = array();

    /**
     * Constructor
     *
     * @param CriteriaElement|null $ele
     * @param string $condition
     */
    public function __construct(CriteriaElement $ele = null, $condition = 'AND')
    {
        if (isset($ele)) {
            $this->add($ele, $condition);
        }
    }

    /**
     * Add an element
     *
     * @param CriteriaElement|object $criteriaElement
     * @param string                 $condition
     * @return object reference to this collection
     */
    public function &add(CriteriaElement $criteriaElement, $condition = 'AND')
    {
        if (is_object($criteriaElement)) {
            $this->criteriaElements[] =& $criteriaElement;
            $this->conditions[]       = $condition;
        }

        return $this;
    }

    /**
     * Make the criteria into a query string
     *
     * @return string
     */
    public function render()
    {
        $ret   = '';
        $count = count($this->criteriaElements);
        if ($count > 0) {
            $render_string = $this->criteriaElements[0]->render();
            for ($i = 1; $i < $count; ++$i) {
                if (!$render = $this->criteriaElements[$i]->render()) {
                    continue;
                }
                $render_string .= (empty($render_string) ? '' : ' ' . $this->conditions[$i] . ' ') . $render;
            }
            $ret = empty($render_string) ? '' : "({$render_string})";
        }

        return $ret;
    }

    /**
     * Make the criteria into a SQL "WHERE" clause
     *
     * @return string
     */
    public function renderWhere()
    {
        $ret = $this->render();
        $ret = ($ret != '') ? 'WHERE ' . $ret : $ret;

        return $ret;
    }

    /**
     * Generate an LDAP filter from criteria
     *
     * @return string
     * @author Nathan Dial ndial@trillion21.com
     */
    public function renderLdap()
    {
        $retval = '';
        $count  = count($this->criteriaElements);
        if ($count > 0) {
            $retval = $this->criteriaElements[0]->renderLdap();
            for ($i = 1; $i < $count; ++$i) {
                $cond   = strtoupper($this->conditions[$i]);
                $op     = ($cond === 'OR') ? '|' : '&';
                $retval = "({$op}{$retval}" . $this->criteriaElements[$i]->renderLdap() . ')';
            }
        }

        return $retval;
    }
}

/**
 * A single criteria
 *
 */
class Criteria extends CriteriaElement
{
    /**
     *
     * @var string
     */
    public $prefix;
    public $function;
    public $column;
    public $operator;
    public $value;

    /**
     * Constructor
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     * @param string $prefix
     * @param string $function
     */
    public function __construct($column, $value = '', $operator = '=', $prefix = '', $function = '')
    {
        $this->prefix   = $prefix;
        $this->function = $function;
        $this->column   = $column;
        $this->value    = $value;
        $this->operator = $operator;
    }

    /**
     * Make a sql condition string
     *
     * @return string
     */
    public function render()
    {
        $clause = (!empty($this->prefix) ? "{$this->prefix}." : '') . $this->column;
        if (!empty($this->function)) {
            $clause = sprintf($this->function, $clause);
        }
        if (in_array(strtoupper($this->operator), array('IS NULL', 'IS NOT NULL'))) {
            $clause .= ' ' . $this->operator;
        } else {
            if ('' === ($value = trim($this->value))) {
                return '';
            }
            if (!in_array(strtoupper($this->operator), array('IN', 'NOT IN'))) {
                if ((substr($value, 0, 1) !== '`') && (substr($value, -1) !== '`')) {
                    $value = "'{$value}'";
                } elseif (!preg_match('/^[a-zA-Z0-9_\.\-`]*$/', $value)) {
                    $value = '``';
                }
            }
            $clause .= " {$this->operator} {$value}";
        }

        return $clause;
    }

    /**
     * Generate an LDAP filter from criteria
     *
     * @return string
     * @author Nathan Dial ndial@trillion21.com, improved by Pierre-Eric MENUET pemen@sourceforge.net
     */
    public function renderLdap()
    {
        if ($this->operator === '>') {
            $this->operator = '>=';
        }
        if ($this->operator === '<') {
            $this->operator = '<=';
        }

        if ($this->operator === '!=' || $this->operator === '<>') {
            $operator = '=';
            $clause   = '(!(' . $this->column . $operator . $this->value . '))';
        } else {
            if ($this->operator === 'IN') {
                $newvalue = str_replace(array('(', ')'), '', $this->value);
                $tab      = explode(',', $newvalue);
                foreach ($tab as $uid) {
                    $clause .= "({$this->column}={$uid})";
                }
                $clause = '(|' . $clause . ')';
            } else {
                $clause = '(' . $this->column . $this->operator . $this->value . ')';
            }
        }

        return $clause;
    }

    /**
     * Make a SQL "WHERE" clause
     *
     * @return string
     */
    public function renderWhere()
    {
        $cond = $this->render();

        return empty($cond) ? '' : "WHERE {$cond}";
    }
}
