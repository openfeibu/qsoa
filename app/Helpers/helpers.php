<?php

use Illuminate\Support\Facades\Request;
use App\Facades\Hashids;
use App\Facades\Trans;

if (!function_exists('hashids_encode')) {
    /**
     * Encode the given id.
     *
     * @param int/array $id
     *
     * @return string
     */
    function hashids_encode($idorarray)
    {
        return Hashids::encode($idorarray);
    }

}

if (!function_exists('hashids_decode')) {
    /**
     * Decode the given value.
     *
     * @param string $value
     *
     * @return array / int
     */
    function hashids_decode($value)
    {
        $return = Hashids::decode($value);

        if (empty($return)) {
            return null;
        }

        if (count($return) == 1) {
            return $return[0];
        }

        return $return;
    }

}

if (!function_exists('folder_new')) {
    /**
     * Get new upload folder pathes.
     *
     * @param string $prefix
     * @param string $sufix
     *
     * @return array
     */
    function folder_new($prefix = null, $sufix = null)
    {
        $folder        = date('Y/m/d/His') . rand(100, 999);
        return $folder;
    }
}

if (!function_exists('blade_compile')) {
    /**
     * Get new upload folder pathes.
     *
     * @param string $prefix
     * @param string $sufix
     *
     * @return array
     */
    function blade_compile($string, array $args = [])
    {
        $compiled = \Blade::compileString($string);
        ob_start() and extract($args, EXTR_SKIP);

        // We'll include the view contents for parsing within a catcher

        // so we can avoid any WSOD errors. If an exception occurs we
        // will throw it out to the exception handler.
        try
        {
            eval('?>' . $compiled);
        }

            // If we caught an exception, we'll silently flush the output

            // buffer so that no partially rendered views get thrown out
            // to the client and confuse the user with junk.
        catch (\Exception $e) {
            ob_get_clean();throw $e;
        }

        $content = ob_get_clean();
        $content = str_replace(['@param  ', '@return  ', '@var  ', '@throws  '], ['@param ', '@return ', '@var ', '@throws '], $content);

        return $content;

    }

}


if (!function_exists('trans_url')) {
    /**
     * Get translated url.
     *
     * @param string $url
     *
     * @return string
     */
    function trans_url($url)
    {
        return Trans::to($url);
    }

}

if (!function_exists('trans_dir')) {
    /**
     * Return the direction of current language.
     *
     * @return string (ltr|rtl)
     *
     */
    function trans_dir()
    {
        return Trans::getCurrentTransDirection();
    }

}

if (!function_exists('trans_setlocale')) {
    /**
     * Set local for the translation
     *
     * @param string $locale
     *
     * @return string
     */
    function trans_setlocale($locale = null)
    {
        return Trans::setLocale($locale);
    }

}

if (!function_exists('checkbox_array')) {
    /**
     * Convert array to use in form check box
     *
     * @param array $array
     * @param string $name
     * @param array $options
     *
     * @return array
     */
    function checkbox_array(array $array, $name, $options = [])
    {
        $return = [];

        foreach ($array as $key => $val) {
            $return[$val] = array_merge(['name' => "{$name}[{$key}]"], $options);
        }

        return $return;
    }

}

if (!function_exists('pager_array')) {
    /**
     * Return request values to be used in paginator
     *
     * @return array
     */
    function pager_array()
    {

        return Request::only(
            config('database.criteria.params.search', 'search'),
            config('database.criteria.params.searchFields', 'searchFields'),
            config('database.criteria.params.columns', 'columns'),
            config('database.criteria.params.sortBy', 'sortBy'),
            config('database.criteria.params.orderBy', 'orderBy'),
            config('database.criteria.params.with', 'with')
        );
    }

}

if (!function_exists('user_type')) {
    /**
     * Get user id.
     *
     * @param string $guard
     *
     * @return int
     */
    function user_type($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        $provider = config("auth.guards." . $guard . ".provider", 'users');
        return config("auth.providers.$provider.model", App\User::class);
    }

}

if (!function_exists('user_id')) {
    /**
     * Get user id.
     *
     * @param string $guard
     *
     * @return int
     */
    function user_id($guard = null)
    {

        $guard = is_null($guard) ? getenv('guard') : $guard;

        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user()->id;
        }
        return null;
    }

}

if (!function_exists('get_guard')) {
    /**
     * Return thr property of the guard for current request.
     *
     * @param string $property
     *
     * @return mixed
     */
    function get_guard($property = 'guard')
    {
        switch ($property) {
            case 'url':
                return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
                break;
            case 'route':
                return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
                break;
            case 'model':
                $provider = config("auth.guards." . getenv('guard') . ".provider", 'users');
                return config("auth.providers.$provider.model", App\User::class);
                break;
            default:
                return getenv('guard');
        }
    }

}

if (!function_exists('guard_url')) {
    /**
     * Return thr property of the guard for current request.
     *
     * @param string $property
     *
     * @return mixed
     */
    function guard_url($url, $translate = true)
    {
        $prefix = empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
        if ($translate){
            return trans_url($prefix . '/' . $url);
        }
        return $prefix . '/' . $url;
    }

}
if (!function_exists('guard_prefix')) {
    function guard_prefix()
    {
        return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
    }
}

if (!function_exists('set_route_guard')) {
    /**
     * Set local for the translation
     *
     * @param string $locale
     *
     * @return string
     */
    function set_route_guard($sub = 'web', $guard=null,$theme=null)
    {
        $i = ($sub == 'web') ? 1 : 2;
        $theme ? set_theme($theme) : '';
        //check whether guard is the first parameter of the route
        $guard = is_null($guard) ? request()->segment($i) : $guard;
        if (!empty(config("auth.guards.$guard"))){
            putenv("guard={$guard}.{$sub}");
            app('auth')->shouldUse("{$guard}.{$sub}");
            return $guard;
        }

        //check whether guard is the second parameter of the route
        $guard = is_null($guard) ? request()->segment(++$i) : $guard;
        if (!empty(config("auth.guards.$guard"))){
            putenv("guard={$guard}.{$sub}");
            app('auth')->shouldUse("{$guard}.{$sub}");
            return $guard;
        }

        putenv("guard=client.{$sub}");
        app('auth')->shouldUse("client.{$sub}");
        return $sub;
    }

}
if(!function_exists('set_theme'))
{
    function set_theme($theme = '')
    {
        if(!empty($theme))
        {
            putenv("theme={$theme}");
        }
    }
}


if (!function_exists('users')) {
    /**
     * Get upload folder.
     *
     * @param string $folder
     *
     * @return string
     */
    function users($property, $guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;

        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user()->$property;
        }
        return null;
    }

}

if (!function_exists('user')) {
    /**
     * Return the user model
     * @param type|null $guard
     * @return type
     */
    function user($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user();
        }

        return null;
    }

}

if (!function_exists('user_check')) {
    /**
     * Check whether user is logged in
     * @param type|null $guard
     * @return type
     */
    function user_check($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        return Auth::guard($guard)->check();
    }

}

if (!function_exists('format_date')) {
    /**
     * Format date
     *
     * @param string $date
     * @param string $format
     *
     * @return date
     */
    function format_date($date, $format = 'd M Y')
    {
        if (empty($date)) return null;
        return date($format, strtotime($date));
    }

}

if (!function_exists('format_date_time')) {
    /**
     * Format datetime
     *
     * @param date $datetime
     * @param string $format
     *
     * @return datetime
     */
    function format_date_time($datetime, $format = 'd M Y h:i A')
    {
        return date($format, strtotime($datetime));
    }

}

if (!function_exists('format_time')) {
    /**
     * Format time.
     *
     * @param string $time
     * @param string $format
     *
     * @return time
     */
    function format_time($time, $format = 'h:i A')
    {
        return date($format, strtotime($time));
    }

}
if (!function_exists('theme_asset')) {
    /**
     * Get translated url.
     *
     * @param string $url
     *
     * @return string
     */
    function theme_asset($file)
    {
        return app('theme')->asset()->url($file);
    }
}
if (!function_exists('replace_image_url')) {
    function replace_image_url($content,$url)
    {
        if($url)
        {
            preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", $content, $matches);
            $img = "";
            if(!empty($matches)) {
                $img = $matches[2];
            }
            if(!empty($img))
            {
                $patterns= array();
                $replacements = array();
                foreach($img as $imgItem){
                    if(strpos($imgItem,'http') === false)
                    {
                        $final_imgUrl = $url.$imgItem;
                        $replacements[] = $final_imgUrl;
                        $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
                        $patterns[] = $img_new;
                    }
                }
                ksort($patterns);
                ksort($replacements);
                $vote_content = preg_replace($patterns, $replacements, $content);
                return $vote_content;
            } else {
                return $content;
            }
        } else {
            return $content;
        }
    }
}
if (!function_exists('get_substr')) {
    function get_substr($str, $len = 12, $dot = true)
    {
        $i = 0;
        $l = 0;
        $c = 0;
        $a = array();
        while ($l < $len) {
            $t = substr($str, $i, 1);
            if (ord($t) >= 224) {
                $c = 3;
                $t = substr($str, $i, $c);
                $l += 2;
            } elseif (ord($t) >= 192) {
                $c = 2;
                $t = substr($str, $i, $c);
                $l += 2;
            } else {
                $c = 1;
                $l++;
            }
            $i += $c;
            if ($l > $len) break;
            $a[] = $t;
        }
        $re = implode('', $a);
        if (substr($str, $i, 1) !== false) {
            array_pop($a);
            ($c == 1) and array_pop($a);
            $re = implode('', $a);
            $dot and $re .= '...';
        }
        return $re;
    }
}
if (!function_exists('handle_image_url')) {
    function handle_image_url($image_url = '', $host = '')
    {
        $host = $host ? $host : config('app.image_url') . '/';
        if (!empty($image_url) && strpos($image_url, 'http') === false) {
            $image_url = $host . $image_url;
        }
        return $image_url;
    }
}
if (!function_exists('first_image')) {
    function first_image($content)
    {
        $data['content'] = $content;
        $soContent = $data['content'];
        $soImages = '~<img [^>]* />~';
        preg_match_all($soImages, $soContent, $thePics);
        $allPics = count($thePics[0]);
        preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|PNG))\"?.+>/i', $thePics[0][0], $match);
        $data['ig'] = $thePics[0][0];
        if ($allPics > 0) {
            return $match[1];
        } else {
            return null;
        }
    }
}
if (!function_exists('list_image_url_absolute')) {
    function list_image_url_absolute($list, $size = 'sm')
    {
        foreach ($list as $key => $data) {
            $list[$key]['image'] = image_url_absolute($data['image'], $size);
        }
        return $list;
    }
}
if (!function_exists('image_url_absolute')) {
    function image_url_absolute($image, $size = 'sm')
    {
        return $image ? url("/image/" . $size . $image) : '';
    }
}
if (!function_exists('handle_images')) {
    function handle_images($images, $host = '')
    {
        foreach ($images as $key => $image) {
            $images[$key] = handle_image_url($image, $host);
        }
        return $images;
    }
}
if (!function_exists('setting')) {
    function setting($slug, $value = 'value')
    {
        return \App\Models\Setting::where('slug', $slug)->value($value);
    }
}
if (!function_exists('logo')) {
    function logo()
    {
        $logo =  \App\Models\Setting::where('slug', 'logo')->value('value');
        return url('/image/original/'.$logo);
    }
}
if (!function_exists('page')) {
    function page($slug, $value = 'content')
    {
        return \App\Models\Page::where('slug', $slug)->value($value);
    }
}
if (!function_exists('date_html')) {
    function date_html($date)
    {
        $month = date('M',strtotime($date));
        $day = date('d',strtotime($date));
        $html = '<div class="date"><p>'.$day.'</p><span>'.$month.'</span></div>';
        return $html;
    }
}
/*
* ============================== 截取含有 html标签的字符串 =========================
* @param (string) $str   待截取字符串
* @param (int)  $lenth  截取长度
* @param (string) $repalce 超出的内容用$repalce替换之（该参数可以为带有html标签的字符串）
* @param (string) $anchor 截取锚点，如果截取过程中遇到这个标记锚点就截至该锚点处
* @return (string) $result 返回值
* @demo  $res = cut_html_str($str, 256, '...'); //截取256个长度，其余部分用'...'替换
* ===============================================================================
*/
if (!function_exists('cut_html_str')) {
    function cut_html_str($str, $lenth, $replace = '......', $anchor = '<!-- break -->')
    {
        $_lenth = mb_strlen($str, "utf-8"); // 统计字符串长度（中、英文都算一个字符）
        if ($_lenth <= $lenth) {
            return $str;    // 传入的字符串长度小于截取长度，原样返回
        }
        $strlen_var = strlen($str);     // 统计字符串长度（UTF8编码下-中文算3个字符，英文算一个字符）
        if (strpos($str, '<') === false) {
            return mb_substr($str, 0, $lenth);  // 不包含 html 标签 ，直接截取
        }
        if ($e = strpos($str, $anchor)) {
            return mb_substr($str, 0, $e);  // 包含截断标志，优先
        }
        $html_tag = 0;  // html 代码标记
        $result = '';   // 摘要字符串
        $html_array = array('left' => array(), 'right' => array()); //记录截取后字符串内出现的 html 标签，开始=>left,结束=>right
        /*
        * 如字符串为：<h3><p><b>a</b></h3>，假设p未闭合，数组则为：array('left'=>array('h3','p','b'), 'right'=>'b','h3');
        * 仅补全 html 标签，<? <% 等其它语言标记，会产生不可预知结果
        */
        for ($i = 0; $i < $strlen_var; ++$i) {
            if (!$lenth) break;  // 遍历完之后跳出
            $current_var = substr($str, $i, 1); // 当前字符
            if ($current_var == '<') { // html 代码开始
                $html_tag = 1;
                $html_array_str = '';
            } else if ($html_tag == 1) { // 一段 html 代码结束
                if ($current_var == '>') {
                    $html_array_str = trim($html_array_str); //去除首尾空格，如 <br / > < img src="" / > 等可能出现首尾空格
                    if (substr($html_array_str, -1) != '/') { //判断最后一个字符是否为 /，若是，则标签已闭合，不记录
                        // 判断第一个字符是否 /，若是，则放在 right 单元
                        $f = substr($html_array_str, 0, 1);
                        if ($f == '/') {
                            $html_array['right'][] = str_replace('/', '', $html_array_str); // 去掉 '/'
                        } else if ($f != '?') { // 若是?，则为 PHP 代码，跳过
                            // 若有半角空格，以空格分割，第一个单元为 html 标签。如：<h2 class="a"> <p class="a">
                            if (strpos($html_array_str, ' ') !== false) {
                                // 分割成2个单元，可能有多个空格，如：<h2 class="" id="">
                                $html_array['left'][] = strtolower(current(explode(' ', $html_array_str, 2)));
                            } else {
                                //若没有空格，整个字符串为 html 标签，如：<b> <p> 等，统一转换为小写
                                $html_array['left'][] = strtolower($html_array_str);
                            }
                        }
                    }
                    $html_array_str = ''; // 字符串重置
                    $html_tag = 0;
                } else {
                    $html_array_str .= $current_var; //将< >之间的字符组成一个字符串,用于提取 html 标签
                }
            } else {
                --$lenth; // 非 html 代码才记数
            }
            $ord_var_c = ord($str{$i});
            switch (true) {
                case (($ord_var_c & 0xE0) == 0xC0): // 2 字节
                    $result .= substr($str, $i, 2);
                    $i += 1;
                    break;
                case (($ord_var_c & 0xF0) == 0xE0): // 3 字节
                    $result .= substr($str, $i, 3);
                    $i += 2;
                    break;
                case (($ord_var_c & 0xF8) == 0xF0): // 4 字节
                    $result .= substr($str, $i, 4);
                    $i += 3;
                    break;
                case (($ord_var_c & 0xFC) == 0xF8): // 5 字节
                    $result .= substr($str, $i, 5);
                    $i += 4;
                    break;
                case (($ord_var_c & 0xFE) == 0xFC): // 6 字节
                    $result .= substr($str, $i, 6);
                    $i += 5;
                    break;
                default: // 1 字节
                    $result .= $current_var;
            }
        }
        if ($html_array['left']) { //比对左右 html 标签，不足则补全
            $html_array['left'] = array_reverse($html_array['left']); //翻转left数组，补充的顺序应与 html 出现的顺序相反
            foreach ($html_array['left'] as $index => $tag) {
                $key = array_search($tag, $html_array['right']); // 判断该标签是否出现在 right 中
                if ($key !== false) { // 出现，从 right 中删除该单元
                    unset($html_array['right'][$key]);
                } else { // 没有出现，需要补全
                    $result .= '</' . $tag . '>';
                }
            }
        }
        return $result . $replace;
    }
}
if (!function_exists('drop_blank')) {
    function drop_blank($str)
    {
        $str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        $str = preg_replace("/ /", "", $str);
        $str = preg_replace("/  /", "", $str);  //匹配html中的空格
        return trim($str); //返回字符串
    }
}
if (!function_exists('build_order_sn')) {
    function build_order_sn($prefix='')
    {
        return $prefix.date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}
if (!function_exists('isVaildImage')) {
    function isVaildImage($files)
    {
        $error = '';

        foreach($files as $key => $file)
        {
            $name = $file->getClientOriginalName();
            if(!$file->isValid())
            {
                $error.= $name.$file->getErrorMessage().';';
            }
            if(!in_array( strtolower($file->extension()),config('common.img_type'))){
                $error.= $name."类型错误;";
            }
            if($file->getClientSize() > config('common.img_size')){
                $img_size = config('common.img_size')/(1024*1024);
                $error.= $name.'超过'.$img_size.'M';
            }
        }
        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('isVaildFile')) {
    function isVaildFile($files)
    {
        $error = '';

        foreach($files as $key => $file)
        {
            $name = $file->getClientOriginalName();
            if(!$file->isValid())
            {
                $error.= $name.$file->getErrorMessage().';';
            }
            if(!in_array( strtolower($file->extension()),config('common.file_type'))){
                $error.= $name."类型错误;";
            }
            if($file->getClientSize() > config('common.file_size')){
                $file_size = config('common.file_size')/(1024*1024);
                $error.= $name.'超过'.$file_size.'M';
            }
        }
        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}

if (!function_exists('isVaildExcel')) {
    function isVaildExcel($file)
    {
        $error = '';


        $name = $file->getClientOriginalName();
        if(!$file->isValid())
        {
            $error.= $name.$file->getErrorMessage().';';
        }

//        if(!in_array( strtolower($file->extension()),config('common.excel_type'))){
//            $error.= $name."为".strtolower($file->extension())."格式，非Excel格式;";
//        }
        if($file->getClientSize() > config('common.file_size')){
            $file_size = config('common.file_size')/(1024*1024);
            $error.= $name.'超过'.$file_size.'M';
        }

        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('image_png_size_add')) {
    function image_png_size_add($imgsrc, $imgdst,$max_width=1000,$size=0.9)
    {
        list($width, $height, $type) = getimagesize($imgsrc);
        $ratio = $width > $max_width ? $max_width / $width : 1;
        $new_width = $ratio * $width * $size;
        $new_height = $ratio * $height * $size;

        switch ($type) {
            case 1:
                $giftype = check_gifcartoon($imgsrc);
                if ($giftype) {
                    $image_wp = imagecreatetruecolor($new_width, $new_height);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagegif($image_wp, $imgdst, 75);
                    imagedestroy($image_wp);
                }
                break;
            case 2:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst, 75);
                imagedestroy($image_wp);
                break;
            case 3:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagesavealpha($image, true);
                imagealphablending($image_wp, false);
                imagesavealpha($image_wp, true);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagepng($image_wp, $imgdst);
                imagedestroy($image_wp);
                break;
        }

    }
}
if (!function_exists('check_gifcartoon')) {
    function check_gifcartoon($image_file)
    {
        $fp = fopen($image_file, 'rb');
        $image_head = fread($fp, 1024);
        fclose($fp);
        return true;
    }
}
if (!function_exists('get_admin_model')) {
    function get_admin_model($admin)
    {
        switch ($admin)
        {
            case $admin instanceof \App\Models\AirlineUser:
                return 'App\Models\AirlineUser';
            case $admin instanceof \App\Models\SupplierUser:
                return 'App\Models\SupplierUser';
            case $admin instanceof \App\Models\AdminUser:
                return 'App\Models\AdminUser';
            case $admin instanceof \App\Models\FinanceUser:
                return 'App\Models\FinanceUser';
        }
    }
}
if(!function_exists('bii_operation_verify'))
{
    function bii_operation_verify($status,$status_arr)
    {
        if(!in_array($status,$status_arr) )
        {
            throw new \App\Exceptions\OutputServerMessageException(trans('messages.operation.illegal'));
        }
    }
}
if(!function_exists('airline_bill_price')) {
    function airline_bill_price($price, $increase_price)
    {
        $price = $price * (1 + $increase_price);
        return bill_round($price);//floor($total*10000)/10000;
    }
}
if(!function_exists('airline_bill_total')) {
    function airline_bill_total($total, $increase_price)
    {
        $total = $total * (1 + $increase_price);
        return bill_round($total);//floor($total*10000)/10000;
    }
}
if(!function_exists('bill_round')) {
    function bill_round($price)
    {
        return round($price,4);
    }
}
if(!function_exists('buildResponse')) {
    function buildResponse($content)
    {
        // define mime type
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

        // return http response
        return new \Illuminate\Http\Response($content, 200, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age=' . (config('image.lifetime') * 60) . ', public',
            'Etag' => md5($content)
        ));
    }
}
if(!function_exists('diffBetweenTwoDays')) {
    function diffBetweenTwoDays($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);
        $str = '';
        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
            $str = '-';
        }
        $day = ($second1 - $second2) / 86400;
        return $str . $day;
    }
}
if(!function_exists('fmoney')) {
    function fmoney($num)
    {
        $num = 0 + $num;
        $num = sprintf("%.02f", $num);
        if (strlen($num) <= 6) return $num;
//从最后开始算起，每3个数它加一个","
        for ($i = strlen($num) - 1, $k = 1, $j = 100; $i >= 0; $i--, $k++) {
            $one_num = substr($num, $i, 1);
            if ($one_num == ".") {
                $numArray[$j--] = $one_num;
                $k = 0;
                continue;
            }

            if ($k % 3 == 0 and $i != 0) {
//如果正好只剩下3个数字，则不加','
                $numArray[$j--] = $one_num;
                $numArray[$j--] = ",";
                $k = 0;
            } else {
                $numArray[$j--] = $one_num;
            }
        }
        ksort($numArray);
        return join("", $numArray);
    }
}
if(!function_exists('umoney')) {
    function umoney($num, $type = "usd")
    {
        if ($num <= 0) {
            return '';
        }
        global $numTable, $commaTable, $moneyType;

        $numTable[0] = "ZERO ";
        $numTable[1] = "ONE ";
        $numTable[2] = "TWO ";
        $numTable[3] = "THREE ";
        $numTable[4] = "FOUR ";
        $numTable[5] = "FIVE ";
        $numTable[6] = "SIX ";
        $numTable[7] = "SEVEN ";
        $numTable[8] = "EIGHT ";
        $numTable[9] = "NINE ";
        $numTable[10] = "TEN ";
        $numTable[11] = "ELEVEN ";
        $numTable[12] = "TWELVE ";
        $numTable[13] = "THIRTEEN ";
        $numTable[14] = "FOURTEEN ";
        $numTable[15] = "FIFTEEN ";
        $numTable[16] = "SIXTEEN ";
        $numTable[17] = "SEVENTEEN ";
        $numTable[18] = "EIGHTEEN ";
        $numTable[19] = "NINETEEN ";
        $numTable[20] = "TWENTY ";
        $numTable[30] = "THIRTY ";
        $numTable[40] = "FORTY ";
        $numTable[50] = "FIFTY ";
        $numTable[60] = "SIXTY ";
        $numTable[70] = "SEVENTY ";
        $numTable[80] = "EIGHTY ";
        $numTable[90] = "NINETY ";

        $commaTable[0] = "HUNDRED ";
        $commaTable[1] = "THOUSAND ";
        $commaTable[2] = "MILLION ";
        $commaTable[3] = "MILLIARD ";
        $commaTable[4] = "BILLION ";
        $commaTable[5] = "????? ";

//单位
        $moneyType["usd"] = "DOLLARS ";
        $moneyType["usd_1"] = "CENTS ONLY";
        $moneyType["rmb"] = "YUAN ";
        $moneyType["rmb_1"] = "FEN ONLY";


        if ($type == "") $type = "usd";
        $fnum = fmoney($num);
        $numArray = explode(",", $fnum);
        $resultArray = array();
        $k = 0;
        $cc = count($numArray);
        for ($i = 0; $i < count($numArray); $i++) {
            $num_str = $numArray[$i];
            //echo "<br>";
            //小数位的处理400.21
            if (eregi("\.", $num_str)) {
                $dotArray = explode(".", $num_str);
                if ($dotArray[1] != 0) {
                    $resultArray[$k++] = format3num($dotArray[0] + 0);
                    $resultArray[$k++] = $moneyType[strtolower($type)];
                    $resultArray[$k++] = "AND ";
                    $resultArray[$k++] = format3num($dotArray[1] + 0);
                    $resultArray[$k++] = $moneyType[strtolower($type) . "_1"];
                } else {
                    $resultArray[$k++] = format3num($dotArray[0] + 0);
                    $resultArray[$k++] = $moneyType[strtolower($type)];
                }
            } else {
//非小数位的处理
                if (($num_str + 0) != 0) {
                    $resultArray[$k++] = format3num($num_str + 0);
                    $resultArray[$k++] = $commaTable[--$cc];
//判断：除小数外其余若不为零则加and
                    for ($j = $i; $j <= $cc; $j++) {
//echo "<br>";
//echo $numArray[$j];
                        if ($numArray[$j] != 0) {
                            $resultArray[$k++] = "AND ";
                            break;
                        }
                    }
                }
            }
        }
        return join("", $resultArray);
    }
}

if(!function_exists('format3num')) {
    function format3num($num)
    {
        global $numTable, $commaTable;
        $numlen = strlen($num);
        for ($i = 0, $j = 0; $i < $numlen; $i++) {
            $bitenum[$j++] = substr($num, $i, 1);
        }
        if ($num == 0) return "";
        if ($numlen == 1) return $numTable[$num];
        if ($numlen == 2) {
            if ($num <= 20) return $numTable[$num];
//第一位不可能零
            if ($bitenum[1] == 0) {
                return $numTable[$num];
            } else {
                return trim($numTable[$bitenum[0] * 10]) . "-" . $numTable[$bitenum[1]];
            }

        }
//第一个不可能为零
        if ($numlen == 3) {
            if ($bitenum[1] == 0 && $bitenum[2] == 0) {
//100
                return $numTable[$bitenum[0]] . $commaTable[0];
            } elseif ($bitenum[1] == 0) {
//102
                return $numTable[$bitenum[0]] . $commaTable[0] . $numTable[$bitenum[2]];
            } elseif ($bitenum[2] == 0) {
//120
                return $numTable[$bitenum[0]] . $commaTable[0] . $numTable[$bitenum[1] * 10];
            } else {
//123
                return $numTable[$bitenum[0]] . $commaTable[0] . trim($numTable[$bitenum[1] * 10]) . "-" . $numTable[$bitenum[2]];
            }
        }
        return $num;
    }
}
if(!function_exists('eregi')){
    function eregi($pattern, $subject, &$matches = [])
    {
        return preg_match('/'.$pattern.'/i', $subject, $matches);
    }
}
function letters()
{
    return ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
}
function common_number_format($number,$decimals=2)
{
    return number_format($number,$decimals);
}