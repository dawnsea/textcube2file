<?php

    $filename = "tc.xml";
    $f = fopen($filename, "r");
    
    $posts = fread($f, filesize($filename));
    fclose($f);
    
    $object = simplexml_load_string($posts);
    
    $p = $object->post;
    $i = 0;
    
    foreach($p as $q) {
        
        $i++;
        $filename = preg_replace('((^\.)|\/|(\.$))', '_', $q->title);
        
        if ($q->visibility  == "syndicated" || $q->visibility  == "public") {
            $title    = "제목 : " . $q->title;
        } else {
            $title    = "제목 : [비공개]" . $q->title;
        }

        $tags     = "태그 : ";
        
        if ($q->category == "전시" || strstr($q->category, "앨범")) continue;
        
        if ($q->category)
            $tags .= $q->category . ", ";


            
        foreach($q->tag as $tag) 
        {
            if ($tag == "사진") break;
            
            $tags .= $tag . ", ";
            
        }
        
        if ($tag == "사진") continue;
        
        if (count($q->tag)) {
            $tags = substr($tags, 0, strlen($tags) - 2);
        }
        
        $year       = strftime("%Y", intval($q->published));
        $month      = strftime("%m", intval($q->published));
        $day        = strftime("%d", intval($q->published));
        
        $date     = "날짜 : " . $year . "/" . $month . "/" . $day;
        $content  = $q->content;
        
        $write_all  = $title . "\n";
        $write_all .= $tags  . "\n";
        $write_all .= $date  . "\n";
        $write_all .= str_replace("[##_kaAmo_##]", "", html_entity_decode(strip_tags(str_replace("<br>", "  \n", str_replace("&nbsp;", "", str_replace("<br ", "  \n<br ", $content))))));
        
        
//        echo $write_all;
       
        
        $direc = "tblog/" . $year . "-" . $month;
        mkdir($direc);
        
        $write_file = fopen($direc . "/" . $filename, "w");
        fwrite($write_file, $write_all, strlen($write_all));
        fclose($write_file);

    }
    
    echo "\n\n\n\n\n" . $i;

    
?>
