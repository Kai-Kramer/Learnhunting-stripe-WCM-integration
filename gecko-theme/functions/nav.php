<?php
namespace GeckoTheme;

class Simple_Nav_Walker extends \Walker_Nav_Menu
{
	function start_lvl(&$output, $depth = 0, $args = [])
	{
		if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat($t, $depth);

		// Default class.
		$classes = [$args->menu_class . '__sub-menu'];

		if ($depth == 0) {
			$classes[] = $args->menu_class . '__sub-menu--top-level';
		}

		$class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$output .= "{$n}{$indent}<div$class_names>{$n}";
	}

	public function end_lvl(&$output, $depth = 0, $args = [])
	{
		if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat($t, $depth);
		$output .= "$indent</div>{$n}";
	}

	function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
	{
		$title = $item->title;
		$permalink = $item->url;

		$target = (isset($item->target) && !empty($item->target)) ? $item->target : "";

		if ($depth == 0) {
			$item->classes[] = 'menu-item--top-level';
		} else {
			$item->classes[] = 'menu-item--n-level';
		}

		$output .= '<a class="' .  implode(" ", $item->classes) . '" href="' . $permalink . '" target="' . $target . '">';
		$output .= '<span>';
		$output .= $title;
		$output .= '</span>';
		$output .= '</a>';
	}

	public function end_el(&$output, $item, $depth = 0, $args = [])
	{
		if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		// $output .= "</a>{$n}";
	}
}
