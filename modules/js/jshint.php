<?php
class js_jshint extends Lint {

    public function check_row ($rs, $pre_rs, $post_rs) {

    }

    public function check_contents ($contents) {

        $ex = 'export PATH=~/bin:/home/lz/node/bin:$PATH';
        $cmd = $ex . "\n" .'jshint ' . $this -> path;
        echo "\nshell script is: \n" . $cmd;
        $array = array();
        $ret = 0;
        exec($cmd, $array, $ret);
        echo "\nJavaSript Syntax Check Result is:\n" . ($ret === 0 ? "Passed" : 'Failed');
        if($ret !== 0){
            $info = "\nJavaSript Syntax Check Infos are:\n" . implode("\n", $array);
            $this -> print_error($info);
            return false;
        }
        return true;
    }
}
?>
