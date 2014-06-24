<!-- Check if a user is logged in -->
<?php if (is_user_logged_in()) : ?>
<!-- Loads header.php -->
<?php get_header(); ?>
<div class="content">
    <h1>THE TOURNAMENT</h1>

<?php
    function __autoload($classname)
    {
        $class = "classes/$classname.php";
        include $class;
    }

    // Wrap the if/then in main() function.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //function set_tourny()
        $tournament = new Tournament();
        $heroes = array( 'post_type' => 'my_heroes' );
        $loop = new WP_Query( $heroes );
        // Create the heroes from the Hero custom post type and automatic custom fields.
        // Add them to the heroes_remaining array.
        while ( $loop->have_posts() ) {
            $loop->the_post();
            $power = get_field( 'stats' );
            $name = the_title('', '', false);
            $hero = new Hero($power, $name);
            $tournament->add_hero($hero);
        }
        $tournament->tournament();
        //end function    

        // Just testing out some logic -- need to clean this all up.

        echo "CONTENDERS: <br>";
        $contenders = $tournament->list_heroes('name');
        foreach ( $contenders as $contender ) {
            echo $contender . "!". " ";
        }
        echo "<br><br>";

        foreach ( range(1, $tournament->get_round()) as $i ) {
            echo "<strong>Round #$i - FIGHT</strong><br>";
            // Returns an array of matches from round i$ containing an array of 2 Heroes.
            $matches = $tournament->get_matches($i);

            foreach ( $matches as $match ) {
                $heroA = $match->heroA->name;
                $heroB = $match->heroB->name;
                $winner = $match->winner->name;
                echo "$heroA vs. $heroB! <br>";
                echo "$winner wins! <br><br>";
            }
        }
        // Returns an array with round numbers as keys to arrays of match objects with
        // teamA/B and winner objects as attributes.
        
        

    }
        
?>

<?php endif; ?>

    <div>
        <form method="POST" action="http://localhost/wp/tournament/">
            <input type="hidden" name="game_on" value="">
            <?php if (!isset($_POST["game_on"])) : ?>
                <input type="submit" value="Lets Go!!">
            <?php endif; ?> 
        </form>
    </div>      
</div>