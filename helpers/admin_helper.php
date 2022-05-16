<?php
define('UPDATE_URL', 'https://update.uniquecoder.com/');
define('TEMP_FOLDER', FCPATH . 'uploads' . '/');
define('MAIN_TEMP_FOLDER', FCPATH . 'temp' . '/');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function btn_edit($uri)
{
    return anchor($uri, '<i class="fa fa-pencil-square-o"></i>', array('class' => "btn btn-outline-success btn-sm", 'title' => 'Edit', 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top'));
}

function btn_update()
{
    return "<button data-bs-toggle='tooltip' title=" . lang('update') . " data-bs-placement='top' type='submit'  class='btn btn-outline-success btn-sm'><i class='fa fa-check'></i></button>";
}

function btn_cancel($uri)
{
    return anchor($uri, '<i class="fa fa-times"></i>', array('class' => "btn btn-outline-danger btn-sm", 'title' => lang('cancel'), 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top'));
}

function btn_edit_disable($uri)
{
    return anchor($uri, '<span class="fa fa-pencil-square-o"></span>', array('class' => "btn btn-outline-success btn-sm disabled", 'title' => 'Edit', 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top'));
}

function btn_edit_modal($uri)
{
    return anchor($uri, '<i class="bx bxs-edit-alt"></i>', array('class' => "btn btn-outline-success btn-sm edit modal-link", 'title' => 'Edit', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#myModal'));
}


function btn_banned_modal($uri)
{
    return anchor($uri, '<span class="fa fa-close"></span>', array('class' => "btn btn-outline-danger btn-sm", 'title' => 'Edit', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#myModal'));
}

function btn_delete($uri)
{
    return anchor($uri, '<i class="bx bxs-trash"></i>', array(
        'class' => "btn btn-outline-danger btn-sm", 'title' => 'Delete', 'data-bs-toggle' => 'tooltip','data-bs-placement' => 'top','data-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'onclick' => "return confirmDeleteAlert(event);"));
}

function btn_delete_disable($uri)
{
    return anchor($uri, '<i class="fa fa-trash-o"></i>', array(
        'class' => "btn btn-outline-danger btn-sm disabled", 'title' => 'Delete', 'data-bs-toggle' => 'tooltip','data-bs-placement' => 'top','data-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'onclick' => "return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
    ));
}

function btn_active($uri)
{
    return anchor($uri, '<i class="fa fa-check"></i>', array(
        'class' => "btn btn-outline-success btn-sm", 'title' => 'Active', 'data-bs-toggle' => 'tooltip','data-bs-placement' => 'top','data-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'onclick' => "return confirm('You are about to active new sesion . This cannot be undone. Are you sure?');"
    ));
}

function btn_print()
{
    return anchor('', '<span class="glyphicon glyphicon-print"></i></span>', array('class' => "btn btn-outline-success btn-sm", 'title' => 'Print', 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top','data-toggle' => 'tooltip','data-bs-placement' => 'top', 'onclick' => 'printDiv("printableArea")'));
}

function btn_atndc_print()
{
    return anchor('', '<span class="glyphicon glyphicon-print"></i></span>', array('class' => "btn btn-customs btn-xs", 'title' => 'Print', 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top','data-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'onclick' => 'printDiv("printableArea")'));
}

function btn_pdf($uri)
{
    return anchor($uri, '<span <i class="fa fa-file-pdf-o"></i></span>', array('class' => "btn btn-primary btn-sm", 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top','data-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'PDF'));
}

function btn_make_pdf($uri)
{
    return anchor($uri, '<span <i class="fa fa-file-pdf-o""></i></span>', array('class' => "btn btn-primary btn-xs", 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Generate&nbsp;PDF'));
}

function btn_excel($uri)
{
    return anchor($uri, '<span <i class="fa fa-file-excel-o"></i></span>', array('class' => "btn btn-primary btn-xs", 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Excel'));
}

function btn_view($uri)
{
    return anchor($uri, '<span class="fa fa-list-alt"></span>', array('class' => "btn btn-outline-info btn-sm", 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'View'));
}

function btn_view_modal($uri)
{
    return anchor($uri, '<span class="fa fa-list-alt"></span>', array('class' => "btn btn-outline-info btn-sm", 'title' => 'View', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#myModal_lg','data-toggle' => 'modal', 'data-target' => '#myModal_lg'));
}

function btn_save($uri)
{
    return anchor($uri, '<span <i class="fa fa-plus-circle"></i></span>', array('class' => "btn btn-success btn-xs", 'title' => 'Save', 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top'));
}

function btn_add()
{
    return "<button type='submit' name='add' value='1' class='btn btn-info custom-btn'>" . lang('add') . "</button>";
}

function btn_publish($uri)
{
    return anchor($uri, '<i class="fa fa-check"></i>', array(
        'class' => "btn btn-outline-success btn-sm", 'title' => lang('click_to_published'), 'data-toggle' => 'tooltip', 'data-bs-placement' => 'top','data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'onclick' => "return confirm('You are about to unpublish this data. Are you sure?');"
    ));
}

function btn_unpublish($uri)
{
    return anchor($uri, '<i class="fa fa-times"></i>', array(
        'class' => "btn btn-outline-danger btn-sm", 'title' => lang('click_to_unpublished'), 'data-toggle' => 'tooltip', 'data-bs-placement' => 'top','data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'onclick' => "return confirm('You are about to publish this data. Are you sure?');"
    ));
}

function btn_approve($uri)
{
    return anchor($uri, '<i class="fa fa-times"></i>', array(
        'class' => "btn btn-outline-danger btn-sm", 'title' => 'Click to Reject', 'data-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top','onclick' => "return confirm('You are about to unpublish this data. Are you sure?');"
    ));
}

function btn_reject($uri)
{
    return anchor($uri, '<i class="fa fa-check"></i>', array(
        'class' => "btn btn-outline-success btn-sm", 'title' => 'Click to Approve', 'data-toggle' => 'tooltip', 'data-bs-placement' => 'top','data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'onclick' => "return confirm('You are about to publish this data. Are you sure?');"
    ));
}


function slug_it($str, $options = array())
{
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '_',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(
            '
            /\b(ѓ)\b/i' => 'gj',
            '/\b(ч)\b/i' => 'ch',
            '/\b(ш)\b/i' => 'sh',
            '/\b(љ)\b/i' => 'lj'
        ),
        'transliterate' => true
    );
    // Merge options
    $options = array_merge($defaults, $options);
    $char_map = array(
        // Latin
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'AE',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ð' => 'D',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ő' => 'O',
        'Ø' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ű' => 'U',
        'Ý' => 'Y',
        'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'ae',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'd',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ő' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ű' => 'u',
        'ý' => 'y',
        'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A',
        'Β' => 'B',
        'Γ' => 'G',
        'Δ' => 'D',
        'Ε' => 'E',
        'Ζ' => 'Z',
        'Η' => 'H',
        'Θ' => '8',
        'Ι' => 'I',
        'Κ' => 'K',
        'Λ' => 'L',
        'Μ' => 'M',
        'Ν' => 'N',
        'Ξ' => '3',
        'Ο' => 'O',
        'Π' => 'P',
        'Ρ' => 'R',
        'Σ' => 'S',
        'Τ' => 'T',
        'Υ' => 'Y',
        'Φ' => 'F',
        'Χ' => 'X',
        'Ψ' => 'PS',
        'Ω' => 'W',
        'Ά' => 'A',
        'Έ' => 'E',
        'Ί' => 'I',
        'Ό' => 'O',
        'Ύ' => 'Y',
        'Ή' => 'H',
        'Ώ' => 'W',
        'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a',
        'β' => 'b',
        'γ' => 'g',
        'δ' => 'd',
        'ε' => 'e',
        'ζ' => 'z',
        'η' => 'h',
        'θ' => '8',
        'ι' => 'i',
        'κ' => 'k',
        'λ' => 'l',
        'μ' => 'm',
        'ν' => 'n',
        'ξ' => '3',
        'ο' => 'o',
        'π' => 'p',
        'ρ' => 'r',
        'σ' => 's',
        'τ' => 't',
        'υ' => 'y',
        'φ' => 'f',
        'χ' => 'x',
        'ψ' => 'ps',
        'ω' => 'w',
        'ά' => 'a',
        'έ' => 'e',
        'ί' => 'i',
        'ό' => 'o',
        'ύ' => 'y',
        'ή' => 'h',
        'ώ' => 'w',
        'ς' => 's',
        'ϊ' => 'i',
        'ΰ' => 'y',
        'ϋ' => 'y',
        'ΐ' => 'i',
        // Turkish
        'Ş' => 'S',
        'İ' => 'I',
        'Ç' => 'C',
        'Ü' => 'U',
        'Ö' => 'O',
        'Ğ' => 'G',
        'ş' => 's',
        'ı' => 'i',
        'ç' => 'c',
        'ü' => 'u',
        'ö' => 'o',
        'ğ' => 'g',
        // Russian
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'Yo',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'C',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Sh',
        'Ъ' => '',
        'Ы' => 'Y',
        'Ь' => '',
        'Э' => 'E',
        'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sh',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye',
        'І' => 'I',
        'Ї' => 'Yi',
        'Ґ' => 'G',
        'є' => 'ye',
        'і' => 'i',
        'ї' => 'yi',
        'ґ' => 'g',
        // Czech
        'Č' => 'C',
        'Ď' => 'D',
        'Ě' => 'E',
        'Ň' => 'N',
        'Ř' => 'R',
        'Š' => 'S',
        'Ť' => 'T',
        'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c',
        'ď' => 'd',
        'ě' => 'e',
        'ň' => 'n',
        'ř' => 'r',
        'š' => 's',
        'ť' => 't',
        'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A',
        'Ć' => 'C',
        'Ę' => 'e',
        'Ł' => 'L',
        'Ń' => 'N',
        'Ó' => 'o',
        'Ś' => 'S',
        'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a',
        'ć' => 'c',
        'ę' => 'e',
        'ł' => 'l',
        'ń' => 'n',
        'ó' => 'o',
        'ś' => 's',
        'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A',
        'Č' => 'C',
        'Ē' => 'E',
        'Ģ' => 'G',
        'Ī' => 'i',
        'Ķ' => 'k',
        'Ļ' => 'L',
        'Ņ' => 'N',
        'Š' => 'S',
        'Ū' => 'u',
        'Ž' => 'Z',
        'ā' => 'a',
        'č' => 'c',
        'ē' => 'e',
        'ģ' => 'g',
        'ī' => 'i',
        'ķ' => 'k',
        'ļ' => 'l',
        'ņ' => 'n',
        'š' => 's',
        'ū' => 'u',
        'ž' => 'z',
    );

    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function display_money($value, $currency = false, $decimal = 2)
{
    if (empty($value)) {
        $value = 0;
    }
    switch (config_item('money_format')) {
        case 1:
            $value = number_format($value, $decimal, '.', ',');
            break;
        case 2:
            $value = number_format($value, $decimal, ',', '.');
            break;
        case 3:
            $value = number_format($value, $decimal, '.', '');
            break;
        case 4:
            $value = number_format($value, $decimal, ',', '');
            break;
        case 5:
            $value = number_format($value, $decimal, ".", "'");
            break;
        case 6:
            $value = number_format($value, $decimal, ".", " ");
            break;
        case 7:
            $value = number_format($value, $decimal, ",", " ");
            break;
        case 8:
            $value = number_format($value, $decimal, "'", " ");
            break;
        default:
            $value = number_format($value, $decimal, '.', ',');
            break;
    }
    switch (config_item('currency_position')) {
        case 1:
            $return = $currency . ' ' . $value;
            break;
        case 2:
            $return = $value . ' ' . $currency;
            break;
        case false:
            $return = $value;
            break;
        default:
            $return = $currency . ' ' . $value;
            break;
    }

    return $return;
}

function display_time($value, $no_str = null)
{
    if (!empty($no_str)) {
        $time = $value;
    } else {
        $time = strtotime($value);
    }
    return date(config_item('time_format'), $time);
}

function display_date($value)
{
    return strftime(config_item('date_format'), strtotime($value));
}

function display_datetime($value, $no_str = null)
{
    if (!empty($no_str)) {
        $datetime = $value;
    } else {
        $datetime = strtotime($value);
    }
    return strftime(config_item('date_format'), $datetime) . ' ' . date(config_item('time_format'), $datetime);
}

function custom_form_Fields($id, $edit_id = null, $col_sm = null)
{
    $CI = &get_instance();
    $all_field = $CI->db->where('form_id', $id)->get('tbl_custom_field')->result();

    $table = $CI->db->where('form_id', $id)->get('tbl_form')->row()->tbl_name;
    $filed_id = str_replace('tbl_', '', $table);
    $html = null;
    if (!empty($all_field)) {
        foreach ($all_field as $v_fileds) {
            if (!empty($v_fileds->visible_for_admin)) {
                if ($CI->session->userdata('user_type') == 1) {
                    $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                    if (!empty($edit_id)) {
                        $showValue = $CI->db->where($filed_id . '_id', $edit_id)->get($table)->row($name);
                    }
                    if (!empty($showValue)) {
                        $value = $showValue;
                    } else {
                        $val = json_decode($v_fileds->default_value);
                        $value = $val[0];
                    }
                    if (!empty($col_sm)) {
                        $col = 'col-md-3';
                    } else {
                        $col = 'col-md-3';
                    }
                    if ($v_fileds->required == 'on') {
                        $required = 'required';
                        $l_required = '<span class="required">*</span>';
                    } else {
                        $required = null;
                        $l_required = null;
                    }
                    if (!empty($v_fileds->help_text)) {
                        $help_text = '<i title="' . $v_fileds->help_text . '" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>';
                    } else {
                        $help_text = null;
                    }

                    if ($v_fileds->field_type == 'text' && $v_fileds->status == 'active') {

                        if($v_fileds->form_id==12){
                            $html .= '<div class="col-lg-6"><div class="mb-3">
                                <label class="col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                                <input type="text" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                                </div></div>';
                        }else{
                            
                            $html .= '<div class="mb-3 row">
                                <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                                <div class="col-md-6">
                                <input type="text" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                                </div>
                                </div>';
                        }
                    }
                    if ($v_fileds->field_type == 'email' && $v_fileds->status == 'active') {

                        $html .= '<div class="mb-3 row">
                <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                <div class="col-md-6">
                <input type="email" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                </div>
                </div>';
                    }
                    if ($v_fileds->field_type == 'textarea' && $v_fileds->status == 'active') {

                        $html .= '<div class="mb-3 row">
                <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                <div class="col-md-6">
                <textarea name="' . $name . '" class="form-control" ' . $required . '>' . $value . '</textarea>
                </div>
                </div>';
                    }
                    if ($v_fileds->field_type == 'dropdown' && $v_fileds->status == 'active') {

                        $html .= '<div class="mb-3 row">
                <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                <div class="col-md-6">
                <select name="' . $name . '" class="form-control select_box" style="width:100%" ' . $required . '>
                ' . dropdownField($v_fileds->default_value, $value) . '

                </select>
                </div>
                </div>';
                    }
                    if ($v_fileds->field_type == 'date' && $v_fileds->status == 'active') {

                        $html .= '<div class="mb-3 row">
                <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                <div class="col-md-6">
                <div class="input-group">
                <input type="text" name="' . $name . '" class="form-control datepicker" value="' . (!empty($value) ? $value : date('Y-m-d')) . '">
                <div class="input-group-addon">
                <a href="#"><i class="fa fa-calendar"></i></a>
                </div>
                </div>
                </div>
                </div>';
                    }
                    if ($v_fileds->field_type == 'checkbox' && $v_fileds->status == 'active') {
                        $val = json_decode($v_fileds->default_value);
                        $html .= '<div class="mb-3 row">
                <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                <div class="col-md-6">
                <div class="checkbox c-checkbox">
                   <label class="needsclick">
                   <input type="checkbox" name="' . $name . '" ' . (!empty($value) && $value == 'on' ? 'checked' : $value = $val[0]) . ' ' . $required . '>
                   <span class="fa fa-check"></span>
                   </label>
                </div>
                </div>
                </div>';
                    }
                    if ($v_fileds->field_type == 'numeric' && $v_fileds->status == 'active') {

                        $html .= '<div class="mb-3 row">
                <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                <div class="col-md-6">
                <input type="number" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                </div>
                </div>';
                    }
                }
            } else {
                $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));

                if (!empty($edit_id)) {
                    $showValue = $CI->db->where($filed_id . '_id', $edit_id)->get($table)->row($name);
                }

                if (!empty($showValue)) {
                    $value = $showValue;
                } else {
                    $val = json_decode($v_fileds->default_value);
                    $value = $val[0];
                }
                if (!empty($col_sm)) {
                    $col = 'col-md-'.$col_sm;
                } else {
                    $col = 'col-lg-4 col-md-4 col-sm-5';
                }


                if ($v_fileds->required == 'on') {
                    $required = 'required';
                    $l_required = '<span class="required">*</span>';
                } else {
                    $required = null;
                    $l_required = null;
                }
                if (!empty($v_fileds->help_text)) {
                    $help_text = '<i title="' . $v_fileds->help_text . '" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>';
                } else {
                    $help_text = null;
                }

                if ($v_fileds->field_type == 'text' && $v_fileds->status == 'active') {

                    if($v_fileds->form_id==12){
                        $html .= '<div class="col-lg-6"><div class="mb-3">
                            <label class="col-form-label">'.$v_fileds->field_label.' '.$l_required.'  '.$help_text.'</label>
                            <input type="text" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                            </div>
                            </div>';

                    }else{
                        $html .= '<div class="mb-3 row">
                            <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <div class="col-md-6">
                            <input type="text" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                            </div>
                            </div>';

                    }


                }
                if ($v_fileds->field_type == 'textarea' && $v_fileds->status == 'active') {
                    if($v_fileds->form_id==12){
                        $html .= '<div class="col-lg-6"><div class="mb-3">
                            <label class="col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <textarea name="' . $name . '" class="form-control" ' . $required . '>' . $value . '</textarea>
                        </div> </div>';

                    }else{ 
                        $html .= '<div class="mb-3 row">
                        <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                        <div class="col-md-6">
                            <textarea name="' . $name . '" class="form-control" ' . $required . '>' . $value . '</textarea>
                        </div>
                        </div>';
                    }
                }
                if ($v_fileds->field_type == 'dropdown' && $v_fileds->status == 'active') {

                    if($v_fileds->form_id==12){
                        $html .= '<div class="col-lg-6"><div class="mb-3">
                            <label class="col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <select name="' . $name . '" class="form-control select_box" style="width:100%" ' . $required . '> ' . dropdownField($v_fileds->default_value, $value) . ' </select>
                            </div>
                            </div>';

                    }else{ 
                        $html .= '<div class="mb-3 row">
                        <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                        <div class="col-md-6">
                            <select name="' . $name . '" class="form-control select_box" style="width:100%" ' . $required . '>
                        ' . dropdownField($v_fileds->default_value, $value) . '
                        </select>
                        </div>
                        </div>';
                    }
                }
                if ($v_fileds->field_type == 'date' && $v_fileds->status == 'active') {
                    if($v_fileds->form_id==12){
                        $html .= '<div class="col-lg-6"><div class="mb-3">
                            <label class="col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <div class="input-group">
                                <input type="text" name="' . $name . '" class="form-control datepicker" value="' . (!empty($value) ? $value : date('d-m-Y H-i')) . '">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div> </div>';

                    }else{ 
                            $html .= '<div class="mb-3 row">
                        <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                        <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="' . $name . '" class="form-control datepicker" value="' . (!empty($value) ? $value : date('d-m-Y H-i')) . '">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                        </div>
                        </div>';
                    }
                }
                if ($v_fileds->field_type == 'checkbox' && $v_fileds->status == 'active') {
                    
                    $val = json_decode($v_fileds->default_value);
                    
                    if($v_fileds->form_id==12){
                        $html .= '<div class="col-lg-6"><div class="mb-3">
                            <label class="col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <div class="form-check form-check-primary mb-3">
                                   <input type="checkbox" class="form-check-input" name="' . $name . '" ' . (!empty($value) && $value == 'on' ? 'checked' : $value = $val[0]) . ' ' . $required . '>
                            </div>
                        </div> </div>';

                    }else{ 

                        $html .= '<div class="mb-3 row">
                            <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <div class="col-md-6">
                                <div class="form-check form-check-primary mb-3">
                                   <input type="checkbox" class="form-check-input" name="' . $name . '" ' . (!empty($value) && $value == 'on' ? 'checked' : $value = $val[0]) . ' ' . $required . '>
                                </div>
                            </div>
                            </div>';
                    }
                }
                if ($v_fileds->field_type == 'numeric' && $v_fileds->status == 'active') {
                    if($v_fileds->form_id==12){
                        $html .= '<div class="col-lg-6"><div class="mb-3">
                            <label class="col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <input type="number" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                        </div> </div>';

                    }else{ 
                        $html .= '<div class="mb-3 row">
                            <label class="' . $col . ' col-form-label">' . $v_fileds->field_label . ' ' . $l_required . '  ' . $help_text . '</label>
                            <div class="col-md-6">
                            <input type="number" name="' . $name . '" class="form-control" ' . $required . ' value="' . $value . '">
                        </div>
                        </div>';
                    }
                }
            }
        }
    }
    return $html;
}


function dropdownField($value, $editValue = null)
{
    $html = null;
    foreach (json_decode($value) as $optionValue) {
        $html .= '<option value="' . $optionValue . '" ' . (!empty($editValue) && $editValue == $optionValue ? "selected" : null) . '>' . $optionValue . '</option>';
    }
    return $html;
}

function company_option($companies_id = null)
{
    $html = null;
    $CI = &get_instance();
    $all_companies = $CI->db->get('tbl_companies')->result();
    if (empty($companies_id)) {
        $companies_id = $CI->session->userdata('companies_id');
    }
    if (empty($companies_id)) {
        $companies_id = null;
    }
    //    $html .= '<option value="">' . lang('none') . '</option>';
    foreach ($all_companies as $companies) {
        $html .= '<option value="' . $companies->companies_id . '" ' . (!empty($companies_id) && $companies_id == $companies->companies_id ? "selected" : null) . '>' . $companies->name . '</option>';
    }
    return $html;
}

function save_custom_field($id, $edit_id = null)
{
    $CI = &get_instance();
    $CI->load->model('admin_model');

    $all_field = get_result('tbl_custom_field', array('form_id' => $id));
    $table = $CI->db->where('form_id', $id)->get('tbl_form')->row()->tbl_name;

    $filed_id = str_replace('tbl_', '', $table);
    $table_id = $filed_id . '_id';
    $custom = array();
    if (!empty($all_field)) {
        foreach ($all_field as $v_fileds) {
            if (!empty($v_fileds->visible_for_admin)) {
                if ($CI->session->userdata('user_type') == 1) {
                    $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                    $custom[$name] = $CI->input->post($name, true);
                }
            } else {
                $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                $custom[$name] = $CI->input->post($name, true);
            }
        }
        $CI->admin_model->_table_name = $table; //table name
        $CI->admin_model->_primary_key = $table_id;
        $CI->admin_model->save($custom, $edit_id);
    }
}

function custom_form_label($id, $show_id)
{

    $CI = &get_instance();
    $CI->load->model('admin_model');
    $all_field = get_result('tbl_custom_field', array('form_id' => $id));
    $table = get_row('tbl_form', array('form_id' => $id), 'tbl_name');
    $filed_id = str_replace('tbl_', '', $table);
    $table_id = $filed_id . '_id';

    $showValue = array();
    if (!empty($all_field)) {
        foreach ($all_field as $v_fileds) {
            if (!empty($v_fileds->visible_for_admin)) {
                if ($CI->session->userdata('user_type') == 1) {
                    if ($v_fileds->show_on_details == 'on') {
                        $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                        $showValue[$v_fileds->field_label] = $CI->db->where($table_id, $show_id)->get($table)->row($name);
                    }
                }
            } else {
                if ($v_fileds->show_on_details == 'on') {
                    $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                    $showValue[$v_fileds->field_label] = $CI->db->where($table_id, $show_id)->get($table)->row($name);
                }
            }
        }
    }
    return $showValue;
}

function custom_form_table($id, $show_id)
{

    $CI = &get_instance();
    $CI->load->model('admin_model');
    $all_field = get_result('tbl_custom_field', array('form_id' => $id));
    $table = get_row('tbl_form', array('form_id' => $id), 'tbl_name');
    $filed_id = str_replace('tbl_', '', $table);
    $table_id = $filed_id . '_id';

    $showValue = array();
    if (!empty($all_field)) {
        foreach ($all_field as $v_fileds) {
            if (!empty($v_fileds->visible_for_admin)) {
                if ($CI->session->userdata('user_type') == 1) {
                    if ($v_fileds->show_on_table == 'on') {
                        $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                        $showValue[$v_fileds->field_label] = $CI->db->where($table_id, $show_id)->get($table)->row($name);
                    }
                }
            } else {
                if ($v_fileds->show_on_table == 'on') {
                    $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                    $showValue[$v_fileds->field_label] = $CI->db->where($table_id, $show_id)->get($table)->row($name);
                }
            }
        }
    }
    return $showValue;
}
function custom_form_table_for_mod($all_field, $table, $show_id)
{

    $CI = &get_instance();
    $CI->load->model('admin_model');
    // $all_field = get_result('tbl_custom_field', array('form_id' => $id));
    // $table = get_row('tbl_form', array('form_id' => $id), 'tbl_name');
    $filed_id = str_replace('tbl_', '', $table);
    $table_id = $filed_id . '_id';

    $showValue = array();
    if (!empty($all_field)) {
        foreach ($all_field as $v_fileds) {
            if (!empty($v_fileds->visible_for_admin)) {
                if ($CI->session->userdata('user_type') == 1) {
                    if ($v_fileds->show_on_table == 'on') {
                        $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                        $showValue[$v_fileds->field_label] = $CI->db->where($table_id, $show_id)->get($table)->row($name);
                    }
                }
            } else {
                if ($v_fileds->show_on_table == 'on') {
                    $name = strtolower(preg_replace('/\s+/', '_', $v_fileds->field_label));
                    $showValue[$v_fileds->field_label] = $CI->db->where($table_id, $show_id)->get($table)->row($name);
                }
            }
        }
    }
    return $showValue;
}

function url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function url_decode($data)
{
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function encrypt($data)
{
    $CI = &get_instance();
    $CI->load->library('encryption');
    return $CI->encryption->encrypt($data);
}

function decrypt($data)
{
    
    $CI = &get_instance();
    $CI->load->library('encryption');
    return $CI->encryption->decrypt($data);
}


function can_action($menu_id, $action)
{
    $CI = &get_instance();
       $user_type = $CI->session->userdata('user_type');
    if ($user_type == 1) {
        return true;
    } else {
    $parent_label = $CI->db->where(array('menu_id' => $menu_id))->get('tbl_menu')->row();
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        $menu_id = get_any_field('tbl_menu', array('label' => $parent_label->label, 'companies_id' => $companies_id), 'menu_id');
    }
    $designations_id = $CI->session->userdata('designations_id');
    $where = array('designations_id' => $designations_id, $action => $menu_id);
 
        $can_do = $CI->db->where($where)->get('tbl_user_role')->row();
       
        if (!empty($can_do)) {
            return true;
        }
          return false;
    }
}

function can_do($menu_id)
{
    $menu_id = menu_by_company($menu_id);
    $CI = &get_instance();
    $designations_id = $CI->session->userdata('designations_id');
    $user_type = $CI->session->userdata('user_type');
    if ($user_type == 1) {
        return true;
    } else {
        $can_do = $CI->db->where(array('designations_id' => $designations_id, 'menu_id' => $menu_id))->get('tbl_user_role')->result();
        if (!empty($can_do)) {
            return true;
        }
    }
}

function value_exists_in_array_by_key($array, $key, $val)
{
    foreach ($array as $item) {
        if (isset($item[$key]) && $item[$key] == $val) {
            return true;
        }
    }
    return false;
}

function clear_textarea_breaks($text)
{
    $_text = '';
    $_text = $text;
    $breaks = array(
        "<br />",
        "<br>",
        "<br/>",
        "'",
        "-",
        "^",
        "/",
        "%",
    );
    $_text = str_ireplace($breaks, "", $_text);
    $_text = trim($_text);
    return $_text;
}

function set_mysql_timezone($timezone)
{
    $offset = timezone_offset_get(new DateTimeZone($timezone), new DateTime());
    $sign = ($offset > 0) ? '+' : '-';
    $offset = gmdate('H:i', abs($offset));
    $zone = $sign . $offset;
    $CI = &get_instance();
    $CI->db->query("SET time_zone='$zone'");
    return true;
}

function access_denied($permission = '', $module = null)
{
    $CI = &get_instance();
    set_message('danger', lang('access_denied'));
    $activity = array(
        'user' => $CI->session->userdata('user_id'),
        'module' => $module,
        'module_field_id' => $CI->session->userdata('user_id'),
        'activity' => 'access_denied',
        'value1' => 'Tried to access page where don\'t have permission [' . $permission . ']',
    );


    $CI->load->model('account_model');
    $CI->admin_model->_table_name = 'tbl_activities';
    $CI->admin_model->_primary_key = 'activities_id';
    $CI->admin_model->save($activity);

    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        redirect('404');
    }
}

function check_installation()
{
    if (is_dir(FCPATH . 'install')) {
        echo '<strong>Delete the install folder</strong>';
        die;
    }
}

function check_complete_setup($domain)
{
    $result = get_row('tbl_subscriptions', array('domain' => $domain, 'status' => 'pending'));
    if (!empty($result)) {
        redirect(base_url('setup'));
    }
    return false;
}

/**
 * Function that will replace the dropbox link size for the images
 * This function is used to preview dropbox image attachments
 * @param string $url
 * @param string $bounding_box
 * @return string
 */
function optimize_dropbox_thumbnail($url, $bounding_box = '800')
{
    $url = str_replace('bounding_box=75', 'bounding_box=' . $bounding_box, $url);

    return $url;
}

function protected_file_url_by_path($path)
{
    return str_replace(FCPATH, '', $path);
}

function _mime_content_type($filename)
{
    if (function_exists('mime_content_type'))
        return mime_content_type($filename);
    else if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filename);
        return $mimetype;
    } else
        return get_mime_by_extension($filename);
}

/**
 * Add user notifications
 * @param array $values array of values [description,from_user_id,to_user_id,is_read]
 */
function add_notification($values)
{
    $CI = &get_instance();
    foreach ($values as $key => $value) {
        $data[$key] = $value;
    }
    if (!empty($data['from_user_id'])) {
        $user_id = $CI->session->userdata('user_id');
        $data['from_user_id'] = $user_id;
        $data['name'] = $CI->db->select('fullname')->where('user_id', $user_id)->get('tbl_account_details')->row()->fullname;
    }
    // Prevent sending notification to non active users.
    if (isset($data['to_user_id']) && $data['to_user_id'] != 0) {
        $CI->db->select('activated');
        $CI->db->where('user_id', $data['to_user_id']);
        $user = $CI->db->get('tbl_users')->row();
        if (!$user) {
            return false;
        }
        if ($user) {
            if ($user->activated == 0) {
                return false;
            }
        }
    }
    $data['date'] = date('Y-m-d H:i:s');
    $CI->db->insert('tbl_notifications', $data);
    return true;
}

function profile($id = null)
{
    $CI = &get_instance();
    if (empty($id)) {
        $id = $CI->session->userdata('user_id');
    }
    if (!empty($id)) {
        return $CI->db
            ->where("tbl_users.user_id", $id)
            ->join("tbl_account_details", "tbl_account_details.user_id = tbl_users.user_id")
            ->get("tbl_users")->row();
    } else {
        return false;
    }
}

function my_id()
{
    $CI = &get_instance();
    return $CI->session->userdata('user_id');
}

function super_admin()
{
    if (!empty(my_id())) {
        $super_admin = get_any_field('tbl_users', array('user_id' => my_id()), 'super_admin');
        if (!empty($super_admin)) {
            if ($super_admin == 'Yes') {
                return true;
            } elseif ($super_admin == 'owner') {
                return 'owner';
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    return false;
}

function admin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('user_type') == 1) {
        return true;
    } else {
        return false;
    }
}

function super_admin_opt_th()
{
    $CI = &get_instance();
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        echo '<th class="col-sm-1">' . lang('companies') . '</th>';
    } else {
        return false;
    }
}

function super_admin_opt_td($companies_id = null, $t = null)
{
    $CI = &get_instance();
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        $company_info = $CI->db->where('companies_id', $companies_id)->get('tbl_companies')->row();
        if (!empty($company_info)) {
            $name = $company_info->name;
        } else {
            $name = '-';
        }
        if (!empty($t)) {
            return $name;
        } else {
            echo '<td class="col-sm-1">' . $name . '</td>';
        }
    } else {
        return false;
    }
}

function by_company($tbl, $orderby = null, $asc = null, $companies_id = null)
{
    $CI = &get_instance();
    if (!empty($companies_id)) {
        $companies_id = $companies_id;
    } else {
        $companies_id = $CI->session->userdata('companies_id');
    }
    if (!empty($asc)) {
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
    if (!empty($companies_id)) {
        $CI->db->where(array('companies_id' => $companies_id));
    }
    if (!empty($orderby)) {
        $CI->db->order_by($orderby, $asc);
    }
    $result = $CI->db->get($tbl)->result();
    return $result;
}

function super_admin_inline($companies_id = null)
{
    $CI = &get_instance();
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        echo '<td><select name="companies_id" class="form-control select_box modal_select_box" style="width:100%" required>
                ' . company_option($companies_id) . '
                </select></td>';
    } else {
        return false;
    }
}

function super_admin_form($companies_id, $lcol = null, $incol = null)
{
    $CI = &get_instance();
    $html = null;
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        if (empty($lcol)) {
            $lcol = '3';
        }
        if (empty($incol)) {
            $lcol = '5';
        }
        $html .= '<div class="mb-3 row" ><label class="col-md-' . $lcol . ' col-form-label">' . lang('select') . ' ' . lang('companies') . '<span class="text-danger">*</span></label>
                <div class="col-md-' . $incol . '" id="companies_id_div">
                <select name="companies_id" class="form-control select_box" style="width:100%" required data-parsley-errors-container="#companies_id_div"    >
                <option value="">' . lang('select') . ' ' . lang('companies') . '...</option>
                ' . company_option($companies_id) . '
                </select>
                </div>
                </div>';
        echo $html;
    } else {
        return false;
    }
}

function super_admin_form_modal($companies_id, $lcol = null, $incol = null)
{
    $CI = &get_instance();
    $html = null;
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        if (empty($lcol)) {
            $lcol = '3';
        }
        if (empty($incol)) {
            $lcol = '5';
        }
        $html .= '<div class="mb-3 row" ><label class="col-md-' . $lcol . ' col-form-label">' . lang('select') . ' ' . lang('companies') . '<span class="text-danger">*</span></label>
                <div class="col-md-' . $incol . '" id="companies_id_div">
                <select name="companies_id" class="form-control modal_select_box" style="width:100%" required data-parsley-errors-container="#companies_id_div"    >
                <option value="">' . lang('select') . ' ' . lang('companies') . '...</option>
                ' . company_option($companies_id) . '
                </select>
                </div>
                </div>';
        echo $html;
    } else {
        return false;
    }
}


function super_admin_form_section($companies_id, $lcol = null, $incol = null)
{
    $CI = &get_instance();
    $html = null;
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        if (empty($lcol)) {
            $lcol = '3';
        }
        if (empty($incol)) {
            $incol = '5';
        }
        $html .= '<div class="col-lg-' . $lcol . '"><div class="mb-3" id="companies_id_div"><label>' . lang('select') . ' ' . lang('companies') . '<span class="text-danger">*</span></label><select name="companies_id" class="form-control select_box" style="width:100%" required data-parsley-errors-container="#companies_id_div"><option value="">' . lang('select') . ' ' . lang('companies') . '...</option>' . company_option($companies_id) . '</select></div></div>';
        echo $html;
    } else {
        return false;
    }
}


function super_admin_details($companies_id, $lcol = null, $incol = null, $col= null, $subcol= null)
{
    $CI = &get_instance();
    $html = null;
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        if (empty($lcol)) {
            $lcol = '4';
        }
        if (empty($incol)) {
            $incol = '8';
        }
        if (empty($col)) {
            $col = $lcol;
        }
        if (empty($subcol)) {
            $subcol = $incol;
        }
        $company_info = $CI->db->where('companies_id', $companies_id)->get('tbl_companies')->row();
        if (!empty($company_info)) {
            $name = $company_info->name;
        } else {
            $name = '-';
        }
        $html .= '<div class="mb-3 row">
                <label class="col-md-' . $lcol . ' col-'. $col .'"><strong>' . lang('companies') . ': </strong></label>
                <div class="col-md-' . $incol . ' col-'. $subcol .'">' . $name . '</div>
                </div>';
        echo $html;
    } else {
        return false;
    }
}

function super_admin_details_p($companies_id, $lcol = null, $mcol = null)
{
    $CI = &get_instance();
    $html = null;
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        if (empty($lcol)) {
            $lcol = '4';
        }
        if (empty($mcol)) {
            $mcol = '6';
        }
        $company_info = $CI->db->where('companies_id', $companies_id)->get('tbl_companies')->row();
        if (!empty($company_info)) {
            $name = $company_info->name;
        } else {
            $name = '-';
        }
        $html .= '<div class="' . $mcol . '">
                <label class="form-label col-md-' . $lcol . '">' . lang('companies') . ': </label>
                <p class="form-control-static">' . $name . '</p>
                </div>';
        echo $html;
    } else {
        return false;
    }
}

function super_admin_invoice($companies_id, $lcol = null)
{
    $CI = &get_instance();
    $html = null;
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        if (empty($lcol)) {
            $lcol = '4';
        }
        $company_info = $CI->db->where('companies_id', $companies_id)->get('tbl_companies')->row();
        if (!empty($company_info)) {
            $name = $company_info->name;
        } else {
            $name = '-';
        }
        $html .= '<div class="clearfix col-xl-'.$lcol.'">
                <p class="pull-left"><strong>' . lang('companies') . ': </strong></p>
                <p class="pull-right mr"><strong>' . $name . '</strong></p>
                </div>';
        echo $html;
    } else {
        return false;
    }
}

function super_admin_pdf($companies_id, $space = null, $style = null)
{
    $CI = &get_instance();
    $html = null;
    $super_admin = $CI->session->userdata('super_admin');
    if (!empty($super_admin) && $super_admin == 'Yes' || !empty($super_admin) && $super_admin == 'owner') {
        $company_info = $CI->db->where('companies_id', $companies_id)->get('tbl_companies')->row();
        if (!empty($company_info)) {
            $name = $company_info->name;
        } else {
            $name = '-';
        }
        if (empty($style)) {
            $style = 'width: 30%;text-align: right';
        } else {
            $style = null;
        }
        $html .= '<tr>
                <td style="' . $style . '"><strong>' . lang('companies') . ': </strong></td>';
        if (!empty($space)) {
            $html .= '<td>&nbsp;&nbsp;&nbsp;</td>';
        }
        $html .= '<td style="">&nbsp;' . $name . '</strong></td>
                </tr>';
        echo $html;
    } else {
        return false;
    }
}

function company_name($companies_id = null)
{
    $CI = &get_instance();
    $company_info = $CI->db->where('companies_id', $companies_id)->get('tbl_companies')->row();
    if (!empty($company_info)) {
        $name = $company_info->name;
    } else {
        $name = '-';
    }
    echo $name;
}

function staffImage($user_id = null)
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }
    $img = 'assets/img/user/default_avatar.jpg';
    $userInfo = $CI->db->select('avatar')->where('user_id', $user_id)->get('tbl_account_details')->row();
    if (!empty($userInfo)) {
        if (is_file($userInfo->avatar)) {
            $img = $userInfo->avatar;
        }
    }
    return $img;
}

function product_image($saved_items_id = null)
{
    $CI = &get_instance();
    $productInfo = $CI->db->where('saved_items_id', $saved_items_id)->get('tbl_saved_items')->row();
    $img = 'assets/img/user/product.png';
    if (!empty($productInfo) && is_file($productInfo->product_image)) {
        $img = $productInfo->product_image;
    }
    return base_url($img);
}

function fullname($user_id = null)
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }
    $userInfo = $CI->db->select('fullname')->where('user_id', $user_id)->get('tbl_account_details')->row();
    if (!empty($userInfo)) {
        return $userInfo->fullname;
    } else {
        return 'Undefined user';
    }
}

function client_name($client_id = null)
{
    $CI = &get_instance();
    if (empty($client_id)) {
        $client_id = $CI->session->userdata('client_id');
    }
    if (is_numeric($client_id)) {
        $clientInfo = $CI->db->where('client_id', $client_id)->get('tbl_client')->row();
    }
    if (!empty($clientInfo)) {
        return $clientInfo->name;
    } else {
        return lang('undefined_client');
    }
}

function client_id()
{
    $CI = &get_instance();
    $client_id = $CI->session->userdata('client_id');
    return $client_id;
}

function designation($id = null)
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $id = $CI->session->userdata('user_id');
    }
    $userInfo = $CI->db->select('designations_id')->where('user_id', $id)->get('tbl_account_details')->row();
    if (!empty($userInfo->designations_id)) {
        $designation = $CI->db->select('designations')->where('designations_id', $userInfo->designations_id)->get('tbl_designations')->row()->designations;
    } else {
        $designation = '-';
    }
    return $designation;
}

/**
 *
 * @param  $array - data
 * @param  $key - value you want to pluck from array
 *
 * @return plucked array only with key data
 */
if (!function_exists('array_pluck')) {
    function array_pluck($array, $key)
    {
        return array_map(function ($v) use ($key) {
            return is_object($v) ? $v->$key : $v[$key];
        }, $array);
    }
}

/**
 * Short Time ago function
 * @param datetime $time_ago
 * @return mixed
 */
function time_ago($time_ago)
{
    if (is_numeric($time_ago) && (int)$time_ago == $time_ago) {
        $time_ago = $time_ago;
    } else {
        $time_ago = strtotime($time_ago);
    }
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return lang('time_ago_just_now');
    } //Minutes
    elseif ($minutes <= 60) {
        if ($minutes == 1) {
            return lang('time_ago_minute');
        } else {
            return lang('time_ago_minutes', $minutes);
        }
    } //Hours
    elseif ($hours <= 24) {
        if ($hours == 1) {
            return lang('time_ago_hour');
        } else {
            return lang('time_ago_hours', $hours);
        }
    } //Days
    elseif ($days <= 7) {
        if ($days == 1) {
            return lang('time_ago_yesterday');
        } else {
            return lang('time_ago_days', $days);
        }
    } //Weeks
    elseif ($weeks <= 4.3) {
        if ($weeks == 1) {
            return lang('time_ago_week');
        } else {
            return lang('time_ago_weeks', $weeks);
        }
    } //Months
    elseif ($months <= 12) {
        if ($months == 1) {
            return lang('time_ago_month');
        } else {
            return lang('time_ago_months', $months);
        }
    } //Years
    else {
        if ($years == 1) {
            return lang('time_ago_year');
        } else {
            return lang('time_ago_years', $years);
        }
    }
}

function daysleft($time)
{
    $result = null;
    $to_date = strtotime($time); //Future date.
    $cur_date = strtotime(date('Y-m-d H:i'));
    $timeleft = $to_date - $cur_date;
    $daysleft = round((($timeleft / 24) / 60) / 60);
    if ($daysleft == 1) {
        $result = $daysleft . ' ' . lang('day') . ' ' . lang('left');
    } else if ($daysleft > 1) {
        $result = $daysleft . ' ' . lang('days') . ' ' . lang('left');
    } else if ($daysleft == -1) {
        $result = $daysleft . ' ' . lang('day') . ' ' . lang('gone');
    } else if ($daysleft > -1) {
        $result = $daysleft . ' ' . lang('days') . ' ' . lang('gone');
    }
    return $result;
}

/**
 * check post file is valid or not
 *
 * @param string $file_name
 * @return json data of success or error message
 */
if (!function_exists('validate_post_file')) {

    function validate_post_file($file_name = "")
    {
        if (is_valid_file_to_upload($file_name)) {
            echo json_encode(array("success" => true));
            exit();
        } else {
            echo json_encode(array("success" => false, 'message' => lang('invalid_file_type') . " ($file_name)"));
            exit();
        }
    }
}

/**
 * this method process 3 types of files
 * 1. direct upload
 * 2. move a uploaded file which has been uploaded in temp folder
 * 3. copy a text based image
 *
 * @param string $file_name
 * @param string $target_path
 * @param string $source_path
 * @param string $static_file_name
 * @return filename
 */
if (!function_exists('move_temp_file')) {

    function move_temp_file($file_name, $target_path, $related_to = "", $source_path = NULL, $static_file_name = "")
    {
        $new_filename = unique_filename($target_path, $file_name);
        //if not provide any source path we'll fi   nd the default path
        if (!$source_path) {
            $source_path = getcwd() . "/uploads/temp/" . $file_name;
        }

        //check destination directory. if not found try to create a new one
        if (!is_dir($target_path)) {
            if (!mkdir($target_path, 0777, true)) {
                die('Failed to create file folders.');
            }
        }

        //overwrite extisting logic and use static file name
        if ($static_file_name) {
            $new_filename = $static_file_name;
        }

        //check the file type is data or file. then copy to destination and remove temp file
        if (starts_with($source_path, "data")) {
            copy_text_based_image($source_path, $target_path . $new_filename);
            return $new_filename;
        } else {
            if (file_exists($source_path)) {
                copy($source_path, $target_path . $new_filename);
                unlink($source_path);
                return $new_filename;
            }
        }
        return false;
    }
}
/**
 * Convert to a file from text based image
 *
 * @param string $source_path
 * @param string $target_path
 * @return file size
 */
if (!function_exists('copy_text_based_image')) {

    function copy_text_based_image($source_path, $target_path)
    {
        $buffer_size = 3145728;
        $byte_number = 0;
        $file_open = fopen($source_path, "rb");
        $file_wirte = fopen($target_path, "w");
        while (!feof($file_open)) {
            $byte_number += fwrite($file_wirte, fread($file_open, $buffer_size));
        }
        fclose($file_open);
        fclose($file_wirte);
        return $byte_number;
    }
}
/**
 * check if a string starts with a specified sting
 *
 * @param string $string
 * @param string $needle
 * @return true/false
 */
if (!function_exists('starts_with')) {

    function starts_with($string, $needle)
    {
        $string = $string;
        return $needle === "" || strrpos($string, $needle, -strlen($string)) !== false;
    }
}

/**
 * check the file type is valid for upload
 *
 * @param string $file_name
 * @return true/false
 */
if (!function_exists('is_valid_file_to_upload')) {

    function is_valid_file_to_upload($file_name = "")
    {

        if (!$file_name)
            return false;

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $file_formates = explode('|', config_item('allowed_files'));
        if (in_array($file_ext, $file_formates)) {
            return true;
        }
    }
}
/**
 * Validate the image
 *
 * @return    bool
 */
function check_image_extension($image)
{

    $images_extentions = array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "gif", "GIF", "bmp", "BMP");

    $image_parts = explode(".", $image);

    $image_end_part = end($image_parts);

    if (in_array($image_end_part, $images_extentions) == true) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * upload a file to temp folder when using dropzone autoque=true
 *
 * @param file $_FILES
 * @return void
 */
if (!function_exists('upload_file_to_temp')) {

    function upload_file_to_temp()
    {
        if (!empty($_FILES)) {
            $temp_file = $_FILES['file']['tmp_name'];
            $file_name = $_FILES['file']['name'];

            if (!is_valid_file_to_upload($file_name))
                return false;

            $target_path = getcwd() . '/uploads/temp/';
            if (!is_dir($target_path)) {
                if (!mkdir($target_path, 0777, true)) {
                    die('Failed to create file folders.');
                }
            }
            $target_file = $target_path . $file_name;
            copy($temp_file, $target_file);
        }
    }
}

/**
 * upload a file to temp folder when using dropzone autoque=true
 *
 * @param file $_FILES
 * @return void
 */
if (!function_exists('upload_file_to_docroom')) {

    function upload_file_to_docroom($folder_id='')
    {
        $CI = &get_instance();
        $CI->load->library('docroom_connect');
        $user_id = $CI->session->userdata('user_id');
        $access_token=$CI->session->userdata('docroom_access_token'); 
        $result=array();
        if (!empty($_FILES)) {
            $temp_file = $_FILES['file']['tmp_name'];
            $file_name = $_FILES['file']['name'];

            $user_docroom_info = userDocroomInfo();

            $main_folder_id=$user_docroom_info->docroom_folder_id;
            $account_id=$user_docroom_info->docroom_account_id;
            if(!empty($folder_id)){
                $docroom_folder_id=$folder_id;
            }else{
                $docroom_folder_id=$main_folder_id;
            }

            $upload_file=$CI->docroom_connect->upload_file(array('access_token'=>$access_token,'account_id'=>$account_id,'folder_id'=>$docroom_folder_id),$temp_file,$file_name);
            if(!empty($upload_file)){
                $upload_file_status=$upload_file->_status;
                $type=$upload_file_status;
                $response=$upload_file->response;
                $result=array('uploaded_file'=> $upload_file,''.$type.'' =>''.$response.'');
            }
            
        }
        
        return $result;
    }
}

/**
 * Supported html5 video extensions
 * @return array
 */
function get_html5_video_extensions()
{

    return do_action(
        'html5_video_extensions',
        array(
            'mp4',
            'm4v',
            'webm',
            'ogv',
            'ogg',
            'flv'
        )
    );
}

/**
 * Check if filename/path is video file
 * @param string $path
 * @return boolean
 */
function is_html5_video($path)
{
    $ext = _get_file_extension($path);
    if (in_array($ext, get_html5_video_extensions())) {
        return true;
    }
    return false;
}

/**
 * Get file extension by filename
 * @param string $file_name file name
 * @return mixed
 */
function _get_file_extension($file_name)
{
    return substr(strrchr($file_name, '.'), 1);
}

/**
 * Function used to validate all recaptcha from google reCAPTCHA feature
 * @param string $str
 * @return boolean
 */
function do_recaptcha_validation($str = '')
{
    $CI = &get_instance();
    $CI->load->library('form_validation');
    $google_url = "https://www.google.com/recaptcha/api/siteverify";
    $secret = config_item('recaptcha_secret_key');
    $ip = $CI->input->ip_address();
    $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($res, true);
    //reCaptcha success check
    if ($res['success']) {
        return true;
    } else {
        $CI->form_validation->set_message('recaptcha', lang('recaptcha_error'));
        return false;
    }
}

function MyDetails($user_id = null)
{
    $CI = &get_instance();
    $CI->db->select('tbl_users.*', FALSE);
    $CI->db->select('tbl_account_details.*', FALSE);
    $CI->db->select('tbl_designations.departments_id', FALSE);
    $CI->db->from('tbl_users');
    $CI->db->join('tbl_account_details', 'tbl_users.user_id = tbl_account_details.user_id', 'left');
    $CI->db->join('tbl_designations', 'tbl_designations.designations_id = tbl_account_details.designations_id', 'left');
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }
    $CI->db->where('tbl_users.user_id', $user_id);
    $CI->db->where('tbl_users.role_id !=', 2);
    $CI->db->where('tbl_users.activated', 1);
    $query_result = $CI->db->get();
    return $query_result->row();
}

function get_staff_details($user_id = null,$type="")
{
    $CI = &get_instance();
    $CI->db->select('tbl_users.*', FALSE);
    $CI->db->select('tbl_account_details.*', FALSE);
    $CI->db->from('tbl_users');
    $CI->db->join('tbl_account_details', 'tbl_users.user_id = tbl_account_details.user_id', 'left');
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        if ($CI->db->field_exists('companies_id', 'tbl_users')) {
            $CI->db->where('companies_id', $companies_id);
        }
    }
    if (!empty($user_id)) {
        $CI->db->where('tbl_users.user_id', $user_id);
        $query_result = $CI->db->get();
        $result = $query_result->row();
    }else if($type=='array'){
       $CI->db->where('tbl_users.role_id !=', 2);
       $CI->db->where('tbl_users.activated', 1);
       $query_result = $CI->db->get();
       $result = $query_result->result_array();
    } else {
        $CI->db->where('tbl_users.role_id !=', 2);
        $CI->db->where('tbl_users.activated', 1);
        $query_result = $CI->db->get();
        $result = $query_result->result();
    }
    return $result;
}

/**
 * prepare a anchor tag for ajax request
 *
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('ajax_anchor')) {

    function ajax_anchor($url, $title = '', $attributes = '')
    {
        $attributes["data-act"] = "ajax-request";
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }
}

/**
 * prepare a anchor tag for any js request
 *
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('js_anchor')) {

    function js_anchor($title = '', $attributes = '')
    {
        $title = (string)$title;

        $html_attributes = "";
        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }
        return '<strong data-bs-toggle="tooltip" data-bs-placement="top" style="cursor:pointer"' . $html_attributes . '>' . $title . '</strong>';
    }
}
/**
 * prepare a anchor tag for any js request
 *
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('remove_files')) {

    function remove_files($fileName, $dir = null)
    {
        //Delete the file
        if (empty($dir)) {
            $dir = 'uploads/';
        }
        if (is_file($dir . $fileName)) {
            unlink($dir . $fileName);
        }
        return true;
    }
}
function get_result($tbl, $where = null, $type = '',$limit=null,$select='*')
{
    $CI = &get_instance();
    $CI->db->select($select);
    $CI->db->from($tbl);
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        // if ($CI->db->field_exists('companies_id', $tbl)) {
        //     $CI->db->where('companies_id', $companies_id);
        // }
    }
    if (!empty($where) && $where != 0) {
        $CI->db->where($where);
    }
    if (!empty($limit) && $limit != 0) {
        $CI->db->limit($limit);
    }
    $query_result = $CI->db->get();
    if ($type=='array') {
        $result = $query_result->result_array();
    }else if(!empty($type)){
          $result = $query_result->row();
    } else {
        $result = $query_result->result();
    }
    return $result;
}
function get_result_selected($tbl, $where = null, $select = '')
{
    $CI = &get_instance();
    $CI->db->select($select);

    $CI->db->from($tbl);
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        // if ($CI->db->field_exists('companies_id', $tbl)) {
        //     $CI->db->where('companies_id', $companies_id);
        // }
    }
    if (!empty($where) && $where != 0) {
        $CI->db->where($where);
    }
    $query_result = $CI->db->get();
   
        $result = $query_result->result();
    
  
    return $result;
}

function get_old_result($tbl, $where = null, $type = null)
{
    $CI = &get_instance();
    $CI->old_db = new_database(true, true);
    $CI->old_db->select('*');
    $CI->old_db->from($tbl);
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        if ($CI->old_db->field_exists('companies_id', $tbl)) {
            $CI->old_db->where('companies_id', $companies_id);
        }
    }
    if (!empty($where) && $where != 0) {
        $CI->old_db->where($where);
    }
    $query_result = $CI->old_db->get();
    if (!empty($type)) {
        $result = $query_result->row();
    } else {
        $result = $query_result->result();
    }
    return $result;
}
function get_old_result_group($where, $tbl_name, $groupby = null, $select='*')
{
    $CI = &get_instance();
    $CI->old_db = new_database(true, true);
    $CI->old_db->select($select);
    $CI->old_db->from($tbl_name);
   
    if (!empty($where) && $where != 0) {
        $CI->old_db->where($where);
    }
      if($groupby) {
            $CI->old_db->group_by($groupby);   
        }
        $query_result = $CI->old_db->get();
      
        $result = $query_result->result_array();
   
    return $result;
}

function result_by_company($tbl, $where2 = null, $order_by = null, $ASC = null)
{
    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->from($tbl);
    $companies_id = $CI->session->userdata('companies_id');
    if (empty($companies_id)) {
        $companies_id = null;
    }
    $where1 = array('companies_id' => $companies_id);
    if (!empty($where2)) {
        $where = array_merge($where1, $where2);
    } else {
        $where = $where1;
    }
    if (!empty($ASC)) {
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
    if (!empty($order_by)) {
        $CI->db->order_by($order_by, $order);
    }
    $CI->db->where($where);
    if (empty($companies_id)) {
        $CI->db->or_where('companies_id', 0);
    }
    $query_result = $CI->db->get();
    $result = $query_result->result();
    return $result;
}

function get_url($input)
{
    // If URI is like, eg. www.way2tutorial.com/
    $input = trim($input, '/');

    // If not have http:// or https:// then prepend it
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }

    $urlParts = parse_url($input);

    // Remove www.
    $domain_name = preg_replace('/^www\./', '', $urlParts['host']);
    return $domain_name;
}

function menu_by_company($old_id)
{
    $CI = &get_instance();
    $parent_label = $CI->db->where(array('menu_id' => $old_id))->get('tbl_menu')->row();
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        $menu_id = get_any_field('tbl_menu', array('label' => $parent_label->label, 'companies_id' => $companies_id), 'menu_id');
        return $menu_id;
    } else {
        return $old_id;
    }
}
function menu_by_company_c_id($old_id,$companies_id='')
{
    $CI = &get_instance();
    $parent_label = $CI->db->where(array('menu_id' => $old_id))->get('tbl_menu')->row();
    // $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        $menu_id = get_any_field('tbl_menu', array('label' => $parent_label->label, 'companies_id' => $companies_id), 'menu_id');
        if(!$menu_id){
            return 0;
        }
        return $menu_id;
    } else {
        return $old_id;
    }
}

function client_menu_by_company($old_id)
{
    $CI = &get_instance();
    $parent_label = $CI->db->where(array('menu_id' => $old_id))->get('tbl_client_menu')->row();
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        $menu_id = get_any_field('tbl_client_menu', array('label' => $parent_label->label, 'companies_id' => $companies_id), 'menu_id');
        return $menu_id;
    } else {
        return $old_id;
    }
}

function get_sum($tbl, $where = null, $fields = null, $row = null)
{
    $CI = &get_instance();
    $CI->db->select_sum($fields);
    $CI->db->from($tbl);
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        if ($CI->db->field_exists('companies_id', $tbl)) {
            $CI->db->where('companies_id', $companies_id);
        }
    }
    if (!empty($where) && $where != 0) {
        $CI->db->where($where);
    }
    $query_result = $CI->db->get();
    if (!empty($row)) {
        $result = $query_result->row();
    } else {
        $result = $query_result->result();
    }

    return $result;
}

function get_order_by($tbl, $where = null, $order_by = null, $ASC = null, $limit = null)
{
    $CI = &get_instance();
    $CI->db->from($tbl);
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        if ($CI->db->field_exists('companies_id', $tbl)) {
            $CI->db->where('companies_id', $companies_id);
        }
    }
    if (!empty($where) && $where != 0) {
        $CI->db->where($where);
    }
    if (!empty($ASC)) {
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
    $CI->db->order_by($order_by, $order);
    if (!empty($limit)) {
        $CI->db->limit($limit);
    }
    $query_result = $CI->db->get();
    $result = $query_result->result();
    return $result;
}

function read_more($str, $limit, $url)
{
    // strip tags to avoid breaking any html
    $string = strip_tags($str);
    if (strlen($string) > $limit) {
        // truncate string
        $stringCut = substr($string, 0, $limit);
        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... <a href="' . base_url($url) . '">' . lang('read_more') . '</a>';
    }
    return $string;
}

if (!function_exists('cal_days_in_month')) {
    function cal_days_in_month($calendar, $month, $year)
    {
        return date('t', mktime(0, 0, 0, $month, 1, $year));
    }
}
if (!defined('CAL_GREGORIAN'))
    define('CAL_GREGORIAN', 1);

function leave_report($id = null)
{
    $CI = &get_instance();
    $office_hours = config_item('office_hours');
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        if ($CI->db->field_exists('companies_id', 'tbl_leave_category')) {
            $all_category = $CI->db->where('companies_id', $companies_id)->get('tbl_leave_category')->result();
        }
    } else {
        $all_category = $CI->db->get('tbl_leave_category')->result();
    }
    $result = array();
    if (!empty($all_category)) {
        foreach ($all_category as $v_category) {
            if (!empty($id)) {
                $where = array('user_id' => $id, 'leave_category_id' => $v_category->leave_category_id, 'application_status' => 2);
            } else {
                $where = array('leave_category_id' => $v_category->leave_category_id, 'application_status' => 2);
            }
            $all_leave_info = $CI->db->where($where)->get('tbl_leave_application')->result();

            $total_days = 0;
            $total_hours = 0;
            if (!empty($all_leave_info)) {
                $ge_days = 0;
                $m_days = 0;
                foreach ($all_leave_info as $v_leave) {
                    if ($v_leave->leave_type != 'hours') {
                        $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));
                        $datetime1 = new DateTime($v_leave->leave_start_date);
                        if (empty($v_leave->leave_end_date)) {
                            $v_leave->leave_end_date = $v_leave->leave_start_date;
                        }
                        $datetime2 = new DateTime($v_leave->leave_end_date);
                        $difference = $datetime1->diff($datetime2);

                        if ($difference->m != 0) {
                            $m_days += $month;
                        } else {
                            $m_days = 0;
                        }
                        $ge_days += $difference->d + 1;
                        $total_days = $m_days + $ge_days;
                    }

                    if ($v_leave->leave_type == 'hours') {
                        $total_hours += ($v_leave->hours / $office_hours);
                    }
                }
                if (empty($total_days)) {
                    $total_days = 0;
                }
                if (empty($total_hours)) {
                    $total_hours = 0;
                } else {
                    $total_hours = number_format($total_hours, 2);
                }
            }
            $result['leave_category'][] = $v_category->leave_category;
            $result['leave_quota'][] = $v_category->leave_quota;
            $result['leave_taken'][] = $total_days + $total_hours;
        }
    }
    return $result;
}

function get_any_field($table, $where, $table_field)
{
    $CI = &get_instance();
    $query = $CI->db->select($table_field)->where($where)->get($table);
    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->$table_field;
    }
}

function get_any_field_old($table, $where, $table_field)
{
    $CI = &get_instance();
    $CI->old_db = new_database(true, true);
    $query = $CI->old_db->select($table_field)->where($where)->get($table);
    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->$table_field;
    }
}

function get_row($table, $where, $fields = null)
{
    $CI = &get_instance();
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        if ($CI->db->field_exists('companies_id', $table)) {
            $CI->db->where('companies_id', $companies_id);
        }
    }
    $query = $CI->db->where($where)->get($table);
    if ($query->num_rows() > 0) {
        $row = $query->row();
        if (!empty($fields)) {
            return $row->$fields;
        } else {
            return $row;
        }
    }
}

function update($table, $where, $data)
{
    $CI = &get_instance();
    $CI->db->where($where);
    $CI->db->update($table, $data);
}

function update_old($table, $where, $data)
{
    $CI = &get_instance();
    $CI->old_db = new_database(true, true);
    $CI->old_db->where($where);
    $CI->old_db->update($table, $data);
}

function save_old($table, $data)
{
    $CI = &get_instance();
    $CI->old_db = new_database(true, true);
    $CI->old_db->set($data);
    $CI->old_db->insert($table);
    return $CI->old_db->insert_id();
}

function delete($table, $where)
{
    $CI = &get_instance();
    $CI->db->where($where);
    $CI->db->delete($table);
}

function convert_currency($currency, $amount)
{
    if (empty($currency)) {
        return $amount;
    }
    if ($currency == config_item('default_currency')) {
        return $amount;
    }
    $CI = &get_instance();
    $currency_info = $CI->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
    if ($currency_info->xrate > 0) {
        $in_local_cur = $amount * $currency_info->xrate;
        $convert_currency = $CI->db->where('code', $currency)->get('currencies')->row();
        $in_local = $in_local_cur / $convert_currency->xrate;
        return $in_local;
    } else {
        return $amount;
    }
}

function pricing_format($number, $lang = null)
{
    if (!empty($number) && $number != 0) {
        echo '<i class="flaticon-check-symbol" style="color: #3378ff"></i>' . $number . ' ' . $lang;
    } elseif (is_numeric($number) && $number == 0) {
        echo '<i class="flaticon-check-symbol" style="color: #3378ff"></i>' . lang('unlimited') . ' ' . $lang;
    } else {
        echo '<i class="flaticon-close" style="color: red"></i><del>' . $lang . '</del>';
    }
}

function pricing_format_YN($value, $lang = null)
{

    if (!empty($value) && $value == 'Yes') {
        echo '<i class="flaticon-check-symbol" style="color: #3378ff"></i>' . $lang;
    } else {
        echo '<i class="flaticon-close" style="color: red"></i><del>' . $lang . '</del>';
    }
}

function pricing_format_admin_YN($value, $lang = null)
{

    if (!empty($value) && $value == 'Yes') {
        echo '<i class="fa fa-check" style="color: #3378ff"></i>' . $lang;
    } else {
        echo '<i class="fa fa-times" style="color: red"></i><del>' . $lang . '</del>';
    }
}

function pricing_format_admin($number, $lang = null)
{
    if (!empty($number) && $number != 0) {
        echo '<i class="fa fa-check" style="color: #3378ff"></i>' . $number . ' ' . $lang;
    } elseif (is_numeric($number) && $number == 0) {
        echo '<i class="fa fa-check" style="color: #3378ff"></i>' . lang('unlimited') . ' ' . $lang;
    } else {
        echo '<i class="fa fa-times" style="color: red"></i><del>' . $lang . '</del>';
    }
}

function pricing_format_register($number, $lang = null)
{

    if (!empty($number) && $number != 0) {
        echo '<li style="margin-bottom: 1px;border-bottom: 1px solid #bce8f1;"><span class="flaticon-check-symbol" style="color: #3378ff;
    font-size: 16px;
    padding-right: 10px;"></span><strong style="font-size: 14px;color: #000;" >' . $number . ' ' . $lang . '</strong></li>';
    } elseif (is_numeric($number) && $number == 0) {
        echo '<li style="margin-bottom: 1px;border-bottom: 1px solid #bce8f1;"><span class="flaticon-check-symbol" style="color: #3378ff;
    font-size: 16px;
    padding-right: 10px;"></span><strong style="font-size: 14px;color: #000;" >' . lang('unlimited') . ' ' . $lang . '</strong></li>';
    } else {
        echo '<li style="margin-bottom: 1px;border-bottom: 1px solid #bce8f1;"><span class="flaticon-close" style="color: red;
    font-size: 16px;
    padding-right: 10px;"></span>' . '<strong style="font-size: 14px;color: #000;" > <del>' . $lang . '</del></strong></li>';
    }
}

function pricing_format_register_YN($value, $lang = null)
{
    if (!empty($value) && $value == 'Yes') {
        echo '<li style="margin-bottom: 1px;border-bottom: 1px solid #bce8f1;"><span class="flaticon-check-symbol" style="color: #3378ff;
    font-size: 16px;
    padding-right: 10px;"></span><strong style="font-size: 14px;color: #000;" >' . $value . ' ' . $lang . '</strong></li>';
    } else {
        echo '<li style="margin-bottom: 1px;border-bottom: 1px solid #bce8f1;"><span class="flaticon-close" style="color: red;
    font-size: 16px;
    padding-right: 10px;"></span>' . '<strong style="font-size: 14px;color: #000;" > <del>' . $lang . '</del></strong></li>';
    }
}

function get_active_plan()
{
    $CI = &get_instance();
    $companies_id = $CI->session->userdata('companies_id');
    if (!empty($companies_id)) {
        $active_subscription = $CI->db->where(array('companies_id' => $companies_id, 'status' => 1))->get('tbl_subscriptions')->row();
        if (!empty($active_subscription)) {
            return $CI->db->where(array('id' => $active_subscription->pricing_id))->get('tbl_frontend_pricing')->row();
        }
    } else {
        return false;
    }
}

function get_active_subs()
{
    $CI = &get_instance();
    $sub_domain = is_subdomain($_SERVER['HTTP_HOST']);
    if (!empty($sub_domain)) {
        $CI->old_db = new_database(true, true);
        return $CI->old_db->where(array('domain' => $sub_domain, 'status' => 'running'))->get('tbl_subscriptions')->row();
    } else {
        return false;
    }
}

function get_my_subs()
{
    $CI = &get_instance();
    $sub_domain = is_subdomain($_SERVER['HTTP_HOST']);
    if (!empty($sub_domain)) {
        $CI->old_db = new_database(true, true);
        return $CI->old_db->where(array('domain' => $sub_domain))->get('tbl_subscriptions')->row();
    } else {
        return false;
    }
}

function get_old_data($table, $where, $result = null)
{
    $CI = &get_instance();
    $CI->old_db = new_database(true, true);
    $query = $CI->old_db->where($where)->get($table);
    if ($query->num_rows() > 0) {
        if (!empty($result)) {
            $row = $query->result();
        } else {
            $row = $query->row();
        }
        return $row;
    }
}

function get_old_order_by($tbl, $where = null, $order_by = null, $ASC = null, $limit = null)
{
    $CI = &get_instance();
    $CI->old_db = new_database(true, true);
    $CI->old_db->from($tbl);
    if (!empty($where) && $where != 0) {
        $CI->old_db->where($where);
    }
    if (!empty($ASC)) {
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
    $CI->old_db->order_by($order_by, $order);
    if (!empty($limit)) {
        $CI->old_db->limit($limit);
    }
    $query_result = $CI->old_db->get();
    $result = $query_result->result();
    return $result;
}

function currency_list()
{
    $CI = &get_instance();

    return $CI->old_db->select('currency')->distinct()->get('tbl_currencywise_price')->result();
}

function trial_period($id = null)
{
    if (empty($id)) {
        $plan_info = get_active_subs();
    } else {
        $plan_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $id));
    }
    if (!empty($plan_info) && $plan_info->trial_period != 0) {
        $time = date('Y-m-d H:i', strtotime($plan_info->created_date));
        $to_date = strtotime($time); //Future date.
        $cur_date = strtotime(date('Y-m-d H:i'));
        $timeleft = $to_date - $cur_date;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        $days = ($plan_info->trial_period + $daysleft);
        return $days;
    } else {
        return false;
    }
}

function running_period($id = null)
{
    if (empty($id)) {
        $plan_info = get_active_subs();
    } else {
        $plan_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $id));
    }
    if (!empty($plan_info) && $plan_info->trial_period == 0) {
        $time = date('Y-m-d H:i', strtotime($plan_info->expired_date));
        $to_date = strtotime($time); //Future date.
        $cur_date = strtotime(date('Y-m-d H:i'));
        $timeleft = $to_date - $cur_date;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        return $daysleft;
    } else {
        return false;
    }
}

function get_running_plan()
{
    $plan_info = get_active_subs();
    $result = array();
    if (!empty($plan_info) && $plan_info->trial_period != 0) {
        $result['trial'] = trial_period();
    } elseif (!empty($plan_info) && $plan_info->trial_period == 0) {
        $result['running'] = running_period();
    }
    return $result;
}

function plan_name($id)
{
    $CI = &get_instance();
    $super_admin = super_admin();
    $subdomain = is_subdomain();
    if (!empty($subdomain) || !empty($super_admin)) {
        $CI->old_db = new_database(true, true);
        return $CI->old_db->where(array('id' => $id))->get('tbl_frontend_pricing')->row()->name;
    } else {
        return false;
    }
}

function plan_info($id)
{
    $CI = &get_instance();
    $super_admin = super_admin();
    $subdomain = is_subdomain();
    if (!empty($subdomain) || !empty($super_admin)) {
        $CI->old_db = new_database(true, true);
        return $CI->old_db->where(array('id' => $id))->get('tbl_frontend_pricing')->row();
    } else {
        return false;
    }
}

function calculate_plan_end_date($id)
{
    $CI = &get_instance();
    $super_admin = super_admin();
    $subdomain = is_subdomain();
    if (!empty($subdomain) || !empty($super_admin)) {
        $plan = $CI->db->where(array('id' => $id))->get('tbl_frontend_pricing')->row();
        if (!empty($plan)) {
            return date("Y-m-d", strtotime("+ " . $plan->interval_value . ' ' . $plan->interval_type));
        }
    } else {
        return false;
    }
}

function plan_capability($type)
{
    $active_subscription = get_active_subs();
    if (!empty($active_subscription)) {
        $plan_info = plan_info($active_subscription->pricing_id);
        if ($type == 'projects') {
            $value = $plan_info->project_no;
            $result = count(get_result('tbl_project'));
        }
        if ($type == 'user') {
            $value = $plan_info->employee_no;
            $result = count(get_result('tbl_users', array('role_id != 2')));
        }
        if ($type == 'client') {
            $value = $plan_info->client_no;
            $result = count(get_result('tbl_client'));
        }
        if ($type == 'invoice') {
            $value = $plan_info->invoice_no;
            $result = count(get_result('tbl_invoices'));
        }
        if ($type == 'leads') {
            $value = $plan_info->leads;
            $result = count(get_result('tbl_leads'));
        }
        if ($type == 'bank_account') {
            $value = $plan_info->bank_account;
            $result = count(get_result('tbl_accounts'));
        }
        if ($type == 'tasks') {
            $value = $plan_info->tasks;
            $result = count(get_result('tbl_task'));
        }
        if (!is_numeric($value)) {
            set_message('error', lang('you_can_not_add_this_is_not_included'));
            redirect('upgradePlan/' . $active_subscription->subscriptions_id);
        } elseif (is_numeric($value) && $value != 0) {
            if ($value <= $result) {
                set_message('error', lang('you_can_not_add_more_please_upgrade_the_package'));
                redirect('upgradePlan/' . $active_subscription->subscriptions_id);
            }
        } elseif (is_numeric($value) && $value == 0) {
            return true;
        }
    } else {
        return false;
    }
}

function available_plan($type)
{
    $active_subscription = get_active_subs();
    if (!empty($active_subscription)) {
        $plan_info = plan_info($active_subscription->pricing_id);

        if ($type == 'employee_no' && !is_numeric($plan_info->employee_no)) {
            return true;
        }
        if ($type == 'client_no' && !is_numeric($plan_info->client_no)) {
            return true;
        }
        if ($type == 'project_no' && !is_numeric($plan_info->project_no)) {
            return true;
        }
        if ($type == 'invoice_no' && !is_numeric($plan_info->invoice_no)) {
            return true;
        }
        if ($type == 'leads' && !is_numeric($plan_info->leads)) {
            return true;
        }
        if ($type == 'accounting' && !is_numeric($plan_info->accounting)) {
            return true;
        }
        if ($type == 'bank_account' && !is_numeric($plan_info->bank_account)) {
            return true;
        }
        if ($type == 'tasks' && !is_numeric($plan_info->tasks)) {
            return true;
        }
        if ($type == 'online_payment' && $plan_info->online_payment == 'No') {
            return true;
        }
        if ($type == 'calendar' && $plan_info->calendar == 'No') {
            return true;
        }
        if ($type == 'mailbox' && $plan_info->mailbox == 'No') {
            return true;
        }
        if ($type == 'live_chat' && $plan_info->live_chat == 'No') {
            return true;
        }
        if ($type == 'tickets' && $plan_info->tickets == 'No') {
            return true;
        }
        if ($type == 'stock_manager' && $plan_info->stock_manager == 'No') {
            return true;
        }
        if ($type == 'filemanager' && $plan_info->filemanager == 'No') {
            return true;
        }
        if ($type == 'recruitment' && $plan_info->recruitment == 'No') {
            return true;
        }
        if ($type == 'attendance' && $plan_info->attendance == 'No') {
            return true;
        }
        if ($type == 'payroll' && $plan_info->payroll == 'No') {
            return true;
        }
        if ($type == 'leave_management' && $plan_info->leave_management == 'No') {
            return true;
        }
        if ($type == 'performance' && $plan_info->performance == 'No') {
            return true;
        }
        if ($type == 'training' && $plan_info->training == 'No') {
            return true;
        }
        if ($type == 'reports' && $plan_info->reports == 'No') {
            return true;
        }
        if ($type == 'spreadsheet' && $plan_info->allow_spreadsheet == 'No') {
            return true;
        }
        if ($type == 'xero' && $plan_info->allow_xero == 'No') {
            return true;
        }
        if ($type == 'zoom' && $plan_info->allow_zoom == 'No') {
            return true;
        }
        if ($type == 'quickbooks' && $plan_info->allow_quickbooks == 'No') {
            return true;
        }
//        $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'), true);
//        if (!empty($all_module)) {
//            foreach ($all_module as $v_module) {
//                $name = 'allow_' . $v_module->module_name;
//                if ($type == $v_module->module_name && $plan_info->$name == 'No') {
//                    return true;
//                }
//            }
//        }
        
    } else {
        return false;
    }
}

function setup_database($domain)
{
    $CI = &get_instance();
    $subs_info = $CI->db->where(array('domain' => $domain, 'status !=' => 'pending'))->get('tbl_subscriptions')->row();
    if (!empty($subs_info)) {
        return new_database($subs_info);
    }
}

function is_domain_available($domain)
{
    $CI = &get_instance();
    $subs_info = $CI->db->where(array('domain' => $domain))->get('tbl_subscriptions')->row();
    if (empty($subs_info)) {
        return false;
    } else {
        return true;
    }
}


function new_database($subscription = null, $default_database = null)
{

    $CI = &get_instance();


    $config_db = $CI->config->config['config_db'];
    if (!empty($default_database)) {
        $database_name = config_item('default_database');
        $database_exist = true;
    } else {
        $database_info = get_row('tbl_subscriptions_database', array('id' => $subscription->db_id));
        $database_name = $database_info->database_name;
        $CI->load->dbutil();
        $database_exist = $CI->dbutil->database_exists($database_name);
    }
    $config_db['database'] = $database_name; /*cambiamos de db*/
  
    if ($database_exist) {
        $CI->new_db = $CI->load->database($config_db, true);
        return $CI->new_db;
    }
    return false;
}

/**
 * Return single setting passed by name
 * @param mixed $name Option name
 * @return string
 */

function getDescription($input_post, $str = null)
{

    if (is_array($input_post)) {
        $input_data = (object)$input_post;
    } else {
        $input_data = $input_post;
    }

    $plan_info = plan_info($input_data->pricing_id);
    if (!empty($plan_info)) {
        $currency = get_old_data('tbl_currencies', array('code' => $input_data->currency));
        if ($input_data->interval_type == 'monthly') {
            $frequency = lang('mo');
        } else {
            $frequency = lang('yr');
        }
        if (!empty($str)) {
            $plan_name = lang('for') . ' ' . lang('plan') . ' :' . $plan_info->name . ' ' . display_money($input_data->total, $currency->symbol) . ' /' . $frequency;
        } else {
            $plan_name = '<a data-bs-toggle="modal" data-bs-target="#myModal" href="' . base_url('admin/global_controller/subs_package_details/' . $plan_info->id) . '">' . lang('for') . ' ' . lang('plan') . ' :' . $plan_info->name . ' ' . display_money($input_data->total, $currency->symbol) . ' /' . $frequency . ' ' . '</a>';
        }
    } else {
        $plan_name = '-';
    }
    return $plan_name;
}

/**
 * Return single setting passed by name
 * @param mixed $name Option name
 * @return string
 */
function getConfigItems($name)
{
    $config_old_data = get_old_result('tbl_config');
    foreach ($config_old_data as $v_old_config) {
        if ($v_old_config->config_key == $name) {
            return $v_old_config->value;
        }
    }
}

// ip_in_range
// This function takes 2 arguments, an IP address and a "range" in several
// different formats.
// Network ranges can be specified as:
// 1. Wildcard format:     1.2.3.*
// 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
// 3. Start-End IP format: 1.2.3.0-1.2.3.255
// The function will return true if the supplied IP is within the range.
// Note little validation is done on the range inputs - it expects you to
// use one of the above 3 formats.
function iPINRange($ip, $range)
{
    if (strpos($range, '/') !== false) {
        // $range is in IP/NETMASK format
        list($range, $netmask) = explode('/', $range, 2);
        if (strpos($netmask, '.') !== false) {
            // $netmask is a 255.255.0.0 format
            $netmask = str_replace('*', '0', $netmask);
            $netmask_dec = ip2long($netmask);

            return ((ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec));
        } else {
            // $netmask is a CIDR size block
            // fix the range argument
            $x = explode('.', $range);
            while (count($x) < 4) {
                $x[] = '0';
            }
            list($a, $b, $c, $d) = $x;
            $range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
            $range_dec = ip2long($range);
            $ip_dec = ip2long($ip);

            # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
            #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

            # Strategy 2 - Use math to create it
            $wildcard_dec = pow(2, (32 - $netmask)) - 1;
            $netmask_dec = ~$wildcard_dec;

            return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
        }
    } else {
        // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
        if (strpos($range, '*') !== false) { // a.b.*.* format
            // Just convert to A-B format by setting * to 0 for A and 255 for B
            $lower = str_replace('*', '0', $range);
            $upper = str_replace('*', '255', $range);
            $range = "$lower-$upper";
        }

        if (strpos($range, '-') !== false) { // A-B format
            list($lower, $upper) = explode('-', $range, 2);
            $lower_dec = (float)sprintf("%u", ip2long($lower));
            $upper_dec = (float)sprintf("%u", ip2long($upper));
            $ip_dec = (float)sprintf("%u", ip2long($ip));

            return (($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec));
        }
        echo 'Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format';
        return false;
    }
}

/**
 * Generate md5 hash
 * @return string
 */
function app_generate_hash()
{
    return md5(rand() . microtime() . time() . uniqid());
}


function get_menu($where, $companies_id = null)
{
    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->from('tbl_menu');
    $CI->db->order_by('sort', 'ASC');
    if (empty($companies_id)) {
        $CI->db->where($where);
        //        $CI->db->or_where('companies_id', 0);
    } else {
        $CI->db->where($where);
    }
    $query_result = $CI->db->get();
    $result = $query_result->result();
    return $result;
}

/**
 * Function that strip all html tags from string/text/html
 * @param string $str
 * @param string $allowed prevent specific tags to be stripped
 * @return string
 */
function strip_html_tags($str, $allowed = '')
{
    if (!empty($allowed) && $allowed == 1) {
        $allowed = "<p>,<br>,<strong>";
    }
    $str = preg_replace('/(<|>)\1{2}/is', '', $str);
    $str = preg_replace(array(
        // Remove invisible content
        '@<head[^>]*?>.*?</head>@siu',
        '@<style[^>]*?>.*?</style>@siu',
        '@<script[^>]*?.*?</script>@siu',
        '@<object[^>]*?.*?</object>@siu',
        '@<embed[^>]*?.*?</embed>@siu',
        '@<applet[^>]*?.*?</applet>@siu',
        '@<noframes[^>]*?.*?</noframes>@siu',
        '@<noscript[^>]*?.*?</noscript>@siu',
        '@<noembed[^>]*?.*?</noembed>@siu',
        // Add line breaks before and after blocks
        '@</?((address)|(blockquote)|(center)|(del))@iu',
        '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
        '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
        '@</?((table)|(th)|(td)|(caption))@iu',
        '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
        '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
        '@</?((frameset)|(frame)|(iframe))@iu'
    ), array(
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0"
    ), $str);

    $str = strip_tags($str, $allowed);

    // Remove on events from attributes
    $re = '/\bon[a-z]+\s*=\s*(?:([\'"]).+?\1|(?:\S+?\(.*?\)(?=[\s>])))/i';
    $str = preg_replace($re, '', $str);

    return $str;
}

function itemsInfo($saved_items_id)
{

    $items_info = get_row('tbl_saved_items', array('saved_items_id' => $saved_items_id));
    return $items_info;
}

function itemsName($saved_items_id)
{
    $items_info = get_row('tbl_saved_items', array('saved_items_id' => $saved_items_id));
    if (!empty($items_info)) {
        return $items_info->item_name;
    } else {
        return lang('undefined_items');
    }
}

if (!function_exists('is_html')) {
    function is_html($string)
    {
        return preg_match("/<[^<]+>/", $string, $m) != 0;
    }
}

function attendance_access()
{
    $CI = &get_instance();
    $IP = $CI->input->ip_address();
    $only_allowed_ip_can_clock = config_item('only_allowed_ip_can_clock');
    if ($only_allowed_ip_can_clock == 'TRUE') {
        $IP_info = $CI->db->where(array('allowed_ip' => $IP))->get('tbl_allowed_ip')->row();
        if (!empty($IP_info)) {
            if ($IP_info->status == 'active') {
                return true;
            }
        } else {

            $CI->load->model('settings_model');
            $CI->settings_model->_table_name = 'tbl_allowed_ip';
            $CI->settings_model->_primary_key = 'allowed_ip_id';
            // input data
            $cate_data['allowed_ip'] = $IP;
            $cate_data['status'] = 'pending';
            $allowed_ip_id = null;

            // check check_allowed_ip by where
            // if not empty show alert message else save data
            $check_allowed_ip = $CI->settings_model->check_update('tbl_allowed_ip', $where = array('allowed_ip' => $cate_data['allowed_ip']), $allowed_ip_id);
            if (empty($check_allowed_ip)) { // if input data already exist show error alert
                $id = $CI->settings_model->save($cate_data);

                send_clock_email('trying_clock_email');

                $activity = array(
                    'user' => $CI->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_added_a_allowed_ip'),
                    'value1' => $cate_data['allowed_ip']
                );
                $CI->settings_model->_table_name = 'tbl_activities';
                $CI->settings_model->_primary_key = 'activities_id';
                $CI->settings_model->save($activity);
            }
            return false;
        }
    } else {
        return true;
    }
}

function send_clock_email($type)
{
    if (!empty(config_item('send_clock_email'))) {
        $CI = &get_instance();
        $IP = $CI->input->ip_address();
        $CI->load->model('settings_model');
        $email_template = get_row('tbl_email_templates', array('email_group' => $type));
        $message = $email_template->template_body;
        $staff_info = profile();
        $subject = str_replace("{NAME}", $staff_info->fullname . '(' . $staff_info->employment_id . ')', $email_template->subject);
        $name = str_replace("{NAME}", $staff_info->fullname . '(' . $staff_info->employment_id . ')', $message);
        $allowed_ip = str_replace("{IP}", $IP, $name);
        $time = str_replace("{TIME}", display_datetime(date('Y-m-d H:i:s')), $allowed_ip);
        if ($type == 'trying_clock_email') {
            $url = 'admin/attendance/time_history';
        } else {
            $url = 'admin/attendance/time_history';
        }
        $site_url = str_replace("{URL}", base_url($url), $time);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $site_url);

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';
        $all_admin = all_admin();
        if (!empty($all_admin)) {
            foreach ($all_admin as $v_user) {
                $params['recipient'] = $v_user->email;
                $CI->settings_model->send_email($params);
            }
        }
        return true;
    }
    return true;
}

function all_admin()
{
    $CI = &get_instance();
    $all_admin = $CI->db->where('role_id', 1)->get('tbl_users')->result();
    if (!empty($all_admin)) {
        return $all_admin;
    } else {
        return false;
    }
}

function get_admin_number()
{
    $CI = &get_instance();
    $admin = $CI->db
        ->where("tbl_users.role_id", 1)
        ->join("tbl_account_details", "tbl_account_details.user_id = tbl_users.user_id")
        ->get("tbl_users")->row();
    if (!empty($admin)) {
        return $admin->mobile;
    } else {
        return false;
    }
}
function leave_days_hours($days)
{
    $office_hours = config_item('office_hours');

    $hours = $days  * $office_hours;
    $hours_r = $hours  % $office_hours;
    if ($hours_r > 0) {
        $days = (int) ($hours  / $office_hours);

        $days = $days . ' d ' .  $hours_r . ' h ';
    }
    return $days;
}

function renew_date($type)
{
    $create_date = date('Y-m-d H:i:s');
    $endDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $create_date);
    if ($type == 'monthly') {
        $ctype = 'month';
    } else if ($type == 'quarterly') {
        $current_quarter = ceil(date('n') / 3);
        $renew_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
    } else {
        $ctype = 'year';
    }
    $endDatetime->modify("+ 1" . $ctype);
    $renew_date = $endDatetime->format('Y-m-d');
    return $renew_date;
}

function get_currencywise_price($frontend = null, $currency_price = null, $pricing_id = null)
{
    $CI = &get_instance();
    if (!empty($currency_price)) {
        $currencywise_price = $currency_price;
    } else {
        $currencywise_price = $CI->input->post('currencywise_price', true);
        if (empty($currencywise_price)) {
            $currencywise_price = config_item('default_currency');
        }
    }
    if (!empty($pricing_id)) {
        return get_old_data('tbl_currencywise_price', array('frontend_pricing_id' => $pricing_id));
    } else {
        $CI->old_db = new_database(true, true);
        $all_pricing = $CI->old_db->where('currency', $currencywise_price)->group_by('frontend_pricing_id')->get('tbl_currencywise_price')->result();

        if (empty($all_pricing)) {
            $currencywise_price = get_old_data('tbl_config', array('config_key' => 'default_currency'))->value;
            $all_pricing = $CI->old_db->where('currency', $currencywise_price)->group_by('frontend_pricing_id')->get('tbl_currencywise_price')->result();
        }
        $result = array();

        if (!empty($all_pricing)) {
            $currency = get_old_data('tbl_currencies', array('code' => $all_pricing[0]->currency));
            foreach ($all_pricing as $v_price) {
                $pricing_info = get_old_data('tbl_frontend_pricing', array('id' => $v_price->frontend_pricing_id));
                $v_price->name = isset($pricing_info->name)?$pricing_info->name:'';
                $v_price->currency = isset($currency->symbol)?$currency->symbol:'';
                array_push($result, $v_price);
            }
        }

        if (!empty($frontend)) {
            return $result;
        } else {
            echo json_encode($result);
            exit();
        }
    }
}

function module_dirPath($module, $concat = '')
{
    return MODULES_PATH . $module . '/' . $concat;
}

function module_direcoty($module, $concat = '')
{
    return 'modules/' . $module . '/' . $concat;
}

function module_dirURL($module, $segment = '')
{
    return site_url(basename(MODULES_PATH) . '/' . $module . '/' . ltrim($segment, '/'));
}

function module_languagesFiles($module, $languages = [])
{
    
    if (is_null($languages) || count($languages) === 0) {
        $languages = [$module];
    }
    
    foreach ($languages as $language) {
        $CI = &get_instance();
        
        $path = MODULES_PATH . $module . '/language/' . $language . '/';
        
        //        foreach ($languages as $file_name) {
        $file_path = $path . $language . '_lang' . '.php';
        if (file_exists($file_path)) {
            $CI->lang->load($module . '/' . $language, $language);
        } elseif ($language != 'english' && !file_exists($file_path)) {
            $CI->lang->load($module . '/' . $language, 'english');
        }
        //        }
    };
}

function is_active_module($module)
{
    $active = get_any_field_old('tbl_modules', array('module_name' => $module), 'active');
    if (!empty($active) && $active == 1) {
        return true;
    } else {
        set_message('error', 'you need to install/active the ' . $module . '  module to run');
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('login');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}

function activity_log($data)
{
    $CI = &get_instance();
    $activity = array(
        'user' => $CI->session->userdata('user_id'),
        'module' => (!empty($data['module']) ? $data['module'] : '-'),
        'module_field_id' => (!empty($data['id']) ? $data['id'] : ''),
        'activity' => (!empty($data['activity']) ? $data['activity'] : '-'),
        'icon' => (!empty($data['icon']) ? $data['icon'] : ''),
        'link' => (!empty($data['url']) ? $data['url'] : ''),
        'value1' => (!empty($data['value1']) ? $data['value1'] : ''),
        'value2' => (!empty($data['value2']) ? $data['value2'] : '')
    );
    $CI->db->insert('tbl_activities', $activity);
}

function make_datatables($where = null, $where_in = null)
{
    $CI = &get_instance();
    $CI->load->model('datatables');
    $CI->datatables->make_query();
    
    if (!empty($where)) {
        $CI->db->where($where);
    }
    if (!empty($where_in)) {
        $CI->db->where_in($where_in[0], $where_in[1]);
    }
    if ($_POST["length"] != -1) {
        $CI->db->limit($_POST['length'], $_POST['start']);
    }
    $query = $CI->db->get();
    return $query->result();
}

function render_table($data, $where = null, $where_in = null)
{
    
    $CI = &get_instance();
    $CI->load->model('datatables');
    $output = array(
        "draw" => intval($_POST["draw"]),
        "recordsTotal" => $CI->datatables->get_all_data($where, $where_in),
        "recordsFiltered" => $CI->datatables->get_filtered_data($where, $where_in),
        "data" => $data
    );
    echo json_encode($output);
    exit();
}
 function is_any_version_update()
{
   $CI = &get_instance();
   $CI->old_db = new_database(true, true);
   $update_record = $CI->old_db->order_by('id','DESC')->get('tbl_features_version')->row_array();
   $log_records = $CI->db->order_by('id','DESC')->get('tbl_features_updated_logs')->row_array();
   if($log_records['version']!=$update_record['version']){
    $CI->db->insert('tbl_features_updated_logs',['version'=>$update_record['version'], 'is_read'=>1, 'created_at'=>date('Y-m-d H:i:s')]);
    return $update_record; 
   }
   return false;
}

function convert_to_html($body)
{
    $CI = &get_instance();
    $CI->load->library('security');
    $body = trim($body);
    $body = str_replace('&nbsp;', ' ', $body);
    $body = trim(strip_html_tags($body, '<br/>, <br>, <a>'));
    $body = $CI->security->xss_clean($body);
    $body = preg_replace("/[\r\n]+/", "\n", $body);
    $body = preg_replace('/\n(\s*\n)+/', '<br />', $body);
    $body = preg_replace('/\n/', '<br>', $body);
    
    return $body;
}

function userDocroomInfo($id = null)
{
    $CI = &get_instance();
    if (empty($id)) {
        $id = $CI->session->userdata('user_id');
    }
   
    $host=$_SERVER['SERVER_NAME'];

    if (!empty($id)) {
        return $CI->db
            ->where("tbl_user_docroom.user_id", $id)
            ->where("tbl_user_docroom.docroom_folderName", $host)
            ->join("tbl_users", "tbl_users.user_id = tbl_user_docroom.user_id")
            ->get("tbl_user_docroom")->row();
    } else {
        return false;
    }
}

function convertToReadableSize($size){
  $base = log($size) / log(1024);
  $suffix = array(" ", " KB", " MB", " GB", " TB");
  $f_base = floor($base);
  if($size>0){
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
  }else{
    return '';
  }
}

function getUnlayerTempateList()
{
    $url = "https://api.unlayer.com/v1/templates/";

    $headers = [
    'Accept : application/json',
    'Authorization : Basic ZVY3MDRuelFaZHFZUVVoMkdyRUU1UVJKVTFMWjR1VGZ6Q0Z6VndSNnFHaUZiM1pYVGZ4R2NCQVQ5S01LWVJ5TDo='
];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($curl);
    curl_close($curl);
     $res = json_decode($res, true);
     // var_dump($res); die;
     if( isset($res['data'])){
        return $res['data'];
     }
      return false;
    //reCaptcha success check
}
  function lang($line, $label = '', $attributes = array())
    {

        $CI = &get_instance();
        $CI->load->library('encoding_lib');
        if (is_array($label) && count($label) > 0) {
            $_line = vsprintf($CI->lang->line(trim($line), $attributes), $label);
        } else {
            $_line = @sprintf($CI->lang->line(trim($line), $attributes), $label);
        }
        $arr = get_industry_lang();

        $_line  = strtr($_line,$arr);
        // var_dump($_line);die;
        if ($_line != '') {
            if (preg_match('/"/', $_line) && !is_html($_line)) {
                $_line = html_escape($_line);
            }
            return ForceUTF8\Encoding::toUTF8($_line);
        }

        if (mb_strpos($line, '_db_') !== false) {
            return 'db_translate_not_found';
        }

        return ForceUTF8\Encoding::toUTF8($line);
    }
    function get_industry_lang(){
         $CI = &get_instance();
         $return_array = [];
         $industry_type = '';
         if(is_subdomain()){
            $subscription = get_active_subs();
            $industry_type = $subscription->industry_type;
         }
         $language = $CI->session->userdata('lang');
             $CI->old_db = new_database(true, true);

          $result =  $CI->old_db
            ->where("language", $language)->where("industry_type", $industry_type)->get("tbl_industry_lang")->result_array();
            // var_dump($result);die();
            if($result){
                foreach ($result as $key => $lang) {
                   $return_array[$lang['word']]=$lang['translation'];
                   $return_array[strtolower($lang['word'])]=strtolower($lang['translation']);
                   $return_array[ucfirst($lang['word'])]=ucfirst($lang['translation']);
                   $return_array[strtoupper($lang['word'])]=strtoupper($lang['translation']);
                }
            }
            return $return_array;
    }
    function get_industries(){
        return [    "Trades",
        "Health Care",
        "Hospitality",
        "Real Estate",
        "Recruitment",
        "Accommodation and Tourism",
        "Agriculture & rural",
        "Training & education",
        "Financial services",
        "Insurance services",
        "Manufacturing",
        "Wholesale and Import",
        "Business Broker",
        "Franchisor",
        "Retail",
        "Transport",
        "Warehousing",
        "Automotive"];
    }
    function get_industry_text_translations($where){
         $CI = &get_instance();
         $result =  $CI->db
            ->where($where)->get("tbl_industry_lang")->result_array();
         return $result;
    }
    function get_all_subscriptions()
{
    $CI = &get_instance();
    $CI->db->select('tbl_subscriptions.*', FALSE);
    $CI->db->select('tbl_frontend_pricing.name as plan_name', FALSE);
    // $CI->db->select('tbl_currencies.name as currency_name,tbl_currencies.symbol', FALSE);
    $CI->db->select('tbl_users.email as creator_email,tbl_users.username as creator_username', FALSE);
    $CI->db->from('tbl_subscriptions');
    $CI->db->join('tbl_frontend_pricing', 'tbl_frontend_pricing.id = tbl_subscriptions.pricing_id', 'left');
    // $CI->db->join('tbl_currencies', 'tbl_currencies.code = tbl_subscriptions.currency', 'left');
    $CI->db->join('tbl_users', 'tbl_users.user_id = tbl_subscriptions.created_by', 'left');
   
    $query_result = $CI->db->get();
    return $query_result->result();
}
 function get_cache_data($where, $table)
{
     $CI = &get_instance();
    $CI->db->cache_on();
   $result =  $CI->db->where($where)->get($table)->row();
    $CI->db->cache_off();
    return $result;


}
function get_lising_connected_email()
{
    $CI = &get_instance();
      $email =   $CI->db->select('email')->where('is_listing_connected', 1)->order_by('user_id','DESC')->get('tbl_users')->row()->email;
      if(!$email){
           $email =   $CI->db->select('email')->where('super_admin', 'owner')->order_by('user_id','DESC')->get('tbl_users')->row()->email;

      }
      return $email;
      
}
