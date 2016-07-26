<?php
/**
 * 生成分页html
 * 主要用于主站列表页、详细页（卖家、晒单列表）
 *
 * @param page          当前页
 * @param pages         总页数
 * @param url_format    链接格式，配合sprintf函数
 * @param show_pages    显示页码数
 * @param show_total    显示总页数
 * @param show_goto     显示页面跳转
 * 
 * @author 刘志超
 * 
 *
 */
function pagination($page, $pages, $url_format = '%d.html', $show_pages = 5, $show_total = FALSE, $show_goto = FALSE) {
    if ($pages < 2)
        return '';
    $html = array();
    $html[] = '<div class="paging">';

    if ($page > 1) {
        $html[] = '<a title="查看第' . ($page - 1) . '页" class="paging-prev" href="' . sprintf($url_format, $page - 1) . '">上一页</a>';
    }

    $page_start = $page - intval($show_pages / 2);
    if ($page_start < 1)
        $page_start = 1;

    if ($page_start > 1) {
        $html[] = '<a title="查看第' . 1 . '页" href="' . sprintf($url_format, 1) . '">' . 1 . '</a>';
        if ($page_start > 2) {
            $html[] = '<span class="mid">...</span>';
        }
    }

    for ($i = 0; $i < $show_pages; $i++) {
        $cur = $page_start + $i;
        if ($cur > $pages)
            break;

        if ($cur == $page) {
            $html[] = '<span class="paging-currentPage">' . $cur . '</span>';
        } else {
            $html[] = '<a title="查看第' . $cur . '页" href="' . sprintf($url_format, $cur) . '">' . $cur . '</a>';
        }
    }
    if ($cur < $pages) {
        if ($cur + 1 < $pages) {
            $html[] = '<em class="paging-ellipsis">...</em>';
        }
        $html[] = '<a title="查看第' . $pages . '页" href="' . sprintf($url_format, $pages) . '">' . $pages . '</a>';
    }

    if ($page < $pages) {
        $html[] = '<a title="查看第' . ($page + 1) . '页" class="paging-next" href="' . sprintf($url_format, $page + 1) . '">下一页</a>';
    }

    if ($show_total) {
        $html[] = '<span class="paging-total">共 ' . $pages . ' 页</span>';
    }

    if ($show_goto) {
        $str = explode('%d', $url_format);
        // 替换URL字串，如：//%%E8%%A1%%A3%%E6%%9C%%8D 转为 %E8%A1%A3%E6%9C%8D
        $str[1] = sprintf($str[1]);
        // 提交表单时，用户输入的非法值不做响应
		$html[] = <<<JS
		<form onsubmit="
				var goPage = this.getElementsByTagName('input')[0].value;
				goPage = parseInt(goPage.replace(/\s+/g,''));
				var totalPage = $pages;
				var curPage = $page;
				if( goPage>=1 && goPage<=totalPage && goPage!=curPage ){
					location.href='$str[0]' + goPage + '$str[1]';
    			}
				return false;
				";>
			
			<span>到第 <input class="paging-pageNum" type="text" name="page" value=""> 页</span>
			<input type="submit" value="确定" class="paging-goto">
		</form>
JS;
    }
    $html[] = '</div>';
    
    return implode($html);
}

	
?>