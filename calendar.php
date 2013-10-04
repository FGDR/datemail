<link rel="stylesheet" href="stylebase.css">

<table>
	<tr>
		<th>Dim</th>
		<th>Lun</th>
		<th>Mar</th>
		<th>Mer</th>
		<th>Jeu</th>
		<th>Ven</th>
		<th>Sam</th>
	</tr>
	<?php

	$nbJoursMois=date('t');

	for ($i=1; $i <= $nbJoursMois; $i++) {
		$today='';

		$dayWeek=date('w',mktime('0','0','0',date('n'),$i));

		if($dayWeek==0)
		{
			echo '<tr>';
		}

		if($i==1 && $dayWeek!=0)
		{
			for ($j=0; $j < $dayWeek; $j++) {
				echo "<td></td>";
			}
		}

		if($i==date('w')-1)
			$today='class="today"';

		echo "<td $today>$i</td>";

		if($dayWeek==6)
		{
			echo '</tr>';
		}

	}

	?>
</tr>
</table>