<?php

class Html {

    public static function dropdownlist($name, $list, $selectedValue = null, $label = null, $attributes = null, $options = null) {

        $htmlAttr = "";
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $htmlAttr .= " " . $key . "='$value'";
            }
        }

        $optionTags = "";
        if (isset($label)) {
            $optionTags .= "<option value=''>$label</option>";
        }

        $textField = "name";
        $valueField = "value";
        if ($options) {
            $textField = $options["textField"];
            $valueField = $options["valueField"];
        }

        if ($list) {
            foreach ($list as $item) {
                $text = $item[$textField];
                $value = $item[$valueField];
                $selected = "";
                if (isset($value) && $value == $selectedValue) {
                    $selected = "selected='selected'";
                }
                $optionTags .= "<option value='$value' $selected>$text</option>";
            }
        }

        $html = "<select name='$name' id='$name' $htmlAttr >$optionTags</select>";
        return $html;
    }

    public static function pagination($url, $total, $pageSize, $currentPage, $pageCount = 8, $options = null) {
        $items = self::generatePaginations($total, $pageSize, $currentPage, $pageCount);
        //remove current page arg
        $url = preg_replace("/&page=[^&]*/i", "", $url);
        $sp = strpos($url, '?') !== false ? "&" : "?";
        $lis = "";
        foreach ($items as $page) {
            $href = "${url}${sp}page=$page";
            $active = $page == $currentPage ? "active" : "";
            $class = $active ? "class='$active'" : "";
            $lis .= "<li $class><a href='$href'>$page</a> </li>";
        }
        $totalPageCount = (int)($total / $pageSize + (($total % $pageSize) == 0 ? 0 : 1));
        $hasPrevious = $currentPage > 1;
        $hasNext = $currentPage < $totalPageCount;

        if ($hasPrevious) {
            $previousIndex = $currentPage - 1;
            $href = "${url}${sp}page=$previousIndex";
            $lis = "<li ><a href=\"$href\" aria-label=\"Previous\"><span aria-hidden=\"true\">«</span></a></li> $lis";
        }

        if ($hasNext) {
            $nextIndex = $currentPage + 1;
            $href = "${url}${sp}page=$nextIndex";
            $lis = "$lis <li><a href=\"$href\" aria-label=\"Next\"><span aria-hidden=\"true\">»</span></a></li>";
        }

        if ($currentPage > 1) {
            $href = "${url}${sp}page=1";
            $lis = "<li ><a href=\"$href\"  ><span >首页</span></a></li> $lis";
        }

        if ($currentPage < $totalPageCount) {
            $href = "${url}${sp}page=$totalPageCount";
            $lis = "$lis <li><a href=\"$href\" ><span>尾页</span></a></li>";
        }

        $lis = "<li><span>总页数：$totalPageCount </span></li> $lis";

        $ul = "<ul class='pagination'>$lis</ul>";
        return $ul;
    }

    public static function generatePaginations($total, $pageSize, $currentPage = 1, $pageCount = 8) {
        $totalPageCount = (int)($total / $pageSize + (($total % $pageSize) == 0 ? 0 : 1));
        $items = array();
        $leftCount = $pageCount / 2;
        $rightCount = $leftCount + (($pageCount % 2 == 0) ? 0 : 1);

        $leftIndex = $currentPage - $leftCount;
        $rightIndex = $currentPage + $rightCount - 1;
        $leftIndex = $leftIndex < 1 ? 1 : $leftIndex;
        $rightIndex = min($rightIndex, $totalPageCount);

        $leftRemain = $leftCount - ($currentPage - $leftIndex);
        $rightRemain = $rightCount - ($totalPageCount - $currentPage) - 1;

        if ($leftRemain > 0) {
            $rightIndex += $leftRemain;
        }

        if ($rightRemain > 0) {
            $leftIndex -= $rightRemain;
        }

        $rightIndex = min($rightIndex, $totalPageCount);
        $leftIndex = $leftIndex < 1 ? 1 : $leftIndex;

        for ($i = $leftIndex; $i <= $rightIndex; $i++) {
            $items[] = $i;
        }
        return $items;
    }
}