<!-- Check if a user is logged in -->
<?php if (is_user_logged_in()) : ?>
<!-- Loads header.php -->
<?php get_header(); ?>
<div class="content">
    <h1 class="title"><?php the_title(); ?></h1>

<?php
    function __autoload($classname)
    {
        $class = "classes/$classname.php";
        include $class;
    }
    function build_table($headings)
    {
        $heroes = array( 'post_type' => 'my_heroes' );
        $loop = new WP_Query( $heroes );  
        $table = "<thead>".PHP_EOL;
        $table .= "<tr>".PHP_EOL;

        foreach($headings as $header) {
            $table .= "<th>$header</th>".PHP_EOL;
        }
        $table .= "</tr>".PHP_EOL;
        $table .= "</thead>".PHP_EOL;
        $table .= "<tbody>".PHP_EOL;
        while ( $loop->have_posts() ) {
            $loop->the_post();
            $name = the_title('', '', false);
            $table .= "<tr>".PHP_EOL;
            $table .= "<td>$name</td>".PHP_EOL;
            $table .= "<td><input type='checkbox' name='selected[]' value='$name' /></td>".PHP_EOL;
            $table .= "</tr>".PHP_EOL;
        }
        $table .= "</tbody>".PHP_EOL;
        return $table;
    }

    function set_tournament() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected'])) {
            $selected = $_POST['selected'];
            $tournament = new Tournament();
            $heroes = array( 'post_type' => 'my_heroes' );
            $loop = new WP_Query( $heroes );
            // Create the heroes from the Hero custom post type and automatic custom fields.
            // Add them to the heroes_remaining array.
            while ( $loop->have_posts() ) {
                $loop->the_post();
                $power = get_field( 'stats' );
                $name = the_title('', '', false);
                foreach ( $selected as $entry ) {
                    if ( $name == $entry ) {
                        $hero = new Hero($power, $name);
                        $tournament->add_hero($hero);
                    }
                }
            } 
            return $tournament;
        }
    }    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $tournament = set_tournament();
        $tournament->run_tournament();
        echo "<strong>CONTENDERS</strong>: <br>";
        $contenders = $tournament->list_heroes('name');
        foreach ( $contenders as $contender ) {
            echo "<div id='contender'>" . $contender . "!" . " " . "</div>";
        }
        echo "<div id='rounds'>".PHP_EOL;
        echo "<ul>".PHP_EOL;
        foreach ( range(1, $tournament->get_round()) as $i ) {
            echo "<li>".PHP_EOL;
            echo "<strong>Round #$i - FIGHT</strong><br>".PHP_EOL;
            // Returns an array of matches from round i$ containing an array of 2 Heroes and the winner.
            $matches = $tournament->get_matches($i);

            foreach ( $matches as $match ) {
                $heroA = $match->heroA->name;
                $heroB = $match->heroB->name;
                $winner = $match->winner->name;
                echo "$heroA vs. $heroB! <br>".PHP_EOL;
                echo "$winner wins! <br><br>".PHP_EOL;
            }
            echo "</li>".PHP_EOL;
        }
        echo "</ul>".PHP_EOL;
        echo "</div>".PHP_EOL;
    }
        
?>

<?php endif; ?>

    <div class='post'>
        <form method="POST" action="http://localhost/wp/tournament/">
            <table class='table'>
                <?php echo build_table(array("Hero Name", "Select")); ?>
            </table>
            <input type="hidden" name="game_on" value="">
            <?php if (!isset($_POST["game_on"])) : ?>
                <input type="submit" value="Lets Go!!">
            <?php endif; ?> 
        </form>
    </div>      
</div>
<?php wp_footer(); ?>