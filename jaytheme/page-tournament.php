<!-- Check if a user is logged in -->
<?php if (is_user_logged_in()) : ?>
<!-- Loads header.php -->
<?php get_header(); ?>
</body>
<!-- Wrapper for bg -->
<body id="tournament-bg">

<div class="content">
<?php if ( !isset($_POST['heroes']) ): ?>
    <h1 class="title"><?php the_title(); ?></h1>
<?php else: ?>
    <h1 class="title">Contenders</h1>
<?php endif; ?>        

<?php
    session_start();

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
            $tagline = get_field ( 'tagline' );
            $table .= "<tr>".PHP_EOL;
            $table .= "<td>$name</td>".PHP_EOL;
            $table .= "<td>$tagline</td>".PHP_EOL;
            $table .= "<td><input type='checkbox' name='selected[]' value='$name' /></td>".PHP_EOL;
            $table .= "</tr>".PHP_EOL;
        }
        $table .= "</tbody>".PHP_EOL;
        return $table;
    }

    function set_tournament($contenders) 
    {
        $tournament = new Tournament();
        foreach ($contenders as $contender) {
            $name = $contender['name'];
            $power = $contender['power'];

            $hero = new Hero($name, $power);
            $tournament->add_hero($hero);
        }
        return $tournament;
    }    
    // End
    function loadContenders() 
    {
        $contenders = array();
        // $_POST['selected'] doesnt exist when called for the tournament.
        if (isset($_POST['selected'])) {
            $selected = $_POST['selected'];
        }

        $heroes = array( 'post_type' => 'my_heroes' );
        $loop = new WP_Query( $heroes );
        while ( $loop->have_posts() ) {
            $loop->the_post();
            // $name will be the post title which is the same as the Hero's name.
            $name = the_title('', '', false);
            // If one of the Hero posts is one of the selected contenders, add the data
            // to the contenders array.
            if ( in_array( $name, $selected )) {
                $id = get_the_id();
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

                $contenders[$name] = $contender;
            }
        }
        $_SESSION['contenders'] = $contenders;
        // Associative array of associative arrays..  ex: $contenders[$name]['description']
        return $contenders;
    }

    function displayContenders($contenders) 
    {
        // Wrapper for the contender previews
        echo "<div id='contender-wrapper'>".PHP_EOL;
        // Load the preview for each Hero entered as a contender.
        foreach ( $contenders as $contender ) {
            $displayName = $contender['name'];
            // Remove spaces for jQuery/CSS handling
            $codeName = str_replace(' ', '_', $displayName);

            $tagline = $contender['tagline'];
            $thumbnail = $contender['thumbnail'];
            $image = $contender['image'];
            echo "<div class='contender' data-targetHero='$codeName'>".PHP_EOL;
            echo "<h4>$displayName</h4>".PHP_EOL;
            echo "<p>$tagline</p>".PHP_EOL;
            echo "<img src='$thumbnail'>".PHP_EOL;
            echo "</div>".PHP_EOL;
        }
        echo "</div>".PHP_EOL;
        // Load details for each Hero/Contender -- this will be hidden until the hero is mouseovered.
        foreach ( $contenders as $contender) {
            $displayName = $contender['name'];
            // Remove spaces for jQuery/CSS handling
            $codeName = str_replace(' ', '_', $displayName);

            $description = $contender['description'];
            $image = $contender['image'];   
            // Wrapper for each hero's description.
            echo "<div class='hero-description-fixed $codeName'>".PHP_EOL;  
            // Text area.
            echo "<div class='hero-description-text'>".PHP_EOL;
            echo "<h3>$displayName</h3>".PHP_EOL;
            echo "<p>$description</p>".PHP_EOL;
            // End text area.
            echo "</div>".PHP_EOL;
            // Separate div for the img.
            echo "<div id='hero-img'><img src='$image'></div>".PHP_EOL;
            // End img div
            // End wrapper.
            echo "</div>".PHP_EOL;      
        }
        echo "<div class='hero-description-fixed default-text'>PLACEHOLDER</div>".PHP_EOL;
    }


    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['heroes']) && !isset($_POST['test']) ) {
        $contenders = loadContenders();
        displayContenders($contenders);
    }
    // Keeping for reference for now.
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tournament_start']) ) {
        $contenders = $_SESSION['contenders'];
        $tournament = set_tournament($contenders);
        $tournament->run_tournament();
        $tournament_winner = $tournament->get_tournament_winner()->name;
        
        // Set the HTML for the rounds of the tournament.
        $rounds_html = "<ul>".PHP_EOL;
        foreach ( range(1, $tournament->count_rounds()) as $i ) {
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
        echo $rounds_html;
        echo "...and the winner is... $tournament_winner!";
        echo "</div>".PHP_EOL;
    }   
    // End reference.
?>
    <!-- Submit button to start tournament once the contenders/heroes are set -->
    <?php if ( isset($_POST['heroes']) ): ?>
    <div id="hero-fight-button">
        <form method="POST" action="/wp/tournament/"><!-- $PATH var won't work here because PHP is weird I dont know -->
            <input type="submit" name="tournament_start" value="FIGHT!">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
        </form>
    </div>
    <?php endif; ?>   
    <!-- End submit button -->     

    <!-- Hero selection table -->
    <?php if ( !isset($_POST['heroes']) ): ?>
    <div class='post'>
        <form method="POST" action=<?php $PATH . "/tournament/"; ?>>
            <table class='table'>
                <?php echo build_table(array("Hero Name", "About", "Select")); ?>
            </table>
            <input type="submit" name="heroes" value="Lets Go!!">
        </form>
    </div> 
    <?php endif; ?>     
    <!-- End table -->
</div>
<?php endif; ?>
<?php wp_footer(); ?>