<?php if (is_user_logged_in()) : ?>
<?php get_header(); ?>
<div class="content">
	<h1>THE TOURNAMENT</h1>

<?php
	class Team
	{
		public function __construct($attr, $name)
		{
			$this->attr = $attr;
			$this->name = $name;
		}
	}

	class Match
	{
		public function __construct($teamA, $teamB)
		{
			$this->teamA = $teamA;
			$this->teamB = $teamB;
			$this->winner = none;
		}

		public function battle()
		{
			if ($this->teamA->attr == $this->teamB->attr) {
				$this->winner = $this->teamA;
			}
			elseif ($this->teamA->attr > $this->teamB->attr) {
				$this->winner = $this->teamA;
			} else {
				$this->winner = $this->teamB;
			}

			return $this->winner;
		}
	}

	class Round
	{
		public function __construct(array $matches)
		{
			$this->matches = $matches;
			$this->winners = array();
		}

		public function play_round()
		{
			foreach ($this->matches as $match) {
				$winner = $match->battle();
				array_push($this->winners, $winner);
				$msg = $match->teamA->name . ' vs. ' . $match->teamB->name;
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
			$this->teams_remaining = array();
		}

		public function solve_round()
		{
			$matches = array();
			for ($i = 0; $i < count($this->teams_remaining); $i = $i + 2) {
				$teamA = $this->teams_remaining[$i];
				$teamB = $this->teams_remaining[$i + 1];
				$match = new Match($teamA, $teamB);
				array_push($matches, $match);
			}
			$round = new Round($matches);
			$winners = $round->play_round();
			$this->teams_remaining = $winners;

			return $winners;
		}

		public function tournament()
		{
			if (count($this->teams_remaining) % 4 != 0) {
				exit("Invalid bracket -- must be a power of 4!");
			}

			$round_num = 1;

			while (count($this->teams_remaining) > 1) {
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

	$tournament = new Tournament();
	$heroes = array( 'post_type' => 'my_heroes' );
	$loop = new WP_Query( $heroes );
	while ( $loop->have_posts() ) {
		$loop->the_post();
		$power = get_field( 'stats' );
		$name = the_title('', '', false);
		$hero = new Team($power, $name);
		array_push($tournament->teams_remaining, $hero);
	}
	$tournament->tournament();


?>

<?php endif; ?>
</div>