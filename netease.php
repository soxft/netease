<?php
    $version = "1.2";
    //用于检查更新,忽删
    @$one = $argv[1];
    switch($one)
    {
        case 'search':
            @$searchtype = $argv[2];
            @$s = $argv[3];
            @$limit = $argv[4];
            @$offset = $argv[5];
            if(empty($searchtype) || empty($s)){
                exit("错误的格式，请使用 php netease.php search 搜索类型 关键词 (搜索个数) (偏移数量,用于分页)");
            }
            echo "searching...\r\n";
            $url = "https://api.xsot.cn/netease/?type=search&offset=" . $offset . "&limit=" . $limit . "&search_type=" . $searchtype . "&s=" . $s;
            //echo $url;
            //exit();
            //echo 1;
            @$data = file_get_contents($url) or die("API接口错误!请尝试通过php netease.php update获取更新解决");
            @$data = json_decode($data,true);
            @$num1 = count($data['result']['songs']);
            if($num1 == 0)
            {
                echo "没搜到唉,换个关键词试试呗";
            }
            //数组化
            //echo $num1;
            //print_r($data);
            //exit();
            $result = array();
            //echo 2;
            for ($i = 0;$i <= $num1 - 1;$i++)
            {
                $result[$i] = $data['result']['songs'][$i];
            }
            //print_r($result);
            //exit();
            $num = count($result);
            $re =array();
            //echo 3;
            //数组初始化
            for($i = 0;$i <= $num - 1;$i++)
            {
                $re[$i] = array(
                    $result[$i]['id'],
                    $result[$i]['name'],
                    $result[$i]['artists'][0]['name']
                );
            }
            for($i = 0;$i <= $num -1;$i++)
            {
                echo $i+1 . " (id:" . $re[$i][0] . ") -> " .  $re[$i][1] . " by " . $re[$i][2] . "\r\n";  
            }
        break;
        case 'playlist':
            @$id = $argv[2];
            if(empty($id)){
                exit("错误的格式，请使用 php netease.php playlist 歌单id");
            }
            echo "Loading...\r\n";
            $url = "https://api.xsot.cn/netease/?type=playlist&id=" . $id;
            @$data = file_get_contents($url) or die("API接口错误!请尝试通过php netease.php update获取更新解决");
            @$data = json_decode($data,true);
            if($data['code'] == 404)
            {
                exit("这个id好像输错啦!");
            }
            //print_r($data);
            //exit();
            $music = $data['music'];
            $num = count($music);
            //获取个数好循环
            echo "歌单名称:" . $data['title'] . "\r\n歌单简介:" . $data['description'] . "\r\n";
            for($i = 0;$i <= $num -1;$i++)
            {
                echo $i+1 . " (id:" . $music[$i]['id'] . ") -> " . $music[$i]['name'] . "\r\n";
            }
        break;
        case 'song':
            @$id = $argv[2];
            if(empty($id)){
                exit("错误的格式，请使用 php netease.php song 歌曲id");
            }
            echo "Loading...\r\n";
            $url = "https://api.xsot.cn/netease/?type=song&id=" . $id;
            @$data = file_get_contents($url) or die("API接口错误!请尝试通过php netease.php update获取更新解决");
            @$data = json_decode($data,true);
            if($data['code'] == 404)
            {
                exit("这个id好像输错啦!");  
            }
            echo "(id:$id) -> " . $data['data']['url'] . "\r\n";
        break;
        case 'update':
            echo "checking...\r\n";
            $url = "https://xsot.cn/api/update/?app=neteaseforphp";
            $data = file_get_contents($url) or die("获取更新失败,请检查您的网络是否良好!");
            $data = json_decode($data,true);
            $versionget = $data['version'];
            $info = $data['info'];
            if($versionget === $version)
            {
                echo "
                当前已是最新版本!
                当前版本信息:$info
                ";
            }else{
                echo "
                检测到新版本!
                当前版本:$version
                最新版本:$versionget
                新版本信息:$info
                请前往Github下载:https://github.com/soxft/netease/
                ";
            }
        break;
        case 'help':
            echo "
                帮助:
                1.php netease.php search 搜索类型 关键词 (搜索个数) (偏移数量,用于分页) ->  搜索
                2.php netease.php playlist 歌单id -> 获取歌单内歌曲
                3.php netease.php song 歌曲id-> 获取歌曲直链
                4.php netease.php update -> 检查更新(暂不支持自动更新,未来会增加)

                搜索类型相关
                |search_type|含义      | 
                | --------- | -------- |
                |1          |	单曲   |
                |10         |	专辑   |
                |100        |	歌手   |
                |1000       |	歌单   |
                |1002       |	用户   |
                |1004       |	mv     |
                |1006       |	歌词   |
                |1009       | 主播电台 |

                关于:
                欢迎使用netease for php,made by xcsoft(https://blog.xsot.cn)
                Version:" . $version .  "
                API引用来自星辰API(https://api.xsot.cn)
                别玩坏了哈哈哈,拒绝伸手党,别改个版权就说是自己的就发出去了,至少留个star呀,哈哈哈哈.
                讲真的,还是希望可以保留版权,如果执意要改的话我也没办法是不是,哈哈哈哈.
                联系方式:contact@xcsoft.top
                ";
        break;
        default:
            echo "未知指令,请输入php netease.php help获取帮助\r\n";
        break;    
    }
?>
