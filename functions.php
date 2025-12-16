<?php defined('ABSPATH') || exit;

/* include class-tgm-plugin-activation */
require_once(get_template_directory() . '/class-tgm-plugin-activation.php');

/* include menu walker */
require_once(get_template_directory() . '/class-fsd-walker.php');

/* include main theme class */
require_once(get_template_directory() . '/class-fsd-theme.php');

/* theme instance */
$FSD_Theme = new FSD_Theme([
    /* theme version */
    'version' => '2.5',
    /* theme name */
    'name' => esc_html__('Levre', 'levre'),
]);

function render_harga_grosir_table() {

    if (!have_rows('harga_grosir')) return '';

    // ambil tipe size dari product
    $size_type = get_field('size_type');

    // default jika kosong
    $size_label_text = 'Size';
    if ($size_type) {
        $size_label_text .= ' (' . esc_html($size_type) . ')';
    }

    // array untuk memetakan size ke row
    $sizes = [];
    while (have_rows('harga_grosir')) : the_row();
        $size = get_sub_field('size');
        $min_order = get_sub_field('min_order');
        $harga = get_sub_field('harga');

        $sizes[$size][] = [
            'min_order' => $min_order,
            'harga' => $harga,
        ];
    endwhile;

    ob_start(); ?>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th><?php echo $size_label_text; ?></th>
                <th>Minimal Order</th>
                <th>Harga</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $color_counter = 0;
            foreach ($sizes as $size_name => $rows) :
                $bg_color = ($color_counter % 2 == 0) ? '#f5f5f5' : '#e0e0e0';
                $color_counter++;
                $first_row = true;
                $rowspan = count($rows);
                foreach ($rows as $row) :
                    echo '<tr style="background:' . $bg_color . '">';
                    if ($first_row) {
                        echo '<td rowspan="' . $rowspan . '">' . esc_html($size_name) . '</td>';
                        $first_row = false;
                    }
                    echo '<td>' . esc_html($row['min_order']) . '</td>';
                    echo '<td>' . esc_html($row['harga']) . '</td>';
                    echo '</tr>';
                endforeach;
            endforeach;
            ?>
            </tbody>
        </table>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('harga_grosir_table', 'render_harga_grosir_table');
