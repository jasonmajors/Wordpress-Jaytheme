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
				$winner_msg = "$winner->name wins!";
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
				$err_msg = "Invalid Bracket";
				echo $err_msg;
			}

			$round_num = 1;

			while (count($this->teams_remaining) > 1) {
				echo "Round Number $round_num </br>";
				$round_winners = $this->solve_round();
				
				if (count($round_winners) == 1) {
					$winner = $round_winners[0];
					echo "WINNER - $winner->name";
					break;
				}

				$round_num += 1;
				}

		}
	}

$team1 = new Team(3, 'team#1');
$team2 = new Team(4, 'team#2');
$team3 = new Team(5, 'team#3');
$team4 = new Team(6, 'team#4');

$tournament = new Tournament();

array_push($tournament->teams_remaining, $team1, $team2, $team3, $team4);
$tournament->tournament();

?>

<?php endif; ?>
</div>