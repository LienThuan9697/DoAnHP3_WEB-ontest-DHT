<?php

$GLOBALS['navbar'] = [
    [
        'name' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt', // icon dashboard
        'url'  => 'dashboard'
    ],
    [
        'name' => 'Sinh viên',
        'type' => 'heading',
        'navbarItem' => [
            [
                'name' => 'Học phần',
                'icon' => 'fas fa-chalkboard-teacher', // rõ nghĩa học phần
                'url'  => 'client/group',
                'role' => 'tghocphan'
            ],
            [
                'name' => 'Đề thi',
                'icon' => 'fas fa-file-alt', // đề thi rõ hơn
                'url'  => 'client/test',
                'role' => 'tgthi'
            ],
        ]
    ],
    [
        'name' => 'Giáo viên',
        'type' => 'heading',
        'navbarItem' => [
            [
                'name' => 'Môn học',
                'icon' => 'fas fa-book-open', // môn học
                'url'  => 'subject',
                'role' => 'monhoc'
            ],
            [
                'name' => 'Câu hỏi',
                'icon' => 'fas fa-question-circle', // câu hỏi
                'url'  => 'question',
                'role' => 'cauhoi'
            ],
            [
                'name' => 'Nhóm học phần',
                'icon' => 'fas fa-layer-group', // nhóm học phần
                'url'  => 'module',
                'role' => 'hocphan'
            ],
            [
                'name' => 'Đề kiểm tra',
                'icon' => 'fas fa-file-lines', // bài kiểm tra
                'url'  => 'test',
                'role' => 'dethi'
            ],
            [
                'name' => 'Thông báo',
                'icon' => 'fas fa-bullhorn',
                'url'  => 'teacher_announcement',
                'role' => 'thongbao'
            ],
             [
                'name' => 'Thống kê',
                'icon' => 'fas fa-chart-bar',
                'url'  => 'statistic',
                'role' => 'thongke'
            ],
        ]
    ]
];

// Xử lý url để active navbar
function getActiveNav()
{
    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_PATH);
    $components = explode('/', $path);
    return $components[2];
}

function build_navbar()
{
    // Loại bỏ các navbar item không có trong session nhóm quyền
    foreach ($GLOBALS['navbar'] as $key => $nav) {
        if (isset($nav['navbarItem'])) {
            foreach ($nav['navbarItem'] as $key1 => $navItem) {
                if (!array_key_exists($navItem['role'], $_SESSION['user_role'])) {
                    unset($GLOBALS['navbar'][$key]['navbarItem'][$key1]);
                }
            }
        }
    }

    // Sau khi xoá các navbar item không có trong session nhóm quyền thì duyệt mảng tạo navbar
    $html = '';
    $current_page = getActiveNav();
    foreach ($GLOBALS['navbar'] as $nav) {
        if (isset($nav['navbarItem']) && isset($nav['type']) && count($nav['navbarItem']) > 0) {
            $html .= "<li class=\"nav-main-heading\">".$nav['name']."</li>";
            foreach ($nav['navbarItem'] as $navItem) {
                $link_name = '<span class="nav-main-link-name">' . $navItem['name'] . '</span>' . "\n";
                $link_icon = '<i class="nav-main-link-icon ' . $navItem['icon'] . '"></i>' . "\n";
                $html .= "<li class=\"nav-main-item\">"."\n";
                $html .= "<a class=\"nav-main-link".($current_page == $navItem['url'] ? " active" : "")."\" href=\"./".$navItem['url']."\">";
                $html .= $link_icon;
                $html .= $link_name;
                $html .= "</a></li>\n";
            }
        }
    }
    echo $html;
}
