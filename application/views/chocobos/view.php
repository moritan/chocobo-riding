<h1><?php echo Kohana::lang('chocobo.title_view'); ?></h1>

<p><?php echo Kohana::lang('chocobo.name'); ?> : <?php echo $chocobo->name; ?></p>

<p>Jockey : <?php echo $chocobo->user->name; ?></p>
<p>Genre : <?php echo $chocobo->gender(); ?></p>

<!-- CLASSE -->
<p>Classe : <?php echo Kohana::lang('chocobo.classes.' . $chocobo->classe); ?> 
<small>
<?php
$nexts = array(10, 30, 50, 70, 90); 
?>
Prochaine classe : niv<?php echo $nexts[$chocobo->classe - 1]; ?>
</small>
</p>

<p>Couleur : <?php echo $chocobo->colour(); ?></p>
<p>Job : <?php echo $chocobo->job(); ?></p>

<p>Vitesse : <?php echo $chocobo->speed; ?></p>
<p>Intelligence : <?php echo $chocobo->intel; ?></p>
<p>Endurance : <?php echo $chocobo->endur; ?></p>

<p>Niveau : <?php echo $chocobo->level .' /'. $chocobo->level_max; ?></p>
<p>Expérience : <?php echo $chocobo->exp; ?> /<?php echo $chocobo->level *100; ?> xp</p>
<p>Côte : <?php echo number_format($chocobo->fame, 2); ?></p>

<!-- HP -->
<p>HP : <?php echo $chocobo->hp .' /'.$chocobo->hp_max; ?> 
<small>
<?php 
$hp_ratio = floor($chocobo->hp / $chocobo->hp_max);
if ($hp_ratio > 0.9)
{
	echo 'Bonus vitesse +10%';
} 
else if ($hp_ratio < 0.5)
{
	echo 'Malus vitesse -50%';
}
?>
</small>
</p>

<p>MP : <?php echo $chocobo->mp .' /'.$chocobo->mp_max; ?></p>
<p>Rage : <?php echo $chocobo->rage .' /'.$chocobo->rage_max; ?></p>