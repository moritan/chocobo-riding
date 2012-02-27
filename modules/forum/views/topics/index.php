<style>
	.infos {margin: 20px 0 0 0; border-bottom: 1px solid #ddd;}
	.infos .pages {color: #999; font-weight: bold; float: right; font-size: 16px;}
	.infos .nbr_topics {font-size: 16px;}
	.infos .nbr_topics .nbr {color: #999;}
	
	.topics {width: 100%; margin: 0 0 20px 0;}
	.topic {border-bottom: 1px solid #ddd; position: relative;}
	.topic .left {width: 64px; float: left; margin: 14px 0 0 18px;}
	.topic .right {width: 650px; float: left; margin: 14px 0 14px 14px;}
	.topic .author {margin: 1px 0 1px 0;}
	.topic .title {line-height: 1.28; margin: 5px 0 5px 0;}
	.topic .footer {color: #999; line-height: 17px;}
	.topic .date {float: left;}
	.topic .icon {margin-bottom: -3px; cursor: pointer;}
	.topic .icon2 {margin: 0 3px -2px 0;}
	
	.topic .favon {font-weight: bold; font-style: italic; color: #333;}
	.topic .hidden {display: none;}
	
	.options {position: absolute; top: 14px; right: 5px; display: none; width: 250px; text-align: right; padding-right: 5px;}
	.options a, .options a:visited {text-decoration: none; color: #666; font-style: italic;}
	.options a:hover {text-decoration: underline;}
</style>

<h1>Forum</h1>

<div class="infos">
	
	<div class="pages">
		<b><?php echo $pagination->render() ?></b>
	</div>
	
	<div class="nbr_topics">
		<?php 
		echo ' <span class="nbr">' . count($topics) . '</span> ';
		echo Kohana::lang('topic.' . inflector::plural('topic', count($topics)));
		if ($tags != 'all') 
		{
			echo ' comportant le tag #' . $tags;
		}
		?>
	</div>
	
</div>

<div class="clearright"></div>

<div class="topics">
	
	<?php
	foreach ($topics as $n => $t) 
	{
		$topic = ORM::factory('topic', $t->id);
		$nbr_comments = count($topic->comments);
		$first_comment = $topic->comments[0];
		$last_comment = $topic->comments[$nbr_comments - 1];
		$notified = false;
	?>
		
	<div class="topic" id="topic<?php echo $topic->id ?>">
		<div class="options">
			<?php
			if ($topic->allow($user, 'w'))
			{
				echo html::anchor('topics/' . $topic->id . '/edit', 
					html::image('images/icons/edit.png', array('class' => 'icon', 'title' => 'Modifier', 'rel' => 'tipsy'))) . ' | ';
				echo html::anchor('#', 
					html::image('images/icons/delete.png', array('class' => 'icon', 'title' => 'Supprimer', 'rel' => 'tipsy')), 
						array('class' => 'delete_topic', 'id'=>'topic' . $topic->id));
			}
			?>
		</div>
		<div class="left">
			<?php echo $last_comment->user->picture(50) ?>
    	</div>
    	<div class="right">
    		<div class="author">
    			<?php 
    			$nbr_comments = count($topic->comments) - 1;
    			
				$last_url = $last_comment->url();
				
				if ($nbr_comments == 0)
				{
					echo $first_comment->user->link() . ' a créé un nouveau sujet : ';
				}
				else
				{
					echo $last_comment->user->link() . ' a posté ';
    				echo html::anchor($last_url, 'un commentaire') . ' sur le sujet : ';
				}
    			?>
    		</div>
    		
    		<div class="title">
	    		<?php 
	    		$notifications = $topic->get_notifications($user->id);
				$nbr_notifications = count($notifications);
				$new = ($nbr_notifications > 0) ? 'notif_new': 'notif_nonew';
				$nbr_comments = ($nbr_notifications > 0) ? $nbr_notifications: $nbr_comments;
				
				if ($nbr_comments > 0) 
				{
					echo '<span class="notif '.$new.'">' . $nbr_comments . '</span> ';
				}
				
	    		echo html::anchor('topics/' . $topic->id, $topic->title, array('class' => 'topictitle'));
	    		echo $topic->display_view_tags();
				?>
    		</div>
    		
    		<div class="footer">
	    		<?php
	    		echo date::display(max($first_comment->created, $first_comment->updated));
	    		
	    		if ($first_comment->updated > $first_comment->created) 
				{
					echo ' (modification)';
				}
				
	    		$nb_interests = $this->db
					->where('comment_id', $first_comment->id)
					->count_records('comments_favorites');
				
				$hidden = ($nb_interests == 0) ? 'hidden' : '';
				$favtext = ($user->has(ORM::factory('c_favorite', $first_comment->id))) ? 'new': 'empty';
				
				if ($user->loaded or $nb_interests > 0)
				{
					echo '<span id="nbfavs' . $first_comment->id . 'w" class="nbfavsw '.$hidden.'">';
					echo ' &nbsp; <span id="nbfavs' . $first_comment->id . '" class="nbfavs favon">+' . $nb_interests . '</span>';
			      	echo '</span>';
				}
						      	
		      	if ($user->loaded)
	      		{
			      	echo '<span class="favw" style="display: none;">';
			      	echo ' &nbsp; ' . html::image('images/forum/star-' . $favtext . '.png', array('id' => 'fav' . $first_comment->id, 'class' => 'fav icon'));
					echo '</span>';
				}
				
				?>
			</div>
    	</div>
		<div class="clearleft"></div>
	</div>
		
	<?php
	}
	?>

</div>

<?php 
if ($user->loaded) 
{
	echo html::anchor('topics/new', 'Créer un nouveau sujet', array('class' => 'button blue')); 
}
?>

<script>
$(document).ready(function(){

	$('*[rel=tipsy]').tipsy({gravity: 's'});
	
	// Afficher les +1/-1
	$('.topic').hover(function(){
		$(this).find('.nbfavsw').show();
		$(this).find('.favw').show();
		$(this).find('.options').fadeIn('slow');
	}, function(){
		var nbfavsw = $(this).find('.nbfavs');
		if (nbfavsw.text() == '+0') {
			$(this).find('.nbfavsw').hide();
		}
		$(this).find('.favw').hide();
		$(this).find('.options').hide();
	});
	
	// mettre en favori un topic
	$('.fav').click(function(){
		var id = $(this).attr('id').substring(3);
		$('#fav' + id).attr('src', baseUrl + 'images/forum/loading.gif');
		$.ajax({
			type: 'POST',
			url: baseUrl + 'comments/' + id + '/favorite/',
			dataType: 'json',
			success: function(data) {
				var nbfavs = parseInt($('#nbfavs' + id).text());
				nbfavs = (data.icon == 'new') ? nbfavs + 1: nbfavs - 1;
				$('#nbfavs' + id).text('+' + nbfavs);
				
				$('#fav' + id).attr('src', baseUrl + 'images/forum/star-' + data.icon + '.png');
			}
		});
		return false;
	});
		
	$('.delete_topic')
		.click(function(){
			var topic_id = $(this).attr('id').substring(5);
			$.post(baseUrl + 'topics/delete', {'id': topic_id}, function(data){
				if (data.success) {
					$('#topic' + topic_id).slideUp();
				}
			});
			return false;
		});

});
</script>
