<?php
class cpp_lint extends Lint {

    public function check_row ($rs, $pre_rs, $post_rs) {

    }

    public function check_contents ($contents) {

        $ex = $this -> libs_dir . '/cpplint.py';
        $cmd = "python " . $ex . " "  . $this -> path;
        $array = array();
        $ret = 0;
        exec($cmd, $array, $ret);
        echo "\n Cpp Syntax Check Result is:\n**********\n" . ($ret === 0 ? "* Passed *" : '* Failed *');
	echo "\n**********\n";
        if($ret !== 0){
            return false;
        }
        return true;
    }
}
?>
