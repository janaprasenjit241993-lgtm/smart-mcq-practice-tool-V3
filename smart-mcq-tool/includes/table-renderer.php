<?php

class SMPP_Table_Renderer {
    public function render($rows) {
        $html = '<table>';

        foreach ($rows as $r) {
            $html .= '<tr><td>' . esc_html($r[0] ?? '') . '</td><td>' . esc_html($r[1] ?? '') . '</td></tr>';
        }

        $html .= '</table>';

        return $html;
    }
}
