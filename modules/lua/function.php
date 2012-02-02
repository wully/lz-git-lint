<?php
class lua_function extends Lint{
    public function check_row($rs,$pre_rs,$post_rs){
        if(preg_match("#function[ \t][ \t]*([^\( \t]*)#msi",$rs,$match)){
                $word = trim($match[1]);
                if(!preg_match("#^[a-z0-9_]*$#",$word)){
                    $info = "函数名称：$word 不合法";
                    $this -> print_error($info);
                    return false;
                }
            }
            return true;
    }
    public function check_contents($contents){

    }


}



?>
