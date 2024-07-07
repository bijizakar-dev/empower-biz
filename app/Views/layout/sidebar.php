<?php

use App\Models\System\Menu;


$this->m_menu = new Menu();
$menuItems = $this->m_menu->getMenuSidebar();
// header('Content-Type: application/json');
// die(json_encode($menuItems));

function generateSidebar($menuItems) {
    $html = '';

    $categories = [
        'Core'              => ['Dashboard'],
        'Data & Layanan'    => ['Masterdata', 'Layanan'],
        'Sistem'            => ['Pengaturan', 'Akun'],
    ];

    foreach ($categories as $category => $items) {
        $html .= generateCategory($category, $items, $menuItems);
    }

    return $html;
}

function generateCategory($category, $items, $menuItems) {
    $html = '<div class="nav accordion" id="accordionSidenav">';
    $html .= '<div class="sidenav-menu-heading">'.$category.'</div>';

    $filteredItems = array_filter($menuItems, function($menuItem) use ($items) {
        return in_array($menuItem->name, $items);
    });
    $html .= generateMenuItems($filteredItems);

    $html .= '</div>';

    return $html;
}

function generateMenuItems($menuItems, $parentId = 'accordionSidenav', $isSubmenu = false) {
    $html = '';

    if ($isSubmenu) {
        $html .= '<nav class="sidenav-menu-nested nav">';
    }

    // Get the current URL path
    $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($menuItems as $item) {
        $collapseId = 'collapse' . str_replace(' ', '', $item->name);
        $icon = isset($item->icon) ? $item->icon : '';
        $activeClass = '';

        if (isset($item->submenu)) {
            $submenuActive = false;
            foreach ($item->submenu as $subItem) {
                if ($currentUrl === '/' . trim($subItem->path, '/')) {
                    $submenuActive = true;
                    $activeClass = 'active';
                    break;
                }

                if (isset($subItem->submenu)) {
                    foreach ($subItem->submenu as $subSubItem) {
                        if ($currentUrl === '/' . trim($subSubItem->path, '/')) {
                            $submenuActive = true;
                            $activeClass = 'active';
                            break 2; // Break out of both loops
                        }
                    }
                }
            }

            $html .= '<a class="nav-link collapsed '.$activeClass.'" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#'.$collapseId.'" aria-expanded="false" aria-controls="'.$collapseId.'">';
            if ($icon) {
                $html .= '<div class="nav-link-icon"><i data-feather="'.$icon.'"></i></div>';
            }
            $html .= $item->name;
            $html .= '<div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>';
            $html .= '</a>';
            $html .= '<div class="collapse '.($submenuActive ? 'show' : '').'" id="'.$collapseId.'" data-bs-parent="#'.$parentId.'">';
            $html .= generateMenuItems($item->submenu, $collapseId, true);
            $html .= '</div>';
        } else {
            if ($currentUrl === '/' . trim($item->path, '/')) {
                $activeClass = 'active';
            }

            $html .= '<a class="nav-link '.$activeClass.'" href="'.base_url().$item->path.'">';
            if ($icon) {
                $html .= '<div class="nav-link-icon"><i data-feather="'.$icon.'"></i></div>';
            }
            $html .= $item->name;
            $html .= '</a>';
        }
    }

    if ($isSubmenu) {
        $html .= '</nav>';
    }

    return $html;
}

// $menuItems = [
//     [
//         'name' => 'Dashboard',
//         'path' => '',
//         'icon' => 'activity',
//     ],
//     [
//         'name' => 'Masterdata',
//         'path' => 'masterdata',
//         'icon' => 'columns',
//         'submenu' => [
//             ['name' => 'Pegawai', 'path' => 'masterdata/employee'],
//             ['name' => 'Departemen', 'path' => 'masterdata/department'],
//             ['name' => 'Warehouse', 'path' => 'masterdata/warehouse'],
//             ['name' => 'Supplier', 'path' => 'masterdata/supplier'],
//             ['name' => 'Unit', 'path' => 'masterdata/unit'],
//         ],
//     ],
//     [
//         'name' => 'Layanan',
//         'path' => '#',
//         'icon' => 'grid',
//         'submenu' => [
//             ['name' => 'Permintaan Barang', 'path' => '#'],
//             ['name' => 'Pemesanan Barang', 'path' => '#'],
//             ['name' => 'Penerimaan Barang', 'path' => '#'],
//             ['name' => 'Permintaan Penjualan', 'path' => '#'],
//         ],
//     ],
//     [
//         'name' => 'Pengaturan',
//         'path' => 'system',
//         'icon' => 'tool',
//         'submenu' => [
//             ['name' => 'User', 'path' => 'system/user'],
//             ['name' => 'Role', 'path' => 'system/role'],
//             ['name' => 'Setting Aplikasi', 'path' => 'setting-app'],
//         ],
//     ],
//     [
//         'name' => 'Akun',
//         'path' => '#',
//         'icon' => 'user',
//     ],
// ];

?>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <?= generateSidebar($menuItems); ?>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?= session()->get('username'); ?> ( <?= session()->get('role'); ?> )</div>
        </div>
    </div>
</nav>
