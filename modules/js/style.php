<?php
class js_style extends Lint {

    public function check_row ($rs, $pre_rs, $post_rs) {

        if ($this -> no === 1) {
            if (stripos($rs, '/*') !== 0) {
                $info = '请在JS文件第一行添加jsdoc注释！';
                $this -> print_error($info);
                return false;
            }
        }

        if (preg_match('#([ \t]$)#msi', $rs, $matchs)) {
            if (! empty($matchs[1])) {
                $info = '行尾不能有空格！';
                $this -> print_error($info);
                return false;
            }
        }

        if (preg_match('#^\s*var\s+([\w_\$]+)#msi', $rs, $matchs)) {
            if (strpos($matchs[1], '_') > 0) {
                $info = '变量名请使用驼峰式命名';
                $this -> print_error($info);
                return false;
            }
        }

        if (preg_match('#^\s*function\s+([\w_\$]+)#msi', $rs, $matchs)) {
            if (strpos($matchs[1], '_') > 0) {
                $info = '变量名请使用驼峰式命名';
                $this -> print_error($info);
                return false;
            }
        }
    }
    public function check_contents ($contents) {
        var_dump($contents);

    }
}
?>
