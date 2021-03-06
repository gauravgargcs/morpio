<?php


class Breadcrumbs
{

    public function build_breadcrumbs()
    {

        $CI = &get_instance();
        $id = $CI->session->userdata('menu_active_id');
        $breadcrumbs = "";
        if (!empty($id)) {
            $menu_id = array_reverse($id);
            foreach ($menu_id as $v_id) {
                if ($v_id == 109) {
                    $v_id = null;
                }
                $menu = $CI->db->where('menu_id', $v_id)->get('tbl_menu')->row();
                if (!empty($menu)) {
                    $breadcrumbs = "<a class='text-muted' href='" . base_url() . $menu->link . "'>" . lang($menu->label) . "</a>\n";
                }
            }
        }
        if (empty($breadcrumbs)) {
            $url_1 = $CI->uri->segment(1);
            $url_2 = $CI->uri->segment(2);
            $url_3 = $CI->uri->segment(3);
            if (empty($url_3)) {
                $breadcrumbs = lang($url_2);
            }  else if (empty($url_2)) {
                $breadcrumbs = lang($url_1);
            } else {
                $breadcrumbs = lang($url_3);
            }
        }
        return $breadcrumbs;
    }

}