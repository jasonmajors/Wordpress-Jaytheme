<!-- Check if a user is logged in -->
<?php if (is_user_logged_in()) : ?>
<!-- Loads header.php -->
<?php get_header(); ?>
<div class="content">
	<h1>THE TOURNAMENT</h1>

<?php
	// Simple class to hold a Hero's name and a single attribute.
	// Could expand to hold multiple attributes.
	class Hero
	{
		public function __construct($attr, $name)
		{
			$this->attr = $attr;
			$this->name = $name;
		}
	}

	// Builds a matchup of two heroes.
	class Match
	{
		public function __construct($heroA, $heroB)
		{
			$this->heroA = $heroA;
			$this->heroB = $heroB;
			$this->winner = none;
		}
		// Set a match winner based off attribute(s)
		public function battle()
		{
			// heroA wins ties for now.
			if ($this->heroA->attr == $this->heroB->attr) {
				$this->winner = $this->heroA;
			}
			elseif ($this->heroA->attr > $this->heroB->attr) {
				$this->winner = $this->heroA;
			} else {
				$this->winner = $this->heroB;
			}

			return $this->winner;
		}
	}

	// Object to hold a set of matches.
	class Round
	{
		public function __construct(array $matches)
		{
			$this->matches = $matches;
			$this->winners = array();
		}

		// Iterate through matches, see who wins via Hero->battle(), return an array of the winners.
		public function play_round()
		{
			foreach ($this->matches as $match) {
				$winner = $match->battle();
				array_push($this->winners, $winner);
				$msg = $match->heroA->name . ' vs. ' . $match->heroB->name;
				$winner_msg = "<em>$winner->name wins!</em>";
				echo $msg;
				echo "<br>";
				echo $winner_msg;
				echo "<br><br>";
			}
			return $this->winners;
		}
	}


	class Tournament
	{
		public function __construct()
		{
			$this->heroes_remaining = array();
		}

		// Place the heroes remaining (heroes_remaining array) in the tournament into matches and those matches into a round.
		// Get the winners of the round and set that to the heroes remaining.
		public function solve_round()
		{
			$matches = array();
			for ($i = 0; $i < count($this->heroes_remaining); $i = $i + 2) {
				$heroA = $this->heroes_remaining[$i];
				$heroB = $this->heroes_remaining[$i + 1];
				$match = new Match($heroA, $heroB);
				array_push($matches, $match);
			}
			$round = new Round($matches);
			$winners = $round->play_round();
			$this->heroes_remaining = $winners;

			return $winners;
		}

		// Keep solving rounds until there is a winner.
		public function tournament()
		{
			// Bracket needs to be 4**x.
			if (count($this->heroes_remaining) % 4 != 0) {
				exit("Invalid bracket -- must be a power of 4!");
			}

			$round_num = 1;

			while (count($this->heroes_remaining) > 1) {
				echo "<strong>Round Number $round_num</strong></br></br>";
				$round_winners = $this->solve_round();
				
				if (count($round_winners) == 1) {
					$winner = $round_winners[0];
					echo "<h3>WINNER - $winner->name</h3>";
					break;
				}

				$round_num += 1;
			}
		}
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
			array_push($tournament->heroes_remaining, $hero);
		}
		$tournament->tournament();
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