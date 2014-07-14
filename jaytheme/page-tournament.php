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
    
    function loadContenders() {
        $contenderData = array();
        $selected = $_POST['selected'];
        $heroes = array( 'post_type' => 'my_heroes' );
        $loop = new WP_Query( $heroes );
        while ( $loop->have_posts() ) {
            $loop->the_post();
            $name = the_title('', '', false);
            // If one of the Hero posts is one of the selected contenders, add the data
            // to the contenderData array.
            if ( in_array( $name, $selected )) {
                $power = get_field( 'stats' );
                $tagline = get_field( 'tagline' );
                $description = get_field( 'description' );
                $image = get_field( 'image' );
                $contender = array( "name" => $name, 
                                    "tagline" => $tagline,
                                    "description" => $description,
                                    "power" => $power,
                                    "image" => $image['url'],
                                    "thumbnail" => $image['sizes']['thumbnail'],
                                    );

                $contenderData[$name] = $contender;
            }
        }
        return $contenderData;
    }

    function displayContenders() {
        $contenders = loadContenders();  
        echo "<h2>CONTENDERS:</h2>".PHP_EOL;
        // Wrapper for the contender previews
        echo "<div id='contender-wrapper'>".PHP_EOL;
        // Load the preview for each Hero entered as a contender.
        foreach ( $contenders as $contender ) {
            $name = $contender['name'];
            // Remove spaces for jQuery/CSS handling
            $name_class = str_replace(' ', '_', $name);
            $tagline = $contender['tagline'];
            $thumbnail = $contender['thumbnail'];
            $image = $contender['image'];
            echo "<div class='contender' data-targetHero='$name_class'>".PHP_EOL;
            echo "<h4>$name</h4>".PHP_EOL;
            echo "<p>$tagline</p>".PHP_EOL;
            echo "<img src='$thumbnail'>".PHP_EOL;
            echo "</div>".PHP_EOL;
        }
        echo "</div>".PHP_EOL;
        // Loading details for each Hero/Contender -- this will be hidden until the hero is mouseovered.
        foreach ( $contenders as $contender) {
            $name = $contender['name'];
            // Remove spaces for jQuery/CSS handling
            $name_class = str_replace(' ', '_', $name);
            $description = $contender['description'];
            $image = $contender['image'];   
            echo "<div class='hero-description-fixed $name_class'>".PHP_EOL;  
            echo "<h3>$name</h3>".PHP_EOL;
            echo "<p>$description</p>".PHP_EOL;
            echo "<div id='hero-img'><img src='$image'></div>".PHP_EOL;
            echo "</div>".PHP_EOL;      
        }
    }



    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['heroes']) ) {
        displayContenders();
    }
    // Keeping for reference for now.
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test'])) {
        $tournament = set_tournament();
        $tournament->run_tournament();
        // Returns an array of the Hero obj's 'name' attributes.
        $contenders = $tournament->list_heroes('name');
        // Set up HTML for Contenders div. TODO - Make less weird.
        echo "<h4>CONTENDERS</h4>".PHP_EOL;
        $contenders_html = "<ul>".PHP_EOL;
        foreach ( $contenders as $contender ) {
            $contenders_html .= "<li>$contender</li>".PHP_EOL;
        }
        $contenders_html .= "</ul>".PHP_EOL;
        
        // Set the HTML for the rounds of the tournament.
        $rounds_html = "<ul>".PHP_EOL;
        foreach ( range(1, $tournament->get_round()) as $i ) {
            $rounds_html .= "<li>".PHP_EOL;
            $rounds_html .= "<strong>Round #$i - FIGHT</strong><br>".PHP_EOL;
            // Returns an array of matches from round i$ containing an array of 2 Heroes and the winner.
            $matches = $tournament->get_matches($i);

            foreach ( $matches as $match ) {
                $heroA = $match->heroA->name;
                $heroB = $match->heroB->name;
                $winner = $match->winner->name;
                $rounds_html .= "$heroA vs. $heroB! <br>".PHP_EOL;
                $rounds_html .= "$winner wins! <br><br>".PHP_EOL;
            }
            $rounds_html .= "</li>".PHP_EOL;
        }
        $rounds_html .= "</ul>".PHP_EOL;
        echo "<div class='round-content'>".PHP_EOL;
        echo $contenders_html;
        echo $rounds_html;
        echo "</div>".PHP_EOL;
    }   
    // End reference.
?>


    <?php if ( !isset($_POST['heroes']) ): ?>
    <div class='post'>
        <form method="POST" action="http://localhost/wp/tournament/">
            <table class='table'>
                <?php echo build_table(array("Hero Name", "Select")); ?>
            </table>
            <input type="submit" name="heroes" value="Lets Go!!">
        </form>
    </div> 
    <?php endif; ?>     
</div>
<?php endif; ?>
<?php wp_footer(); ?>