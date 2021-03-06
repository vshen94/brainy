<?php
/**
 * Smarty plugin
 *
 * @package    Brainy
 * @subpackage PluginsFunction
 */

/**
 * Smarty {html_checkboxes} function plugin
 *
 * File:       function.html_checkboxes.php<br>
 * Type:       function<br>
 * Name:       html_checkboxes<br>
 * Date:       24.Feb.2003<br>
 * Purpose:    Prints out a list of checkbox input types<br>
 * Examples:
 * <pre>
 * {html_checkboxes values=$ids output=$names}
 * {html_checkboxes values=$ids name='box' separator='<br>' output=$names}
 * {html_checkboxes values=$ids checked=$checked separator='<br>' output=$names}
 * </pre>
 * Params:
 * <pre>
 * - name       (optional) - string default "checkbox"
 * - values     (required) - array
 * - options    (optional) - associative array
 * - checked    (optional) - array default not set
 * - separator  (optional) - ie <br> or &nbsp;
 * - output     (optional) - the output next to each checkbox
 * - assign     (optional) - assign the output as an array to this variable
 * - escape     (optional) - escape the content (not value), defaults to true
 * </pre>
 *
 * @link    http://www.smarty.net/manual/en/language.function.html.checkboxes.php {html_checkboxes}
 *      (Smarty online manual)
 * @author  Christopher Kvarme <christopher.kvarme@flashjab.com>
 * @author  credits to Monte Ohrt <monte at ohrt dot com>
 * @version 1.0
 * @param   array  $params   parameters
 * @param   object $template template object
 * @return  string
 * @uses    smarty_function_escape_special_chars()
 */
function smarty_function_html_checkboxes($params, $template)
{

    $template->assertIsNotStrict('`{html_checkboxes}` is a deprecated plugin and is not allowed in strict mode');

    include_once BRAINY_PLUGINS_DIR . 'shared.escape_special_chars.php';

    $options = array(
        'name' => 'checkbox',
        'values' => null,
        'options' => null,
        'selected' => array(),
        'separator' => '',
        'escape' => true,
        'labels' => true,
        'label_ids' => false,
        'output' => null,

        'extra' => '',
    );

    foreach ($params as $_key => $_val) {
        switch ($_key) {
            case 'name':
            case 'separator':
                $options[$_key] = (string) $_val;
                break;

            case 'escape':
            case 'labels':
            case 'label_ids':
                $options[$_key] = (bool) $_val;
                break;

            case 'options':
                $options[$_key] = (array) $_val;
                break;

            case 'values':
            case 'output':
                $options[$_key] = array_values((array) $_val);
                break;

            case 'checked':
            case 'selected':
                if (is_array($_val)) {
                    $options['selected'] = array();
                    foreach ($_val as $_sel) {
                        if (is_object($_sel)) {
                            if (method_exists($_sel, "__toString")) {
                                $_sel = smarty_function_escape_special_chars((string) $_sel->__toString());
                            } else {
                                trigger_error("html_checkboxes: selected attribute contains an object of class '". get_class($_sel) ."' without __toString() method", E_USER_NOTICE);
                                continue;
                            }
                        } else {
                            $_sel = smarty_function_escape_special_chars((string) $_sel);
                        }
                        $options['selected'][$_sel] = true;
                    }
                } elseif (is_object($_val)) {
                    if (method_exists($_val, "__toString")) {
                        $options['selected'] = smarty_function_escape_special_chars((string) $_val->__toString());
                    } else {
                        trigger_error("html_checkboxes: selected attribute is an object of class '". get_class($_val) ."' without __toString() method", E_USER_NOTICE);
                    }
                } else {
                    $options['selected'] = smarty_function_escape_special_chars((string) $_val);
                }
                break;

            case 'checkboxes':
                trigger_error('html_checkboxes: the use of the "checkboxes" attribute is deprecated, use "options" instead', E_USER_WARNING);
                $options['options'] = (array) $_val;
                break;

            case 'assign':
                break;

            case 'strict':
                break;

            case 'disabled':
            case 'readonly':
                if (!empty($params['strict'])) {
                    if (!is_scalar($_val)) {
                        trigger_error("html_options: $_key attribute must be a scalar, only boolean true or string '$_key' will actually add the attribute", E_USER_NOTICE);
                    }

                    if ($_val === true || $_val === $_key) {
                        $options['extra'] .= ' ' . $_key . '="' . smarty_function_escape_special_chars($_key) . '"';
                    }

                    break;
                }
                // omit break; to fall through!

            default:
                if (!is_array($_val)) {
                    $options['extra'] .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    trigger_error("html_checkboxes: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($options['options']) && empty($options['values'])) {
        return '';
    } /* raise error here? */

    $_html_result = array();

    if (!empty($options['options'])) {
        foreach ($options['options'] as $_key => $_val) {
            $_html_result[] = smarty_function_html_checkboxes_output($options['name'], $_key, $_val, $options['selected'], $options['extra'], $options['separator'], $options['labels'], $options['label_ids'], $options['escape']);
        }
    } else {
        foreach ($options['values'] as $_i => $_key) {
            $_val = isset($options['output'][$_i]) ? $options['output'][$_i] : '';
            $_html_result[] = smarty_function_html_checkboxes_output($options['name'], $_key, $_val, $options['selected'], $options['extra'], $options['separator'], $options['labels'], $options['label_ids'], $options['escape']);
        }
    }

    if (!empty($params['assign'])) {
        $template->assign($params['assign'], $_html_result);
    } else {
        return implode("\n", $_html_result);
    }

}

function smarty_function_html_checkboxes_output($name, $value, $output, $selected, $extra, $separator, $labels, $label_ids, $escape = true)
{
    $_output = '';

    if (is_object($value)) {
        if (method_exists($value, "__toString")) {
            $value = (string) $value->__toString();
        } else {
            trigger_error("html_options: value is an object of class '". get_class($value) ."' without __toString() method", E_USER_NOTICE);

            return '';
        }
    } else {
        $value = (string) $value;
    }

    if (is_object($output)) {
        if (method_exists($output, "__toString")) {
            $output = (string) $output->__toString();
        } else {
            trigger_error("html_options: output is an object of class '". get_class($output) ."' without __toString() method", E_USER_NOTICE);

            return '';
        }
    } else {
        $output = (string) $output;
    }

    if ($labels) {
        if ($label_ids) {
            $_id = smarty_function_escape_special_chars(preg_replace('![^\w\-\.]!u', '_', $name . '_' . $value));
            $_output .= '<label for="' . $_id . '">';
        } else {
            $_output .= '<label>';
        }
    }

    $name = smarty_function_escape_special_chars($name);
    $value = smarty_function_escape_special_chars($value);
    if ($escape) {
        $output = smarty_function_escape_special_chars($output);
    }

    $_output .= '<input type="checkbox" name="' . $name . '[]" value="' . $value . '"';

    if ($labels && $label_ids) {
        $_output .= ' id="' . $_id . '"';
    }

    if (is_array($selected)) {
        if (isset($selected[$value])) {
            $_output .= ' checked="checked"';
        }
    } elseif ($value === $selected) {
        $_output .= ' checked="checked"';
    }

    $_output .= $extra . ' />' . $output;
    if ($labels) {
        $_output .= '</label>';
    }

    $_output .= $separator;

    return $_output;
}
