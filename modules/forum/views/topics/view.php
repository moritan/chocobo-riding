<style>
	.infos {margin: 20px 0 0 0; border-bottom: 1px solid #ddd;}
	.infos .pages {color: #999; font-weight: bold; float: right; font-size: 16px;}
	.infos .nbr_topics {font-size: 16px;}
	.infos .nbr_topics .nbr {color: #999;}
	
	.comments {width: 100%; margin: 0 0 20px 0; color: #333;}
	.comment {border-bottom: 1px solid #e9e9e9;}
	.comment .avatar {width: 64px; float: left; margin: 14px 0 0 18px;}
	.comment .right {width: 650px; float: left; margin: 14px 0 14px 14px;}
	.comment .author {margin: 1px 0 1px 0; font-weight: bold;}
	.comment .notifications {line-height: 1.28; margin: 5px 0 5px 0; color: #900;}
	.comment .title {font-weight: bold; line-height: 1.28; margin: 5px 0 5px 0;}
	.comment .text {line-height: 1.28; margin: 5px 0 5px 0;}
	.comment .form {display: none; margin: 5px 0 5px 0;}
	.comment .footer {color: #999; line-height: 17px;}
	.comment .icon {margin-bottom: -3px; cursor: pointer;}
	.comment .icon2 {margin: 0 3px -2px 0;}
	
	.reply {width: 100%; margin: 20px 0 20px 0;}
	.reply .avatar {width: 64px; float: left; margin: 14px 0 0 18px;}
	.reply .textarea {float: left; width: 450px; margin-left: 14px;}
	.reply textarea {width: 450px; height: 150px; outline: none; resize: none; padding: 3px; border-color: #899BC1; color: #333;}
	.reply .submit {float: left;}
	
	.reply2 {width: 100%; margin: 5px 0 5px 0;}
	.reply2 .textarea {float: left; width: 450px;}
	.reply2 textarea {width: 450px; height: 150px; outline: none; resize: none; padding: 3px; border-color: #899BC1;}
	.reply2 .submit {float: left;}
	
	.comment .favon {font-weight: bold; font-style: italic; color: #333;}
	.comment .hidden {display: none;}
</style>

<h1>Forum</h1>

<div class="clearright"></div>
	
<div class="comments">
<?php
$tr = 0;
$last_user_id = 0;
$first_comment_id = $topic->comments[0]->id;
$last_dark = " error";
foreach ($comments as $n => $comment) 
{
	
	$inc = true;
	$dark = ($tr % 2 == 0) ? ' dark' : "";
	if ($comment->user->has_role('modo')) {$dark = ' modo'; $inc = false;}
	if ($comment->user->has_role('admin')) {$dark = ' admin'; $inc = false;}
	if ($last_user_id == $comment->user_id) {$dark = $last_dark; $inc = false;}
	if ($inc) {
		$tr ++;
	}
	?>
<div id="c<?php echo $comment->id ?>"></div>
<div class="comment">
	<div class="avatar">
		<?php
		if ($last_user_id !== $comment->user_id) 
		{
			echo $comment->user->picture(50);
		}
    	?>
	</div>
	<div class="right<?php echo $dark ?>" id="c<?php echo $comment->id ?>">
		<div class="author">
			<?php echo $comment->user->link() ?>
        </div>
		
		<div class="notifications">
			<?php
			if ($user->has(ORM::factory('c_notification', $comment->id))) 
			{
				$user->remove(ORM::factory('c_notification', $comment->id));
				$user->save();
				echo html::image('images/forum/post.png', array('class' => 'icon2'));
				echo 'Message non lu';
			}
			?>
		</div>
		
		<div class="title">
			<?php
			if ($n == 0)
			{
				echo $topic->title;
				echo $topic->display_view_tags();
			}
			?>
		</div>
		
		<div class="text">
			<?php 
			//$textile = new Textile;
			//$content = $textile->TextileThis($comment->content);
			echo nl2br($comment->content);
			?>
		</div>
		
		<?php if ($n > 0 and $comment->user_id == $user->id): ?>
			<div class="form">
				<div class="reply2">
				<?php echo form::open('comments/' . $comment->id . '/edit') ?>
				<div class="textarea"><?php echo form::textarea('content-edit', $comment->content) ?></div>
				<div class="submit">
					<div class="button blue submit" id="c<?php echo $comment->id ?>">Modifier</div><br />
					<div class="button grey cancel" id="c<?php echo $comment->id ?>">Annuler</div>
				</div>
				<?php echo form::close() ?>
				</div>
				<div class="clearleft"></div>
			</div>
		<?php endif; ?>
		
    	<div class="footer">	
			<?php
			
			echo '<span class="date">' . date::display(max($comment->created, $comment->updated)) . '</span>';
			
			if ($comment->updated > $comment->created) 
			{
				echo ' (modification)';
			}
			
			if ($n == 0 and $topic->allow($user, 'w'))
			{
				echo ' · ' . html::anchor('topics/' . $topic->id . '/edit', 'Modifier');   		
			}
			else if ($n > 0 and $comment->user_id == $user->id)
			{
				echo ' · ' . html::anchor('#', 'Modifier', array('class' => 'edit'));   		
			}
						
			$nb_interests = $this->db
				->where('comment_id', $comment->id)
				->count_records('comments_favorites');
			
			$hidden = ($nb_interests == 0) ? 'hidden' : '';
			$favtext = ($user->has(ORM::factory('c_favorite', $comment->id))) ? 'new': 'empty';
			
			if ($user->loaded or $nb_interests > 0)
			{
				echo '<span id="nbfavs' . $comment->id . 'w" class="nbfavsw '.$hidden.'">';
				echo ' &nbsp; <span id="nbfavs' . $comment->id . '" class="nbfavs favon">+' . $nb_interests . '</span>';
		      	echo '</span>';
			}
				      	
	      	if ($user->loaded)
	      	{
		      	echo '<span class="favw" style="display: none;">';
		      	echo ' &nbsp; ' . html::image('images/forum/star-' . $favtext . '.png', array('id' => 'fav' . $comment->id, 'class' => 'fav icon'));
				echo '</span>';
			}
				      	
			/*if ( $comment->topic->allow($user, 'w') ) 
			{
				echo ' · ' . html::anchor('#', 'éditer');
				echo ' · ' . html::anchor('#', 'supprimer');
			}*/
			?>
		</div>
    </div>
    <div class="clearleft"></div>
</div>

<?php if ($n == 0): ?>
	<div class="infos">
		
		<div class="pages">
			<b><?php echo $pagination->render() ?></b>
		</div>
		
		<div class="nbr_topics">
			<?php 
			echo ' <span class="nbr">' . (count($comments) - 1) . '</span> ';
			echo Kohana::lang('topic.' . inflector::plural('comment', count($comments) - 1));
			?>
		</div>
		
	</div>
<?php endif; ?>
	
	<?php
	$last_user_id = $comment->user_id;
	$last_dark = $dark;
}
?>
</div>

<?php if ( ($user->loaded and ($user->has_role('modo') or ! $topic->locked)) ) : ?>

	<?php echo form::open('comments/new', array(), array('topic_id' => $topic->id)) ?>
	<div class="reply">
		<div class="avatar">
		
		</div>
		<div class="textarea">
			<?php echo form::textarea(array(
	        	'id' => 'textile', 
	        	'placeholder' => 'Un commentaire ?', 
	        	'name' => 'content', 
	        	'value' => ''
	        )) ?>
		</div>
		<div class="submit">
			<?php echo form::submit(array(
		    	'name' => 'submit', 
		    	'id' => 'submit', 
		    	'class' => 'button blue',
		    	'value' => 'Poster'
		    )) ?>
		</div>
	</div>
	<?php echo form::close() ?>	
	<div class="clearleft"></div>
	
<?php endif; ?>

<script>

$(function(){

	// Afficher les +1/-1
	$('.comment').hover(function(){
		$(this).find('.nbfavsw').show();
		$(this).find('.favw').show();
	}, function(){
		var nbfavsw = $(this).find('.nbfavs');
		if (nbfavsw.text() == '+0') {
			$(this).find('.nbfavsw').hide();
		}
		$(this).find('.favw').hide();
	});
	
	// mettre en favori un topic
	$('.fav').click(function(){
		var id = $(this).attr('id').substring(3);
		$('#fav' + id).attr('src', baseUrl + 'images/forum/loading.gif');
		$.ajax({
			type: 'POST',
			url: baseUrl + 'comments/' + id + '/favorite',
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
	
	$('.edit').click(function(){
		$(this).parent().hide();
		$(this).parent().prev().show();
		$(this).parent().prev().prev().hide();
		return false;
	});
	
	$('.submit').click(function(){
		var id = $(this).attr('id').substring(1);
		var content = $('#c' + id + ' textarea[name=content-edit]').val();
		$.post(baseUrl + 'comments/' + id + '/edit', {'content': content}, function(data){
			$('#c' + id + ' .form').hide();
			$('#c' + id + ' .text').show().html(data.text);
			$('#c' + id + ' .footer').show();
			$('#c' + id + ' .footer .date').html(data.date + ' (modification)');
		});
		return false;
	});
	
	$('.cancel').click(function(){
		var id = $(this).attr('id');
		$('#' + id + ' .form').hide();
		$('#' + id + ' .text').show();
		$('#' + id + ' .footer').show();
		return false;
	});
	
});

</script>