<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'SIAKUN',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>SI</b>AKUN',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [

        ['header' => 'MENU UTAMA'],
        [
            'text' => 'Dashboard',
            'url'  => 'dashboard',
            'icon' => 'fas fa-home',
        ],

        ['header' => 'MASTER DATA'],
        [
            'text' => 'Master Data',
            'icon' => 'fas fa-database',
            'submenu' => [
                ['text' => 'Customer', 'url' => 'customer', 'icon' => 'far fa-circle'],
                ['text' => 'Supplier', 'url' => 'supplier', 'icon' => 'far fa-circle'],
                ['text' => 'Chart of Account (COA)', 'url' => 'coa', 'icon' => 'far fa-circle'],
                ['text' => 'Mapping Jurnal', 'url' => 'mapping_jurnal', 'icon' => 'far fa-circle'],
                ['text' => 'Metode Pembayaran', 'url' => 'metode-pembayaran', 'icon' => 'far fa-circle'],
            ],
        ],

        ['header' => 'TRANSAKSI'],

        // PENJUALAN
        [
            'text' => 'Penjualan',
            'icon' => 'fas fa-shopping-cart',
            'submenu' => [
                ['text' => 'Pesanan Penjualan', 'url' => 'sales_order', 'icon' => 'far fa-circle'],
                ['text' => 'Faktur Penjualan', 'url' => 'sales-invoice', 'icon' => 'far fa-circle'],
                ['text' => 'Pengiriman Penjualan', 'url' => 'pengiriman-penjualan', 'icon' => 'far fa-circle'],
            ],
        ],

        // PEMBELIAN
        [
            'text' => 'Pembelian',
            'icon' => 'fas fa-truck',
            'submenu' => [
                ['text' => 'Purchase Order', 'url' => 'purchase-order', 'icon' => 'far fa-circle'],
                ['text' => 'Penerimaan Barang', 'url' => 'penerimaan', 'icon' => 'far fa-circle'],
                ['text' => 'Faktur Pembelian', 'url' => 'pembelian-invoice', 'icon' => 'far fa-circle'],
                ['text' => 'Retur Pembelian', 'url' => 'retur-pembelian', 'icon' => 'far fa-circle'],
            ],
        ],

        // PIUTANG
        [
            'text' => 'Piutang Customer',
            'icon' => 'fas fa-hand-holding-usd',
            'submenu' => [
                ['text' => 'Daftar Piutang', 'url' => 'piutang', 'icon' => 'far fa-circle'],
                ['text' => 'Penerimaan Pembayaran', 'url' => 'pembayaran-penjualan', 'icon' => 'far fa-circle'],
            ],
        ],

        // HUTANG
        [
            'text' => 'Hutang Supplier',
            'icon' => 'fas fa-hand-holding-usd',
            'submenu' => [
                ['text' => 'Daftar Hutang', 'url' => 'hutang-supplier', 'icon' => 'far fa-circle'],
                ['text' => 'Kontrabon', 'url' => 'kontrabon', 'icon' => 'far fa-circle'],
                ['text' => 'Pembayaran Hutang', 'url' => 'pembayaran-pembelian', 'icon' => 'far fa-circle'],
            ],
        ],

        // KAS & BANK
        [
            'text' => 'Kas & Bank',
            'icon' => 'fas fa-wallet',
            'submenu' => [
                ['text' => 'Arus Kas', 'url' => 'kas/arus-kas', 'icon' => 'far fa-circle'],
                ['text' => 'Mutasi Kas', 'url' => 'kas/mutasi', 'icon' => 'far fa-circle'],
                ['text' => 'Rekonsiliasi Bank', 'url' => 'kas/rekonsiliasi', 'icon' => 'far fa-circle'],
            ],
        ],

        // PAJAK
        [
            'text' => 'Pajak',
            'icon' => 'fas fa-file-invoice-dollar',
            'submenu' => [
                ['text' => 'PPN Keluaran', 'url' => 'ppn-keluaran', 'icon' => 'far fa-circle'],
                ['text' => 'PPN Masukan', 'url' => 'faktur-pajak-masukan', 'icon' => 'far fa-circle'],
            ],
        ],

        // AKUNTANSI
        [
            'text' => 'Akuntansi',
            'icon' => 'fas fa-book',
            'submenu' => [
                ['text' => 'Jurnal Umum', 'url' => 'akuntansi/jurnal_umum', 'icon' => 'far fa-circle'],
                ['text' => 'Jurnal Penyesuaian', 'url' => 'akuntansi/jurnal_penyesuaian', 'icon' => 'far fa-circle'],
                ['text' => 'Buku Besar', 'url' => 'akuntansi/buku_besar', 'icon' => 'far fa-circle'],
                ['text' => 'Neraca Saldo', 'url' => 'akuntansi/neraca_saldo', 'icon' => 'far fa-circle'],
                ['text' => 'Neraca Lajur', 'url' => 'akuntansi/neraca-lajur', 'icon' => 'far fa-circle'],
            ],
        ],

        // PERSEDIAAN
        [
            'text' => 'Persediaan',
            'icon' => 'fas fa-boxes',
            'submenu' => [
                ['text' => 'Daftar Barang', 'url' => 'produk', 'icon' => 'far fa-circle'],
                ['text' => 'Kategori Barang', 'url' => 'kategori-produk', 'icon' => 'far fa-circle'],
                ['text' => 'Stok Awal', 'url' => 'persediaan/stok-awal', 'icon' => 'far fa-circle'], // ❗ wajib untuk pembukaan awal
                ['text' => 'Penerimaan Barang', 'url' => 'penerimaan-barang', 'icon' => 'far fa-circle'],
                ['text' => 'Pengeluaran Barang', 'url' => 'pengeluaran-barang', 'icon' => 'far fa-circle'],
                ['text' => 'Mutasi Antar Gudang', 'url' => 'persediaan/mutasi-gudang', 'icon' => 'far fa-circle'], // jika multi-gudang
                ['text' => 'Penyesuaian Stok', 'url' => 'persediaan/penyesuaian-stok', 'icon' => 'far fa-circle'], // untuk koreksi manual
                ['text' => 'Kartu Stok', 'url' => 'persediaan/kartu-stok', 'icon' => 'far fa-circle'],
                ['text' => 'Laporan Persediaan', 'url' => 'laporan-persediaan', 'icon' => 'far fa-circle'], // laporan saldo & mutasi
            ],

        ],
        // PENGGAJIAN
        [
            'text' => 'Penggajian',
            'icon' => 'fas fa-money-bill-wave',
            'submenu' => [
                ['text' => 'Data Karyawan', 'url' => 'payroll/karyawan', 'icon' => 'far fa-circle'],
                ['text' => 'Gaji & Potongan', 'url' => 'payroll/gaji', 'icon' => 'far fa-circle'],
                ['text' => 'Slip Gaji', 'url' => 'payroll/slip', 'icon' => 'far fa-circle'],
            ],
        ],

        // ASET TETAP
        [
            'text' => 'Aset Tetap',
            'icon' => 'fas fa-warehouse',
            'submenu' => [
                ['text' => 'Daftar Aset', 'url' => 'aset/daftar', 'icon' => 'far fa-circle'],
                ['text' => 'Penyusutan', 'url' => 'aset/penyusutan', 'icon' => 'far fa-circle'],
            ],
        ],

        // LAPORAN
        [
            'text' => 'Laporan',
            'icon' => 'fas fa-file-alt',
            'submenu' => [
                ['text' => 'Laba Rugi', 'url' => 'laporan/laba-rugi', 'icon' => 'far fa-circle'],
                ['text' => 'Neraca', 'url' => 'laporan/neraca', 'icon' => 'far fa-circle'],
                ['text' => 'Arus Kas', 'url' => 'laporan/arus-kas', 'icon' => 'far fa-circle'],
                ['text' => 'Perubahan Modal', 'url' => 'laporan/perubahan-modal', 'icon' => 'far fa-circle'],
            ],
        ],

        // PENGATURAN
        [
            'text' => 'Pengaturan',
            'icon' => 'fas fa-cogs',
            'submenu' => [
                ['text' => 'Perusahaan', 'url' => 'setting/perusahaan', 'icon' => 'far fa-circle'],
                ['text' => 'Cabang', 'url' => 'setting/cabang', 'icon' => 'far fa-circle'],
                ['text' => 'Mata Uang', 'url' => 'setting/mata-uang', 'icon' => 'far fa-circle'],
                ['text' => 'User & Hak Akses', 'url' => 'setting/user', 'icon' => 'far fa-circle'],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
