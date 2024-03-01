<?php
class LearnHuntingCheckout
{
    public $student_product_id;
    public $mentor_product_id;

    public $is_student;
    public $is_mentor;

    private $req_html;

    public $game_types;
    public $hunting_types;
    public $instructor_types;

    public function __construct($check_cart_contents = true)
    {
        $this->student_product_id = 303;
        $this->mentor_product_id = 304;

        if ($check_cart_contents) {
            if (!WC()->cart->is_empty() && $cart_items = WC()->cart->get_cart()) {
                foreach ($cart_items as $cart_item) {
                    if ($cart_item["product_id"] === $this->mentor_product_id) {
                        $this->is_student = false;
                        $this->is_mentor = true;
                    } else if ($cart_item["product_id"] === $this->student_product_id) {
                        $this->is_student = true;
                        $this->is_mentor = false;
                    }
                }
            }
        }

        $this->req_html = ' <abbr class="required" title="required">*</abbr>';

        $this->game_types = [
            "deer" => "Deer",
            "turkey" => "Turkey",
            "waterfowl" => "Waterfowl (goose, duck, etc.)",
            "small_game" => "Small Game (squirrel, rabbit, etc.)",
            "elk" => "Elk",
            "upland_bird" => "Upland Bird (pheasant, grouse, etc.)",
            "bear" => "Bear",
            "hog" => "Hog",
            "antelope" => "Antelope",
            "predators (coyote, wolf, etc.)" => "Predators (coyote, wolf, etc.)",
            "moose" => "Moose",
            "sheep_or_goat" => "Sheep or Goat",
            "alligator" => "Alligator",
        ];

        if ($custom_game_types = get_field("game_types", "option")) {
            $this->game_types = [];

            foreach ($custom_game_types as $custom_game_type) {
                $this->game_types[$custom_game_type["key"]] = $custom_game_type["value"];
            }
        }

        $this->hunting_types = [
            "rifle" => "Rifle",
            "shotgun" => "Shotgun",
            "bow" => "Bow",
            "crossbow" => "Crossbow",
            "muzzleloader" => "Muzzleloader",
            "trapping" => "Trapping",
        ];

        if ($custom_hunting_types = get_field("hunting_types", "option")) {
            $this->hunting_types = [];

            foreach ($custom_hunting_types as $custom_hunting_type) {
                $this->hunting_types[$custom_hunting_type["key"]] = $custom_hunting_type["value"];
            }
        }

        $this->instructor_types = [];

        if ($custom_instructor_types = get_field("instructor_types", "option")) {
            $this->instructor_types = [];

            foreach ($custom_instructor_types as $custom_instructor_type) {
                $this->instructor_types[$custom_instructor_type["key"]] = $custom_instructor_type["value"];
            }
        }
    }

    public function gameTypeInputs()
    {
        echo '<div><strong>Game Types</strong>'.$this->req_html.'</div>';

        foreach ($this->game_types as $value => $label) {
            echo '<label for="'.$value.'">';
                echo '<input type="checkbox" name="game_type[]" value="'.$value.'" id="'.$value.'" />';
                echo '&nbsp;&nbsp;<span>' . $label . '</span>';
            echo '</label>';
        }
    }

    public function huntingTypeInputs()
    {
        echo '<div><strong>Hunting Types</strong>'.$this->req_html.'</div>';

        foreach ($this->hunting_types as $value => $label) {
            echo '<label for="'.$value.'">';
                echo '<input type="checkbox" name="hunting_type[]" value="'.$value.'" id="'.$value.'" />';
                echo '&nbsp;&nbsp;<span>' . $label . '</span>';
            echo '</label>';
        }
    }

    public function instructorTypeInputs()
    {
        echo '<div><strong>Instructor Type</strong>'.$this->req_html.'</div>';

        foreach ($this->instructor_types as $value => $label) {
            echo '<label for="'.$value.'">';
                echo '<input type="checkbox" name="instructor_type[]" value="'.$value.'" id="'.$value.'" />';
                echo '&nbsp;&nbsp;<span>' . $label . '</span>';
            echo '</label>';
        }
    }

    public function availabilityInputs()
    {
        echo '<div><strong>Availability</strong>'.$this->req_html.'</div>';

        echo '<label for="is_available_yes">';
            echo '<input type="radio" name="is_available" value="yes" id="is_available_yes" checked="checked" />';
            echo '&nbsp;&nbsp;<span>I would like to mentor others</span>';
        echo '</label>';

        echo '<label for="is_available_no">';
            echo '<input type="radio" name="is_available" value="no" id="is_available_no" />';
            echo '&nbsp;&nbsp;<span>I am not ready to mentor others</span>';
        echo '</label>';
    }
}
