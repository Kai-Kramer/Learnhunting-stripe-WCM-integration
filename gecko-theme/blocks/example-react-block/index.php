<?php
// Create id attribute allowing for custom "anchor" value.
$id = 'example-react-block-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'example-react-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php if ($is_preview): ?>
       <div style="padding: 3rem; border: 1px dashed salmon">
            [ example react block ]
        </div>
    <?php endif ?>
</div>
