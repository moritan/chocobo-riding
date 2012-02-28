/*
 * Compte à rebours
 *
 * @param	integer		Sélecteur CSS
 * @param	integer		Nombre de secondes restantes
 * @param	string		Texte à afficher quand le compte à rebours est terminé
 * @param	boolean		Affiche ou pas le temps restant dans le title (sauf IE)
 */
var countdown = function (id, tps, end, title) 
{
    if (tps >= 0) {
		
		if (tps >= 60) {
			minutes = Math.floor(tps/60) % 60;
			if (minutes<10) {minutes = '0' + minutes;}
		} else {minutes = "00";}
		
		if (tps >= 0) {
			secondes = tps % 60;
			if (secondes<10) {secondes = '0' + secondes;}
			secondes = ":" + secondes;
		}
		
		if (title && tps < 60) {
			$(id).html(secondes + 's');
		} else {
			$(id).html(minutes + 'm');
		}
		
		if (title && ! $.browser.msie) {
			$('title').text(minutes + secondes + ' - NEW CR');
		}
		
		setTimeout(function(){
			countdown(id, tps-1, end, title)
		}, 1000);
	
	} else {
	
		$(id).html(end);
		if (title && ! $.browser.msie) {
			$('title').text('NEW CR');
		}
	
	}
}