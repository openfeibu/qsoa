<?php
function my_dir($dir,$filename) {
    $files = array();
    if(@$handle = opendir($dir)) { //注意这里要加一个@，不然会有warning错误提示：）
        while(($file = readdir($handle)) !== false) {
            if($file != ".." && $file != ".") { //排除根目录；
                if(is_dir($dir."/".$file)) { //如果是子文件夹，就进行递归
                    $files[$file] = my_dir($dir."/".$file,$filename);
                } else { //不然就将文件的名字存入数组；
                    $suffix = substr(strrchr($file, '.'), 1);
                    if($suffix == 'php'){
                        $files[] = $file;

                        $myfile = fopen($dir."/".$file, "r");
                        $str = fread($myfile,filesize($dir."/".$file));
                        fclose($myfile);

                        //$str = php_strip_whitespace($dir."/".$file);
                        //$str = str_replace("\r\n","<br />",$str);
                        $str = preg_replace("/\s+\r/is", "\n", $str);//回车符是\r
                        $str = preg_replace("/\s+\r\n/is", "\n", $str);//回车符是\r\n
                        $str = preg_replace("/\s+\n/is", "\n", $str);//回车符是\n
                        //$str = str_replace("\n",'<br />',$str);
                        //$str = compress_html($str);

                        $fh = fopen($filename, "a");
                        echo fwrite($fh, $str);


                    }
                }

            }
        }
        closedir($handle);
        return $files;
    }
}
function compress_html($string) {

    //return $string = preg_replace("/\s+\r/","",$string);
    $pattern = array(
        "/> *([^ ]*) *</", //去掉注释标记
        "/[\s]+/",
        "/<!--[^!]*-->/",
        "/\" /",
        "/ \"/",
        "'/\*[^*]*\*/'"
    );
    $replace = array(
        ">\\1<",
        " ",
        "",
        "\"",
        "\"",
        ""
    );
    return preg_replace($pattern, $replace, $string);

}
print_r(my_dir('/home/vagrant/Code/qsoa/app/Repositories/Eloquent','/home/vagrant/Code/qsoa_airline.txt'));exit;
// file_put_contents('测试接口.txt',my_dir('/home/vagrant/Code/eolinker_os/server'));
//print_r(my_dir('/home/vagrant/Code/32resources/app'));
